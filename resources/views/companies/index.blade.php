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

                    <button type="submit" class="sm:mt-1 inline-flex h-10 items-center justify-center px-4 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">{{ __('Search') }}</button>

                    @if($search !== '')
                        <a href="{{ route('companies.index') }}" class="inline-flex h-10 items-center justify-center px-4 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4 xl:hidden">
                        @forelse($companies as $company)
                            @php
                                $name = (string) $company->name;
                                $registrationNo = (string) $company->registration_no;
                                $city = (string) ($company->city ?? '-');
                            @endphp

                            <div class="rounded-lg border border-gray-200 bg-white p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <a class="block truncate text-base font-semibold text-gray-900 hover:text-orange-700" href="{{ route('companies.show', $company) }}" title="{{ $name }}">
                                            {{ $name }}
                                        </a>
                                        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-600">
                                            <span class="truncate" title="{{ $registrationNo }}">{{ $registrationNo }}</span>
                                            <span class="text-gray-300">â€¢</span>
                                            <span class="truncate" title="{{ $city }}">{{ $city }}</span>
                                        </div>
                                    </div>

                                    <div class="shrink-0 flex items-center gap-2">
                                        <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center px-3 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-[11px] text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('View') }}
                                        </a>

                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button type="button" aria-label="{{ __('More') }}" class="inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-[11px] text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <span class="sr-only">{{ __('More') }}</span>
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <x-dropdown-link href="{{ route('companies.edit', $company) }}">
                                                    {{ __('Edit') }}
                                                </x-dropdown-link>

                                                <form method="POST" action="{{ route('companies.destroy', $company) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out" onclick="return confirm('{{ __('Delete this record?') }}')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-gray-200 bg-white p-6 text-sm text-gray-600">
                                {{ __('No companies found.') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden xl:block">
                        <table class="min-w-full table-fixed divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="w-44 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Registration No.') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('City') }}</th>
                                    <th class="w-40 px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($companies as $company)
                                    @php
                                        $name = (string) $company->name;
                                        $registrationNo = (string) $company->registration_no;
                                        $city = (string) ($company->city ?? '-');
                                    @endphp

                                    <tr>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="truncate" title="{{ $registrationNo }}">{{ $registrationNo }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <a class="block truncate text-orange-700 hover:text-orange-600" href="{{ route('companies.show', $company) }}" title="{{ $name }}">
                                                {{ $name }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="truncate" title="{{ $city }}">{{ $city }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                                <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center px-3 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    {{ __('View') }}
                                                </a>

                                                <x-dropdown align="right" width="48">
                                                    <x-slot name="trigger">
                                                        <button type="button" aria-label="{{ __('More') }}" class="inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <span class="sr-only">{{ __('More') }}</span>
                                                            <i class="fa-solid fa-ellipsis"></i>
                                                        </button>
                                                    </x-slot>

                                                    <x-slot name="content">
                                                        <x-dropdown-link href="{{ route('companies.edit', $company) }}">
                                                            {{ __('Edit') }}
                                                        </x-dropdown-link>

                                                        <form method="POST" action="{{ route('companies.destroy', $company) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out" onclick="return confirm('{{ __('Delete this record?') }}')">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </x-slot>
                                                </x-dropdown>
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
