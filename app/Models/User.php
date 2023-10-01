<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use VentureDrake\LaravelCrm\Traits\HasCrmAccess;
use VentureDrake\LaravelCrm\Traits\HasCrmTeams;
use Lab404\AuthChecker\Models\HasLoginsAndDevices;
use Lab404\AuthChecker\Interfaces\HasLoginsAndDevicesInterface;
use VentureDrake\LaravelCrm\Models\Team;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasCrmAccess, HasCrmTeams, HasLoginsAndDevices;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'current_crm_team_id');
    }

    public function canForTeam($permission, $teamId)
    {
        
        $team = Teams::where('team_id', $teamId)->first();
       
        if ($this->current_crm_team_id == $teamId) {
            $teamPermissions = $team->permissions->pluck('name')->toArray();
    
            if (in_array($permission, $teamPermissions)) {
              return true;
            }
        }

        return false;
    }

}
