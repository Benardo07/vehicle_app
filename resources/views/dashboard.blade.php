<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden sm:rounded-lg">

                <div id="vehicleContainer" class="flex flex-row flex-wrap items-center justify-center gap-10">
                    @foreach($vehicles as $vehicle)
                        @php
                            $lastServiceDate = \Carbon\Carbon::parse($vehicle->service->service_date ?? now());
                            $daysSinceService = (int) $lastServiceDate->diffInDays(now());
                            $maintenanceRequired = $daysSinceService > 90 ;
                        @endphp
                        <div class="p-4 flex flex-col items-center bg-white border rounded-lg shadow" style="width: 350px;">
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}" class="h-40 object-cover">
                            <div class="w-full mt-4 flex flex-col gap-3">
                                <h3 class="text-lg font-bold flex flex-row justify-between">
                                    <span>{{ $vehicle->model_name }}</span> 
                                    <span>{{ $vehicle->license_plate }}</span>
                                </h3>
                                <p><span class="font-bold">Type:</span> {{ $vehicle->type }}</p>
                                <div class="flex flex-row justify-between">
                                    <p class="font-bold tracking-wide rounded-full flex items-center justify-center px-3 py-1 {{ $vehicle->status == 'maintenance' ? 'bg-red-500 text-white' : ($vehicle->status == 'inUsed' ? 'bg-yellow-500 text-black' : 'bg-green-500 text-white') }}">
                                        {{ $vehicle->status }}
                                    </p>
                                    @if($vehicle->status == 'maintenance')
                                        <x-button onclick="markAsDone({{ $vehicle->id }})">Done</x-button>
                                    @elseif($vehicle->status == 'inUsed')
                                        <x-button onclick="returnCar({{ $vehicle->id }})">Return</x-button>
                                    @elseif($vehicle->status == 'available')
                                        <x-button onclick="bookVehicle({{ $vehicle->id }})">Book</x-button>
                                    @endif
                                </div>
                                <p><span class="font-bold">Km Used:</span> {{ $vehicle->usages->sum('kilometers_driven') }} km</p>
                                <p><span class="font-bold">Fuel Used:</span> {{ $vehicle->usages->sum('kilometers_driven') * $vehicle->fuel_consumption_per_km }} litres</p>
                                <p>
                                    <span class="font-bold">Last Service Date:</span>
                                    {{ $vehicle->service->service_date ?? 'No service data' }}
                                    @if($lastServiceDate)
                                        ({{ $daysSinceService }} days ago)
                                        @if($maintenanceRequired && $vehicle->status != 'maintenance')
                                            <span class="text-red-500">(Maintenance Required)</span>
                                        @endif
                                    @endif
                                </p>
                                <div class="flex flex-row justify-between">
                                       
                                    <x-button onclick="viewUsageDetail({{ $vehicle->id }})">View Usage</x-button>
                                    @if($maintenanceRequired && $vehicle->status != 'maintenance')
                                        <x-button class="bg-red-500 hover:bg-red-700 text-white ml-4" onclick="initiateMaintenance({{ $vehicle->id }})">Maintenance</x-button>
                                    @endif 
                                </div>
                                    
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Return Vehicle</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="returnForm" action="/return-vehicle" method="POST">
                        <input type="hidden" name="vehicle_id" id="returnVehicleId" value="">
                        <div class="mb-4">
                            <label for="km_driven" class="block text-sm font-bold text-left w-full text-gray-700">Kilometers Driven</label>
                            <input type="number" name="km_driven" id="km_driven" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="notes" class="block text-sm text-left font-bold w-full text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="cancelButton" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded text-left">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded text-right float-right">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bookVehicle(vehicleId) {
            window.location.href = `/bookings/create?vehicle_id=${vehicleId}`; // Direct to the booking page with vehicle_id as query param
        }
        function markAsDone(vehicleId, serviceType = 'Regular') {
            fetch(`/vehicles/${vehicleId}/done`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ service_type: serviceType })
            })
            .then(response => {
                if (!response.ok) {
                    console.log(response)
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                Toastify({
                    text: data.message,
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    className: "info",
                    position: "center",
                    gravity: "top",
                    duration: 3000
                }).showToast();
                
                // Fetch the updated dashboard content
                return fetch('/');
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load dashboard data');
                }
                return response.text();
            })
            .then(html => {
                // Replace the content of the dashboard with the new HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                const newContent = doc.querySelector('.py-12'); // Assuming '.py-12' contains the dashboard content
                document.querySelector('.py-12').replaceWith(newContent);
            })
            .catch((error) => {
                console.error('Error:', error);
                Toastify({
                    text: "Error: " + error.message,
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    className: "error",
                    position: "center",
                    gravity: "top",
                    duration: 3000
                }).showToast();
            });
        }

        function returnCar(vehicleId) {
            document.getElementById('returnVehicleId').value = vehicleId; // Set vehicle ID for form
            document.getElementById('returnModal').style.display = 'block'; // Show modal
        }

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('returnModal').style.display = 'none'; // Hide modal
        });

        document.getElementById('returnForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const vehicleId = document.getElementById('returnVehicleId').value;
            const kmDriven = document.getElementById('km_driven').value;
            const notes = document.getElementById('notes').value;
            

            fetch(`/return-vehicle/${vehicleId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    kilometers_driven: kmDriven,
                    notes: notes
                })
            })
            .then(response => {
                if (!response.ok) {
                    console.log(response)
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data)
                Toastify({
                    text: data.message,
                    backgroundColor: "green",
                    duration: 3000
                }).showToast();
                document.getElementById('returnModal').style.display = 'none'; // Hide the modal after successful operation
                // Optionally, refresh the page or update the UI to reflect the changes
                return fetch('/');
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load dashboard data');
                }
                return response.text();
            })
            .then(html => {
                // Replace the content of the dashboard with the new HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                const newContent = doc.querySelector('.py-12'); // Assuming '.py-12' contains the dashboard content
                document.querySelector('.py-12').replaceWith(newContent);
            })
            .catch(error => {
                console.error('Error:', error);
                Toastify({
                    text: "Network Error: " + error.message,
                    backgroundColor: "red",
                    duration: 3000
                }).showToast();
            });
        });

        function viewUsageDetail(vehicleId) {
            window.location.href = `/vehicles/usage-details?vehicle_id=${vehicleId}`; // Assume this is the route for viewing usage details
            console.log('View usage', vehicleId);
        }

        function initiateMaintenance(vehicleId) {
            fetch(`/vehicles/${vehicleId}/initiate-maintenance`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toastify({
                        text: "Maintenance initiated successfully",
                        backgroundColor: "green",
                        duration: 3000
                    }).showToast();
                } else {
                    Toastify({
                        text: data.message,
                        backgroundColor: "red",
                        duration: 3000
                    }).showToast();
                }
                console.log('Maintenance initiated:', data);
                return fetch('/');
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load dashboard data');
                }
                return response.text();
            })
            .then(html => {
                // Replace the content of the dashboard with the new HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                const newContent = doc.querySelector('.py-12'); // Assuming '.py-12' contains the dashboard content
                document.querySelector('.py-12').replaceWith(newContent);
            })
            .catch(error => {
                console.error('Error initiating maintenance:', error);
                Toastify({
                    text: "Error initiating maintenance",
                    backgroundColor: "red",
                    duration: 3000
                }).showToast();
            });
        }

        

    </script>
</x-app-layout>
