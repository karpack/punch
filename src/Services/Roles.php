<?php

namespace Karpack\Punch\Services;

use Karpack\Contracts\Punch\PermissionsManager;
use Karpack\Contracts\Punch\RolesManager;
use Karpack\Hexagon\Services\CrudService;
use Spatie\Permission\Models\Role;

class Roles extends CrudService implements RolesManager
{
    /**
     * Permissions service/repo
     * 
     * @var \Karpack\Contracts\Punch\PermissionsManager
     */
    protected $permissions;

    public function __construct(PermissionsManager $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * Creates a new role from the given data.
     * 
     * @param array $data
     * @return \Spatie\Permission\Models\Role
     */
    public function create(array $data)
    {
        $role = $this->resolveWrapper(new Role());

        $data = collect($data);

        $role
            ->setGuard($data->get('guard_name') ?: 'web')
            ->validate($data)
            ->setData($data)
            ->save();

        return $role->model();
    }

    /**
     * Grants the given permission to the role.
     * 
     * @param \Spatie\Permission\Models\Role|int
     * @param \Karpack\Punch\Models\Permission|int
     * @return \Spatie\Permission\Models\Role
     */
    public function assignPermissionsToRole($roleOrId, $permissionOrIds)
    {
        $role = $this->get($roleOrId);
        $permissions = $this->permissions->get($permissionOrIds);

        if ($role instanceof Role) {
            $role->givePermissionTo($permissions);
        }

        return $role;
    }

    /**
     * Syncs the role with the given `$permissionIds` ie only these permissions will be 
     * assigned to the role.
     * 
     * @param \Spatie\Permission\Models\Role|int
     * @param int|array
     * @return \Spatie\Permission\Models\Role
     */
    public function syncPermissions($roleOrId, $permissionIds)
    {
        $role = $this->get($roleOrId);
        $permissions = $this->permissions->get($permissionIds);

        if ($role instanceof Role) {
            $role->syncPermissions($permissions);
        }

        return $role;
    }

    /**
     * Revokes the given permission from the given role.
     * 
     * @param \Spatie\Permission\Models\Role|int
     * @param \Karpack\Punch\Models\Permission|int
     * @return \Spatie\Permission\Models\Role
     */
    public function revokePermissionFromRole($roleOrId, $permissionOrId)
    {
        $role = $this->get($roleOrId);
        $permission = $this->permissions->get($permissionOrId);

        if ($role instanceof Role) {
            $role->revokePermissionTo($permission);
        }

        return $role;
    }

    /**
     * Resolves a new handler for the given model.
     * 
     * @param \Illuminate\Database\Eloquent\Model
     * @return \Karpack\Punch\Wrappers\RoleWrapper
     */
    public function resolveWrapper($model)
    {
        return parent::resolveWrapper($model);
    }

    /**
     * Returns the model class on which this repository operates.
     * 
     * @return string
     */
    protected function modelClass()
    {
        return Role::class;
    }
}
