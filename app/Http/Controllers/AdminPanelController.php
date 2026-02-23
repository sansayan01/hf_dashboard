<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CouponCode;
use App\Models\IncentiveConfig;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class AdminPanelController extends Controller
{
    /**
     * Show the consolidated Admin Control Panel.
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            abort(403, 'Unauthorized access: Only Super Admin can access the Control Panel.');
        }

        // Stats for the tiles
        $stats = [
            'permissions' => [
                'roles_count' => 6, // office_in_charge, hs, dm, bm, rm, ro
                'total_perms' => RolePermission::count(),
            ],
            'coupons' => [
                'total' => CouponCode::count(),
                'active' => CouponCode::unused()->notExpired()->count(),
            ],
            'incentives' => [
                'global_plans' => IncentiveConfig::whereNull('user_id')->count(),
                'user_overrides' => IncentiveConfig::whereNotNull('user_id')->count(),
            ]
        ];

        return view('admin.control_panel.index', compact('stats'));
    }
}
