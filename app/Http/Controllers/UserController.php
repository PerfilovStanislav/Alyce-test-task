<?php

namespace App\Http\Controllers;

use App\Adapters\UserAdapter;
use App\Exceptions\AccessDeniedException;
use App\Http\Requests\UserRequest;
use App\Interfaces\RightsServiceInterface;
use App\Interfaces\UserAdapterInterface;
use App\ModelManagers\UserModelManager;
use App\Models\Ability;
use App\Models\Role;
use App\Services\RightsService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userModelManager;

    /** @var RightsService */
    protected $rightsService;

    /** @var UserAdapter */
    protected $userAdapter;

    /**
     * UserController constructor.
     * @param RightsServiceInterface $rightsService
     * @param UserModelManager $userModelManager
     * @param UserAdapterInterface $userAdapter
     */
    public function __construct(
        RightsServiceInterface $rightsService,
        UserModelManager $userModelManager,
        UserAdapterInterface $userAdapter
    )
    {
        $this->userModelManager = $userModelManager;
        $this->rightsService = $rightsService;
        $this->userAdapter = $userAdapter;
    }

    /**
     * Return list of users
     * @return array
     */
    public function list() : array
    {
        return $this->userModelManager->list()->toArray();
    }

    /**
     * Get user's info
     * @param int $id
     * @return array
     */
    public function get(int $id)
    {
        return $this->userModelManager->get($id)->toArray();
    }

    /**
     * Create new user
     * @param UserRequest $request
     * @return array
     */
    public function create(UserRequest $request)
    {
        if (!$this->rightsService->hasUserIdRoleByName($this->userAdapter->getUserId(), Role::ADMIN)) {
            throw new AccessDeniedException();
        }
        return ['id' => DB::transaction(function () use ($request) {
            return $this->userModelManager->create($request->validated());
        })];
    }

    /**
     * Update user
     * @param int $id
     * @param UserRequest $request
     * @return array
     */
    public function update(int $id, UserRequest $request)
    {
        if (!$this->rightsService->hasUserIdAbilityByName($this->userAdapter->getUserId(), Ability::EDIT_USER)) {
            throw new AccessDeniedException();
        }
        return ['success' => DB::transaction(function () use ($id, $request) {
            return $this->userModelManager->update($id, $request->validated());
        })];
    }

    /**
     * Delete user
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        if (!$this->rightsService->hasUserIdAbilityByName($this->userAdapter->getUserId(), Ability::DELETE_USER)) {
            throw new AccessDeniedException();
        }
        return ['success' => DB::transaction(function () use ($id) {
            return $this->userModelManager->delete($id);
        })];
    }

    /**
     * Attach role to user
     * @param int $userId
     * @param int $roleId
     * @return array
     */
    public function attach(int $userId, int $roleId)
    {
        if (!$this->rightsService->hasUserIdRoleByName($this->userAdapter->getUserId(), Role::ADMIN)
            && !$this->rightsService->hasUserIdRoleByName($this->userAdapter->getUserId(), Role::EDITOR)) {
            throw new AccessDeniedException();
        }
        DB::transaction(function () use ($userId, $roleId) {
            $this->userModelManager->attach($userId, $roleId);
        });
        return ['success' => true];
    }

    /**
     * Detach role from user
     * @param int $userId
     * @param int $roleId
     * @return array
     */
    public function detach(int $userId, int $roleId)
    {
        if (!$this->rightsService->hasUserIdRoleByName($this->userAdapter->getUserId(), Role::ADMIN)
            && !$this->rightsService->hasUserIdRoleByName($this->userAdapter->getUserId(), Role::EDITOR)) {
            throw new AccessDeniedException();
        }
        DB::transaction(function () use ($userId, $roleId) {
            $this->userModelManager->detach($userId, $roleId);
        });
        return ['success' => true];
    }
}
