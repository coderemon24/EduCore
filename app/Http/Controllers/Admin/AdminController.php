<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display the school admin dashboard landing page.
     */
    public function index()
    {
        $kpi = [
            'students' => [
                'count' => '15,000',
                'raw' => 15000,
                'growth' => '+5.2%',
                'boys' => 7850,
                'girls' => 7150,
            ],
            'teachers' => [
                'count' => '2,250',
                'raw' => 2250,
                'growth' => '+1.8%',
                'active' => 2180,
            ],
            'parents' => [
                'count' => '5,690',
                'raw' => 5690,
                'growth' => '+3.4%',
                'portal_active' => '94%',
            ],
            'earnings' => [
                'count' => '$193,450',
                'raw' => 193450,
                'growth' => '+12.6%',
                'collected_rate' => '92.4%',
            ],
        ];

        $examPerformance = [
            ['month' => 'Jan', 'students' => 85, 'teachers' => 90],
            ['month' => 'Feb', 'students' => 70, 'teachers' => 82],
            ['month' => 'Mar', 'students' => 92, 'teachers' => 88],
            ['month' => 'Apr', 'students' => 80, 'teachers' => 85],
            ['month' => 'May', 'students' => 95, 'teachers' => 92],
            ['month' => 'Jun', 'students' => 88, 'teachers' => 89],
            ['month' => 'Jul', 'students' => 76, 'teachers' => 80],
            ['month' => 'Aug', 'teachers' => 86, 'students' => 82],
            ['month' => 'Sep', 'students' => 91, 'teachers' => 90],
            ['month' => 'Oct', 'students' => 94, 'teachers' => 93],
            ['month' => 'Nov', 'students' => 89, 'teachers' => 87],
            ['month' => 'Dec', 'students' => 96, 'teachers' => 95],
        ];

        $genderRatio = [
            'male_percentage' => 52.3,
            'female_percentage' => 47.7,
            'male_count' => 7850,
            'female_count' => 7150,
            'total' => 15000,
        ];

        $starStudents = [
            [
                'id' => 'PRE-0021',
                'name' => 'Sophia Montgomery',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
                'marks' => '1185/1200',
                'percentage' => '98.8%',
                'grade' => 'Grade 10',
                'year' => '2026',
            ],
            [
                'id' => 'PRE-0022',
                'name' => 'Liam Alexander Vance',
                'avatar' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=100&auto=format&fit=crop&q=80',
                'marks' => '1172/1200',
                'percentage' => '97.6%',
                'grade' => 'Grade 10',
                'year' => '2026',
            ],
            [
                'id' => 'PRE-0023',
                'name' => 'Charlotte Dubois',
                'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&auto=format&fit=crop&q=80',
                'marks' => '1164/1200',
                'percentage' => '97.0%',
                'grade' => 'Grade 11',
                'year' => '2026',
            ],
            [
                'id' => 'PRE-0024',
                'name' => 'Aarav Patel',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80',
                'marks' => '1158/1200',
                'percentage' => '96.5%',
                'grade' => 'Grade 9',
                'year' => '2026',
            ],
            [
                'id' => 'PRE-0025',
                'name' => 'Amelia Rose Watson',
                'avatar' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=100&auto=format&fit=crop&q=80',
                'marks' => '1150/1200',
                'percentage' => '95.8%',
                'grade' => 'Grade 8',
                'year' => '2026',
            ],
        ];

        $notices = [
            [
                'title' => 'Fall Term Parent-Teacher Academic Conference',
                'date' => '20 Oct 2026',
                'badge' => 'Academic',
                'color' => 'bg-violet-100 text-violet-700',
            ],
            [
                'title' => 'Annual Inter-School Robotics & STEM Olympiad',
                'date' => '28 Oct 2026',
                'badge' => 'Event',
                'color' => 'bg-sky-100 text-sky-700',
            ],
            [
                'title' => 'Term 1 Tuition Fee & Accounts Reconciliation Deadline',
                'date' => '31 Oct 2026',
                'badge' => 'Accounts',
                'color' => 'bg-amber-100 text-amber-700',
            ],
            [
                'title' => 'Faculty Professional Development & Pedagogy Workshop',
                'date' => '05 Nov 2026',
                'badge' => 'Faculty',
                'color' => 'bg-emerald-100 text-emerald-700',
            ],
        ];

        return view('admin.dashboard', compact(
            'kpi',
            'examPerformance',
            'genderRatio',
            'starStudents',
            'notices'
        ));
    }
}
