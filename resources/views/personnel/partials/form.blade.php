@php
    /** @var \App\Models\Personnel|null $personnel */
    $personnel = $personnel ?? null;
    $readonly = (bool) ($readonly ?? false);
@endphp

<div class="space-y-6">
    <h2 class="text-base font-semibold text-orange-500 border-b border-gray-200 pb-2">{{ __('Primary data') }}</h2>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="first_name" :value="__('First name')" required />
            <x-text-input
                id="first_name"
                name="first_name"
                type="text"
                class="mt-1 block w-full"
                :value="old('first_name', $personnel?->first_name)"
                :disabled="$readonly"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Last name')" required />
            <x-text-input
                id="last_name"
                name="last_name"
                type="text"
                class="mt-1 block w-full"
                :value="old('last_name', $personnel?->last_name)"
                :disabled="$readonly"
                required
            />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="personal_code" :value="__('Personal code')" required />
            <x-text-input
                id="personal_code"
                name="personal_code"
                type="text"
                class="mt-1 block w-full"
                :value="old('personal_code', $personnel?->personal_code)"
                placeholder="000000-00000"
                :disabled="$readonly"
                required
            />
            <x-input-error :messages="$errors->get('personal_code')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select
                id="gender"
                name="gender"
                class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm disabled:bg-gray-100 disabled:text-gray-500 disabled:border-gray-200 disabled:cursor-not-allowed"
                @disabled($readonly)
            >
                <option value="">{{ __("Select") }}</option>
                <option value="Male" @selected(old('gender', $personnel?->gender) === 'Male')>{{ __("Male") }}</option>
                <option value="Female" @selected(old('gender', $personnel?->gender) === 'Female')>{{ __("Female") }}</option>
                <option value="Other" @selected(old('gender', $personnel?->gender) === 'Other')>{{ __("Other") }}</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="phone_number" :value="__('Phone number')" required />
            <x-text-input
                id="phone_number"
                name="phone_number"
                type="text"
                class="mt-1 block w-full"
                :value="old('phone_number', $personnel?->phone_number)"
                :disabled="$readonly"
                required
            />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" required />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $personnel?->email)"
                :disabled="$readonly"
                required
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    </div>

    <div class="pt-6">
        <h2 class="text-base font-semibold text-orange-500 border-b border-gray-200 pb-2">{{ __('Address') }}</h2>

        <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="country_code" :value="__('Country code')" />
                @php($countryCode = old('country_code', $personnel?->country_code))
                <select
                    id="country_code"
                    name="country_code"
                    class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm disabled:bg-gray-100 disabled:text-gray-500 disabled:border-gray-200 disabled:cursor-not-allowed"
                    @disabled($readonly)
                >
                    <option value="">{{ __("Select") }}</option>
                    @foreach(\App\Enums\CountryCode::cases() as $code)
                        <option value="{{ $code->value }}" @selected($countryCode === $code->value)>{{ $code->label(app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('country_code')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="postal_code" :value="__('Postal code')" />
                <x-text-input
                    id="postal_code"
                    name="postal_code"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('postal_code', $personnel?->postal_code)"
                    :disabled="$readonly"
                />
                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input
                    id="city"
                    name="city"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('city', $personnel?->city)"
                    :disabled="$readonly"
                />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="street" :value="__('Street')" />
                <x-text-input
                    id="street"
                    name="street"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('street', $personnel?->street)"
                    :disabled="$readonly"
                />
                <x-input-error :messages="$errors->get('street')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="street_number" :value="__('Street number')" />
                <x-text-input
                    id="street_number"
                    name="street_number"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('street_number', $personnel?->street_number)"
                    :disabled="$readonly"
                />
                <x-input-error :messages="$errors->get('street_number')" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="pt-6">
        <h2 class="text-base font-semibold text-orange-500 border-b border-gray-200 pb-2">{{ __('Notes') }}</h2>

        <div class="mt-4">
            <textarea
                id="notes"
                name="notes"
                rows="4"
                class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm disabled:bg-gray-100 disabled:text-gray-500 disabled:border-gray-200 disabled:cursor-not-allowed"
                @disabled($readonly)
            >{{ old('notes', $personnel?->notes) }}</textarea>
            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
        </div>
    </div>
</div>
