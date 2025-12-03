<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanRequest;
use App\Services\ScanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ScanController extends Controller
{
    public function __construct(private readonly ScanService $scanService)
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('scan.index');
    }

    public function process(ScanRequest $request): RedirectResponse
    {
        $result = $this->scanService->scan($request->user(), $request->get('upc'));

        return redirect()->route('scan.result')->with('scan_result', $result);
    }

    public function result(): View|RedirectResponse
    {
        $result = session('scan_result');

        if (!$result) {
            return redirect()->route('scan.index');
        }

        return view('scan.result', ['result' => $result]);
    }
}
