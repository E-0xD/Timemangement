<?php

namespace Database\Seeders;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'student@studyflow.app')->first();

        if (! $student) {
            return;
        }

        $csDept   = Department::where('code', 'CS')->first();
        $semester = Semester::where('is_current', true)->first();

        $courses = [
            [
                'name'          => 'Data Structures & Algorithms',
                'code'          => 'CS301',
                'lecturer'      => 'Dr. Smith',
                'color'         => '#3B82F6',
                'department_id' => $csDept?->id,
                'semester_id'   => $semester?->id,
            ],
            [
                'name'          => 'Database Management Systems',
                'code'          => 'CS302',
                'lecturer'      => 'Prof. Johnson',
                'color'         => '#10B981',
                'department_id' => $csDept?->id,
                'semester_id'   => $semester?->id,
            ],
            [
                'name'          => 'Software Engineering',
                'code'          => 'CS303',
                'lecturer'      => 'Dr. Williams',
                'color'         => '#F59E0B',
                'department_id' => $csDept?->id,
                'semester_id'   => $semester?->id,
            ],
            [
                'name'          => 'Operating Systems',
                'code'          => 'CS304',
                'lecturer'      => 'Prof. Brown',
                'color'         => '#8B5CF6',
                'department_id' => $csDept?->id,
                'semester_id'   => $semester?->id,
            ],
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(
                ['user_id' => $student->id, 'code' => $courseData['code']],
                array_merge($courseData, ['user_id' => $student->id])
            );
        }
    }
}
