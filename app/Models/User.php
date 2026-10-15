<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function accessRules()
    {
        return $this->hasMany(UserAccessRule::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function hasFullAccess(): bool
    {
        if ($this->hasAnyRole(['Super Admin', 'Admin Kesekretariatan'])) {
            return true;
        }
        if ($this->access_mode === 'all') {
            return true;
        }
        return $this->groups()->where('access_mode', 'all')->exists();
    }

    public function getDirectCategoryIds(): array
    {
        $userCatIds = $this->accessRules()
            ->where('accessible_type', Category::class)
            ->pluck('accessible_id')
            ->toArray();

        $groupIds = $this->groups()->pluck('groups.id')->toArray();
        $groupCatIds = GroupAccessRule::whereIn('group_id', $groupIds)
            ->where('accessible_type', Category::class)
            ->pluck('accessible_id')
            ->toArray();

        return array_values(array_unique(array_merge($userCatIds, $groupCatIds)));
    }

    public function getDirectInformationIds(): array
    {
        $userInfoIds = $this->accessRules()
            ->where('accessible_type', Information::class)
            ->pluck('accessible_id')
            ->toArray();

        $groupIds = $this->groups()->pluck('groups.id')->toArray();
        $groupInfoIds = GroupAccessRule::whereIn('group_id', $groupIds)
            ->where('accessible_type', Information::class)
            ->pluck('accessible_id')
            ->toArray();

        return array_values(array_unique(array_merge($userInfoIds, $groupInfoIds)));
    }

    public function getDirectVersionIds(): array
    {
        $userVerIds = $this->accessRules()
            ->where('accessible_type', InformationVersion::class)
            ->pluck('accessible_id')
            ->toArray();

        $groupIds = $this->groups()->pluck('groups.id')->toArray();
        $groupVerIds = GroupAccessRule::whereIn('group_id', $groupIds)
            ->where('accessible_type', InformationVersion::class)
            ->pluck('accessible_id')
            ->toArray();

        return array_values(array_unique(array_merge($userVerIds, $groupVerIds)));
    }

    public function getAllowedCategoryIds(): array
    {
        if ($this->hasFullAccess()) {
            return Category::pluck('id')->toArray();
        }

        $directCatIds = $this->getDirectCategoryIds();
        $directInfoIds = $this->getDirectInformationIds();
        $directVerIds = $this->getDirectVersionIds();

        $catFromInfoIds = Information::whereIn('id', $directInfoIds)->pluck('category_id')->toArray();
        $infoFromVerIds = InformationVersion::whereIn('id', $directVerIds)->pluck('information_id')->toArray();
        $catFromVerIds = Information::whereIn('id', $infoFromVerIds)->pluck('category_id')->toArray();

        return array_values(array_unique(array_merge($directCatIds, $catFromInfoIds, $catFromVerIds)));
    }

    public function getAllowedInformationIds(): array
    {
        if ($this->hasFullAccess()) {
            return Information::pluck('id')->toArray();
        }

        $directCatIds = $this->getDirectCategoryIds();
        $directInfoIds = $this->getDirectInformationIds();
        $directVerIds = $this->getDirectVersionIds();

        $infoFromCatIds = Information::whereIn('category_id', $directCatIds)->pluck('id')->toArray();
        $infoFromVerIds = InformationVersion::whereIn('id', $directVerIds)->pluck('information_id')->toArray();

        return array_values(array_unique(array_merge($directInfoIds, $infoFromCatIds, $infoFromVerIds)));
    }

    public function getAllowedVersionIds(): array
    {
        if ($this->hasFullAccess()) {
            return InformationVersion::pluck('id')->toArray();
        }

        $directCatIds = $this->getDirectCategoryIds();
        $directInfoIds = $this->getDirectInformationIds();
        $directVerIds = $this->getDirectVersionIds();

        // Versions from direct categories
        $verFromCatIds = InformationVersion::whereHas('information', function ($q) use ($directCatIds) {
            $q->whereIn('category_id', $directCatIds);
        })->pluck('id')->toArray();

        // Versions from direct informations
        $verFromInfoIds = InformationVersion::whereIn('information_id', $directInfoIds)->pluck('id')->toArray();

        return array_values(array_unique(array_merge($directVerIds, $verFromCatIds, $verFromInfoIds)));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'access_mode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'is_active' => true,
        'access_mode' => 'custom',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
