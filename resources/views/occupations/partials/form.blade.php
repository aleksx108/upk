@php
    /** @var \App\Models\Occupation|null $occupation */
    $occupation = $occupation ?? null;
    $readonly = (bool) ($readonly ?? false);
@endphp

<div class="space-y-6">
    <h2 class="text-base font-semibold text-orange-500 border-b border-gray-200 pb-2">{{ __('Occupation') }}</h2>

    <div>
        <x-input-label for="name" :value="__('Name')" required />
        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            :value="old('name', $occupation?->name)"
            :disabled="$readonly"
            required
            autofocus
        />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
</div>