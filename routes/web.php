<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Volt::route('/login', 'login')->name('login');

Route::middleware('auth')->group(function () {

    Route::redirect('/', '/home');
    Volt::route('/home', 'home');
    Volt::route('/user/profile', 'users.profile');

    Volt::route('/crud-example', 'crud-example.index');
    Volt::route('/crud-example/create', 'crud-example.create');
    Volt::route('/crud-example/{post}/edit', 'crud-example.edit');

    Volt::route('/invoice', 'invoice.index');
    Volt::route('/invoice/create', 'invoice.create');
    Volt::route('/invoice/{invoice}/edit', 'invoice.edit');

    Volt::route('/choices-offline-single', 'choices-example.choices-offline-single');
    Volt::route('/choices-offline-multiple', 'choices-example.choices-offline-multiple');
    Volt::route('/choices-server-single', 'choices-example.choices-server-single');
    Volt::route('/choices-server-multiple', 'choices-example.choices-server-multiple');
    Volt::route('/choices-custom', 'choices-example.choices2');

    Volt::route('/ui/badge', 'ui.badge');
    Volt::route('/ui/card', 'ui.card');

});

Route::middleware(['auth','can:admin'])->group(function () {



});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});
