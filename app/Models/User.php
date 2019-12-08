<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App
 *
 * @property-read int $id
 * @property string $name
 */
class User extends Model
{
    protected $table = 'user';

    protected $fillable = [
        'name',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles')->withPivot('created_at');
    }

    public function getRolesAttribute() : Collection
    {
        return $this->roles()->get()->map(function (Role $role) {
            return $role->append(['abilities']);
        });
    }
}
