<?php

namespace Karpack\Punch;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Karpack\Contracts\Punch\PermissionCategoriesManager as PermissionCategories;
use Karpack\Contracts\Punch\PermissionsManager as Permissions;
use Karpack\Punch\Models\Permission;

class Punch
{
    /**
     * The default permission's category name
     * 
     * @var string
     */
    protected const DEFAULT_CATEGORY = 'Default';

    /**
     * Default permissions loaded with this package and its corresponding description
     * These has to be seeded after initial db migration.
     * 
     * @var array
     */
    protected static $defaultPermissions = [
        Permission::CREATE_PERMISSIONS => 'Allows the user to create a new permission',
        Permission::READ_PERMISSIONS => 'Allows the user to read permission details',
        Permission::UPDATE_PERMISSIONS => 'Allows the user to update a permission',
        Permission::DELETE_PERMISSIONS => 'Allows the user to delete permission',
        Permission::CREATE_ROLES => 'Allows the user to create a new role',
        Permission::READ_ROLES => 'Allows the user to read role details',
        Permission::UPDATE_ROLES => 'Allows the user to update a role',
        Permission::DELETE_ROLES => 'Allows the user to delete a role',
    ];

    /**
     * Seeds the default permissions, roles, and categories
     * 
     * @return void
     */
    public static function seed(PermissionCategories $permissionCategories, Permissions $permissions)
    {
        $category = $permissionCategories->getQuery()->where('name', static::DEFAULT_CATEGORY)->first();

        if (!is_null($category)) {
            return;
        }

        DB::transaction(function () use ($permissionCategories, $permissions) {
            $category = $permissionCategories->create([
                'name' => static::DEFAULT_CATEGORY,
                'description' => 'Permissions related to permission/role management'
            ]);

            foreach (static::$defaultPermissions as $name => $description) {
                $permissions->create([
                    'name' => $name,
                    'description' => $description,
                    'category_id' => $category->getKey(),
                ]);
            }
        });
    }

    /**
     * Register permission and roles related routes
     * 
     * @return void
     */
    public static function routes()
    {
        Route::namespace('Karpack\Punch\Controllers')->group(function () {
            static::registerPermissionRoutes();
            static::registerRoleRoutes();
        });
    }

    /**
     * Register permission related routes
     * 
     * @return void
     */
    protected static function registerPermissionRoutes()
    {
        Route::prefix('permissions')->group(function () {
            Route::get('/', 'PermissionController@index');
            Route::post('/', 'PermissionController@create');
            Route::get('/{permission}', 'PermissionController@retrieve');
            Route::put('/{permission}', 'PermissionController@update');
            Route::delete('/{permission}', 'PermissionController@delete');

            Route::get('/categories', 'PermissionCategoryController@index');
            Route::post('/categories', 'PermissionCategoryController@create');
            Route::get('/categories/{category}', 'PermissionCategoryController@retrieve');
            Route::put('/categories/{category}', 'PermissionCategoryController@update');
            Route::delete('/categories/{category}', 'PermissionCategoryController@delete');
        });
    }

    /**
     * Register role related routes
     * 
     * @return void
     */
    protected static function registerRoleRoutes()
    {
        Route::prefix('roles')->group(function () {
            Route::get('/', 'RoleController@index');
            Route::post('/', 'RoleController@create');
            Route::get('/{role}', 'RoleController@retrieve');
            Route::put('/{role}', 'RoleController@update');
            Route::delete('/{role}', 'RoleController@delete');
        });
    }
}
