<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Computer Science',            'code' => 'CS',   'description' => 'Computing, algorithms, and software engineering.'],
            ['name' => 'Information Technology',      'code' => 'IT',   'description' => 'Information systems and technology management.'],
            ['name' => 'Electrical Engineering',      'code' => 'EE',   'description' => 'Electronics, circuits, and power systems.'],
            ['name' => 'Mechanical Engineering',      'code' => 'ME',   'description' => 'Mechanics, thermodynamics, and manufacturing.'],
            ['name' => 'Civil Engineering',           'code' => 'CE',   'description' => 'Structures, infrastructure, and environmental systems.'],
            ['name' => 'Business Administration',     'code' => 'BA',   'description' => 'Management, finance, and entrepreneurship.'],
            ['name' => 'Accounting & Finance',        'code' => 'AF',   'description' => 'Financial accounting, auditing, and taxation.'],
            ['name' => 'Medicine',                    'code' => 'MED',  'description' => 'Medical sciences and clinical practice.'],
            ['name' => 'Pharmacy',                    'code' => 'PHARM','description' => 'Pharmaceutical sciences and drug management.'],
            ['name' => 'Law',                         'code' => 'LAW',  'description' => 'Legal studies, jurisprudence, and advocacy.'],
            ['name' => 'Architecture',                'code' => 'ARCH', 'description' => 'Building design, urban planning, and construction.'],
            ['name' => 'Mathematics & Statistics',   'code' => 'MATH', 'description' => 'Pure and applied mathematics.'],
            ['name' => 'Physics',                     'code' => 'PHY',  'description' => 'Classical and modern physics.'],
            ['name' => 'Chemistry',                   'code' => 'CHEM', 'description' => 'Organic, inorganic, and physical chemistry.'],
            ['name' => 'Biology',                     'code' => 'BIO',  'description' => 'Life sciences, ecology, and genetics.'],
            ['name' => 'Economics',                   'code' => 'ECON', 'description' => 'Microeconomics, macroeconomics, and economic policy.'],
            ['name' => 'Psychology',                  'code' => 'PSY',  'description' => 'Human behaviour, cognition, and mental health.'],
            ['name' => 'Education',                   'code' => 'EDU',  'description' => 'Teaching, curriculum design, and learning science.'],
            ['name' => 'Mass Communication',          'code' => 'COMM', 'description' => 'Journalism, media studies, and public relations.'],
            ['name' => 'Environmental Science',       'code' => 'ENV',  'description' => 'Ecology, sustainability, and environmental management.'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
