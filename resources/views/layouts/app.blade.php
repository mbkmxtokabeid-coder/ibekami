<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Disable PWA Install Prompt - Multiple Methods -->
    <meta name="mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#ffffff">
    
    <!-- Prevent indexing by app stores -->
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="googlebot" content="noindex, nofollow">
    
    <!-- Link to manifest that disables installation -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Explicitly prevent service worker registration -->
    <meta http-equiv="origin-trial" content="">

    <title>@yield('title', 'IBEKAMI - Digital Printing & Souvenir Custom Medan')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/logos/logo ibekami (3).png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/logos/logo ibekami (3).png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen text-gray-900 dark:text-gray-100 antialiased">

    {{-- Navbar --}}
    <livewire:navbar />

    {{-- Page header (optional section) --}}
    @hasSection('header')
        <header class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- Flash messages --}}
    @if (session()->has('success') || session()->has('error') || session()->has('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-2">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/30
                            border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 text-sm">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 text-green-600 hover:text-green-800">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/30
                            border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 text-sm">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-4 text-red-600 hover:text-red-800">&times;</button>
                </div>
            @endif
            @if (session('info'))
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/30
                            border border-blue-200 dark:border-blue-700 text-blue-800 dark:text-blue-300 text-sm">
                    <span>{{ session('info') }}</span>
                    <button @click="show = false" class="ml-4 text-blue-600 hover:text-blue-800">&times;</button>
                </div>
            @endif
        </div>
    @endif

    {{-- Main content --}}
    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @stack('scripts')
    
    <!-- Prevent PWA Install Prompt - Enhanced Multi-Layer Defense -->
    <script>
        (function() {
            'use strict';
            
            // Layer 1: Prevent beforeinstallprompt (Standard PWA)
            let deferredPrompt = null;
            
            window.addEventListener('beforeinstallprompt', (e) => {
                console.log('PWA install prompt blocked');
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                deferredPrompt = null; // Don't store the event
                return false;
            }, { capture: true, passive: false });
            
            // Layer 2: Prevent appinstalled event
            window.addEventListener('appinstalled', (e) => {
                console.log('App installation blocked');
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                return false;
            }, { capture: true, passive: false });
            
            // Layer 3: Unregister ALL service workers (Android WebAPK prevention)
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(function(registrations) {
                    if (registrations.length > 0) {
                        console.log('Unregistering ' + registrations.length + ' service worker(s)');
                        for(let registration of registrations) {
                            registration.unregister().then(function(success) {
                                if (success) {
                                    console.log('Service worker unregistered successfully');
                                }
                            });
                        }
                    }
                }).catch(function(err) {
                    console.log('Service worker unregistration failed:', err);
                });
                
                // Prevent future service worker registrations
                const originalRegister = navigator.serviceWorker.register;
                navigator.serviceWorker.register = function() {
                    console.log('Service worker registration blocked');
                    return Promise.reject(new Error('Service worker registration is disabled'));
                };
            }
            
            // Layer 4: Block all PWA-related events at document level
            ['beforeinstallprompt', 'appinstalled'].forEach(eventName => {
                document.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }, { capture: true, passive: false });
            });
            
            // Layer 5: Override window.matchMedia for display-mode detection
            const originalMatchMedia = window.matchMedia;
            window.matchMedia = function(query) {
                if (query.includes('display-mode')) {
                    // Always return browser mode, never standalone
                    return {
                        matches: query.includes('browser'),
                        media: query,
                        onchange: null,
                        addListener: function() {},
                        removeListener: function() {},
                        addEventListener: function() {},
                        removeEventListener: function() {},
                        dispatchEvent: function() { return true; }
                    };
                }
                return originalMatchMedia.call(window, query);
            };
            
            // Layer 6: Clear any PWA-related storage
            try {
                if (window.localStorage) {
                    const pwaKeys = Object.keys(localStorage).filter(key => 
                        key.includes('pwa') || key.includes('install') || key.includes('prompt')
                    );
                    pwaKeys.forEach(key => localStorage.removeItem(key));
                }
            } catch(e) {
                console.log('Could not clear PWA storage:', e);
            }
            
            console.log('PWA prevention layers activated');
        })();
    </script>
</body>
</html>
