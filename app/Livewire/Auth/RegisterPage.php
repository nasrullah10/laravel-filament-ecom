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
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        auth()->login($user);

        return redirect('/')->intended();
    }
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
