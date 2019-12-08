<?php

namespace App\ModelManagers;

use App\Exceptions\DuplicateException;
use App\Exceptions\RoleNotFoundException;
use App\Exceptions\UserExistsException;
use App\Exceptions\UserNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

class UserModelManager
{
    protected $roleModelManager;

    public function __construct(RoleModelManager $roleModelManager)
    {
        $this->roleModelManager = $roleModelManager;
    }

    /**
     * Return list of users
     * @return Collection
     */
    public function list()
    {
        return User::all();
    }

    /**
     * Get user's info
     * @param int $id
     * @return User
     */
    public function get(int $id)
    {
        return $this->getModelByIdOrFail($id)->append(['roles']);
    }

    /**
     * Create new user
     * @param array $attributes
     * @return int
     * @throws UserExistsException
     */
    public function create(array $attributes) : int
    {
        if (!is_null(User::query()->where('name', $attributes['name'])->first())) {
            throw new UserExistsException('User already exists');
        }

        $user = new User($attributes);
        $user->save();
        return $user->id;
    }

    /**
     * Update user
     * @param int $id
     * @param array $attributes
     * @return bool
     * @throws UserNotFoundException
     */
    public function update(int $id, array $attributes) : bool
    {
        return $this->getModelByIdOrFail($id)->fill($attributes)->save();
    }

    /**
     * Delete user
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id) : bool
    {
        return $this->getModelByIdOrFail($id)->delete();
    }

    /**
     * Attach role to user
     * @param int $userId
     * @param int $roleId
     * @throws DuplicateException
     * @throws UserNotFoundException
     * @throws RoleNotFoundException
     */
    public function attach(int $userId, int $roleId)
    {
        // get models by IDs
        $user = $this->getModelByIdOrFail($userId);
        $role = $this->roleModelManager->getModelByIdOrFail($roleId);

        if ($user->roles()->where('role_id', $roleId)->exists()) {
            throw new DuplicateException(sprintf('User# #%d is already linked with Role #%d', $userId, $roleId));
        }

        // create link between models
        $user->roles()->attach($role);
    }

    /**
     * Detach role from user
     * @param int $userId
     * @param int $roleId
     * @throws UserNotFoundException
     * @throws RoleNotFoundException
     */
    public function detach(int $userId, int $roleId)
    {
        // get models by IDs
        $user = $this->getModelByIdOrFail($userId);
        $role = $this->roleModelManager->getModelByIdOrFail($roleId);

        // remove link between models
        $user->roles()->detach($role);
    }

    /**
     * Get model or fail
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getModelByIdOrFail(int $id) : User
    {
        /** @var User $user */
        if (!$user = User::find($id)) {
            throw new UserNotFoundException('User doesn\'t exist');
        }
        return $user;
    }
}