<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardBirthdaysController extends Controller
{
    /**
     * Return JSON data for vue component BirthdayCalendar. Displays people with upcoming birthdays within specified number of days.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $days = (int) $request->integer('days', 60);
        $days = max(1, min(366, $days));

        $start = Carbon::today();
        $end = $start->copy()->addDays($days);

        $people = Personnel::query()
            ->with('media')
            ->whereNotNull('birthday_date')
            ->get();

        $items = $people
            ->map(function (Personnel $person) use ($start) {
                $birthday = $person->birthday_date;
                if (! $birthday) {
                    return null;
                }

                $month = (int) $birthday->month;
                $day = (int) $birthday->day;

                $next = $this->nextOccurrence($start, $month, $day);
                if (! $next) {
                    return null;
                }

                $turningAge = (int) $next->year - (int) $birthday->year;

                return [
                    'id' => $person->id,
                    'name' => trim((string) ($person->first_name.' '.$person->last_name)),
                    'first_name' => $person->first_name,
                    'last_name' => $person->last_name,
                    'birthday_date' => $birthday->toDateString(),
                    'next_birthday' => $next->toDateString(),
                    'turning_age' => $turningAge,
                    'portrait_photo_url' => $person->portrait_photo_url,
                    'personnel_url' => route('personnel.show', $person),
                ];
            })
            ->filter()
            ->filter(function (array $item) use ($start, $end) {
                $next = Carbon::parse($item['next_birthday']);

                return $next->betweenIncluded($start, $end);
            })
            ->sortBy('next_birthday')
            ->values();

        return response()->json([
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'days' => $days,
            'items' => $items,
        ]);
    }

    private function nextOccurrence(Carbon $start, int $month, int $day): ?Carbon
    {
        $year = (int) $start->year;

        $next = $this->safeDate($year, $month, $day);
        if (! $next) {
            return null;
        }

        if ($next->lt($start)) {
            $next = $this->safeDate($year + 1, $month, $day);
        }

        return $next;
    }

    private function safeDate(int $year, int $month, int $day): ?Carbon
    {
        try {
            return Carbon::create($year, $month, $day)->startOfDay();
        } catch (\Throwable $e) {
            // Handle Feb 29 on non-leap years by moving to Feb 28.
            if ($month === 2 && $day === 29) {
                return Carbon::create($year, 2, 28)->startOfDay();
            }

            return null;
        }
    }
}
