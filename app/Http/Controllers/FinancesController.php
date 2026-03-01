<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancesController extends Controller
{
    private function authorizeSuperAdmin()
    {
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admin can access the finances section.');
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();
        return view('finances.index');
    }
}
