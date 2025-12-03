<?php

namespace App\Http\Controllers;

use App\Models\ForgedUnit;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function show(ForgedUnit $forgedUnit): View
    {
        $this->authorize('view', $forgedUnit);

        return view('units.show', ['unit' => $forgedUnit]);
    }
}
