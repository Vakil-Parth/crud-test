<?php

namespace App\Domain\Team;

use App\Domain\Role\Role;
use App\Domain\User\User;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'team_id', 'model_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'team_id', 'role_id');
    }
}
