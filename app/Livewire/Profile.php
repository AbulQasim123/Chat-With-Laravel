<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $name;
    public $email;
    public $username;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->username = Auth::user()->username;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'username' => 'required'

        ]);

        Auth::user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username
        ]);

        session()->flash(
            'success',
            'Profile Updated Successfully.'
        );
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
