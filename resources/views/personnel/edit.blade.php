<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Personnel') }}
            </h2>

            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('personnel.destroy', $personnel) }}">
                    @csrf
                    @method('DELETE')

                    <x-danger-button onclick="return confirm('{{ __('Delete this record?') }}')">
                        {{ __('Delete') }}
                    </x-danger-button>
                </form>

                <a href="{{ route('personnel.show', $personnel) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to view') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('personnel.update', $personnel) }}" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')

                    @include('personnel.partials.form', ['personnel' => $personnel])

                    <div class="flex items-center gap-3">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                        <a href="{{ route('personnel.show', $personnel) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>