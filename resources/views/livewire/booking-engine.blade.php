<div>
    <!-- Filter Bar -->
    <div class="grid grid-cols-2 gap-4">
        <input type="date" wire:model.live="check_in" class="rounded border-gray-300">
        <input type="date" wire:model.live="check_out" class="rounded border-gray-300">
    </div>

    <!-- Room List -->
    <div class="grid grid-cols-3 gap-6 mt-10">
        @foreach($this->rooms as $room)
            <div class="border rounded-lg overflow-hidden shadow-sm">
                <img src="{{ asset('storage/'.$room->image) }}" class="h-48 w-full object-cover">
                <div class="p-4">
                    <h3 class="text-xl font-bold">{{ $room->name }}</h3>
                    <p class="text-green-600 font-bold">${{ $room->price_per_night }}/night</p>
                    <!-- NAVIGATION -->
                    <a href="{{ route('room.show', [$room->id, 'check_in' => $check_in, 'check_out' => $check_out]) }}" 
                    wire:navigate 
                    class="block mt-4 text-center bg-blue-600 text-white py-2 rounded">
                    View Details
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
