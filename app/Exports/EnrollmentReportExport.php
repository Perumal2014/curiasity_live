<?php

namespace App\Exports;

use Modules\CourseSetting\Entities\CourseEnrolled;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EnrollmentReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tenantId;
    protected $courseId;

    public function __construct($tenantId = null, $courseId = null)
    {
        $this->tenantId = $tenantId;
        $this->courseId = $courseId;
    }

    public function collection()
    {
        return CourseEnrolled::with(['user', 'course'])
            ->when($this->tenantId, fn($q) => $q->whereHas('user', fn($u) => $u->where('lms_id', $this->tenantId)))
            ->when($this->courseId, fn($q) => $q->where('course_id', $this->courseId))
            ->get();
    }

    public function map($enroll): array
    {
        $user = $enroll->user;
        $course = $enroll->course;

        $progress = method_exists($course, 'userTotalPercentage')
            ? round($course->userTotalPercentage($user->id, $course->id))
            : 0;

        $status = match (true) {
            $progress == 0 => 'Not Started',
            $progress == 100 => 'Completed',
            $progress > 0 && $progress < 100 => 'In Progress',
            default => 'Withdrawn',
        };

        return [
            $user->name ?? '-',
            $user->email ?? '-',
            $course->title ?? '-',
            $user->unique_id ?? $enroll->id,
            $enroll->selection_criteria ?? 'Direct Enrollment',
            $course->module_name ?? 'General',
            $user->company_name ?? '-',
            $user->college_name ?? '-',
            $course->version ?? '-',
            $course->language->name ?? '-',
            $enroll->created_at?->format('Y-m-d H:i:s'),
            $enroll->started_at?->format('Y-m-d H:i:s') ?? 'N/A',
            $enroll->completed_at?->format('Y-m-d H:i:s') ?? 'N/A',
            $status,
            $progress,
            $enroll->time_spent_minutes ?? 0,
            $enroll->grade ?? '-',
            $enroll->assessment_score ?? 0,
            $enroll->assessment_total_mark ?? 0,
            $enroll->attempts_taken ?? 0,
            $user->location ?? '-',
            $user->profile ?? '-',
            $course->training_duration_minutes ?? 0,
        ];
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Course', 'Enrollment ID', 'Selection Criteria', 'Module',
            'Company', 'College', 'Version', 'Language', 'Enrolled (UTC)', 'Started (UTC)',
            'Completed (UTC)', 'Status', 'Progress %', 'Time Spent (min)', 'Grade',
            'Score', 'Total Mark', 'Attempts', 'Location', 'Profile', 'Training Duration (min)'
        ];
    }
}
