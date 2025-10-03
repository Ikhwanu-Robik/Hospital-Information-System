<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\PrescriptionRecord;
use Illuminate\Support\Facades\DB;
use App\Models\PrescriptionMedicine;

class MedicineUsageReport
{
    private $date;
    private $from;
    private $to;

    public function whereDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function whereDateRange($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
        return $this;
    }

    public function getTotalPrescription()
    {
        if ($this->date) {
            $totalPrescription = PrescriptionRecord::whereDate('created_at', $this->date)->count();
        } else if ($this->from && $this->to) {
            $totalPrescription = PrescriptionRecord::whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->count();
        } else {
            $this->date = today();
            $totalPrescription = PrescriptionRecord::whereDate('created_at', $this->date)->count();
        }

        return $totalPrescription;
    }

    public function getTotalMedicineDispensed()
    {
        if ($this->date) {
            $totalMedicineDispensed = PrescriptionMedicine::whereDate('created_at', $this->date)->count();
        } else if ($this->from && $this->to) {
            $totalMedicineDispensed = PrescriptionMedicine::whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->count();
        } else {
            $this->date = today();
            $totalMedicineDispensed = PrescriptionMedicine::whereDate('created_at', $this->date)->count();
        }

        return $totalMedicineDispensed;
    }

    public function getCostOverview()
    {
        if ($this->date) {
            $costOverview = PrescriptionMedicine::whereDate('prescription_medicine.created_at', $this->date)
                ->select(
                    DB::raw('SUM(prescription_medicine.dose_amount * medicines.price) as total_cost')
                )
                ->join('medicines', 'prescription_medicine.medicine_id', '=', 'medicines.id')
                ->first()->total_cost;

        } else if ($this->from && $this->to) {
            $costOverview = PrescriptionMedicine::whereDate('prescription_medicine.created_at', '>=', $this->from)
                ->whereDate('prescription_medicine.created_at', '<=', $this->to)
                ->select(
                    DB::raw('SUM(prescription_medicine.dose_amount * medicines.price) as total_cost')
                )
                ->join('medicines', 'prescription_medicine.medicine_id', '=', 'medicines.id')
                ->first()->total_cost;
        } else {
            $this->date = today();
            $costOverview = PrescriptionMedicine::whereDate('prescription_medicine.created_at', $this->date)
                ->select(
                    DB::raw('SUM(prescription_medicine.dose_amount * medicines.price) as total_cost')
                )
                ->join('medicines', 'prescription_medicine.medicine_id', '=', 'medicines.id')
                ->first()->total_cost;
        }

        return $costOverview;
    }

    public function getExpensiveMedicineDispesion()
    {
        if ($this->date) {
            $expensiveMedicineDispension = Medicine::join('prescription_medicine', 'medicines.id', '=', 'prescription_medicine.medicine_id')
                ->whereDate('prescription_medicine.created_at', $this->date)
                ->groupBy('medicines.id', 'medicines.name', 'medicines.price')
                ->selectRaw('medicines.id, medicines.name, medicines.price, COUNT(prescription_medicine.id) AS dispensed_amount')
                ->orderBy('medicines.price', 'DESC')
                ->limit(10)
                ->get();
        } else if ($this->from && $this->to) {
            $expensiveMedicineDispension = Medicine::join('prescription_medicine', 'medicines.id', '=', 'prescription_medicine.medicine_id')->whereDate('prescription_medicine.created_at', '>=', $this->from)
                ->whereDate('prescription_medicine.created_at', '<=', $this->to)
                ->groupBy('medicines.id', 'medicines.name', 'medicines.price')
                ->selectRaw('medicines.id, medicines.name, medicines.price, COUNT(prescription_medicine.id) AS dispensed_amount')
                ->orderBy('medicines.price', 'DESC')
                ->limit(10)
                ->get();
        } else {
            $this->date = today();
            $expensiveMedicineDispension = Medicine::join('prescription_medicine', 'medicines.id', '=', 'prescription_medicine.medicine_id')
                ->whereDate('prescription_medicine.created_at', $this->date)
                ->groupBy('medicines.id', 'medicines.name', 'medicines.price')
                ->selectRaw('medicines.id, medicines.name, medicines.price, COUNT(prescription_medicine.id) AS dispensed_amount')
                ->orderBy('medicines.price', 'DESC')
                ->limit(10)
                ->get();
        }

        return $expensiveMedicineDispension;
    }

