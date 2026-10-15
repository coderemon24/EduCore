<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the administrative dashboard landing page.
     */
    public function index()
    {
        $metrics = [
            'total_students' => [
                'count' => 1485,
                'growth' => '+4.8%',
                'boys' => 780,
                'girls' => 705,
            ],
            'total_teachers' => [
                'count' => 92,
                'on_duty' => 88,
                'on_leave' => 4,
            ],
            'today_attendance' => [
                'rate' => 96.2,
                'present' => 1428,
                'absent' => 57,
            ],
            'fee_collection' => [
                'collected' => 148250,
                'target' => 165000,
                'percentage' => 89.8,
            ],
        ];

        $recentAdmissions = [
            [
                'id' => 'ADM-2026-0412',
                'name' => 'Sophia Montgomery',
                'grade' => 'Grade 10',
                'section' => 'Section A',
                'gender' => 'Female',
                'guardian' => 'Marcus Montgomery',
                'phone' => '+1 (555) 234-8901',
                'date' => 'Oct 14, 2026',
                'status' => 'Enrolled',
            ],
            [
                'id' => 'ADM-2026-0411',
                'name' => 'Liam Alexander Vance',
                'grade' => 'Grade 8',
                'section' => 'Section B',
                'gender' => 'Male',
                'guardian' => 'Elena Vance',
                'phone' => '+1 (555) 872-1144',
                'date' => 'Oct 14, 2026',
                'status' => 'Enrolled',
            ],
            [
                'id' => 'ADM-2026-0410',
                'name' => 'Aarav Patel',
                'grade' => 'Grade 6',
                'section' => 'Section A',
                'gender' => 'Male',
                'guardian' => 'Devendra Patel',
                'phone' => '+1 (555) 431-9082',
                'date' => 'Oct 13, 2026',
                'status' => 'Pending Verification',
            ],
            [
                'id' => 'ADM-2026-0409',
                'name' => 'Charlotte Dubois',
                'grade' => 'Grade 11',
                'section' => 'Science A',
                'gender' => 'Female',
                'guardian' => 'Claire Dubois',
                'phone' => '+1 (555) 678-3490',
                'date' => 'Oct 12, 2026',
                'status' => 'Enrolled',
            ],
            [
                'id' => 'ADM-2026-0408',
                'name' => 'Ethan Walker',
                'grade' => 'Grade 4',
                'section' => 'Section C',
                'gender' => 'Male',
                'guardian' => 'Robert Walker',
                'phone' => '+1 (555) 912-7801',
                'date' => 'Oct 11, 2026',
                'status' => 'Enrolled',
            ],
        ];

        $attendanceByGrade = [
            ['grade' => 'Grade 1', 'present' => 98, 'percentage' => 98],
            ['grade' => 'Grade 2', 'present' => 95, 'percentage' => 95],
            ['grade' => 'Grade 3', 'present' => 96, 'percentage' => 96],
            ['grade' => 'Grade 4', 'present' => 94, 'percentage' => 94],
            ['grade' => 'Grade 5', 'present' => 97, 'percentage' => 97],
            ['grade' => 'Grade 6', 'present' => 93, 'percentage' => 93],
            ['grade' => 'Grade 7', 'present' => 96, 'percentage' => 96],
            ['grade' => 'Grade 8', 'present' => 95, 'percentage' => 95],
            ['grade' => 'Grade 9', 'present' => 97, 'percentage' => 97],
            ['grade' => 'Grade 10', 'present' => 98, 'percentage' => 98],
        ];

        $todaySchedule = [
            [
                'time' => '08:30 AM',
                'title' => 'Morning Assembly & Oath',
                'location' => 'Main Auditorium',
                'type' => 'General',
                'status' => 'Completed',
            ],
            [
                'time' => '10:00 AM',
                'title' => 'Mid-Term Physics Lab Practical (Grade 11)',
                'location' => 'Science Wing - Lab 3',
                'type' => 'Examination',
                'status' => 'In Progress',
            ],
            [
                'time' => '01:30 PM',
                'title' => 'Academic Curriculum Review Meeting',
                'location' => 'Conference Room B',
                'type' => 'Faculty',
                'status' => 'Upcoming',
            ],
            [
                'time' => '03:15 PM',
                'title' => 'Interschool Basketball Practice',
                'location' => 'Sports Complex',
                'type' => 'Extracurricular',
                'status' => 'Upcoming',
            ],
        ];

        $notices = [
            [
                'title' => 'Parent-Teacher Conference Schedule (Fall 2026)',
                'category' => 'Academic',
                'date' => 'Oct 20, 2026',
                'priority' => 'High',
            ],
            [
                'title' => 'Annual Science & Robotics Exhibition Registration',
                'category' => 'Event',
                'date' => 'Oct 28, 2026',
                'priority' => 'Normal',
            ],
            [
                'title' => 'Term 1 Tuition Fee Clearance Deadline Notice',
                'category' => 'Accounts',
                'date' => 'Oct 31, 2026',
                'priority' => 'Urgent',
            ],
        ];

        return view('admin.dashboard', compact(
            'metrics',
            'recentAdmissions',
            'attendanceByGrade',
            'todaySchedule',
            'notices'
        ));
    }
}
