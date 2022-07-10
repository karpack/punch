<?php

namespace Karpack\Punch\Models;

use Karpack\Hexagon\Models\Model;

class PermissionCategory extends Model
{
    /**
     * All the permissions linked to this category.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}