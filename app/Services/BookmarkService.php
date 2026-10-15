<?php

namespace App\Services;

use App\Models\Bookmark;
use App\Models\Information;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookmarkService
{
    public function toggleBookmark(int $informationId, int $userId): bool
    {
        $bookmark = Bookmark::where('user_id', $userId)
            ->where('information_id', $informationId)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return false; // unbookmarked
        }

        Bookmark::create([
            'user_id' => $userId,
            'information_id' => $informationId,
        ]);

        return true; // bookmarked
    }

    public function isBookmarked(int $informationId, int $userId): bool
    {
        return Bookmark::where('user_id', $userId)
            ->where('information_id', $informationId)
            ->exists();
    }

    public function getUserBookmarks(int $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return collect();
        }

        $hasFullAccess = $user->hasFullAccess();
        $allowedInfoIds = $hasFullAccess ? [] : $user->getAllowedInformationIds();

        $query = Information::published()->whereHas('bookmarks', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('category');

        if (!$hasFullAccess) {
            $query->whereIn('id', $allowedInfoIds);
        }

        return $query->get();
    }
}
