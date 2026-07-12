<?php

use App\Livewire\AuthComponent\Login;
use App\Livewire\AuthComponent\RegisterCompoment;
use App\Livewire\Chat\ChatList;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', RegisterCompoment::class)->name('register');


Route::middleware(['auth'])->group(function () {
    Route::get('/chat', ChatList::class)->name('chat.list');
});
