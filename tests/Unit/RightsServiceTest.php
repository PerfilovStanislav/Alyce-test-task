<?php

namespace Tests\Unit;

use App\ModelManagers\AbilityModelManager;
use App\ModelManagers\RoleModelManager;
use App\ModelManagers\UserModelManager;
use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use App\Services\RightsService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RightsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_rights()
    {
        /** @var UserModelManager $userModelManager */
        $userModelManager = resolve(UserModelManager::class);
        $userId = $userModelManager->create([
            'name' => 'Stas'
        ]);

        /** @var RoleModelManager $roleModelManager */
        $roleModelManager = resolve(RoleModelManager::class);
        $roleId = $roleModelManager->create([
            'name' => Role::ADMIN
        ]);
        $userModelManager->attach($userId, $roleId);

        /** @var AbilityModelManager $abilityModelManager */
        $abilityModelManager = resolve(AbilityModelManager::class);
        $abilityId = $abilityModelManager->create([
            'name' => Ability::ADD_USER
        ]);
        $roleModelManager->attach($roleId, $abilityId);

        /** @var RightsService $rightsService */
        $rightsService = resolve(RightsService::class);

        /**
         * Test all rights
         */
        $this->assertTrue($rightsService->hasUserIdRoleById($userId, $roleId));
        $this->assertFalse($rightsService->hasUserIdRoleById($userId, -1));

        $this->assertTrue($rightsService->hasUserIdRoleByName($userId, Role::ADMIN));
        $this->assertFalse($rightsService->hasUserIdRoleByName($userId, Role::EDITOR));

        $fakeUser = factory(User::class)->create();
        $this->assertFalse($rightsService->hasUserRoleByAttributes($fakeUser, ['name' => Role::ADMIN]));

        $this->assertTrue($rightsService->hasUserIdAbilityById($userId, $abilityId));
        $this->assertFalse($rightsService->hasUserIdAbilityById($userId, -1));

        $this->assertTrue($rightsService->hasUserIdAbilityByName($userId, Ability::ADD_USER));
        $this->assertFalse($rightsService->hasUserIdAbilityByName($userId, Ability::DELETE_ABILITY));

        $this->assertFalse($rightsService->hasUserAbilityByAttributes($fakeUser, ['id' => $abilityId]));
    }
}
