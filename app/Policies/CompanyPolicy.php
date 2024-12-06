<?php

namespace App\Policies;

use App\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Model $admin): bool
    {
        return $admin->can('view_any_company');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Model $admin, Company $company): bool
    {
        return $admin->can('view_company');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Model $admin): bool
    {
        return $admin->can('create_company');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Model $admin, Company $company): bool
    {
        return $admin->can('update_company');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Model $admin, Company $company): bool
    {
        return $admin->can('delete_company');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Model $admin): bool
    {
        return $admin->can('delete_any_company');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Model $admin, Company $company): bool
    {
        return $admin->can('force_delete_company');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Model $admin): bool
    {
        return $admin->can('force_delete_any_company');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Model $admin, Company $company): bool
    {
        return $admin->can('restore_company');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Model $admin): bool
    {
        return $admin->can('restore_any_company');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Model $admin, Company $company): bool
    {
        return $admin->can('replicate_company');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Model $admin): bool
    {
        return $admin->can('reorder_company');
    }
}
