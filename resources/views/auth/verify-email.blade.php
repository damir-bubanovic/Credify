<x-guest-layout>
    <div class="w-full max-w-md mt-6 px-6 py-6 bg-white shadow-sm overflow-hidden sm:rounded-lg">
        <h1 class="text-xl font-semibold text-gray-900">
            {{ __('Verify your email address') }}
        </h1>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('Before getting started, please verify your email address by clicking the link we just emailed to you.') }}
        </p>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("If you didn’t receive the email, we will gladly send you another.") }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="text-sm text-gray-600 underline hover:text-gray-900"
                >
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
