<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OccupationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));

        $query = Occupation::query()->orderBy('name');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $occupations = $query->paginate(15)->withQueryString();

        return view('occupations.index', [
            'occupations' => $occupations,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('occupations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:occupations,name'],
        ]);

        $occupation = Occupation::create($validated);

        return Redirect::route('occupations.show', $occupation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Occupation $occupation): View
    {
        return view('occupations.show', [
            'occupation' => $occupation,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Occupation $occupation): View
    {
        return view('occupations.edit', [
            'occupation' => $occupation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Occupation $occupation): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('occupations', 'name')->ignore($occupation->id)],
        ]);

        $occupation->update($validated);

        return Redirect::route('occupations.show', $occupation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Occupation $occupation): RedirectResponse
    {
        $occupation->delete();

        return Redirect::route('occupations.index');
    }
}