<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Book a Car
        </h2>
    </x-slot>
    
    <div class="min-h-screen py-16">
        <div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg mt-12">
            <div class="p-6 bg-white border-b border-gray-200">
                <form id="bookingForm" action="/bookings" method="POST" class="space-y-4">
                    @csrf

                    <input type="hidden" id="vehicle_id" name="vehicle_id" value="{{ $selectedVehicle->id ?? '' }}">
                    @if ($selectedVehicle)
                        <div id="selected-vehicle-card" class="card bg-white shadow-md rounded-lg overflow-hidden flex items-center" style="width: 350px; border: 2px solid blue; cursor: pointer;" onclick="selectVehicle('{{ $selectedVehicle->id }}', '{{ $selectedVehicle->image_url }}', this)">
                            <img src="{{ $selectedVehicle->image_url }}" alt="{{ $selectedVehicle->model_name }}" class="w-fit h-20 object-cover">
                            <div class="p-4">
                                <h5 class="text-lg font-bold">{{ $selectedVehicle->model_name }}</h5>
                                <p>Type: {{ $selectedVehicle->type }}</p>
                                <p>License Plate: {{ $selectedVehicle->license_plate }}</p>
                            </div>
                        </div>
                    @else
                        <x-label for="vehicle_id" value="{{ __('Select a Car') }}" />
                        <div class="flex flex-row flex-wrap justify-center overflow-x-auto gap-4" style="max-height: 400px; overflow-y: auto;">
                            @foreach ($availableCars as $car)
                                <div id="vehicle-card-{{ $car->id }}" class="card bg-white shadow-md rounded-lg overflow-hidden flex items-center" style="width: 350px; cursor: pointer;" onclick="selectVehicle('{{ $car->id }}', '{{ $car->image_url }}', this)">
                                    <img src="{{ $car->image_url }}" alt="{{ $car->make }} - {{ $car->model }}" class="w-fit h-20 object-cover">
                                    <div class="p-4">
                                        <h5 class="text-lg font-bold">{{ $car->model_name }}</h5>
                                        <p>Type: {{ $car->type }}</p>
                                        <p>License Plate: {{ $car->license_plate }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    {{-- The rest of your form here --}}

                    <div class="mt-4">
                        <x-label for="employee_id" value="{{ __('Driver') }}" />
                        <select id="employee_id" name="employee_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-label for="approver_id" value="{{ __('Approver (Manager)') }}" />
                        <select id="approver_id" name="approver_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-label for="end_time" value="{{ __('End Time') }}" />
                        <x-input id="end_time" type="datetime-local" name="end_time" class="block mt-1 w-full" required min="{{ now()->format('Y-m-d\TH:i') }}" />
                    </div>

                    <div class="mt-4">
                        <x-label for="purpose" value="{{ __('Purpose') }}" />
                        <x-input id="purpose" type="text" name="purpose" class="block mt-1 w-full" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Submit') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function selectVehicle(vehicleId, imageUrl, element) {
            console.log("Selected Vehicle ID:", vehicleId);
            document.getElementById('vehicle_id').value = vehicleId; 

            // Remove active class from all vehicle cards
            document.querySelectorAll('.card').forEach(card => {
                card.style.border = 'none'; // Remove border
            });

            // Add active class to the clicked vehicle card
            element.style.border = '2px solid black'; // Add border to the selected card
        }

        document.getElementById('bookingForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if (data && data.message === 'Booking created successfully!') {  // Adjust this line as per your actual successful response structure
                    Toastify({
                        text: data.message,
                        backgroundColor: "green",
                    }).showToast();
                    window.location.href = '/'; // Redirect to dashboard
                } else {
                    Toastify({
                        text: "Error: " + data.message,
                        backgroundColor: "red",
                    }).showToast();
                }
            })
            .catch(error => {
                Toastify({
                    text: "Network Error",
                    backgroundColor: "red",
                }).showToast();
            });
        });
    </script>
</x-app-layout>
