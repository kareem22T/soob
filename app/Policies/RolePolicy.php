<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin | Employee $admin): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('view_any_role');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin | Employee $admin, Role $role): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('view_role');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin | Employee $admin): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('create_role');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin | Employee $admin, Role $role): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('update_role');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin | Employee $admin, Role $role): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('delete_role');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Admin | Employee $admin): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('delete_any_role');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Admin | Employee $admin, Role $role): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Admin | Employee $admin): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Admin | Employee $admin, Role $role): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('{{ Restore }}');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Admin | Employee $admin): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin | Employee $admin, Role $role): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('{{ Replicate }}');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin | Employee $admin): bool
    {
        if ($this->isAuth($admin)) {
            return true;
        }

        return $admin->can('{{ Reorder }}');
    }

    public function isAuth($admin): bool {
        if ($admin instanceof Employee && $admin->member_role == "SEO")
            return true;

        return false;
    }

}
