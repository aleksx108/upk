@php
    /** @var \App\Models\Personnel|null $personnel */
    $personnel = $personnel ?? null;
    $readonly = (bool) ($readonly ?? false);

    $removePortraitPhoto = (bool) old('remove_portrait_photo');

    $portraitPhotoUrl = $personnel?->portrait_photo_url;
    $placeholderPortraitSrc = asset('img/profile_placeholder.png');
    $portraitSrc = $removePortraitPhoto ? $placeholderPortraitSrc : ($portraitPhotoUrl ?: $placeholderPortraitSrc);
    $workplaceRows = old('workplaces');
    if (!is_array($workplaceRows)) {
        $workplaceRows = $personnel?->workplaces
            ?->map(fn ($workplace) => [
                'id' => $workplace->id,
                'company_id' => $workplace->company_id,
                'occupation_id' => $workplace->occupation_id,
                'from_date' => $workplace->from_date?->format('Y-m-d'),
                'to_date' => $workplace->to_date?->format('Y-m-d'),
            ])
            ?->values()
            ?->toArray() ?? [];
    } else {
        $workplaceRows = array_values($workplaceRows);
    }

    $companies = $companies ?? collect();
    $occupations = $occupations ?? collect();

    $companyOptions = $companyOptions ?? $companies->map(fn ($company) => ['value' => $company->id, 'label' => (string) $company->name])->values();
    $occupationOptions = $occupationOptions ?? $occupations->map(fn ($occupation) => ['value' => $occupation->id, 'label' => (string) $occupation->name])->values();
@endphp

