<?php

namespace Karpack\Punch\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Karpack\Contracts\Punch\PermissionCategoriesManager;
use Karpack\Punch\Models\Permission;
use Karpack\Punch\Models\PermissionCategory;

class PermissionCategoryController
{
    /**
     * Permission category service.
     * 
     * @var \Karpack\Contracts\Punch\PermissionCategoriesManager
     */
    protected $permissionCategories;

    public function __construct(PermissionCategoriesManager $permissionCategories)
    {
        $this->permissionCategories = $permissionCategories;
    }

    /**
     * Returns all the permission categories.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        Gate::authorize(Permission::CREATE_PERMISSIONS);

        return PermissionCategory::query()->latest('id')->get();
    }

    /**
     * Creates a new Permission category using the given request data
     * 
     * @return \Karpack\Punch\Models\Permission
     */
    public function create(Request $request)
    {
        Gate::authorize(Permission::CREATE_PERMISSIONS);

        return $this->permissionCategories->create($request->all());
    }

    /**
     * Returns the requested permission.
     * 
     * @return \Karpack\Punch\Models\PermissionCategory
     */
    public function retrieve(PermissionCategory $category)
    {
        Gate::authorize(Permission::READ_PERMISSIONS);

        return $category;
    }

    /**
     * Updates the name of an existing permission.
     * 
     * @return \Karpack\Punch\Models\PermissionCategory
     */
    public function update(Request $request, PermissionCategory $category)
    {
        Gate::authorize(Permission::CREATE_PERMISSIONS);

        return $this->permissionCategories->update($category, $request->all());
    }

    /**
     * Deletes a permission from the database.
     * 
     * @return bool
     */
    public function delete(PermissionCategory $category)
    {
        Gate::authorize(Permission::DELETE_PERMISSIONS);

        return $this->permissionCategories->delete($category);
    }
}
