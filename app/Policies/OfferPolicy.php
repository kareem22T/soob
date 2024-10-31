<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
            return true;

        return $admin->can('view_any_offer');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin | Company | User $admin, Offer $offer): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('view_offer');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('create_offer');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin | Company | User $admin, Offer $offer): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('update_offer');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin | Company | User $admin, Offer $offer): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('delete_offer');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('delete_any_offer');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Admin | Company | User $admin, Offer $offer): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('force_delete_offer');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('force_delete_any_offer');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Admin | Company | User $admin, Offer $offer): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('restore_offer');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('restore_any_offer');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin | Company | User $admin, Offer $offer): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('replicate_offer');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('reorder_offer');
    }
}
