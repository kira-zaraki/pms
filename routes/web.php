<?php

use App\Livewire\RoomList;
use App\Livewire\RoomShow;
use App\Http\Controllers\ChannelController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', RoomList::class)->name('rooms.index');
Route::get('/rooms/{room}', RoomShow::class)->name('rooms.show');
Route::get('/ical/export/{room:ical_export_token}', [ChannelController::class, 'eventCreate'])->name('ical.export');;

Route::get('/images/{path}', function ($path) {
    if (!Storage::disk('local')->exists($path)) abort(404);

    return Storage::disk('local')->response($path);
})->where('path', '.*')->name('image.serve');