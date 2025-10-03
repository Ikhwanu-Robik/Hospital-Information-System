<?php

namespace App\Http\Controllers\Admin;

use App\Facades\MedicineUsageReport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientMedicineUsageController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date');
        $to = $request->query('to');
        $from = $request->query('from');

        if ($date) {
            $totalPrescription = MedicineUsageReport::whereDate($date)->getTotalPrescription();
            $totalMedicineDispensed = MedicineUsageReport::whereDate($date)->getTotalMedicineDispensed();
            $costOverview = MedicineUsageReport::whereDate($date)->getCostOverview();
            $expensiveMedicines = MedicineUsageReport::whereDate($date)->getExpensiveMedicineDispesion();
            $dispensionsPerMonth = MedicineUsageReport::whereDate($date)->getDispensionsPerMonth();
            $dispensionsPerSpecialization = MedicineUsageReport::whereDate($date)->getDispensionsPerSpecialization();
            $dispensionsPerMedicine = MedicineUsageReport::whereDate($date)->getDispensionsPerMedicine();
        } else if ($to && $from) {
            $totalPrescription = MedicineUsageReport::whereDateRange($from, $to)->getTotalPrescription();
            $totalMedicineDispensed = MedicineUsageReport::whereDateRange($from, $to)->getTotalMedicineDispensed();
            $costOverview = MedicineUsageReport::whereDateRange($from, $to)->getCostOverview();
            $expensiveMedicines = MedicineUsageReport::whereDateRange($from, $to)->getExpensiveMedicineDispesion();
            $dispensionsPerMonth = MedicineUsageReport::whereDateRange($from, $to)->getDispensionsPerMonth();
            $dispensionsPerSpecialization = MedicineUsageReport::whereDateRange($from, $to)->getDispensionsPerSpecialization();
            $dispensionsPerMedicine = MedicineUsageReport::whereDateRange($from, $to)->getDispensionsPerMedicine();
        } else {
            $totalPrescription = MedicineUsageReport::getTotalPrescription();
            $totalMedicineDispensed = MedicineUsageReport::getTotalMedicineDispensed();
            $costOverview = MedicineUsageReport::getCostOverview();
            $expensiveMedicines = MedicineUsageReport::getExpensiveMedicineDispesion();
            $dispensionsPerMonth = MedicineUsageReport::getDispensionsPerMonth();
            $dispensionsPerSpecialization = MedicineUsageReport::getDispensionsPerSpecialization();
            $dispensionsPerMedicine = MedicineUsageReport::getDispensionsPerMedicine();
        }

        return view('admin.report.patient-medicine-usage.index', [
            'dispensionsPerMonth' => $dispensionsPerMonth, // ["label" => [], "data" => []]
            'dispensionsPerSpecialization' => $dispensionsPerSpecialization,
            'dispensionsPerMedicine' => $dispensionsPerMedicine,

            'totalPrescription' => $totalPrescription ?? "N/A", // int or double
            'totalMedicineDispensed' => $totalMedicineDispensed ?? "N/A",
            'costOverview' => $costOverview ?? "N/A",

            'expensiveMedicines' => $expensiveMedicines // Collection<Medicine>
        ]);
    }
}
