<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App
 *
 * @property-read int $id
 * @property string $name
 */
class Role extends Model
{
    CONST
        ADMIN  = 'Admin',
        EDITOR = 'Editor',
        USER   = 'User';

    protected $table = 'role';

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_roles');
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'roles_abilities');
    }
}
