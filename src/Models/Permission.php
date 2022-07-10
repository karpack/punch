<?php

namespace Karpack\Punch\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    const CREATE_PERMISSIONS = 'create-permissions';
    const READ_PERMISSIONS = 'read-permissions';
    const UPDATE_PERMISSIONS = 'update-permissions';
    const DELETE_PERMISSIONS = 'delete-permissions';

    const CREATE_ROLES = 'create-roles';
    const READ_ROLES = 'read-roles';
    const UPDATE_ROLES = 'update-roles';
    const DELETE_ROLES = 'delete-roles';

    /**
     * Returns the category to which this permission belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(PermissionCategory::class);
    }
}
