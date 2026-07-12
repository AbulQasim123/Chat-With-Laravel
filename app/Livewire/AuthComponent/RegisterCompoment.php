<?php

namespace App\Livewire\AuthComponent;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rules\Password;

class RegisterCompoment extends Component
{
    #[Layout('components.user.auth')]
    #[Title('User Authentication')]

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms = false;

    public string $usernameMessage = '';

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'username' => ['required', 'string', 'min:3', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'terms' => ['accepted'],
        ];
    }

    protected $messages = [
        'name.required' => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email.',
        'email.unique' => 'Email is already registered.',
        'password.required' => 'Password is required.',
        'password.confirmed' => 'Passwords do not match.',
        'terms.accepted' => 'You must accept the terms and conditions.',
    ];

    public function register()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
        ]);
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'terms']);
        flash()->success('Account created successfully.');
        return $this->redirect('/login', navigate: true);
    }

    public function updatedUsername()
    {
        try {
            $this->validateOnly('username', [
                'username' => ['required', 'string', 'min:3', 'unique:users,username'],
            ]);
            $this->usernameMessage = 'Username is available!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->usernameMessage = '';
        }
    }


    public function render()
    {
        return view('livewire.auth-component.register-compoment');
    }
}