<div class="space-y-6">
    <h2 class="text-base font-semibold text-orange-500 border-b border-gray-200 pb-2">
        <label for="portrait_photo">{{ __('Portrait photo') }}</label>
    </h2>

    <div>
        <div
            class="vue-portrait-photo-input"
            data-readonly="{{ $readonly ? '1' : '0' }}"
            data-original-src="{{ $portraitPhotoUrl ?? '' }}"
            data-placeholder-src="{{ $placeholderPortraitSrc }}"
            data-initial-remove="{{ old('remove_portrait_photo') ? '1' : '0' }}"
            data-file-input-id="portrait_photo"
            data-file-name="portrait_photo"
            data-remove-id="remove_portrait_photo"
            data-remove-name="remove_portrait_photo"
            data-text-alt="{{ __('Portrait photo') }}"
            data-text-upload="{{ __('Upload') }}"
            data-text-current-photo="{{ __('Current photo') }}"
            data-text-no-photo="{{ __('No photo') }}"
            data-text-replace="{{ __('Uploading a new file will replace the current photo.') }}"
            data-text-hover-upload="{{ __('Hover to upload; click to choose a file.') }}"
        ></div>

        <x-input-error :messages="$errors->get('portrait_photo')" class="mt-2" />
        <x-input-error :messages="$errors->get('remove_portrait_photo')" class="mt-2" />
    </div>

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

        <div>
            <x-input-label for="birthday_date" :value="__('Birthday')" />
            <x-text-input
                id="birthday_date"
                name="birthday_date"
                type="date"
                class="mt-1 block w-full"
                :value="old('birthday_date', $personnel?->birthday_date?->format('Y-m-d'))"
                :disabled="$readonly"
            />
            <x-input-error :messages="$errors->get('birthday_date')" class="mt-2" />
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
        <div class="flex items-center justify-between gap-4 border-b border-gray-200 pb-2">
            <h2 class="text-base font-semibold text-orange-500">{{ __('Workplaces') }}</h2>

            @unless($readonly)
                <button
                    type="button"
                    id="add_workplace"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ __('Add workplace') }}
                </button>
            @endunless
        </div>

        @if($readonly)
            <div class="mt-4">
                @if(($personnel?->workplaces?->count() ?? 0) === 0)
                    <p class="text-sm text-gray-500">{{ __('No workplaces added.') }}</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Company') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Occupation') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('From') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('To') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($personnel->workplaces as $workplace)
                                    <tr>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">{{ $workplace->company?->name ?? '-' }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">{{ $workplace->occupation?->name ?? '-' }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">{{ $workplace->from_date?->format('Y-m-d') ?? '-' }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">{{ $workplace->to_date?->format('Y-m-d') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @else
            @php($workplaceCount = is_array($workplaceRows) ? count($workplaceRows) : 0)

            <p id="workplaces_empty" class="mt-4 text-sm text-gray-500 {{ $workplaceCount > 0 ? 'hidden' : '' }}">
                {{ __('No workplaces yet. Click "Add workplace".') }}
            </p>

            <div id="workplaces_container" data-next-index="{{ $workplaceCount }}" class="mt-4 space-y-4">
                @foreach($workplaceRows as $i => $row)
                    <div class="workplace-row rounded-lg border border-gray-200 p-4" data-workplace-index="{{ $i }}">
                        <input type="hidden" name="workplaces[{{ $i }}][id]" value="{{ $row['id'] ?? '' }}" />

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input-label for="workplaces_{{ $i }}_company_id" :value="__('Company')" required />
                                <div
                                    class="vue-searchable-select"
                                    data-id="workplaces_{{ $i }}_company_id"
                                    data-name="workplaces[{{ $i }}][company_id]"
                                    data-placeholder="{{ __('Select') }}"
                                    data-selected="{{ $row['company_id'] ?? '' }}"
                                    data-options='@json($companyOptions)'
                                >
                                    <select
                                        id="workplaces_{{ $i }}_company_id"
                                        name="workplaces[{{ $i }}][company_id]"
                                        class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" @selected((string) ($row['company_id'] ?? '') === (string) $company->id)>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('workplaces.' . $i . '.company_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="workplaces_{{ $i }}_occupation_id" :value="__('Occupation')" required />
                                <div
                                    class="vue-searchable-select"
                                    data-id="workplaces_{{ $i }}_occupation_id"
                                    data-name="workplaces[{{ $i }}][occupation_id]"
                                    data-placeholder="{{ __('Select') }}"
                                    data-selected="{{ $row['occupation_id'] ?? '' }}"
                                    data-options='@json($occupationOptions)'
                                >
                                    <select
                                        id="workplaces_{{ $i }}_occupation_id"
                                        name="workplaces[{{ $i }}][occupation_id]"
                                        class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach($occupations as $occupation)
                                            <option value="{{ $occupation->id }}" @selected((string) ($row['occupation_id'] ?? '') === (string) $occupation->id)>
                                                {{ $occupation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('workplaces.' . $i . '.occupation_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="workplaces_{{ $i }}_from_date" :value="__('From date')" />
                                <x-text-input
                                    id="workplaces_{{ $i }}_from_date"
                                    name="workplaces[{{ $i }}][from_date]"
                                    type="date"
                                    class="mt-1 block w-full"
                                    :value="old('workplaces.' . $i . '.from_date', $row['from_date'] ?? '')"
                                />
                                <x-input-error :messages="$errors->get('workplaces.' . $i . '.from_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="workplaces_{{ $i }}_to_date" :value="__('To date')" />
                                <x-text-input
                                    id="workplaces_{{ $i }}_to_date"
                                    name="workplaces[{{ $i }}][to_date]"
                                    type="date"
                                    class="mt-1 block w-full"
                                    :value="old('workplaces.' . $i . '.to_date', $row['to_date'] ?? '')"
                                />
                                <x-input-error :messages="$errors->get('workplaces.' . $i . '.to_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="button" class="remove-workplace inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Remove') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <template id="workplace_template">
                <div class="workplace-row rounded-lg border border-gray-200 p-4" data-workplace-index="__INDEX__">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <x-input-label for="workplaces___INDEX___company_id" :value="__('Company')" required />
                            <div
                                class="vue-searchable-select"
                                data-id="workplaces___INDEX___company_id"
                                data-name="workplaces[__INDEX__][company_id]"
                                data-placeholder="{{ __('Select') }}"
                                data-selected=""
                                data-options='@json($companyOptions)'
                            >
                                <select
                                    id="workplaces___INDEX___company_id"
                                    name="workplaces[__INDEX__][company_id]"
                                    class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="workplaces___INDEX___occupation_id" :value="__('Occupation')" required />
                            <div
                                class="vue-searchable-select"
                                data-id="workplaces___INDEX___occupation_id"
                                data-name="workplaces[__INDEX__][occupation_id]"
                                data-placeholder="{{ __('Select') }}"
                                data-selected=""
                                data-options='@json($occupationOptions)'
                            >
                                <select
                                    id="workplaces___INDEX___occupation_id"
                                    name="workplaces[__INDEX__][occupation_id]"
                                    class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach($occupations as $occupation)
                                        <option value="{{ $occupation->id }}">{{ $occupation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="workplaces___INDEX___from_date" :value="__('From date')" />
                            <x-text-input
                                id="workplaces___INDEX___from_date"
                                name="workplaces[__INDEX__][from_date]"
                                type="date"
                                class="mt-1 block w-full"
                            />
                        </div>

                        <div>
                            <x-input-label for="workplaces___INDEX___to_date" :value="__('To date')" />
                            <x-text-input
                                id="workplaces___INDEX___to_date"
                                name="workplaces[__INDEX__][to_date]"
                                type="date"
                                class="mt-1 block w-full"
                            />
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" class="remove-workplace inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Remove') }}
                        </button>
                    </div>
                </div>
            </template>
        @endif
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

@unless($readonly)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const workplacesContainer = document.getElementById('workplaces_container');
            const addWorkplaceButton = document.getElementById('add_workplace');
            const workplaceTemplate = document.getElementById('workplace_template');
            const workplacesEmpty = document.getElementById('workplaces_empty');

            if (workplacesContainer && addWorkplaceButton && workplaceTemplate) {
                let nextIndex = parseInt(workplacesContainer.dataset.nextIndex || '0', 10);

                const addWorkplaceRow = () => {
                    const html = workplaceTemplate.innerHTML.replaceAll('__INDEX__', String(nextIndex));
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = html.trim();

                    const row = wrapper.firstElementChild;
                    if (!row) {
                        return;
                    }

                    workplacesContainer.appendChild(row);
                    nextIndex += 1;
                    workplacesContainer.dataset.nextIndex = String(nextIndex);

                    if (workplacesEmpty) {
                        workplacesEmpty.classList.add('hidden');
                    }

                    if (window.mountSearchableSelects) {
                        window.mountSearchableSelects();
                    }
                };

                addWorkplaceButton.addEventListener('click', addWorkplaceRow);

                workplacesContainer.addEventListener('click', (event) => {
                    const removeButton = event.target.closest('.remove-workplace');
                    if (!removeButton) {
                        return;
                    }

                    const row = removeButton.closest('.workplace-row');
                    if (row) {
                        row.querySelectorAll('.vue-searchable-select').forEach((el) => {
                            const app = el.__searchableSelectApp;
                            if (app && typeof app.unmount === 'function') {
                                app.unmount();
                            }
                        });

                        row.remove();
                    }

                    if (workplacesEmpty && workplacesContainer.querySelectorAll('.workplace-row').length === 0) {
                        workplacesEmpty.classList.remove('hidden');
                    }
                });
            }
        });
    </script>
@endunless


