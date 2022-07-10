<?php

namespace Karpack\Punch\Services;

use Karpack\Contracts\Punch\PermissionCategoriesManager;
use Karpack\Contracts\Punch\PermissionsManager;
use Karpack\Hexagon\Services\CrudService;
use Karpack\Punch\Models\Permission;

class Permissions extends CrudService implements PermissionsManager
{
    /**
     * Permission catgory service.
     * 
     * @var \Karpack\Contracts\Punch\PermissionCategoriesManager
     */
    protected $permissionCategories;

    public function __construct(PermissionCategoriesManager $permissionCategories)
    {
        $this->permissionCategories = $permissionCategories;
    }

    /**
     * Creates a new permission from the given data.
     * 
     * @param array $data
     * @return \Karpack\Punch\Models\Permission
     */
    public function create(array $data)
    {
        $data = collect($data);
        $permission = $this->resolveWrapper(new Permission());

        $category = $this->permissionCategories->get($data->get('category_id'));

        $permission
            ->setGuard($data->get('guard_name') ?: 'web')
            ->setCategory($category)
            ->validate($data)
            ->setData($data)
            ->save();

        return $permission->model();
    }

    /**
     * Updates the details of the given model with the given data and returns the same
     * model.
     * 
     * @param \Karpack\Punch\Models\Permission|int $permissionOrId
     * @param array $data
     * @return \Karpack\Punch\Models\Permission
     */
    public function update($permissionOrId, array $data)
    {
        $data = collect($data);
        $permission = $this->resolveWrapper($this->get($permissionOrId));

        $category = $this->permissionCategories->get($data->get('category_id'));

        $permission
            ->setCategory($category)
            ->validate($data, $permission->model())
            ->saveData($data);

        return $permission->model();
    }

    /**
     * Resolves a new handler for the given model.
     * 
     * @param \Illuminate\Database\Eloquent\Model
     * @return \Karpack\Punch\Wrappers\PermissionWrapper
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
        return Permission::class;
    }
}