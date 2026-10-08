<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\EnrollmentReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function exportEnrollmentReport(Request $request)
    {
        $format = strtolower($request->get('format', 'xlsx')); // default XLSX
        $allowedFormats = ['xlsx', 'csv'];
        if (!in_array($format, $allowedFormats)) {
            return response()->json([
                'message' => 'Invalid format. Allowed formats: xlsx, csv'
            ], 400);
        }

        $tenantId = $request->get('tenant_id');
        $courseId = $request->get('course_id');

        $fileName = 'enrollment_report_' . now()->format('Y_m_d_H_i_s') . '.' . $format;

        return Excel::download(
            new EnrollmentReportExport($tenantId, $courseId),
            $fileName,
            $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
