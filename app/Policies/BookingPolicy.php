<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
            return true;

        return $admin->can('view_any_booking');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin | Company | User $admin, Booking $booking): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('view_booking');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('create_booking');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin | Company | User $admin, Booking $booking): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('update_booking');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin | Company | User $admin, Booking $booking): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('delete_booking');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('delete_any_booking');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Admin | Company | User $admin, Booking $booking): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('force_delete_booking');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('force_delete_any_booking');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Admin | Company | User $admin, Booking $booking): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('restore_booking');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('restore_any_booking');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin | Company | User $admin, Booking $booking): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('replicate_booking');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin | Company | User $admin): bool
    {
        if ($admin instanceof Company || $admin instanceof User)
        return true;

        return $admin->can('reorder_booking');
    }
}
