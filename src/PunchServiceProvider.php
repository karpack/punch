<?php

namespace Karpack\Punch;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Karpack\Contracts\Punch\PermissionCategoriesManager as PermissionCategories;
use Karpack\Contracts\Punch\PermissionsManager as Permissions;
use Karpack\Contracts\Punch\RolesManager as Roles;
use Karpack\Punch\Services\PermissionCategories as ServicesPermissionCategories;
use Karpack\Punch\Services\Permissions as ServicesPermissions;
use Karpack\Punch\Services\Roles as ServicesRoles;
use Karpack\Punch\Wrappers\PermissionCategoryWrapper;
use Karpack\Punch\Wrappers\PermissionWrapper;
use Karpack\Punch\Wrappers\RoleWrapper;
use Spatie\Permission\PermissionServiceProvider;

class PunchServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // We have to wait till spaties publishes its permissions table before publishing our
        // own migrations. This is because, our migrations contains update table on the spatie
        // migration tables. So register the spatie service provider first.
        $this->app->register(PermissionServiceProvider::class);

        $this->registerPermissionCategoryService();
        $this->registerPermissionsService();
        $this->registerRoleService();
    }

    /**
     * Registers the service that takes care of permission category related tasks.
     * 
     * @return void
     */
    protected function registerPermissionCategoryService()
    {
        $this->app->singleton(PermissionCategories::class, ServicesPermissionCategories::class);
    }

    /**
     * Registers the service that takes care of user permission related tasks.
     * 
     * @return void
     */
    protected function registerPermissionsService()
    {
        $this->app->singleton(Permissions::class, function ($app) {
            return new ServicesPermissions($app->make(PermissionCategories::class));
        });
    }

    /**
     * Registers the service that takes care of user role related tasks.
     * 
     * @return void
     */
    protected function registerRoleService()
    {
        $this->app->singleton(Roles::class, function ($app) {
            return new ServicesRoles($app->make(Permissions::class));
        });
    }

    /**
     * Boots the services
     * 
     * @return void
     */
    public function boot(PermissionCategories $permissionCategories, Permissions $permissions, Roles $roles)
    {
        $this->offerPublishing();

        $permissionCategories->setWrapperResolver(fn ($model) => new PermissionCategoryWrapper($model));
        $permissions->setWrapperResolver(fn ($model) => new PermissionWrapper($model));
        $roles->setWrapperResolver(fn ($model) => new RoleWrapper($model));
    }

    /**
     * Publishes migration files to app migrations directory.
     * 
     * @return void
     */
    protected function offerPublishing()
    {
        $categoriesPath = __DIR__ . '/../database/migrations/create_permission_categories.php.stub';

        $this->publishes([
            $categoriesPath => $this->getMigrationFileName('create_permission_categories.php'),
        ], 'migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = container()->make(Filesystem::class);

        return Collection::make(App::databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push(App::databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
