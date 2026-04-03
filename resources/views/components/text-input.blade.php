@props([
    'disabled' => false,
    'readonly' => false,
    'value' => null,
])

<input
    @disabled($disabled)
    @if($readonly) readonly @endif
    @if(!is_null($value)) value="{{ $value }}" @endif
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm disabled:bg-gray-100 disabled:text-gray-500 disabled:border-gray-200 disabled:cursor-not-allowed']) }}
>