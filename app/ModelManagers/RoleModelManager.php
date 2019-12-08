<?php

namespace App\ModelManagers;

use App\Exceptions\DuplicateException;
use App\Exceptions\RoleExistsException;
use App\Exceptions\RoleNotFoundException;
use App\Exceptions\AbilityNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Role;

class RoleModelManager
{
    protected $abilityModelManager;

    public function __construct(AbilityModelManager $abilityModelManager)
    {
        $this->abilityModelManager = $abilityModelManager;
    }

    /**
     * Return list of users
     * @return Collection
     */
    public function list()
    {
        return Role::all();
    }

    /**
     * Get role's info
     * @param int $id
     * @return mixed
     */
    public function get(int $id)
    {
        return $this->getModelByIdOrFail($id)->append(['abilities']);
    }

    /**
     * Create new role
     * @param array $attributes
     * @return int
     * @throws RoleExistsException
     */
    public function create(array $attributes) : int
    {
        if (!is_null(Role::query()->where('name', $attributes['name'])->first())) {
            throw new RoleExistsException('Role already exists');
        }

        $role = new Role($attributes);
        $role->save();
        return $role->id;
    }

    /**
     * Update role
     * @param int $id
     * @param array $attributes
     * @return bool
     * @throws RoleNotFoundException
     */
    public function update(int $id, array $attributes) : bool
    {
        return $this->getModelByIdOrFail($id)->fill($attributes)->save();
    }

    /**
     * Delete role
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id) : bool
    {
        return $this->getModelByIdOrFail($id)->delete();
    }

    /**
     * Attach ability to role
     * @param int $roleId
     * @param int $abilityId
     * @throws DuplicateException
     * @throws RoleNotFoundException
     * @throws AbilityNotFoundException
     */
    public function attach(int $roleId, int $abilityId)
    {
        // get models by IDs
        $role = $this->getModelByIdOrFail($roleId);
        $ability = $this->abilityModelManager->getModelByIdOrFail($abilityId);

        if ($role->abilities()->where('ability_id', $abilityId)->exists()) {
            throw new DuplicateException(sprintf('Role# #%d is already linked with Ability #%d', $roleId, $abilityId));
        }

        // create link between models
        $role->abilities()->attach($ability);
    }

    /**
     * Detach ability from role
     * @param int $roleId
     * @param int $abilityId
     * @throws AbilityNotFoundException
     * @throws RoleNotFoundException
     */
    public function detach(int $roleId, int $abilityId)
    {
        // get models by IDs
        $role = $this->getModelByIdOrFail($roleId);
        $ability = $this->abilityModelManager->getModelByIdOrFail($abilityId);

        // remove link between models
        $role->abilities()->detach($ability);
    }

    /**
     * Get model or fail
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function getModelByIdOrFail(int $id) : Role
    {
        /** @var Role $role */
        if (!$role = Role::find($id)) {
            throw new RoleNotFoundException('Role doesn\'t exist');
        }
        return $role;
    }
}