<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Companies') }}
            </h2>

            <a href="{{ route('companies.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-500 focus:bg-orange-500 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="GET" action="{{ route('companies.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <x-input-label for="q" :value="__('Search')" />
                        <x-text-input id="q" name="q" type="text" class="mt-1 block w-full" :value="$search" placeholder="{{ __('Name, registration no, city') }}" />
                    </div>

                    <button type="submit" class="sm:mt-1 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">{{ __('Search') }}</button>

                    @if($search !== '')
                        <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Registration No.') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('City') }}</th>
                                    <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($companies as $company)
                                    <tr>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">{{ $company->registration_no }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            <a class="text-orange-700 hover:text-orange-600" href="{{ route('companies.show', $company) }}">
                                                {{ $company->name }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">{{ $company->city ?? '-' }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    {{ __('View') }}
                                                </a>

                                                <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center px-3 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    {{ __('Edit') }}
                                                </a>

                                                <form method="POST" action="{{ route('companies.destroy', $company) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('{{ __('Delete this record?') }}')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-6 text-sm text-gray-600">{{ __('No companies found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $companies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>