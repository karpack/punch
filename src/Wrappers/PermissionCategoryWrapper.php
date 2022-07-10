<?php

namespace Karpack\Punch\Wrappers;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Karpack\Hexagon\Wrappers\SimpleModelWrapper;

/**
 * Magic methods and properties
 * 
 * @property \Karpack\Punch\Models\PermissionCategory $category
 * @method \Karpack\Punch\Models\PermissionCategory category()
 * @method int id()
 */
class PermissionCategoryWrapper extends SimpleModelWrapper
{
    /**
     * Sets the the given data on the model. This won't save it to the database.
     * 
     * @param \Illuminate\Support\Collection $data
     * @return static
     */
    public function setData(Collection $data)
    {
        $this->category->name = $data->get('name');
        $this->category->description = $data->get('description');

        return $this;
    }

    /**
     * Returns all the validation rules of a model. The argument `$model` can
     * be used for removing unique constraint checks.
     * 
     * @return array
     */
    public function validationRules($category = null)
    {
        $uniqueName = Rule::unique('permission_categories', 'name');

        if (!is_null($category)) {
            $uniqueName = $uniqueName->ignoreModel($category);
        }
        return [
            'name' => ['required', $uniqueName]
        ];
    }

    /**
     * Returns the alias that can be used to get the model used by this wrapper.
     * 
     * @return string
     */
    protected function modelName()
    {
        return 'category';
    }
}
