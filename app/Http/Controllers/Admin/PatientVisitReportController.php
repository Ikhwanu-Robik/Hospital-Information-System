<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VisitReportType;
use App\Facades\VisitReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PatientVisitReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date');
        $to = $request->query('to');
        $from = $request->query('from');

        if ($date) {
            $reportByGender = VisitReport::whereDate($date)
                ->get(VisitReportType::BY_GENDER);
            $reportByAgeGroup = VisitReport::whereDate($date)
                ->get(VisitReportType::BY_AGE_GROUP);
            $reportByTimeOfTheDay = VisitReport::whereDate($date)
                ->get(VisitReportType::BY_TIME_OF_DAY);
            $reportByBPJS = VisitReport::whereDate($date)
                ->get(VisitReportType::BY_BPJS);
            $reportBySpecialization = VisitReport::whereDate($date)
                ->get(VisitReportType::BY_SPECIALIZATION);
        } else if ($to && $from) {
            $reportByGender = VisitReport::whereDateRange($from, $to)
                ->get(VisitReportType::BY_GENDER);
            $reportByAgeGroup = VisitReport::whereDateRange($from, $to)
                ->get(VisitReportType::BY_AGE_GROUP);
            $reportByTimeOfTheDay = VisitReport::whereDateRange($from, $to)
                ->get(VisitReportType::BY_TIME_OF_DAY);
            $reportByBPJS = VisitReport::whereDateRange($from, $to)
                ->get(VisitReportType::BY_BPJS);
            $reportBySpecialization = VisitReport::whereDateRange($from, $to)
                ->get(VisitReportType::BY_SPECIALIZATION);
        } else {
            $reportByGender = VisitReport::get(VisitReportType::BY_GENDER);
            $reportByAgeGroup = VisitReport::get(VisitReportType::BY_AGE_GROUP);
            $reportByTimeOfTheDay = VisitReport::get(VisitReportType::BY_TIME_OF_DAY);
            $reportByBPJS = VisitReport::get(VisitReportType::BY_BPJS);
            $reportBySpecialization = VisitReport::get(VisitReportType::BY_SPECIALIZATION);
        }

        $reportOverTime = VisitReport::get(VisitReportType::OVER_TIME);

        return view('admin.report.patient-visit.index', [
            'reportByGender' => $reportByGender,
            'reportByAgeGroup' => $reportByAgeGroup,
            'reportByTimeOfTheDay' => $reportByTimeOfTheDay,
            'reportByBPJS' => $reportByBPJS,
            'reportOverTime' => $reportOverTime,
            'reportBySpecialization' => $reportBySpecialization
        ]);
    }
}
