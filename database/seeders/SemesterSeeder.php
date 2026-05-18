<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            [
                'name'       => 'First Semester 2024/2025',
                'start_date' => '2024-09-01',
                'end_date'   => '2025-01-31',
                'is_current' => false,
            ],
            [
                'name'       => 'Second Semester 2024/2025',
                'start_date' => '2025-02-01',
                'end_date'   => '2025-06-30',
                'is_current' => false,
            ],
            [
                'name'       => 'First Semester 2025/2026',
                'start_date' => '2025-09-01',
                'end_date'   => '2026-01-31',
                'is_current' => true,
            ],
        ];

        foreach ($semesters as $sem) {
            Semester::firstOrCreate(['name' => $sem['name']], $sem);
        }
    }
}
