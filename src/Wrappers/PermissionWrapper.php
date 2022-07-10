<?php

namespace Karpack\Punch\Wrappers;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Karpack\Hexagon\Wrappers\SimpleModelWrapper;

/**
 * Magic methods and properties
 * 
 * @property \Karpack\Punch\Models\Permission $permission
 * @method \Karpack\Punch\Models\Permission permission()
 * @method int id()
 */
class PermissionWrapper extends SimpleModelWrapper
{
    /**
     * Sets the permission category .
     * 
     * @param \Karpack\Punch\Models\PermissionCategory $category
     * @return static
     */
    public function setCategory($category)
    {
        $this->permission->category()->associate($category);

        return $this;
    }

    /**
     * Sets the guard for which this permission should be valid.
     * 
     * @param string $guard
     * @return static
     */
    public function setGuard($guard)
    {
        $this->permission->guard_name = $guard;

        return $this;
    }

    /**
     * Sets the the given data on the model. This won't save it to the database.
     * 
     * @param \Illuminate\Support\Collection $data
     * @return static
     */
    public function setData(Collection $data)
    {
        $this->permission->name = $data->get('name');
        $this->permission->description = $data->get('description');

        return $this;
    }

    /**
     * Returns all the validation rules of a model. The argument `$model` can
     * be used for removing unique constraint checks.
     * 
     * @return array
     */
    public function validationRules($permission = null)
    {
        $uniqueName = Rule::unique($this->permission()->getTable(), 'name');

        if (!is_null($permission)) {
            $uniqueName = $uniqueName->ignoreModel($permission);
        }
        return [
            'name' => ['required', $uniqueName]
        ];
    }
}
