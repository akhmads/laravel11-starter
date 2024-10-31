<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.empty')]
#[Title('Login')]
class extends Component {

    public LoginForm $form;

    public function mount()
    {
        // It is logged in
        if (auth()->user()) {
            return redirect()->intended('/');
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        redirect()->intended('/');
    }
}; ?>
<div>
    <div class="mx-auto sm:w-96">
        <div class="mb-10 flex justify-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/img/login.png') }}" alt="">
            </a>
        </div>

        <x-form wire:submit="login">
            <x-input label="E-mail" wire:model="form.email" icon="o-envelope" inline class="w-full" />
            <x-input label="Password" wire:model="form.password" type="password" icon="o-key" inline class="w-full" />

            <x-slot:actions>
                {{-- <x-button label="Create an account" class="btn-ghost" link="/register" /> --}}
                <div class="w-full flex justify-between items-center gap-4">
                    <x-checkbox label="Remember me" wire:model="form.remember" />
                    <x-button label="Login" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="login" />
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</div>
