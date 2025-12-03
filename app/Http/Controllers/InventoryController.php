<?php

namespace App\Http\Controllers;

use App\Models\Blueprint;
use App\Models\BlueprintFragment;
use App\Models\GearItem;
use App\Models\Material;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(): View
    {
        $userId = auth()->id();

        $materials = Material::where('user_id', $userId)->orderBy('name')->get();
        $blueprints = Blueprint::where('user_id', $userId)->orderBy('name')->get();
        $fragments = BlueprintFragment::where('user_id', $userId)->with('blueprint')->get();
        $gear = GearItem::where('user_id', $userId)->orderBy('name')->get();

        return view('inventory.index', [
            'materials' => $materials,
            'blueprints' => $blueprints,
            'fragments' => $fragments,
            'gear' => $gear,
        ]);
    }
}
