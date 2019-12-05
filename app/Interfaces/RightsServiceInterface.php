<?php

namespace App\Interfaces;

use App\Exceptions\UserNotFoundException;
use App\Models\User;

interface RightsServiceInterface
{
    /**
     * check if userId has selected roleId
     * @param int $userId
     * @param int $roleId
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdRoleById(int $userId, int $roleId) : bool;

    /**
     * check if userId has selected roleName
     * @param int $userId
     * @param string $roleName
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdRoleByName(int $userId, string $roleName) : bool;

    /**
     * check if user has selected role by attributes
     * @param User $user
     * @param array $attributes
     * @return bool
     */
    public function hasUserRoleByAttributes(User $user, array $attributes) : bool;

    /**
     * check if userId has selected abilityId
     * @param int $userId
     * @param string $abilityId
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdAbilityById(int $userId, string $abilityId) : bool;

    /**
     * check if userId has selected abilityName
     * @param int $userId
     * @param string $abilityName
     * @return bool
     * @throws UserNotFoundException
     */
    public function hasUserIdAbilityByName(int $userId, string $abilityName) : bool;

    /**
     * check if user has selected ability by attributes
     * @param User $user
     * @param array $attributes
     * @return bool
     */
    public function hasUserAbilityByAttributes(User $user, array $attributes) : bool;
}
