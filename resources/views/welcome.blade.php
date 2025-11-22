{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Credify</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">

            {{-- Top navigation --}}
            <header class="border-b border-gray-100 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="flex items-center gap-2">
                            <img
                                src="{{ asset('images/logo-final.png') }}"
                                alt="Credify"
                                class="h-8 w-auto object-contain"
                            >
                            <span class="text-base font-semibold text-gray-900">
                                Credify
                            </span>
                        </a>
                    </div>

                    <nav class="flex items-center gap-4 text-sm">
                        @if (Route::has('login'))
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    {{ __('Dashboard') }}
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="rounded-md px-3 py-2 font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    {{ __('Log in') }}
                                </a>

                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="rounded-md bg-indigo-600 px-3 py-2 font-medium text-white shadow-sm hover:bg-indigo-500"
                                    >
                                        {{ __('Sign up') }}
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </header>

            {{-- Hero section --}}
            <main class="flex-1">
                <div class="mx-auto flex max-w-7xl flex-col items-center px-4 py-16 sm:px-6 lg:flex-row lg:py-24 lg:px-8">
                    <div class="w-full max-w-xl lg:w-1/2">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl lg:text-5xl">
                            {{ __('Manage campaign spend with confidence.') }}
                        </h1>

                        <p class="mt-4 text-base text-gray-600 sm:text-lg">
                            {{ __('Credify gives you a clear view of credits, campaigns, and billing across tenants, so you stay in control of your marketing budget.') }}
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                                >
                                    {{ __('Go to dashboard') }}
                                </a>
                            @else
                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                                    >
                                        {{ __('Get started') }}
                                    </a>
                                @endif

                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center rounded-md border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                >
                                    {{ __('Log in') }}
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="mt-10 w-full max-w-xl lg:mt-0 lg:w-1/2">
                        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                            <h2 class="text-sm font-semibold text-gray-700">
                                {{ __('At a glance') }}
                            </h2>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ __('A centralized place for campaigns, credits, and billing for all your tenants.') }}
                            </p>

                            <dl class="mt-6 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-lg bg-gray-50 px-4 py-3">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                        {{ __('Tenants') }}
                                    </dt>
                                    <dd class="mt-1 text-xl font-semibold text-gray-900">
                                        —
                                    </dd>
                                </div>

                                <div class="rounded-lg bg-gray-50 px-4 py-3">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                        {{ __('Campaigns') }}
                                    </dt>
                                    <dd class="mt-1 text-xl font-semibold text-gray-900">
                                        —
                                    </dd>
                                </div>

                                <div class="rounded-lg bg-gray-50 px-4 py-3">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                        {{ __('Credits tracked') }}
                                    </dt>
                                    <dd class="mt-1 text-xl font-semibold text-gray-900">
                                        —
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-100 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 text-xs text-gray-500 sm:px-6 lg:px-8">
                    <span>© {{ date('Y') }} Credify</span>
                    <span>{{ __('Multi-tenant credits & billing platform') }}</span>
                </div>
            </footer>
        </div>
    </body>
</html>
