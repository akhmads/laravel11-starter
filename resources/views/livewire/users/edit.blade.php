<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use App\Enums\Role;
use App\Enums\ActiveStatus;
use App\Models\User;

new class extends Component {
    use Toast, WithFileUploads;

    public User $user;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $avatar = '';
    public $role = Role::admin;
    public $status = ActiveStatus::active;
    public Collection $satkerSearchable;
    public $storedAvatar = '';

    public function mount(): void
    {
        $this->fill($this->user);

        $this->user->password = '';
        $this->storedAvatar = $this->avatar;
        $this->avatar = '';
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($this->user)],
            'password' => 'nullable|confirmed',
            'password_confirmation' => 'nullable',
            'avatar' => 'nullable|image|max:1024',
            'role' => 'required',
            'status' => 'required',
        ]);

        unset($data['avatar']);
        unset($data['password_confirmation']);

        if ($this->password) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($this->avatar) {
            $url = $this->avatar->store('avatar', 'public');
            $data['avatar'] =  "/storage/".$url;
        }

        $this->user->update($data);

        $this->success('User has been updated.', redirectTo: '/users');
    }
}; ?>

<div>
    <x-header title="Update User" separator />
    <div class="xl:w-[70%]">
        <x-form wire:submit="save">
            <x-card>
                <div class="space-y-4">
                    <x-file label="Avatar" wire:model="avatar" accept="image/png, image/jpeg" crop-after-change>
                        <img src="{{ $storedAvatar ?? asset('assets/img/default-avatar.png') }}" class="h-40 rounded-lg" />
                    </x-file>
                    <div class="space-y-4 xl:space-y-0 xl:grid grid-cols-2 gap-4">
                        <x-input label="Name" wire:model="name" />
                        <x-input label="Email" wire:model="email" />
                    </div>
                    <div class="space-y-4 xl:space-y-0 xl:grid grid-cols-2 gap-4">
                        <x-input label="Password" wire:model="password" type="password" />
                        <x-input label="Confirm Password" wire:model="password_confirmation" type="password" />
                    </div>
                    <div class="space-y-4 xl:space-y-0 xl:grid grid-cols-2 gap-4">
                        <x-select label="Role" :options="\App\Enums\Role::toSelect()" wire:model="role" />
                        <x-select label="Status" :options="\App\Enums\ActiveStatus::toSelect()" wire:model="status" />
                    </div>
                </div>
            </x-card>
            <x-slot:actions>
                <x-button label="Cancel" link="/users" />
                <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </div>
</div>
