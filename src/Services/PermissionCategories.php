<?php

namespace Karpack\Punch\Services;

use Karpack\Contracts\Punch\PermissionCategoriesManager;
use Karpack\Hexagon\Services\CrudService;
use Karpack\Punch\Models\PermissionCategory;

class PermissionCategories extends CrudService implements PermissionCategoriesManager
{
    /**
     * Returns the model class on which this repository operates.
     * 
     * @return string
     */
    protected function modelClass()
    {
        return PermissionCategory::class;
    }
}