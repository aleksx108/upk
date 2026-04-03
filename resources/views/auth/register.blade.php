<x-guest-layout class="sm:max-w-4xl p-0">
    <div class="flex flex-col md:flex-row flex-col-reverse">
        <div class="px-6 py-6 md:px-8 md:py-8 w-full md:w-1/2">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Register') }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ __('Create a new account.') }}</p>

            <form class="mt-6" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="ms-4">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        <div class="bg-gray-50 w-full md:w-1/2">
            <div class="h-[15vh] md:h-[100%] w-full sm:rounded-lg overflow-hidden">
                <img src="{{ asset('img/upk-illustration.svg') }}" alt="" class="w-full h-full object-cover" />
            </div>
        </div>
    </div>
</x-guest-layout>
