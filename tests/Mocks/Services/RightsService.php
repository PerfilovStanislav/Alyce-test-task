<?php

namespace Tests\Mocks\Services;

use App\Interfaces\RightsServiceInterface;
use App\Models\User;
use \App\Exceptions\UserNotFoundException;

class RightsService implements RightsServiceInterface
{
    /**
     * check if userId has selected roleId
     * @param int $userId
     * @param int $roleId
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdRoleById(int $userId, int $roleId) : bool
    {
        return true;
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
        return true;
    }

    /**
     * check if user has selected role by attributes
     * @param User $user
     * @param array $attributes
     * @return bool
     */
    public function hasUserRoleByAttributes(User $user, array $attributes) : bool
    {
        return true;
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
        return true;
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
        return true;
    }

    /**
     * check if user has selected ability by attributes
     * @param User $user
     * @param array $attributes
     * @return bool
     */
    public function hasUserAbilityByAttributes(User $user, array $attributes) : bool
    {
        return true;
    }
}
