<?php

namespace App\Models;

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
        return $this->belongsToMany(Role::class, 'users_roles');
    }
}
