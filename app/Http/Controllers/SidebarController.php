<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class SidebarController extends BaseController
{
    // GET stato sidebar
public function getState(Request $request)
{
    return response()->json([
        'collapsed' => (bool) $request->user()->sidebar_collapsed
    ]);
}

// POST aggiornamento sidebar
public function updateState(Request $request)
{
    $request->validate(['collapsed' => 'required|boolean']);

    $request->user()->update([
        'sidebar_collapsed' => $request->collapsed
    ]);

    return response()->json(['success' => true]);
}
}
