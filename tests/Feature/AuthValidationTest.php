<?php

namespace Tests\Feature;

use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class AuthValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_shows_validation_errors(): void
    {
        Livewire::test(LoginPage::class)
            ->set('email', 'invalid')
            ->set('password', 'short')
            ->call('save')
            ->assertHasErrors(['email' => 'email', 'password' => 'min']);
    }

    public function test_login_shows_invalid_credentials_error(): void
    {
        Livewire::test(LoginPage::class)
            ->set('email', 'missing-user@example.com')
            ->set('password', 'wrong-password')
            ->call('save')
            ->assertHasErrors('email')
            ->assertSet('authError', 'The email or password you entered is incorrect.');
    }

    public function test_registration_shows_validation_errors(): void
    {
        Livewire::test(RegisterPage::class)
            ->set('name', '')
            ->set('email', 'invalid')
            ->set('password', 'short')
            ->call('save')
            ->assertHasErrors(['name' => 'required', 'email' => 'email', 'password' => 'min']);
    }

    public function test_successful_registration_redirects_and_flashes_success_message(): void
    {
        Livewire::test(RegisterPage::class)
            ->set('name', 'Test Customer')
            ->set('email', 'registration-test-'.uniqid().'@example.com')
            ->set('password', 'password123')
            ->call('save')
            ->assertRedirect('/')
            ->assertSessionHas('success');

        $this->assertAuthenticated();
    }
}
