<x-guest-layout>
    <div class="w-full max-w-md mt-6 px-6 py-6 bg-white shadow-sm overflow-hidden sm:rounded-lg">
        <h1 class="text-xl font-semibold text-gray-900">
            {{ __('Log in') }}
        </h1>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Access your account to manage campaigns, credits, and billing.') }}
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mt-4 mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-4 space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input
                    id="email"
                    class="mt-1 block w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input
                    id="password"
                    class="mt-1 block w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me + Forgot -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember"
                    >
                    <span class="ms-2 text-sm text-gray-600">
                        {{ __('Remember me') }}
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a
                        class="text-sm text-gray-600 underline hover:text-gray-900"
                        href="{{ route('password.request') }}"
                    >
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="pt-2 flex items-center justify-between">
                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="text-xs text-gray-500 hover:text-gray-700"
                    >
                        {{ __("Don't have an account? Sign up") }}
                    </a>
                @endif

                <x-primary-button>
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
