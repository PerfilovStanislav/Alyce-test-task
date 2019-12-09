<?php

namespace App\Services;

use App\Interfaces\RightsServiceInterface;
use App\ModelManagers\UserModelManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use \App\Exceptions\UserNotFoundException;

class RightsService implements RightsServiceInterface
{
    protected $userModelManager;

    /**
     * RightsService constructor.
     * @param UserModelManager $userModelManager
     */
    public function __construct(UserModelManager $userModelManager)
    {
        $this->userModelManager = $userModelManager;
    }

    /**
     * check if userId has selected roleId
     * @param int $userId
     * @param int $roleId
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdRoleById(int $userId, int $roleId) : bool
    {
        return $this->hasUserRoleByAttributes(
            $this->userModelManager->getModelByIdOrFail($userId),
            ['role_id' => $roleId]
        );
    }

    /**
     * check if userId has selected roleName
     * @param int $userId
     * @param string $roleName
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdRoleByName(int $userId, string $roleName) : bool
    {
        return $this->hasUserRoleByAttributes(
            $this->userModelManager->getModelByIdOrFail($userId),
            ['name' => $roleName]
        );
    }

    /**
     * check if user has selected role by attributes
     * @param User $user
     * @param array $attributes
     * @return bool
     */
    public function hasUserRoleByAttributes(User $user, array $attributes) : bool
    {
        return $user->roles()->where($attributes)->exists();
    }

    /**
     * check if userId has selected abilityId
     * @param int $userId
     * @param string $abilityId
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdAbilityById(int $userId, int $abilityId) : bool
    {
        return $this->hasUserAbilityByAttributes(
            $this->userModelManager->getModelByIdOrFail($userId),
            ['id' => $abilityId]
        );
    }

    /**
     * check if userId has selected abilityName
     * @param int $userId
     * @param string $abilityName
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdAbilityByName(int $userId, string $abilityName) : bool
    {
        return $this->hasUserAbilityByAttributes(
            $this->userModelManager->getModelByIdOrFail($userId),
            ['name' => $abilityName]
        );
    }

    /**
     * check if user has selected ability by attributes
     * @param User $user
     * @param array $attributes
     * @return bool
     */
    public function hasUserAbilityByAttributes(User $user, array $attributes) : bool
    {
        return $user->roles()->whereHas('abilities', function(Builder $q) use($attributes) {
            foreach ($attributes as $column => $value) {
                $q->where('ability.' . $column, '=', $value);
            }
            })->exists();
    }
}
