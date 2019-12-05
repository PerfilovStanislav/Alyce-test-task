<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ability
 * @package App
 *
 * @property-read int $id
 * @property string $name
 */
class Ability extends Model
{
    CONST
        ADD_USER       = 'AddUser',
        EDIT_USER      = 'EditUser',
        DELETE_USER    = 'DeleteUser',
        ADD_ROLE       = 'AddRole',
        EDIT_ROLE      = 'EditRole',
        DELETE_ROLE    = 'DeleteRole',
        ADD_ABILITY    = 'AddAbbility',
        EDIT_ABILITY   = 'EditAbbility',
        DELETE_ABILITY = 'DeleteAbbility';

    protected $table = 'ability';

    protected $fillable = [
        'name',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_abilities');
    }
}
