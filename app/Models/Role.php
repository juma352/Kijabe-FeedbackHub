<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get the permissions associated with this role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission')->select('permissions.id', 'permissions.name', 'permissions.display_name', 'permissions.description');
    }

    /**
     * Get the users associated with this role
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        return $this->permissions()->where('permissions.id', $permission->id)->exists();
    }

    /**
     * Give a permission to this role
     */
    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        $this->permissions()->attach($permission);
    }

    /**
     * Remove a permission from this role
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        $this->permissions()->detach($permission);
    }
}