    public function getDispensionsPerMonth()
    {
        if ($this->date) {
            $prescriptionMedicines = PrescriptionMedicine::whereDate('created_at', $this->date)
                ->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as record_count')
                ->whereYear('created_at', now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            $monthLabel = $prescriptionMedicines->pluck('month')->toArray();
            $monthlyData = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerMonth = [
                "label" => $monthLabel,
                "data" => $monthlyData
            ];
        } else if ($this->from && $this->to) {
            $prescriptionMedicines = PrescriptionMedicine::whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as record_count')
                ->whereYear('created_at', now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            $monthLabel = $prescriptionMedicines->pluck('month')->toArray();
            $monthlyData = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerMonth = [
                "label" => $monthLabel,
                "data" => $monthlyData
            ];
        } else {
            $this->date = today();
            $prescriptionMedicines = PrescriptionMedicine::whereDate('created_at', $this->date)
                ->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as record_count')
                ->whereYear('created_at', now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            $monthLabel = $prescriptionMedicines->pluck('month')->toArray();
            $monthlyData = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerMonth = [
                "label" => $monthLabel,
                "data" => $monthlyData
            ];
        }

        return $dispensionsPerMonth;
    }

    public function getDispensionsPerSpecialization()
    {
        if ($this->date) {
            $prescriptionMedicines = PrescriptionMedicine::whereDate('prescription_medicine.created_at', $this->date)
                ->selectRaw('specializations.name AS specialization, COUNT(prescription_medicine.id) as record_count')
                ->join('prescription_records', 'prescription_medicine.prescription_record_id', '=', 'prescription_records.id')
                ->join('medical_records', 'prescription_records.medical_record_id', '=', 'medical_records.id')
                ->join('doctor_profiles', 'medical_records.doctor_profile_id', '=', 'doctor_profiles.id')
                ->join('specializations', 'doctor_profiles.specialization_id', '=', 'specializations.id')
                ->groupBy('specialization')
                ->get();

            $specializations = $prescriptionMedicines->pluck('specialization')->toArray();
            $data = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerSpecialization = [
                "label" => $specializations,
                "data" => $data
            ];
        } else if ($this->from && $this->to) {
            $prescriptionMedicines = PrescriptionMedicine::whereDate('prescription_medicine.created_at', '>=', $this->from)
                ->whereDate('prescription_medicine.created_at', '<=', $this->to)
                ->selectRaw('specializations.name AS specialization, COUNT(prescription_medicine.id) as record_count')
                ->join('prescription_records', 'prescription_medicine.prescription_record_id', '=', 'prescription_records.id')
                ->join('medical_records', 'prescription_records.medical_record_id', '=', 'medical_records.id')
                ->join('doctor_profiles', 'medical_records.doctor_profile_id', '=', 'doctor_profiles.id')
                ->join('specializations', 'doctor_profiles.specialization_id', '=', 'specializations.id')
                ->groupBy('specialization')
                ->get();

            $specializations = $prescriptionMedicines->pluck('specialization')->toArray();
            $data = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerSpecialization = [
                "label" => $specializations,
                "data" => $data
            ];
        } else {
            $this->date = today();
            $prescriptionMedicines = PrescriptionMedicine::whereDate('prescription_medicine.created_at', $this->date)
                ->selectRaw('specializations.name AS specialization, COUNT(prescription_medicine.id) as record_count')
                ->join('prescription_records', 'prescription_medicine.prescription_record_id', '=', 'prescription_records.id')
                ->join('medical_records', 'prescription_records.medical_record_id', '=', 'medical_records.id')
                ->join('doctor_profiles', 'medical_records.doctor_profile_id', '=', 'doctor_profiles.id')
                ->join('specializations', 'doctor_profiles.specialization_id', '=', 'specializations.id')
                ->groupBy('specialization')
                ->get();

            $specializations = $prescriptionMedicines->pluck('specialization')->toArray();
            $data = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerSpecialization = [
                "label" => $specializations,
                "data" => $data
            ];
        }

        return $dispensionsPerSpecialization;
    }

    public function getDispensionsPerMedicine()
    {
        if ($this->date) {
            $prescriptionMedicines = PrescriptionMedicine::whereDate('prescription_medicine.created_at', $this->date)
                ->selectRaw('medicines.name AS medicine, COUNT(prescription_medicine.id) as record_count')
                ->join('medicines', 'prescription_medicine.medicine_id', '=', 'medicines.id')
                ->groupBy('medicine')
                ->get();

            $medicines = $prescriptionMedicines->pluck('medicine')->toArray();
            $data = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerMedicine = [
                "label" => $medicines,
                "data" => $data
            ];
        } else if ($this->from && $this->to) {
            $prescriptionMedicines = PrescriptionMedicine::whereDate('prescription_medicine.created_at', '>=', $this->from)
                ->whereDate('prescription_medicine.created_at', '<=', $this->to)
                ->selectRaw('medicines.name AS medicine, COUNT(prescription_medicine.id) as record_count')
                ->join('medicines', 'prescription_medicine.medicine_id', '=', 'medicines.id')
                ->groupBy('medicine')
                ->get();

            $medicines = $prescriptionMedicines->pluck('medicine')->toArray();
            $data = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerMedicine = [
                "label" => $medicines,
                "data" => $data
            ];
        } else {
            $this->date = today();
            $prescriptionMedicines = PrescriptionMedicine::whereDate('prescription_medicine.created_at', $this->date)
                ->selectRaw('medicines.name AS medicine, COUNT(prescription_medicine.id) as record_count')
                ->join('medicines', 'prescription_medicine.medicine_id', '=', 'medicines.id')
                ->groupBy('medicine')
                ->get();

            $medicines = $prescriptionMedicines->pluck('medicine')->toArray();
            $data = $prescriptionMedicines->pluck('record_count')->toArray();

            $dispensionsPerMedicine = [
                "label" => $medicines,
                "data" => $data
            ];
        }

        return $dispensionsPerMedicine;
    }
}