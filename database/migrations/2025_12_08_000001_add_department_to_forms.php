<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            // Add department field if it doesn't exist
            if (!Schema::hasColumn('forms', 'department')) {
                $table->string('department')->nullable()->after('user_id');
            }
            
            // Add department_subdivision for sub-departments
            if (!Schema::hasColumn('forms', 'department_subdivision')) {
                $table->string('department_subdivision')->nullable()->after('department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn(['department', 'department_subdivision']);
        });
    }
};
