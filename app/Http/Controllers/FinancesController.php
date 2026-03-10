<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancesController extends Controller
{
    private function authorizeFinanceAccess()
    {
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->hasFinancePermission('view')) {
            abort(403, 'Unauthorized access: You do not have permission to access the finances section.');
        }
    }

    public function index()
    {
        $this->authorizeFinanceAccess();
        return view('finances.index');
    }
}
