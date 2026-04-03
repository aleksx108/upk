<x-guest-layout class="sm:max-w-4xl p-0">
    <div class="flex flex-col md:flex-row flex-col-reverse">
        <div class="px-6 py-6 md:px-8 md:py-8 w-full md:w-1/2">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Log in') }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ __('Welcome back.') }}</p>

            <form class="mt-6" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                  type="password"
                                  name="password"
                                  required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-6">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ __('Log in') }}
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
