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

        if (!$user->hasPermission('admin.view')) {
            abort(403, 'Unauthorized access: You do not have permission to access the Control Panel.');
        }

        // Stats for the tiles
        $stats = [
            'permissions' => [
                'roles_count' => 8, // hs, dm, bm, rm, ro, office_in_charge, staff, camp_organizer
                'total_perms' => count(\App\Models\User::PERMISSION_DEFAULTS),
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
