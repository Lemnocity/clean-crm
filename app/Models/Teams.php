<?php

namespace App\Models;

use VentureDrake\LaravelCrm\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Permission;

class Teams extends Team
{
    protected $guard_name = 'web';

    /**
     * Get all the permissions for the model.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->morphToMany(
            config('permission.models.permission'),
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key'),
            'permission_id'
        )
        ->withPivot('team_id')
        ->wherePivot('team_id', $this->team_id)
        ->as('access');
    }
}
