<?php

namespace App\Http\Controllers;

use App\Http\Requests\Personnel\StorePersonnelRequest;
use App\Http\Requests\Personnel\UpdatePersonnelRequest;
use App\Models\Company;
use App\Models\Occupation;
use App\Models\Personnel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));

        $companyId = filter_var($request->input('company_id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
        $occupationId = filter_var($request->input('occupation_id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;

        $personnel = Personnel::query()
            ->forIndex($search, $companyId, $occupationId)
            ->paginate(15)
            ->withQueryString();

        $companies = Company::query()->orderBy('name')->get(['id', 'name']);
        $occupations = Occupation::query()->orderBy('name')->get(['id', 'name']);

        return view('personnel.index', [
            'personnel' => $personnel,
            'search' => $search,
            'companies' => $companies,
            'occupations' => $occupations,
            'companyOptions' => $companies->map(fn ($company) => ['value' => $company->id, 'label' => (string) $company->name])->values(),
            'occupationOptions' => $occupations->map(fn ($occupation) => ['value' => $occupation->id, 'label' => (string) $occupation->name])->values(),
            'companyId' => $companyId,
            'occupationId' => $occupationId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $companies = Company::query()->orderBy('name')->get();
        $occupations = Occupation::query()->orderBy('name')->get();

        return view('personnel.create', [
            'companies' => $companies,
            'occupations' => $occupations,
            'companyOptions' => $companies->map(fn ($company) => ['value' => $company->id, 'label' => (string) $company->name])->values(),
            'occupationOptions' => $occupations->map(fn ($occupation) => ['value' => $occupation->id, 'label' => (string) $occupation->name])->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonnelRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $workplaces = $validated['workplaces'] ?? [];

        unset($validated['portrait_photo'], $validated['remove_portrait_photo'], $validated['workplaces']);

        $personnel = Personnel::create($validated);

        $this->syncWorkplaces($personnel, $workplaces);

        if ($request->hasFile('portrait_photo')) {
            $personnel
                ->addMediaFromRequest('portrait_photo')
                ->toMediaCollection('portrait_photo');
        }

        return Redirect::route('personnel.show', $personnel)->with('status', __('Personnel created successfully.'));
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

        $companies = Company::query()->orderBy('name')->get();
        $occupations = Occupation::query()->orderBy('name')->get();

        return view('personnel.edit', [
            'personnel' => $personnel,
            'companies' => $companies,
            'occupations' => $occupations,
            'companyOptions' => $companies->map(fn ($company) => ['value' => $company->id, 'label' => (string) $company->name])->values(),
            'occupationOptions' => $occupations->map(fn ($occupation) => ['value' => $occupation->id, 'label' => (string) $occupation->name])->values(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonnelRequest $request, Personnel $personnel): RedirectResponse
    {
        $validated = $request->validated();

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

        return Redirect::route('personnel.show', $personnel)->with('status', __('Personnel updated successfully.'));
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

        return Redirect::route('personnel.index')->with('status', __('Personnel deleted.'));
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
