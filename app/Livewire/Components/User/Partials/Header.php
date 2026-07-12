<?php

namespace App\Livewire\Components\User\Partials;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{


    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return $this->redirectRoute('login', navigate: true);
    }
    public function render()
    {
        return view('livewire.components.user.partials.header');
    }
}
