<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'user_type',
        'status',
        'email',
        'email_verified_at',
        'nim',
        'nip',
        'role',
        'password',
    ];

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            if (empty($user->name)) {
                $user->name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            }
        });
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function userProfile() {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * Admin dashboard: pakai kolom role jika ada, fallback ke user_type (schema Hope UI).
     */
    public function isDashboardAdmin(): bool
    {
        if ($this->hasAttribute('role') && $this->role === 'admin') {
            return true;
        }

        return in_array($this->user_type, ['admin', 'demo_admin'], true);
    }

    public function isActiveAccount(): bool
    {
        return ($this->status ?? 'active') === 'active';
    }
}
