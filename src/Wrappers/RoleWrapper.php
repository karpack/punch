<?php

namespace Karpack\Punch\Wrappers;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Karpack\Hexagon\Wrappers\SimpleModelWrapper;

/**
 * Magic methods and properties
 * 
 * @property \Spatie\Permission\Models\Role $role
 * @method \Spatie\Permission\Models\Role role()
 */
class RoleWrapper extends SimpleModelWrapper
{
    /**
     * Sets the permission on a new guard.
     * 
     * @param string $guard
     * @return static
     */
    public function setGuard($guard)
    {
        $this->role->guard_name = $guard;

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
        $this->role->name = $data->get('name');
        $this->role->description = $data->get('description');

        return $this;
    }

    /**
     * Returns all the validation rules of a model. The argument `$model` can
     * be used for removing unique constraint checks.
     * 
     * @return array
     */
    public function validationRules($role = null)
    {
        $uniqueName = Rule::unique($this->role()->getTable(), 'name');

        if (!is_null($role)) {
            $uniqueName = $uniqueName->ignoreModel($role);
        }
        return [
            'name' => ['required', $uniqueName]
        ];
    }
}
