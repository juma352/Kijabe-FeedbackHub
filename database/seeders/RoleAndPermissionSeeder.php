<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create all roles
        $roles = [
            [
                'name' => 'quality_assurance',
                'display_name' => 'Quality Assurance',
                'description' => 'Responsible for quality assurance operations and feedback analysis'
            ],
            [
                'name' => 'simulation_manager',
                'display_name' => 'Simulation Manager',
                'description' => 'Manages simulation programs and related feedback'
            ],
            [
                'name' => 'gme_manager',
                'display_name' => 'GME Manager',
                'description' => 'Graduate Medical Education manager'
            ],
            [
                'name' => 'customer_satisfaction_chair',
                'display_name' => 'Customer Satisfaction Committee Chair',
                'description' => 'Chairs KCHS customer satisfaction committee'
            ],
            [
                'name' => 'cpd_coordinator',
                'display_name' => 'CPD Coordinator',
                'description' => 'Continuous Professional Development coordinator'
            ],
            [
                'name' => 'quality_improvement',
                'display_name' => 'Quality Improvement Officer',
                'description' => 'Manages quality improvement initiatives'
            ],
            [
                'name' => 'internship_coordinator',
                'display_name' => 'Internship Coordinator',
                'description' => 'Coordinates internship programs'
            ],
            [
                'name' => 'visitors_gme_coordinator',
                'display_name' => 'Visitors GME Coordinator',
                'description' => 'Coordinates GME for visitors'
            ],
            [
                'name' => 'elearning_coordinator',
                'display_name' => 'E-Learning Coordinator',
                'description' => 'Manages e-learning programs and content'
            ],
            [
                'name' => 'research_coordinator',
                'display_name' => 'Research Coordinator',
                'description' => 'Coordinates research initiatives'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create permissions
        $permissions = [
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'description' => 'Can view the main dashboard'],
            ['name' => 'view_feedback', 'display_name' => 'View Feedback', 'description' => 'Can view feedback items'],
            ['name' => 'manage_feedback', 'display_name' => 'Manage Feedback', 'description' => 'Can edit and manage feedback'],
            ['name' => 'require_action', 'display_name' => 'Require Action', 'description' => 'Can mark feedback as requiring action'],
            ['name' => 'send_notifications', 'display_name' => 'Send Notifications', 'description' => 'Can send bulk notifications'],
            ['name' => 'view_analytics', 'display_name' => 'View Analytics', 'description' => 'Can view feedback analytics'],
            ['name' => 'create_form', 'display_name' => 'Create Form', 'description' => 'Can create new forms'],
            ['name' => 'manage_form', 'display_name' => 'Manage Form', 'description' => 'Can manage forms'],
            ['name' => 'view_form_analytics', 'display_name' => 'View Form Analytics', 'description' => 'Can view form analytics'],
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'description' => 'Can manage user accounts and roles'],
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'description' => 'Can manage roles and permissions'],
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'description' => 'Can view system reports'],
            ['name' => 'export_data', 'display_name' => 'Export Data', 'description' => 'Can export feedback and form data'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        // Quality Assurance - view and manage feedback
        $qualityAssurance = Role::where('name', 'quality_assurance')->first();
        $qualityAssurance->givePermission('view_dashboard');
        $qualityAssurance->givePermission('view_feedback');
        $qualityAssurance->givePermission('manage_feedback');
        $qualityAssurance->givePermission('require_action');
        $qualityAssurance->givePermission('view_analytics');

        // Simulation Manager - manage forms related to simulations
        $simulationManager = Role::where('name', 'simulation_manager')->first();
        $simulationManager->givePermission('view_dashboard');
        $simulationManager->givePermission('view_feedback');
        $simulationManager->givePermission('create_form');
        $simulationManager->givePermission('manage_form');
        $simulationManager->givePermission('view_form_analytics');

        // GME Manager - view and manage
        $gmeManager = Role::where('name', 'gme_manager')->first();
        $gmeManager->givePermission('view_dashboard');
        $gmeManager->givePermission('view_feedback');
        $gmeManager->givePermission('manage_feedback');
        $gmeManager->givePermission('view_analytics');
        $gmeManager->givePermission('view_reports');

        // Customer Satisfaction Chair - view feedback and analytics
        $custSatisfaction = Role::where('name', 'customer_satisfaction_chair')->first();
        $custSatisfaction->givePermission('view_dashboard');
        $custSatisfaction->givePermission('view_feedback');
        $custSatisfaction->givePermission('view_analytics');
        $custSatisfaction->givePermission('view_reports');
        $custSatisfaction->givePermission('send_notifications');

        // CPD Coordinator - manage forms for CPD
        $cpdCoordinator = Role::where('name', 'cpd_coordinator')->first();
        $cpdCoordinator->givePermission('view_dashboard');
        $cpdCoordinator->givePermission('create_form');
        $cpdCoordinator->givePermission('manage_form');
        $cpdCoordinator->givePermission('view_form_analytics');

        // Quality Improvement - full feedback access
        $qualityImprovement = Role::where('name', 'quality_improvement')->first();
        $qualityImprovement->givePermission('view_dashboard');
        $qualityImprovement->givePermission('view_feedback');
        $qualityImprovement->givePermission('manage_feedback');
        $qualityImprovement->givePermission('require_action');
        $qualityImprovement->givePermission('send_notifications');
        $qualityImprovement->givePermission('view_analytics');
        $qualityImprovement->givePermission('view_reports');

        // Internship Coordinator - manage forms
        $internshipCoordinator = Role::where('name', 'internship_coordinator')->first();
        $internshipCoordinator->givePermission('view_dashboard');
        $internshipCoordinator->givePermission('create_form');
        $internshipCoordinator->givePermission('manage_form');
        $internshipCoordinator->givePermission('view_form_analytics');

        // Visitors GME Coordinator - manage forms
        $visitorsGme = Role::where('name', 'visitors_gme_coordinator')->first();
        $visitorsGme->givePermission('view_dashboard');
        $visitorsGme->givePermission('create_form');
        $visitorsGme->givePermission('manage_form');
        $visitorsGme->givePermission('view_form_analytics');

        // E-Learning Coordinator - manage forms
        $elearning = Role::where('name', 'elearning_coordinator')->first();
        $elearning->givePermission('view_dashboard');
        $elearning->givePermission('create_form');
        $elearning->givePermission('manage_form');
        $elearning->givePermission('view_form_analytics');

        // Research Coordinator - view analytics and export data
        $research = Role::where('name', 'research_coordinator')->first();
        $research->givePermission('view_dashboard');
        $research->givePermission('view_feedback');
        $research->givePermission('view_analytics');
        $research->givePermission('view_reports');
        $research->givePermission('export_data');
    }
}
