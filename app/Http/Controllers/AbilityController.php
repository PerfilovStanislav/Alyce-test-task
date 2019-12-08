<?php

namespace App\Http\Controllers;

use App\Adapters\UserAdapter;
use App\Exceptions\AccessDeniedException;
use App\Http\Requests\AbilityRequest;
use App\Interfaces\RightsServiceInterface;
use App\Interfaces\UserAdapterInterface;
use App\ModelManagers\AbilityModelManager;
use App\Models\Ability;
use App\Models\Role;
use App\Services\RightsService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AbilityController extends Controller
{
    protected $abilityModelManager;

    /** @var RightsService */
    protected $rightsService;

    /** @var UserAdapter */
    protected $userAdapter;

    /**
     * AbilityController constructor.
     * @param RightsServiceInterface $rightsService
     * @param AbilityModelManager $abilityModelManager
     * @param UserAdapterInterface $userAdapter
     */
    public function __construct(
        RightsServiceInterface $rightsService,
        AbilityModelManager $abilityModelManager,
        UserAdapterInterface $userAdapter)
    {
        $this->abilityModelManager = $abilityModelManager;
        $this->rightsService = $rightsService;
        $this->userAdapter = $userAdapter;
    }

    /**
     * Return list of users
     * @return array
     */
    public function list() : array
    {
        return $this->abilityModelManager->list()->toArray();
    }

    /**
     * Get Ability info
     * @param int $id
     * @return mixed
     */
    public function get(int $id)
    {
        return $this->abilityModelManager->get($id)->toArray();
    }

    /**
     * Create new ability
     * @param AbilityRequest $request
     * @return mixed
     */
    public function create(AbilityRequest $request)
    {
        if (!$this->rightsService->hasUserIdRoleByName($this->userAdapter->getUserId(), Role::ADMIN)
            && !$this->rightsService->hasUserIdAbilityByName($this->userAdapter->getUserId(), Ability::ADD_ABILITY)
        ) {
            throw new AccessDeniedException();
        }
        return ['id' => DB::transaction(function () use ($request) {
            return $this->abilityModelManager->create($request->validated());
        })];
    }

    /**
     * Update ability
     * @param int $id
     * @param AbilityRequest $request
     * @return array
     */
    public function update(int $id, AbilityRequest $request)
    {
        // @TODO check permissions
        return ['success' => DB::transaction(function () use ($id, $request) {
            return $this->abilityModelManager->update($id, $request->validated());
        })];
    }

    /**
     * Delete ability
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        // @TODO check permissions
        return ['success' => DB::transaction(function () use ($id) {
            return $this->abilityModelManager->delete($id);
        })];
    }
}
