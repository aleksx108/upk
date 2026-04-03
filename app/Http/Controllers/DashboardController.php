<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $start = now()->subDays(29)->startOfDay();
        $end = now()->endOfDay();

        $countsByDay = Personnel::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day')
            ->pluck('count', 'day');

        $newPersonnelLabels = [];
        $newPersonnelData = [];

        for ($i = 0; $i < 30; $i++) {
            $date = now()->subDays(29 - $i)->toDateString();
            $newPersonnelLabels[] = Carbon::parse($date)->format('M j');
            $newPersonnelData[] = (int) ($countsByDay[$date] ?? 0);
        }

        $genderCounts = Personnel::query()
            ->selectRaw("COALESCE(NULLIF(gender, ''), 'Unspecified') as gender_label, COUNT(*) as count")
            ->groupByRaw("COALESCE(NULLIF(gender, ''), 'Unspecified')")
            ->orderBy('gender_label')
            ->pluck('count', 'gender_label');

        $genderOrder = ['Male', 'Female', 'Other', 'Unspecified'];
        $genderLabels = [];
        $genderData = [];

        foreach ($genderOrder as $label) {
            $genderLabels[] = $label;
            $genderData[] = (int) ($genderCounts[$label] ?? 0);
        }

        foreach ($genderCounts as $label => $count) {
            if (! in_array($label, $genderOrder, true)) {
                $genderLabels[] = (string) $label;
                $genderData[] = (int) $count;
            }
        }

        return view('dashboard', [
            'newPersonnelLabels' => $newPersonnelLabels,
            'newPersonnelData' => $newPersonnelData,
            'newPersonnelTotal30d' => array_sum($newPersonnelData),
            'genderLabels' => $genderLabels,
            'genderData' => $genderData,
            'personnelTotal' => Personnel::count(),
        ]);
    }
}