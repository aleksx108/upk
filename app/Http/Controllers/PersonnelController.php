<?php

namespace App\Http\Controllers;

use App\Enums\CountryCode;
use App\Models\Company;
use App\Models\Occupation;
use App\Models\Personnel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));

        $query = Personnel::query()->with('media')->orderBy('last_name')->orderBy('first_name');

        // single input for universal searching in multiple fields
        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('personal_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('street', 'like', "%{$search}%");
            });
        }

        $personnel = $query->paginate(15)->withQueryString();

        return view('personnel.index', [
            'personnel' => $personnel,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('personnel.create', [
            'companies' => Company::query()->orderBy('name')->get(),
            'occupations' => Occupation::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'personal_code' => ['required', 'string', 'max:255', 'unique:personnel,personal_code'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:personnel,email'],

            'country_code' => ['nullable', 'string', 'size:2', Rule::in(CountryCode::values())],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'street_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:10000'],

            'portrait_photo' => ['nullable', 'image', 'max:5120'],
            'remove_portrait_photo' => ['sometimes', 'boolean'],

            'workplaces' => ['nullable', 'array'],
            'workplaces.*.id' => ['nullable', 'integer'],
            'workplaces.*.company_id' => ['required', 'integer', 'exists:companies,id'],
            'workplaces.*.occupation_id' => ['required', 'integer', 'exists:occupations,id'],
            'workplaces.*.from_date' => ['nullable', 'date'],
            'workplaces.*.to_date' => ['nullable', 'date'],
        ]);

        $workplaces = $validated['workplaces'] ?? [];

        unset($validated['portrait_photo'], $validated['remove_portrait_photo'], $validated['workplaces']);

        $personnel = Personnel::create($validated);

        $this->syncWorkplaces($personnel, $workplaces);

        if ($request->hasFile('portrait_photo')) {
            $personnel
                ->addMediaFromRequest('portrait_photo')
                ->toMediaCollection('portrait_photo');
        }

        return Redirect::route('personnel.show', $personnel);
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel): View
    {
        $personnel->load(['media', 'workplaces.company', 'workplaces.occupation']);

        return view('personnel.show', [
            'personnel' => $personnel,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel): View
    {
        $personnel->load(['media', 'workplaces.company', 'workplaces.occupation']);

        return view('personnel.edit', [
            'personnel' => $personnel,
            'companies' => Company::query()->orderBy('name')->get(),
            'occupations' => Occupation::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'personal_code' => ['required', 'string', 'max:255', Rule::unique('personnel', 'personal_code')->ignore($personnel->id)],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255', Rule::unique('personnel', 'email')->ignore($personnel->id)],

            'country_code' => ['nullable', 'string', 'size:2', Rule::in(CountryCode::values())],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'street_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:10000'],

            'portrait_photo' => ['nullable', 'image', 'max:5120'],
            'remove_portrait_photo' => ['sometimes', 'boolean'],

            'workplaces' => ['nullable', 'array'],
            'workplaces.*.id' => ['nullable', 'integer'],
            'workplaces.*.company_id' => ['required', 'integer', 'exists:companies,id'],
            'workplaces.*.occupation_id' => ['required', 'integer', 'exists:occupations,id'],
            'workplaces.*.from_date' => ['nullable', 'date'],
            'workplaces.*.to_date' => ['nullable', 'date'],
        ]);

        $removePortraitPhoto = $request->boolean('remove_portrait_photo');
        $workplaces = $validated['workplaces'] ?? [];

        unset($validated['portrait_photo'], $validated['remove_portrait_photo'], $validated['workplaces']);

        $personnel->update($validated);

        $this->syncWorkplaces($personnel, $workplaces);

        if ($request->hasFile('portrait_photo')) {
            $personnel
                ->addMediaFromRequest('portrait_photo')
                ->toMediaCollection('portrait_photo');
        } elseif ($removePortraitPhoto) {
            $personnel->clearMediaCollection('portrait_photo');
        }

        return Redirect::route('personnel.show', $personnel);
    }

    /**
     * Render the personnel record as a PDF.
     */
    public function pdf(Personnel $personnel)
    {
        $personnel->load(['media', 'workplaces.company', 'workplaces.occupation']);

        $fileName = 'personnel-'.Str::slug(trim($personnel->first_name.' '.$personnel->last_name.' '.$personnel->personal_code)).'.pdf';

        return Pdf::loadView('personnel.pdf', [
            'personnel' => $personnel,
        ])->stream($fileName);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel $personnel): RedirectResponse
    {
        $personnel->delete();

        return Redirect::route('personnel.index');
    }

    private function syncWorkplaces(Personnel $personnel, array $workplaces): void
    {
        $savedIds = [];

        foreach ($workplaces as $workplaceData) {
            $attributes = Arr::only($workplaceData, [
                'company_id',
                'occupation_id',
                'from_date',
                'to_date',
            ]);

            $workplaceId = $workplaceData['id'] ?? null;

            if ($workplaceId) {
                $existing = $personnel->workplaces()->whereKey($workplaceId)->first();
                if ($existing) {
                    $existing->update($attributes);
                    $savedIds[] = $existing->id;
                    continue;
                }
            }

            $created = $personnel->workplaces()->create($attributes);
            $savedIds[] = $created->id;
        }

        $deleteQuery = $personnel->workplaces();

        if (count($savedIds) > 0) {
            $deleteQuery->whereNotIn('id', $savedIds)->delete();
            return;
        }

        $deleteQuery->delete();
    }
}


