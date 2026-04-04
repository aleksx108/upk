<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Personnel') }} - {{ trim($personnel->first_name.' '.$personnel->last_name) }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 6px; color: #f97316; }
        h2 { font-size: 14px; margin: 16px 0 8px; color: #f97316; }
        .sub { color: #6b7280; margin: 0; }
        .header { width: 100%; margin: 0 0 14px; }
        .header td { vertical-align: top; }
        .header td.logo { width: 140px; text-align: center; vertical-align: middle; }
        .logo img { height: 28px; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px 10px; vertical-align: top; }
        th { background: #f9fafb; text-align: left; width: 32%; }
        .pre { white-space: pre-line; }

        .workplaces th { width: auto; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <h1>{{ $personnel->first_name }} {{ $personnel->last_name }}</h1>
                <p class="sub">{{ __('Personal code') }}: {{ $personnel->personal_code }}</p>
            </td>
            <td class="logo">
                <img src="{{ public_path('img/upk-logo.svg') }}" alt="UPK" />
            </td>
        </tr>
    </table>

    <table>
        <tbody>
            <tr>
                <th>{{ __('Email') }}</th>
                <td>{{ $personnel->email ?: '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Phone') }}</th>
                <td>{{ $personnel->phone_number ?: '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Gender') }}</th>
                <td>{{ $personnel->gender ?: '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Address') }}</th>
                <td class="pre">{{ trim(collect([
                    $personnel->street ? ($personnel->street.' '.$personnel->street_number) : null,
                    trim(($personnel->postal_code ?: '').' '.($personnel->city ?: '')) ?: null,
                    $personnel->country_code ?: null,
                ])->filter()->implode("\n")) ?: '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Notes') }}</th>
                <td class="pre">{{ $personnel->notes ?: '-' }}</td>
            </tr>
        </tbody>
    </table>

    <h2>{{ __('Workplaces') }}</h2>

    @if(($personnel->workplaces?->count() ?? 0) === 0)
        <p class="sub">{{ __('No workplaces added.') }}</p>
    @else
        <table class="workplaces">
            <thead>
                <tr>
                    <th>{{ __('Company') }}</th>
                    <th>{{ __('Occupation') }}</th>
                    <th>{{ __('From') }}</th>
                    <th>{{ __('To') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personnel->workplaces as $workplace)
                    <tr>
                        <td>{{ $workplace->company?->name ?? '-' }}</td>
                        <td>{{ $workplace->occupation?->name ?? '-' }}</td>
                        <td>{{ $workplace->from_date?->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ $workplace->to_date?->format('Y-m-d') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>