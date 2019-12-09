<?php

namespace Tests\Feature;

use App\Interfaces\RightsServiceInterface;
use App\ModelManagers\RoleModelManager;
use App\ModelManagers\UserModelManager;
use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use \Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Подстановка сервисов для иммитации работы их реальных аналогов
        app()->singleton(RightsServiceInterface::class,  function () {
            return new \Tests\Mocks\Services\RightsService();
        });
    }

    /** @test */
    public function get_user_list()
    {
        factory(User::class, 2)->create();
        $this->getJson('/user/list')->assertJsonCount(2);
    }

    /** @test */
    public function get_user_by_id()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Role $role */
        $role = factory(Role::class)->create();
        /** @var Ability $ability */
        $ability = factory(Ability::class)->create();

        /** @var UserModelManager $userModelManager */
        $userModelManager = resolve(UserModelManager::class);
        $userModelManager->attach($user->id, $role->id);

        /** @var RoleModelManager $roleModelManager */
        $roleModelManager = resolve(RoleModelManager::class);
        $roleModelManager->attach($role->id, $ability->id);

        $response = $this->getJson("/user/{$user->id}");
        $response->assertStatus(200)->assertJsonStructure([
            'id', 'name', 'roles' => [
                ['id', 'name', 'pivot', 'abilities' => [
                    ['id', 'name', 'pivot']
                ]]
            ]
        ]);
    }

    /** @test */
    public function create_new_user()
    {
        $response = $this->postJson('/user/create', [
            'name' => 'testUser',
        ]);
        $response->assertStatus(200)->assertJson(['id' => $response->json(['id'])]);
    }

    /** @test */
    public function update_user()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $response = $this->putJson("/user/update/{$user->id}", [
            'name' => 'testUser',
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function delete_user()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->deleteJson("/user/delete/{$user->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function attach_role_to_user()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Role $role */
        $role = factory(Role::class)->create();
        $this->postJson("/user/{$user->id}/attach/role/{$role->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function detach_role_from_user()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Role $role */
        $role = factory(Role::class)->create();
        $this->deleteJson("/user/{$user->id}/detach/role/{$role->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
