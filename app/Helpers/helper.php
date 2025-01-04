<?php

use Spatie\Permission\Models\Role;

function getRoleIdByName($roleName)
{
    $role = Role::where('name', $roleName)->first();
    return $role ? $role->id : null;
}
