<?php

namespace App\Policies;

use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Model $admin): bool
    {
        return $admin->can('view_any_role');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Model $admin, Role $role): bool
    {
        return $admin->can('view_role');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Model $admin): bool
    {
        return $admin->can('create_role');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Model $admin, Role $role): bool
    {
        return $admin->can('update_role');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Model $admin, Role $role): bool
    {
        return $admin->can('delete_role');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Model $admin): bool
    {
        return $admin->can('delete_any_role');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Model $admin, Role $role): bool
    {
        return $admin->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Model $admin): bool
    {
        return $admin->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Model $admin, Role $role): bool
    {
        return $admin->can('{{ Restore }}');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Model $admin): bool
    {
        return $admin->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Model $admin, Role $role): bool
    {
        return $admin->can('{{ Replicate }}');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Model $admin): bool
    {
        return $admin->can('{{ Reorder }}');
    }
}
