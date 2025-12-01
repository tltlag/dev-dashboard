<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalActiveEmployees = User::where([
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLE_TYPE_EMPLOYEE
        ])->count();

        $totalInActiveEmployees = User::where([
            'status' => User::STATUS_IN_ACTIVE,
            'role' => User::ROLE_TYPE_EMPLOYEE
        ])->count();

        $totalEmployees = User::where([
            'role' => User::ROLE_TYPE_EMPLOYEE
        ])->count();

        return view('admin.dashboard.index', [
            'totalActiveEmployees' => $totalActiveEmployees,
            'totalInActiveEmployees' => $totalInActiveEmployees,
            'totalEmployees' => $totalEmployees,
        ]);
    }
}
