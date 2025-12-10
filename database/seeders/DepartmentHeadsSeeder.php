<?php

namespace Database\Seeders;

use App\Models\DepartmentHead;
use Illuminate\Database\Seeder;

class DepartmentHeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department_key' => 'quality_assurance',
                'department_name' => 'Quality Assurance',
                'head_name' => 'Dr. Sarah Johnson',
                'head_email' => 'sarah.johnson@kijabe.org',
                'cc_emails' => 'qa.team@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'simulation_manager',
                'department_name' => 'Simulation Manager',
                'head_name' => 'Dr. Michael Chen',
                'head_email' => 'michael.chen@kijabe.org',
                'cc_emails' => 'simulation@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'gme',
                'department_name' => 'GME (Graduate Medical Education)',
                'head_name' => 'Dr. Emily Roberts',
                'head_email' => 'emily.roberts@kijabe.org',
                'cc_emails' => 'gme.admin@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'customer_satisfaction_chair',
                'department_name' => 'Customer Satisfaction Chair',
                'head_name' => 'Dr. James Williams',
                'head_email' => 'james.williams@kijabe.org',
                'cc_emails' => 'customer.satisfaction@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'cpd_coordinator',
                'department_name' => 'CPD Coordinator',
                'head_name' => 'Dr. Patricia Martinez',
                'head_email' => 'patricia.martinez@kijabe.org',
                'cc_emails' => 'cpd@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'quality_improvement',
                'department_name' => 'Quality Improvement Officer',
                'head_name' => 'Dr. David Brown',
                'head_email' => 'david.brown@kijabe.org',
                'cc_emails' => 'qi.team@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'internship_coordinator',
                'department_name' => 'Internship Coordinator',
                'head_name' => 'Dr. Lisa Anderson',
                'head_email' => 'lisa.anderson@kijabe.org',
                'cc_emails' => 'internships@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'visitors_gme_coordinator',
                'department_name' => 'Visitors GME Coordinator',
                'head_name' => 'Dr. Robert Taylor',
                'head_email' => 'robert.taylor@kijabe.org',
                'cc_emails' => 'visitors.gme@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'elearning_coordinator',
                'department_name' => 'E-Learning Coordinator',
                'head_name' => 'Dr. Jennifer Lee',
                'head_email' => 'jennifer.lee@kijabe.org',
                'cc_emails' => 'elearning@kijabe.org',
                'is_active' => true,
            ],
            [
                'department_key' => 'research_coordinator',
                'department_name' => 'Research Coordinator',
                'head_email' => 'thomas.wilson@kijabe.org',
                'head_name' => 'Dr. Thomas Wilson',
                'cc_emails' => 'research@kijabe.org',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            DepartmentHead::updateOrCreate(
                ['department_key' => $department['department_key']],
                $department
            );
        }
    }
}
