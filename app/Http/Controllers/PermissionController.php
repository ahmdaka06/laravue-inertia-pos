<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $permissons = Permission::when(request()->has('q') AND request()->q <> '', function ($permissons) use ($request) {
                        $permissons = $permissons->where('name', 'LIKE', '%' . request()->q . '%');
                    })
                    ->latest() 
                    ->paginate(10);

        return Inertia::render('Apps/Permissions/Index', [
            'permissions' => $permissons
        ]);
    }
}
