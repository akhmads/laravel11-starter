<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cupcake">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/favicon/favicon.ico') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon/favicon.ico') }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:100,200,300,400,500,600,700" rel="stylesheet" />

    {{-- Flatpickr  --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    {{-- EasyMDE --}}
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    {{-- Chart.js  --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-inter antialiased bg-base-200/50 dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>

        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-app-brand class="p-4 pt-3" />

            {{-- MENU --}}
            <x-menu activate-by-route class="gap-1">

                {{-- User --}}
                @if($user = auth()->user())
                    <x-menu-separator />
                    <x-list-item :item="$user" link="/user/profile" value="name" sub-value="branch.name" no-separator class="-mx-2 !-my-2 rounded">
                        <x-slot:avatar>
                            <x-avatar image="{{ $user->avatar ?? asset('assets/img/default-avatar.png') }}" class="!w-10" />
                        </x-slot:avatar>
                        <x-slot:actions>
                            {{-- <x-badge value="12" class="badge-primary" /> --}}
                            <x-dropdown>
                                <x-slot:trigger>
                                    <x-button icon="o-cog-6-tooth" class="btn-circle btn-ghost btn-sm" />
                                </x-slot:trigger>
                                <x-menu-item title="My profile" icon="o-user" link="/user/profile" />
                                <x-menu-item title="Change Theme" icon="o-swatch" @click="$dispatch('mary-toggle-theme')" />
                                <x-menu-item title="Log Out" icon="o-power" no-wire-navigate link="/logout" />
                            </x-dropdown>
                        </x-slot:actions>
                    </x-list-item>
                    <x-menu-separator />
                @endif

                <x-menu-item title="Home" icon="o-home" link="/home" />
                <x-menu-sub title="Example" icon="o-document-text">
                    <x-menu-item title="CRUD Example" link="/crud-example"/>
                    <x-menu-item title="Invoice Example" link="/invoice"/>
                </x-menu-sub>
                <x-menu-sub title="Form" icon="o-pencil-square">
                    <x-menu-item title="Form" link="/form"/>
                </x-menu-sub>
                <x-menu-sub title="Choice" icon="o-list-bullet">
                    <x-menu-item title="Choices Offline Single" link="/choices-offline-single"/>
                    <x-menu-item title="Choices Offline Multiple" link="/choices-offline-multiple"/>
                    <x-menu-item title="Choices Server Single" link="/choices-server-single"/>
                    <x-menu-item title="Choices Server Multiple" link="/choices-server-multiple"/>
                </x-menu-sub>
                <x-menu-sub title="UI" icon="o-computer-desktop">
                    <x-menu-item title="Badge" link="/ui/badge"/>
                    <x-menu-item title="Card" link="/ui/card"/>
                </x-menu-sub>

                @can('admin')
                <x-menu-sub title="Setup" icon="o-cog-6-tooth">
                    <x-menu-item title="Users" link="/users" />
                </x-menu-sub>
                @endcan

                <x-menu-separator />
                <x-menu-item icon="o-magnifying-glass" @click.stop="$dispatch('mary-search-open')">
                    Search <x-badge value="Cmd + G" class="badge-ghost" />
                </x-menu-item>
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast position="toast-bottom toast-right" />

    {{-- Spotlight --}}
    <x-spotlight />

    {{-- Theme toggle --}}
    <x-theme-toggle class="hidden" />

    @livewireScriptConfig
</body>
</html>
