<?php

use App\Http\Controllers\SocialAuthController;
use App\Livewire\AuthComponent\Login;
use App\Livewire\AuthComponent\RegisterCompoment;
use App\Livewire\Chat\ChatList;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', RegisterCompoment::class)->name('register');



Route::middleware(['auth'])->group(function () {
    Route::get('/chat', ChatList::class)->name('chat.list');
});


Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

Route::get('/test-google2', function () {

    $response = Http::withOptions([
        'verify' => 'C:\\wamp64\\php\\cacert.pem',
    ])->get('https://www.googleapis.com/oauth2/v3/certs');

    dd(
        $response->status(),
        $response->json()
    );
});
