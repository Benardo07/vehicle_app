<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Approve Requests
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                @foreach ($bookings as $booking)
                    <div class="p-6 bg-white border-b rounded-lg border-gray-200 flex gap-4 w-[450px]">
                        <img src="{{ $booking->vehicle->image_url }}" alt="{{ $booking->vehicle->model_name }}" class="h-32 object-cover rounded-lg">
                        <div class="flex flex-col justify-between gap-2">
                            <h3 class="font-bold flex justify-between"><span>{{ $booking->vehicle->model_name }}</span> - <span>{{ $booking->vehicle->license_plate }}</span></h3>
                            <p><strong>Driver:</strong> {{ $booking->driver->name }}</p>
                            <p><strong>End Date:</strong> {{ $booking->end_time }}</p>
                            <div class="flex gap-2">
                                <x-button onclick="handleApproval('{{ $booking->id }}', 'approve')" class="px-4 py-2 bg-green-500 text-white rounded">Approve</x-button>
                                <x-button onclick="handleApproval('{{ $booking->id }}', 'reject')" class="px-4 py-2 bg-red-500 text-white rounded">Reject</x-button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        function handleApproval(bookingId, action) {
            fetch(`/bookings/${bookingId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to process the request');
                }
                return response.json();
            })
            .then(data => {
                Toastify({
                    text: data.message,
                    backgroundColor: action === 'approve' ? "linear-gradient(to right, #00b09b, #96c93d)" : "linear-gradient(to right, #ff5f6d, #ffc371)",
                    duration: 3000
                }).showToast();
                
                // Fetch and update the page part showing the bookings
                return fetch('/approve-requests').then(response => response.text());
            })
            .then(html => {
                // Assuming '.request-list' is the container that needs updating
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                const newContent = doc.querySelector('.py-12');
                document.querySelector('.py-12').replaceWith(newContent);
            })
            .catch(error => {
                console.error('Error:', error);
                Toastify({
                    text: "Network Error: " + error.message,
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    duration: 3000
                }).showToast();
            });
        }
    </script>
</x-app-layout>
