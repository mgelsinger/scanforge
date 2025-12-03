<?php

namespace App\Http\Controllers;

use App\Http\Requests\CraftingActionRequest;
use App\Models\Recipe;
use App\Models\ForgedUnit;
use App\Models\Material;
use App\Services\CraftingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CraftingController extends Controller
{
    public function __construct(private readonly CraftingService $craftingService)
    {
        $this->middleware('auth');
    }

    public function unitFoundry(): View
    {
        $recipes = Recipe::where('station', 'unit_foundry')->get();
        $materials = Material::where('user_id', auth()->id())->get()->keyBy('name');

        return view('craft.unit', [
            'recipes' => $recipes,
            'materials' => $materials,
        ]);
    }

    public function craftUnit(CraftingActionRequest $request): RedirectResponse
    {
        $recipe = Recipe::findOrFail($request->integer('recipe_id'));

        $result = $this->craftingService->craftUnit($request->user(), $recipe);

        return $this->redirectWithResult('craft.unit', $result);
    }

    public function gearForge(): View
    {
        $recipes = Recipe::where('station', 'gear_forge')->get();
        $materials = Material::where('user_id', auth()->id())->get()->keyBy('name');

        return view('craft.gear', [
            'recipes' => $recipes,
            'materials' => $materials,
        ]);
    }

    public function craftGear(CraftingActionRequest $request): RedirectResponse
    {
        $recipe = Recipe::findOrFail($request->integer('recipe_id'));

        $result = $this->craftingService->craftGear($request->user(), $recipe);

        return $this->redirectWithResult('craft.gear', $result);
    }

    public function essenceVault(): View
    {
        $recipes = Recipe::where('station', 'essence_vault')->get();
        $units = ForgedUnit::where('user_id', auth()->id())->get();
        $materials = Material::where('user_id', auth()->id())->get()->keyBy('name');

        return view('craft.essence', [
            'recipes' => $recipes,
            'units' => $units,
            'materials' => $materials,
        ]);
    }

    public function upgradeUnit(CraftingActionRequest $request): RedirectResponse
    {
        $recipe = Recipe::findOrFail($request->integer('recipe_id'));
        $unitId = $request->integer('target_unit_id');

        $result = $this->craftingService->upgradeUnit($request->user(), $recipe, $unitId);

        return $this->redirectWithResult('craft.essence', $result);
    }

    protected function redirectWithResult(string $route, array $result): RedirectResponse
    {
        if ($result['success'] ?? false) {
            return redirect()->route($route)->with('craft_success', $result['message'])->with('craft_payload', $result);
        }

        return redirect()->route($route)->with('craft_error', $result['message'])->withInput();
    }
}
