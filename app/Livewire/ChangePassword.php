<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $confirm_password;

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'same:new_password'
        ]);

        if (
            !Hash::check(
                $this->current_password,
                Auth::user()->password
            )
        ) {

            $this->addError(
                'current_password',
                'Current Password Incorrect'
            );

            return;

        }

        Auth::user()->update([
            'password' => Hash::make(
                $this->new_password
            )
        ]);
        $this->reset();
        session()->flash(
            'success',
            'Password Changed Successfully.'
        );
    }

    public function render()
    {
        return view('livewire.change-password');
    }

}
