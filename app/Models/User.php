<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
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
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get the role associated with this user
     */
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (!$this->roleModel) {
            return false;
        }

        return $this->roleModel->hasPermission($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return collect($permissions)->some(function ($permission) {
            return $this->hasPermission($permission);
        });
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return collect($permissions)->every(function ($permission) {
            return $this->hasPermission($permission);
        });
    }

    /**
     * Get the forms for the user.
     */
    public function forms()
    {
        return $this->hasMany(Form::class);
    }
}
