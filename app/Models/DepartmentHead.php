<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentHead extends Model
{
    protected $fillable = [
        'department_key',
        'department_name',
        'head_name',
        'head_email',
        'cc_emails',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all email addresses for this department (head + CC)
     */
    public function getAllEmails(): array
    {
        $emails = [$this->head_email];
        
        if ($this->cc_emails) {
            $ccEmails = array_map('trim', explode(',', $this->cc_emails));
            $emails = array_merge($emails, $ccEmails);
        }
        
        return array_filter($emails);
    }

    /**
     * Get department head by department key
     */
    public static function getByDepartment(string $departmentKey): ?self
    {
        return self::where('department_key', $departmentKey)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get all active departments
     */
    public static function getActiveDepartments(): array
    {
        return self::where('is_active', true)
            ->pluck('department_name', 'department_key')
            ->toArray();
    }
}
