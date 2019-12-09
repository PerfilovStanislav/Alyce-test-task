<?php

namespace Tests\Feature;

use App\Interfaces\RightsServiceInterface;
use App\ModelManagers\RoleModelManager;
use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use \Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
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
    public function get_role_list()
    {
        factory(Role::class, 2)->create();
        $this->getJson('/role/list')->assertJsonCount(2);
    }

    /** @test */
    public function get_role_by_id()
    {
        /** @var Role $role */
        $role = factory(Role::class)->create();
        /** @var Ability $ability */
        $ability = factory(Ability::class)->create();

        /** @var RoleModelManager $roleModelManager */
        $roleModelManager = resolve(RoleModelManager::class);
        $roleModelManager->attach($role->id, $ability->id);

        $response = $this->getJson("/role/{$role->id}");
        $response->assertStatus(200)->assertJsonStructure([
            'id', 'name', 'abilities' => [
                ['id', 'name', 'pivot']
            ]
        ]);
    }

    /** @test */
    public function create_new_role()
    {
        $response = $this->postJson('/role/create', [
            'name' => 'testRole',
        ]);
        $response->assertStatus(200)->assertJson(['id' => $response->json(['id'])]);
    }

    /** @test */
    public function update_role()
    {
        /** @var Role $role */
        $role = factory(Role::class)->create();
        $response = $this->putJson("/role/update/{$role->id}", [
            'name' => 'testRole',
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function delete_role()
    {
        /** @var Role $role */
        $role = factory(Role::class)->create();
        $this->deleteJson("/role/delete/{$role->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function attach_ability_to_role()
    {
        /** @var Role $role */
        $role = factory(Role::class)->create();
        /** @var Ability $ability */
        $ability = factory(Ability::class)->create();
        $this->postJson("/role/{$role->id}/attach/ability/{$ability->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function detach_ability_from_role()
    {
        /** @var Role $role */
        $role = factory(Role::class)->create();
        /** @var Ability $ability */
        $ability = factory(Ability::class)->create();
        $this->deleteJson("/role/{$role->id}/detach/ability/{$ability->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
