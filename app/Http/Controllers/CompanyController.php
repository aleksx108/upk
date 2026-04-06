<?php

namespace App\Http\Controllers;

use App\Enums\CountryCode;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));

        $query = Company::query()->orderBy('name');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('registration_no', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('street', 'like', "%{$search}%");
            });
        }

        $companies = $query->paginate(15)->withQueryString();

        return view('companies.index', [
            'companies' => $companies,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_no' => ['required', 'string', 'max:255', 'unique:companies,registration_no'],

            'country_code' => ['nullable', 'string', 'size:2', Rule::in(CountryCode::values())],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'street_number' => ['nullable', 'string', 'max:255'],
        ]);

        $company = Company::create($validated);

        return Redirect::route('companies.show', $company)->with('status', __('Company created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): View
    {
        return view('companies.show', [
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company): View
    {
        return view('companies.edit', [
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_no' => ['required', 'string', 'max:255', Rule::unique('companies', 'registration_no')->ignore($company->id)],

            'country_code' => ['nullable', 'string', 'size:2', Rule::in(CountryCode::values())],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'street_number' => ['nullable', 'string', 'max:255'],
        ]);

        $company->update($validated);

        return Redirect::route('companies.show', $company)->with('status', __('Company updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $activeWorkplaceCount = $company->workplaces()
            ->whereHas('personnel')
            ->count();

        if ($activeWorkplaceCount > 0) {
            return Redirect::back()->with(
                'error',
                trans_choice(
                    'Cannot delete :name because it is linked to :count active personnel workplace.|Cannot delete :name because it is linked to :count active personnel workplaces.',
                    $activeWorkplaceCount,
                    ['name' => $company->name, 'count' => $activeWorkplaceCount]
                )
            );
        }

        $company->delete();

        return Redirect::route('companies.index')->with('status', __('Company deleted.'));
    }
}
