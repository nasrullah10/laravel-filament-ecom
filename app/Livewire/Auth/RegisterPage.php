<?php

namespace App\Livewire\Auth;

use App\Models\User;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

#[Title('Register - NAAS Shopping')]
class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;

    // save register
    public function save()
    {
        $this->resetErrorBag();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An account with this email already exists.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        auth()->login($user);

        session()->flash('success', 'Your account has been created successfully. Welcome to NAAS Shopping!');

        return redirect('/')->intended();
    }
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
