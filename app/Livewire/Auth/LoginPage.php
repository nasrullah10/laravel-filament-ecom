<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Login - NAAS Shopping')]
class LoginPage extends Component
{
    public $email;
    public $password;
    public $authError = '';

    public function save()
    {
        $this->resetErrorBag();
        $this->authError = '';

        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->authError = 'The email or password you entered is incorrect.';
            $this->addError('email', $this->authError);
            return;
        }

        request()->session()->regenerate();

        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
