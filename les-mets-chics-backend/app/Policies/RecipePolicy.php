<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecipePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin and authors can view recipes in Filament
        return in_array($user->role, ['admin', 'auteur']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Recipe $recipe): bool
    {
        // Admin can view all, authors can view their own
        return $user->role === 'admin' || $recipe->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin and authors can create recipes
        return in_array($user->role, ['admin', 'auteur']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        // Admin can update all, authors can update only their own
        return $user->role === 'admin' || $recipe->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        // Admin can delete all, authors can delete only their own
        return $user->role === 'admin' || $recipe->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recipe $recipe): bool
    {
        // Admin can restore all, authors can restore only their own
        return $user->role === 'admin' || $recipe->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recipe $recipe): bool
    {
        // Only admin can force delete
        return $user->role === 'admin';
    }
}
