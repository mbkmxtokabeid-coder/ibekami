<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin-guest')]
class LoginForm extends Component
{
    public string $username = '';
    public string $password = '';
    public bool $showPassword = false;

    protected function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    public function login(): void
    {
        $this->validate();

        $throttleKey = Str::lower($this->username) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('username', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
            return;
        }

        // Cek credentials hardcoded admin
        if ($this->username === 'admin' && $this->password === 'ibeka-99!!adm') {
            RateLimiter::clear($throttleKey);

            // Login menggunakan Auth dengan user dari database
            $user = \App\Models\User::where('username', 'admin')->first();

            if ($user) {
                Auth::login($user, false);
                session()->regenerate();
                $this->redirect(route('admin.dashboard'), navigate: true);
                return;
            }
        }

        RateLimiter::hit($throttleKey, 300);
        $this->addError('username', 'Username atau password salah.');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.admin.login-form');
    }
}
