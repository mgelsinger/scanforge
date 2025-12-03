<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransmutationRequest;
use App\Models\TransmutationRecipe;
use App\Services\TransmutationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransmuterController extends Controller
{
    public function __construct(private readonly TransmutationService $transmutationService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $recipes = $this->transmutationService->recipesWithStatus($request->user());

        return view('craft.transmuter', [
            'recipes' => $recipes,
        ]);
    }

    public function transmute(TransmutationRequest $request, TransmutationRecipe $transmutationRecipe): RedirectResponse
    {
        $result = $this->transmutationService->transmute(
            $request->user(),
            $transmutationRecipe,
            (int) $request->input('times', 1)
        );

        if ($result['success'] ?? false) {
            return redirect()->route('craft.transmuter')->with('craft_success', $result['message']);
        }

        return back()->with('craft_error', $result['message'] ?? 'Unable to transmute right now.')->withInput();
    }
}
