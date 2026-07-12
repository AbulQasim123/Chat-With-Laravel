<?php

namespace App\Livewire\AuthComponent;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';
    #[Validate('required')]
    public $password = '';
    public $remember = false;


    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            session()->flash('message', 'You have successfully logged in!');

            return $this->redirectRoute('chat.list', navigate: true);
        }

        session()->flash('error', 'Invalid credentials!');
    }

    #[Layout('components.user.auth')]
    #[Title('User Authentication')]
    public function render()
    {
        return view('livewire.auth-component.login');
    }
}
