<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Contracts\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        \Jurager\Teams\Models\Team::class => \App\Policies\TeamPolicy::class, // Add this class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    
        // List of permissions
        $permissions = \DB::table('permissions')->pluck('name');
        
        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                // Direct permission check
                if ($user->getPermissionsViaRoles()->contains($permission)) {
                    return true;
                }
       
                // Team permission check
                if ($user->canForTeam($permission, $user->current_crm_team_id)) {
                   return true;
                }
    
                return false;
            });
        }
    }
}