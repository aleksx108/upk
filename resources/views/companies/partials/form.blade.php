@php
    /** @var \App\Models\Company|null $company */
    $company = $company ?? null;
    $readonly = (bool) ($readonly ?? false);
@endphp

<div class="space-y-6">
    <h2 class="text-base font-semibold text-orange-500 border-b border-gray-200 pb-2">{{ __('Company') }}</h2>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <x-input-label for="name" :value="__('Name')" required />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $company?->name)"
                :disabled="$readonly"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="sm:col-span-2">
            <x-input-label for="registration_no" :value="__('Registration No.')" required />
            <x-text-input
                id="registration_no"
                name="registration_no"
                type="text"
                class="mt-1 block w-full"
                :value="old('registration_no', $company?->registration_no)"
                :disabled="$readonly"
                required
            />
            <x-input-error :messages="$errors->get('registration_no')" class="mt-2" />
        </div>
    </div>

    <div class="pt-6">
        <h2 class="text-base font-semibold text-orange-500 border-b border-gray-200 pb-2">{{ __('Address') }}</h2>

        <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="country_code" :value="__('Country code')" />
                @php($countryCode = old('country_code', $company?->country_code))
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
                    :value="old('postal_code', $company?->postal_code)"
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
                    :value="old('city', $company?->city)"
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
                    :value="old('street', $company?->street)"
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
                    :value="old('street_number', $company?->street_number)"
                    :disabled="$readonly"
                />
                <x-input-error :messages="$errors->get('street_number')" class="mt-2" />
            </div>
        </div>
    </div>
</div>