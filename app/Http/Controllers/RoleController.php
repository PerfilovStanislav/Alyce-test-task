<?php

namespace App\Http\Controllers;

use App\Adapters\UserAdapter;
use App\Exceptions\AccessDeniedException;
use App\Http\Requests\RoleRequest;
use App\Interfaces\RightsServiceInterface;
use App\Interfaces\UserAdapterInterface;
use App\ModelManagers\RoleModelManager;
use App\Models\Role;
use App\Services\RightsService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    protected $roleModelManager;

    /** @var RightsService */
    protected $rightsService;

    /** @var UserAdapter */
    protected $userAdapter;

    /**
     * RoleController constructor.
     * @param RightsServiceInterface $rightsService
     * @param RoleModelManager $roleModelManager
     * @param UserAdapterInterface $userAdapter
     */
    public function __construct(
        RightsServiceInterface $rightsService,
        RoleModelManager $roleModelManager,
        UserAdapterInterface $userAdapter
    )
    {
        $this->roleModelManager = $roleModelManager;
        $this->rightsService = $rightsService;
        $this->userAdapter = $userAdapter;
    }

    /**
     * Create new role
     * @param RoleRequest $request
     * @return array
     */
    public function create(RoleRequest $request)
    {
        if (!$this->rightsService->hasUserIdRoleByName($this->userAdapter->getUserId(), Role::ADMIN)) {
            throw new AccessDeniedException();
        }
        return ['id' => DB::transaction(function () use ($request) {
            return $this->roleModelManager->create($request->validated());
        })];
    }

    /**
     * Update role
     * @param int $id
     * @param RoleRequest $request
     * @return array
     */
    public function update(int $id, RoleRequest $request)
    {
        // @TODO check permissions
        return ['success' => DB::transaction(function () use ($id, $request) {
            return $this->roleModelManager->update($id, $request->validated());
        })];
    }

    /**
     * Delete role
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        // @TODO check permissions
        return ['success' => DB::transaction(function () use ($id) {
            return $this->roleModelManager->delete($id);
        })];
    }

    /**
     * Attach ability to role
     * @param int $roleId
     * @param int $abilityId
     * @return array
     */
    public function attach(int $roleId, int $abilityId)
    {
        // @TODO check permissions
        DB::transaction(function () use ($roleId, $abilityId) {
            $this->roleModelManager->attach($roleId, $abilityId);
        });
        return ['success' => true];
    }

    /**
     * Detach ability from role
     * @param int $roleId
     * @param int $abilityId
     * @return array
     */
    public function detach(int $roleId, int $abilityId)
    {
        // @TODO check permissions
        DB::transaction(function () use ($roleId, $abilityId) {
            $this->roleModelManager->detach($roleId, $abilityId);
        });
        return ['success' => true];
    }
}
