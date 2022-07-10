<?php

namespace Karpack\Punch\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Karpack\Contracts\Punch\PermissionsManager;
use Karpack\Punch\Models\Permission;

class PermissionController
{
    /**
     * Permission service/repo
     * 
     * @var \Karpack\Contracts\Punch\PermissionsManager
     */
    protected $permissions;

    public function __construct(PermissionsManager $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * Returns all the permissions.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return Permission::with('category')->latest('id')->get();
    }

    /**
     * Creates a new permission using the given data
     * 
     * @return \Karpack\Punch\Models\Permission
     */
    public function create(Request $request)
    {
        Gate::authorize(Permission::CREATE_PERMISSIONS);

        return $this->permissions->create($request->all());
    }

    /**
     * Returns the requested permission.
     * 
     * @return \Karpack\Punch\Models\Permission
     */
    public function retrieve(Permission $permission)
    {
        Gate::authorize(Permission::READ_PERMISSIONS);

        return $permission;
    }

    /**
     * Updates the name of an existing permission.
     * 
     * @return \Karpack\Punch\Models\Permission
     */
    public function update(Request $request, Permission $permission)
    {
        Gate::authorize(Permission::UPDATE_PERMISSIONS);

        return $this->permissions->update($permission, $request->all());
    }

    /**
     * Deletes a permission from the database.
     * 
     * @return bool
     */
    public function delete(Permission $permission)
    {
        Gate::authorize(Permission::DELETE_PERMISSIONS);

        return $this->permissions->delete($permission);
    }
}
