<?php

namespace Karpack\Punch\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Karpack\Contracts\Punch\RolesManager;
use Karpack\Punch\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController
{
    /**
     * Application user role service.
     * 
     * @var \Karpack\Contracts\Punch\RolesManager
     */
    protected $roles;

    public function __construct(RolesManager $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns all the permissions.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return Role::query()->with('permissions')->latest('id')->get();
    }

    /**
     * Creates a new role using name.
     * 
     * @return \Spatie\Permission\Models\Role
     */
    public function create(Request $request)
    {
        Gate::authorize(Permission::CREATE_ROLES);

        $role = $this->roles->create($request->all());

        return $this->roles->syncPermissions($role, $request->get('permissions'));
    }

    /**
     * Returns the requested role.
     * 
     * @return \Spatie\Permission\Models\Role
     */
    public function retrieve(Role $role)
    {
        Gate::authorize(Permission::READ_ROLES);

        $role->loadMissing('permissions');

        return $role;
    }

    /**
     * Updates the name of an existing permission.
     * 
     * @return \Spatie\Permission\Models\Role
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize(Permission::UPDATE_ROLES);

        $role = $this->roles->update($role, $request->all());

        return $this->roles->syncPermissions($role, $request->get('permissions'));
    }

    /**
     * Deletes a role from the database.
     * 
     * @return bool
     */
    public function delete(Role $role)
    {
        Gate::authorize(Permission::DELETE_ROLES);

        return $this->roles->delete($role);
    }
}