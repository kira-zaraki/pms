<div>
    <div class="max-w-5xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Room Details & Gallery -->
        <div class="lg:col-span-2 space-y-6">
            <h1 class="text-4xl font-extrabold text-gray-900">{{ $room->name }}</h1>
            
            <!-- Main Image -->
            <div class="rounded-2xl overflow-hidden shadow-lg aspect-video">
                <img src="{{ route('image.serve', ['path' => $room->image]) }}" class="w-full h-full object-cover">
            </div>

            <!-- Gallery Grid -->
            <div class="grid grid-cols-4 gap-4">
                @foreach($room->galleries as $image)
                    <img src="{{ route('image.serve', ['path' => $image->image])}}" class="w-60 h-auto">
                @endforeach
            </div>

            <div class="prose max-w-none text-gray-600">
                <h3 class="text-xl font-bold text-gray-800">Description</h3>
                <p>{{ $room->description }}</p>
            </div>
        </div>

        <!-- Right: Booking Form Card -->
        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 h-fit sticky top-6">
            <h2 class="text-2xl font-bold mb-6">Book Your Stay</h2>
            
            <form wire:submit.prevent="book" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Check In</label>
                        <input type="date" wire:model="check_in" class="w-full border-gray-200 rounded-lg">
                        @error('check_in') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Check Out</label>
                        <input type="date" wire:model.live="check_out" class="w-full border-gray-200 rounded-lg">
                        @error('check_out') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div>
                    <label class="text-xs font-bold uppercase text-gray-500">Full Name</label>
                    <input type="text" wire:model="full_name" placeholder="John Doe" class="w-full border-gray-200 rounded-lg">
                    @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-gray-500">Email Address</label>
                    <input type="email" wire:model="email" placeholder="john@example.com" class="w-full border-gray-200 rounded-lg">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-gray-500">Phone Number</label>
                    <input type="text" wire:model="phone_number" placeholder="+212..." class="w-full border-gray-200 rounded-lg">
                    @error('phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-gray-500">Address</label>
                    <textarea wire:model="address"  placeholder="address" class="w-full border-gray-200 rounded-lg"></textarea>
                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="price">
                    <span class="text-lg font-bold uppercase text-black-500">Price: {{ $room->price_per_night }}</span>
                </div>
                @if($this->total_price)
                    <div class="total_price">
                        <span class="text-lg font-bold uppercase text-black-500">Total Price: {{ $this->total_price }}</span>
                    </div>
                @endif
                <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-indigo-200">
                    <span wire:loading.remove>Confirm Reservation</span>
                    <span wire:loading>Processing...</span>
                </button>
            </form>

            @if (session()->has('message'))
                <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm text-center">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg text-sm text-center">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>
