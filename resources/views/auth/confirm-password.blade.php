<x-guest-layout>
    <div class="w-full max-w-md mt-6 px-6 py-6 bg-white shadow-sm overflow-hidden sm:rounded-lg">
        <h1 class="text-xl font-semibold text-gray-900">
            {{ __('Confirm password') }}
        </h1>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('For security reasons, please confirm your password to continue.') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-4 space-y-4">
            @csrf

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

            <div class="pt-2 flex justify-end">
                <x-primary-button>
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
