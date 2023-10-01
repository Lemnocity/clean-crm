<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use VentureDrake\LaravelCrm\Models\Permission;
use App\Models\Teams;

class TeamPermissionController extends Controller
{
    public function edit(Teams $team)
    {
        $allTeams = Teams::all()->groupBy('team_id')->map(function ($teamGroup) {
            return $teamGroup->first();
        });
        $allPermissions = Permission::all();
        return view('admin.dashboard.teams_permissions', compact('team', 'allPermissions', 'allTeams'));
    }

    public function update(Request $request, Teams $team)
    {
        $currentPermissions = $team->permissions->pluck('id')->toArray();

        // Get the desired permissions from the request.
        $desiredPermissions = $request->input('permissions');

        foreach ($currentPermissions as $permissionId) {
            if (!in_array($permissionId, $desiredPermissions)) {
                $team->permissions()->detach($permissionId);
            }
        }

        foreach ($desiredPermissions as $permissionId) {
            if (!in_array($permissionId, $currentPermissions)) {
                $team->permissions()->attach($permissionId, ['team_id' => $team->id]);
            }
        }

        return redirect()->route('teams_permission.edit', $team)->with('success', 'Permissions updated successfully');
    }
}
