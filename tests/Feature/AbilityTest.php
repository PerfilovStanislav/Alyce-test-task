<?php

namespace Tests\Feature;

use App\Interfaces\RightsServiceInterface;
use App\Models\Ability;
use \Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbilityTest extends TestCase
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
    public function get_ability_list()
    {
        factory(Ability::class, 2)->create();
        $this->getJson('/ability/list')->assertJsonCount(2);
    }

    /** @test */
    public function get_ability_by_id()
    {
        /** @var Ability $ability */
        $ability = factory(Ability::class)->create();

        $response = $this->getJson("/ability/{$ability->id}");
        $response->assertStatus(200)->assertJsonStructure(['id', 'name']);
    }

    /** @test */
    public function create_new_ability()
    {
        $response = $this->postJson('/ability/create', [
            'name' => 'testAbility',
        ]);
        $response->assertStatus(200)->assertJson(['id' => $response->json(['id'])]);
    }

    /** @test */
    public function update_ability()
    {
        /** @var Ability $ability */
        $ability = factory(Ability::class)->create();
        $response = $this->putJson("/ability/update/{$ability->id}", [
            'name' => 'testAbility',
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function delete_ability()
    {
        /** @var Ability $ability */
        $ability = factory(Ability::class)->create();
        $this->deleteJson("/ability/delete/{$ability->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
