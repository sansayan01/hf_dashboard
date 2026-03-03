<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserHierarchyObserver
{
    /**
     * Handle the User "saved" event (created or updated).
     */
    public function saved(User $user): void
    {
        // Always clear ancestors for the CURRENT parent
        $this->clearHierarchyCaches($user);

        // If parent_id changed, clear ancestors for the OLD parent too
        if (!$user->wasRecentlyCreated && $user->isDirty('parent_id')) {
            $oldParentId = $user->getOriginal('parent_id');
            if ($oldParentId) {
                $oldParent = User::find($oldParentId);
                if ($oldParent) {
                    $this->clearHierarchyCaches($oldParent);
                }
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->clearHierarchyCaches($user);
    }

    /**
     * Recursive helper to clear caches up the hierarchy
     */
    protected function clearHierarchyCaches(User $user): void
    {
        // 0. Clear the modified user's own cache
        $this->clearUserCache($user->id);

        // 1. Clear the user's direct parent/upline immediately.
        // We start with the parent because that's where the counts/lists were stale.
        $parentId = $user->parent_id ?: ($user->isOfficeInCharge() ? $user->upline_id : null);

        if ($parentId) {
            $processedIds = [$user->id];
            $currentId = $parentId;

            while ($currentId && !in_array($currentId, $processedIds)) {
                $this->clearUserCache($currentId);
                $processedIds[] = $currentId;

                // Get the next parent in line
                $parent = User::find($currentId);
                if (!$parent)
                    break;

                $currentId = $parent->parent_id ?: ($parent->isOfficeInCharge() ? $parent->upline_id : null);
            }
        }

        // 2. Always clear Super Admin caches as they often show global counts for the entire team
        $superAdminIds = User::where('designation', 'super_admin')->pluck('id');
        foreach ($superAdminIds as $saId) {
            $this->clearUserCache($saId);
        }
    }

    protected function clearUserCache(int $userId): void
    {
        $keys = [
            "user_{$userId}_downline_ids_v2",
            "user_{$userId}_dashboard_children_count_v2",
            "user_{$userId}_team_downline_ids",
            "user_{$userId}_downline_count_v2",
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
