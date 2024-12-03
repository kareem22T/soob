<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin | Employee| User $admin): bool
    {
        if ($this->isAuth($admin) || $admin instanceof User)
            return true;

        return $admin->can('view_any_offer');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin | Employee| User $admin, Offer $offer): bool
    {


        return $admin->can('view_offer');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin | Employee| User $admin): bool
    {


        return $admin->can('create_offer');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin | Employee| User $admin, Offer $offer): bool
    {


        return $admin->can('update_offer');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin | Employee| User $admin, Offer $offer): bool
    {


        return $admin->can('delete_offer');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Admin | Employee| User $admin): bool
    {


        return $admin->can('delete_any_offer');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Admin | Employee| User $admin, Offer $offer): bool
    {


        return $admin->can('force_delete_offer');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Admin | Employee| User $admin): bool
    {


        return $admin->can('force_delete_any_offer');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Admin | Employee| User $admin, Offer $offer): bool
    {


        return $admin->can('restore_offer');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Admin | Employee| User $admin): bool
    {


        return $admin->can('restore_any_offer');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin | Employee| User $admin, Offer $offer): bool
    {


        return $admin->can('replicate_offer');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin | Employee| User $admin): bool
    {


        return $admin->can('reorder_offer');
    }

    public function __call($method, $arguments)
    {
        // Force return true if isAuth is true
        if ($this->isAuth($arguments)) {
            return true;
        }

        // If not forced, call the actual method
        return call_user_func_array([$this, $method], $arguments);
    }

    public function isAuth($admin): bool {
        if ($admin instanceof Employee && $admin->member_role == "SEO")
            return true;

        return false;
    }

}
