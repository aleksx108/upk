@props([
    'value',
    'required' => false,
])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}

    @if($required)
        <span class="ms-1 text-red-600">*</span>
    @endif
</label>