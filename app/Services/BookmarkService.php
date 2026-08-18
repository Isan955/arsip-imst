<?php

namespace App\Services;

use App\Models\Bookmark;
use App\Models\Information;
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
        return Information::whereHas('bookmarks', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('category')->get();
    }
}
