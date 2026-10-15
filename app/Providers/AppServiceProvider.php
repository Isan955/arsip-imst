<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\Attachment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bypass checks for Admins & Super Admins or users with full access
        Gate::before(function (User $user) {
            if ($user->hasAnyRole(['Super Admin', 'Admin Kesekretariatan'])) {
                return true;
            }
            if ($user->hasFullAccess()) {
                return true;
            }
        });

        // 1. Gate: view-category
        Gate::define('view-category', function (User $user, Category $category) {
            if ($user->hasFullAccess()) {
                return true;
            }
            return in_array($category->id, $user->getAllowedCategoryIds());
        });

        // 2. Gate: view-information
        Gate::define('view-information', function (User $user, Information $information) {
            if ($user->hasFullAccess()) {
                return true;
            }
            return in_array($information->id, $user->getAllowedInformationIds());
        });

        // 3. Gate: view-version
        Gate::define('view-version', function (User $user, InformationVersion $version) {
            if ($user->hasFullAccess()) {
                return true;
            }
            return in_array($version->id, $user->getAllowedVersionIds());
        });

        // 4. Gate: stream-attachment
        Gate::define('stream-attachment', function (User $user, Attachment $attachment) {
            return Gate::forUser($user)->allows('view-version', $attachment->version);
        });
    }
}
