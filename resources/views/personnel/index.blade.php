<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Personnel') }}
            </h2>

            <a href="{{ route('personnel.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-500 focus:bg-orange-500 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="GET" action="{{ route('personnel.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <x-input-label for="q" :value="__('Search')" />
                        <x-text-input id="q" name="q" type="text" class="mt-1 block w-full" :value="$search" placeholder="{{ __('Name, personal code, email') }}" />
                    </div>

                    <button type="submit" class="sm:mt-1 inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">{{ __('Search') }}</button>

                    @if($search !== '')
                        <a href="{{ route('personnel.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4 xl:hidden">
                        @forelse($personnel as $person)
                            @php
                                $initials = \Illuminate\Support\Str::upper(
                                    \Illuminate\Support\Str::substr((string) $person->first_name, 0, 1)
                                    .\Illuminate\Support\Str::substr((string) $person->last_name, 0, 1)
                                );

                                $workplace = $person->workplaces->first();
                                $fullName = trim((string) ($person->first_name.' '.$person->last_name));
                            @endphp

                            <div class="rounded-lg border border-gray-200 bg-white p-4">
                                <div class="flex items-start gap-4">
                                    <a href="{{ route('personnel.show', $person) }}" class="shrink-0">
                                        @if($person->portrait_photo_url)
                                            <img
                                                src="{{ $person->portrait_photo_url }}"
                                                alt="{{ __('Portrait photo of :name', ['name' => $fullName]) }}"
                                                class="h-12 w-12 rounded-full object-cover ring-1 ring-gray-200"
                                                loading="lazy"
                                            />
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-sm font-semibold ring-1 ring-gray-200">
                                                {{ $initials !== '' ? $initials : '?' }}
                                            </div>
                                        @endif
                                    </a>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <a class="min-w-0 truncate text-base font-semibold text-gray-900 hover:text-orange-700" href="{{ route('personnel.show', $person) }}" title="{{ $fullName }}">
                                                {{ $fullName }}
                                            </a>
                                            <div class="shrink-0 text-xs text-gray-500" title="{{ $person->personal_code }}">
                                                {{ $person->personal_code }}
                                            </div>
                                        </div>

                                        <div class="mt-1 text-sm text-gray-600 break-words">
                                            {{ $person->email ?? '-' }}
                                        </div>

                                        <div class="mt-3 space-y-1 text-sm text-gray-700">
                                            <div class="flex items-baseline justify-between gap-3">
                                                <span class="shrink-0 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ __('Company') }}</span>
                                                <span class="min-w-0 truncate" title="{{ $workplace?->company?->name ?? '-' }}">
                                                    {{ $workplace?->company?->name ?? '-' }}
                                                </span>
                                            </div>
                                            <div class="flex items-baseline justify-between gap-3">
                                                <span class="shrink-0 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ __('Occupation') }}</span>
                                                <span class="min-w-0 truncate" title="{{ $workplace?->occupation?->name ?? '-' }}">
                                                    {{ $workplace?->occupation?->name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex items-center justify-end gap-2">
                                            <a href="{{ route('personnel.show', $person) }}" class="inline-flex items-center px-3 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-[11px] text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                                                    <x-dropdown-link href="{{ route('personnel.edit', $person) }}">
                                                        {{ __('Edit') }}
                                                    </x-dropdown-link>

                                                    <x-dropdown-link href="{{ route('personnel.pdf', $person) }}" target="_blank" rel="noopener">
                                                        {{ __('Print') }}
                                                    </x-dropdown-link>

                                                    <form method="POST" action="{{ route('personnel.destroy', $person) }}">
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
                            </div>
                        @empty
                            <div class="rounded-lg border border-gray-200 bg-white p-6 text-sm text-gray-600">
                                {{ __('No personnel found.') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden xl:block">
                        <table class="min-w-full table-fixed divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Photo') }}</th>
                                    <th class="w-40 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Personal code') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Company') }}</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Occupation') }}</th>
                                    <th class="w-48 px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($personnel as $person)
                                    @php
                                        $initials = \Illuminate\Support\Str::upper(
                                            \Illuminate\Support\Str::substr((string) $person->first_name, 0, 1)
                                            .\Illuminate\Support\Str::substr((string) $person->last_name, 0, 1)
                                        );

                                        $workplace = $person->workplaces->first();
                                        $fullName = trim((string) ($person->first_name.' '.$person->last_name));
                                    @endphp

                                    <tr>
                                        <td class="px-3 py-3">
                                            <a href="{{ route('personnel.show', $person) }}" class="inline-flex items-center">
                                                @if($person->portrait_photo_url)
                                                    <img
                                                        src="{{ $person->portrait_photo_url }}"
                                                        alt="{{ __('Portrait photo of :name', ['name' => $fullName]) }}"
                                                        class="h-10 w-10 rounded-full object-cover ring-1 ring-gray-200"
                                                        loading="lazy"
                                                    />
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-semibold ring-1 ring-gray-200">
                                                        {{ $initials !== '' ? $initials : '?' }}
                                                    </div>
                                                @endif
                                            </a>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="truncate" title="{{ $person->personal_code }}">{{ $person->personal_code }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <a class="block truncate text-orange-700 hover:text-orange-600" href="{{ route('personnel.show', $person) }}" title="{{ $fullName }}">
                                                {{ $fullName }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="truncate" title="{{ $person->email ?? '-' }}">{{ $person->email ?? '-' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="truncate" title="{{ $workplace?->company?->name ?? '-' }}">{{ $workplace?->company?->name ?? '-' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="truncate" title="{{ $workplace?->occupation?->name ?? '-' }}">{{ $workplace?->occupation?->name ?? '-' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-right">
    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
        <a href="{{ route('personnel.show', $person) }}" class="inline-flex items-center px-3 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                <x-dropdown-link href="{{ route('personnel.edit', $person) }}">
                    {{ __('Edit') }}
                </x-dropdown-link>

                <x-dropdown-link href="{{ route('personnel.pdf', $person) }}" target="_blank" rel="noopener">
                    {{ __('Print') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('personnel.destroy', $person) }}">
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
                                        <td colspan="7" class="px-3 py-6 text-sm text-gray-600">{{ __('No personnel found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $personnel->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
