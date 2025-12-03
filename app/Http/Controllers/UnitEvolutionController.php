<?php

namespace App\Http\Controllers;

use App\Models\ForgedUnit;
use App\Services\EvolutionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UnitEvolutionController extends Controller
{
    public function __construct(private readonly EvolutionService $evolutionService)
    {
        $this->middleware('auth');
    }

    public function show(ForgedUnit $forgedUnit): View
    {
        $this->authorize('view', $forgedUnit);

        $evolution = $this->evolutionService->getNextEvolution($forgedUnit);
        $check = $evolution ? $this->evolutionService->canEvolve(auth()->user(), $forgedUnit) : ['ok' => false, 'reason' => 'This unit has reached its maximum tier.'];

        return view('units.evolution', [
            'unit' => $forgedUnit,
            'evolution' => $evolution,
            'check' => $check,
        ]);
    }

    public function evolve(ForgedUnit $forgedUnit): RedirectResponse
    {
        $this->authorize('update', $forgedUnit);

        $result = $this->evolutionService->evolve(auth()->user(), $forgedUnit);

        if ($result['success'] ?? false) {
            return redirect()->route('units.show', $forgedUnit)->with('success', 'Unit evolved!');
        }

        return back()->with('error', $result['message'] ?? 'Cannot evolve unit right now.');
    }
}
