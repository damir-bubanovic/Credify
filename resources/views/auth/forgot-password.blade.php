<x-guest-layout>
    <div class="w-full max-w-md mt-6 px-6 py-6 bg-white shadow-sm overflow-hidden sm:rounded-lg">
        <h1 class="text-xl font-semibold text-gray-900">
            {{ __('Forgot your password?') }}
        </h1>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('No problem. Enter your email and we will send you a password reset link.') }}
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mt-4 mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-4 space-y-4">
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
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between pt-2">
                <a
                    href="{{ route('login') }}"
                    class="text-xs text-gray-500 hover:text-gray-700"
                >
                    {{ __('Back to login') }}
                </a>

                <x-primary-button>
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
