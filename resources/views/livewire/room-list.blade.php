<div class="max-w-7xl mx-auto p-6">
    <!-- Search Bar -->
    <div class="bg-white p-6 rounded-xl shadow-sm border mb-8 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700">Check In</label>
            <input type="date" wire:model.live="check_in" min="{{ date('Y-m-d') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Check Out</label>
            <input type="date" wire:model.live="check_out" min="{{ $check_in ?? date('Y-m-d') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Room Type</label>
            <select wire:model.live="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach($roomTypes as $roomType)
                    <option value="{{ $roomType->value }}">
                        {{ $roomType->getLabel() }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Room Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($this->rooms as $room)
            <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow border border-gray-100">
                <!-- Room Image -->
                <div class="relative h-56">
                    <img src="{{ route('image.serve', ['path' => $room->image]) }}" 
                         class="w-full h-full object-cover" alt="{{ $room->name }}">
                    <div class="absolute top-4 left-4 bg-white/90 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ $room->type }}
                    </div>
                </div>

                <!-- Room Content -->
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-900">{{ $room->name }}</h3>
                        <span class="text-indigo-600 font-bold text-lg">${{ number_format($room->price_per_night, 2) }}<span class="text-xs text-gray-500">/night</span></span>
                    </div>
                    
                    <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $room->description }}</p>
                    
                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-6">
                        <span>Capacity: {{ $room->capacity }} Persons</span>
                        <span>Floor: {{ $room->floor }}</span>
                    </div>

                    <a href="{{ route('rooms.show', ['room' => $room->id, 'check_in' => $check_in, 'check_out' => $check_out]) }}" 
                       wire:navigate
                       class="block w-full text-center bg-gray-900 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-colors">
                        View & Book
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    @if($this->rooms->isEmpty())
        <div class="text-center py-20 bg-gray-50 rounded-xl border-2 border-dashed">
            <p class="text-gray-500">No rooms available for these criteria.</p>
        </div>
    @endif
</div>
