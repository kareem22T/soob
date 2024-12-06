<?php

namespace App\Policies;

use App\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Model $admin): bool
    {
        return $admin->can('view_any_booking');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Model $admin, Booking $booking): bool
    {
        return $admin->can('view_booking');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Model $admin): bool
    {
        return $admin->can('create_booking');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Model $admin, Booking $booking): bool
    {
        return $admin->can('update_booking');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Model $admin, Booking $booking): bool
    {
        return $admin->can('delete_booking');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Model $admin): bool
    {
        return $admin->can('delete_any_booking');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Model $admin, Booking $booking): bool
    {
        return $admin->can('force_delete_booking');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Model $admin): bool
    {
        return $admin->can('force_delete_any_booking');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Model $admin, Booking $booking): bool
    {
        return $admin->can('restore_booking');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Model $admin): bool
    {
        return $admin->can('restore_any_booking');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Model $admin, Booking $booking): bool
    {
        return $admin->can('replicate_booking');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Model $admin): bool
    {
        return $admin->can('reorder_booking');
    }
}
