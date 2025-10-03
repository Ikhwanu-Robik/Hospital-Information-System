<?php

namespace App\Services;

use App\Facades\BPJS;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Enums\VisitReportType;
use Illuminate\Support\Facades\DB;

class VisitReport
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

    public function get(VisitReportType $reportType)
    {
        switch ($reportType) {
            case VisitReportType::BY_GENDER:
                $reportByGender = null;

                if ($this->date) {
                    $reportByGender = $this->getReportByGenderDate();
                } else if ($this->from && $this->to) {
                    $reportByGender = $this->getReportByGenderDateRange();
                } else {
                    $this->date = today();
                    $reportByGender = $this->getReportByGenderDate();
                }

                return $reportByGender;
            case VisitReportType::BY_AGE_GROUP:
                $reportByAgeGroup = null;

                if ($this->date) {
                    $reportByAgeGroup = $this->getReportByAgeGroupDate();
                } else if ($this->from && $this->to) {
                    $reportByAgeGroup = $this->getReportByAgeGroupDateRange();
                } else {
                    $this->date = today();
                    $reportByAgeGroup = $this->getReportByAgeGroupDate();
                }

                return $reportByAgeGroup;
            case VisitReportType::BY_TIME_OF_DAY:
                if ($this->date) {
                    $reportByTimeOfTheDay = $this->getReportByTimeOfTheDayDate();
                } else if ($this->from && $this->to) {
                    $reportByTimeOfTheDay = $this->getReportByTimeOfTheDayDateRange();
                } else {
                    $this->date = today();
                    $reportByTimeOfTheDay = $this->getReportByTimeOfTheDayDate();
                }

                return $reportByTimeOfTheDay;
            case VisitReportType::BY_BPJS:
                if ($this->date) {
                    $reportByBPJS = $this->getReportByBPJSDate();
                } else if ($this->from && $this->to) {
                    $reportByBPJS = $this->getReportByBPJSDateRange();
                } else {
                    $this->date = today();
                    $reportByBPJS = $this->getReportByBPJSDate();
                }

                return $reportByBPJS;
            case VisitReportType::OVER_TIME:
                $reportOverTime = $this->getReportOverTime();
                return $reportOverTime;
            case VisitReportType::BY_SPECIALIZATION:
                if ($this->date) {
                    $reportBySpecialization = $this->getReportBySpecializationDate();
                } else if ($this->from && $this->to) {
                    $reportBySpecialization = $this->getReportBySpecializationDateRange();
                } else {
                    $this->date = today();
                    $reportBySpecialization = $this->getReportBySpecializationDate();
                }

                return $reportBySpecialization;
        }
    }

    private function getReportByGenderDate()
    {
        $medicalRecordsByGender = Patient::whereDate(
            'medical_records.created_at',
            $this->date
        )
            ->join('medical_records', 'medical_records.patient_id', '=', 'patients.id')
            ->selectRaw('patients.gender, COUNT(medical_records.id) AS record_count')
            ->groupBy('patients.gender')->get();

        $genderLabel = $medicalRecordsByGender->pluck('gender')->toArray();
        $genderData = $medicalRecordsByGender->pluck('record_count')->toArray();

        $reportByGender = [
            "label" => $genderLabel,
            "data" => $genderData
        ];

        return $reportByGender;
    }

    private function getReportByGenderDateRange()
    {
        $medicalRecordsByGender = Patient::whereDate(
            'medical_records.created_at',
            '<=',
            $this->to
        )
            ->whereDate(
                'medical_records.created_at',
                '>=',
                $this->from
            )
            ->join('medical_records', 'medical_records.patient_id', '=', 'patients.id')
            ->selectRaw('patients.gender, COUNT(medical_records.id) AS record_count')
            ->groupBy('patients.gender')->get();

        $genderLabel = $medicalRecordsByGender->pluck('gender')->toArray();
        $genderData = $medicalRecordsByGender->pluck('record_count')->toArray();

        $reportByGender = [
            "label" => $genderLabel,
            "data" => $genderData
        ];

        return $reportByGender;
    }

    private function getReportByAgeGroupDate()
    {
        $medicalRecordsByAgeGroup = Patient::whereDate(
            'medical_records.created_at',
            $this->date
        )
            ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
            ->selectRaw("
        CASE
            WHEN TIMESTAMPDIFF(YEAR, patients.birthdate, CURDATE()) < 13 THEN 'children'
            WHEN TIMESTAMPDIFF(YEAR, patients.birthdate, CURDATE()) BETWEEN 13 AND 18 THEN 'teenager'
            WHEN TIMESTAMPDIFF(YEAR, patients.birthdate, CURDATE()) BETWEEN 19 AND 40 THEN 'adult'
            ELSE 'elderly'
        END as age_group,
        COUNT(medical_records.id) as record_count
    ")
            ->groupBy('age_group')
            ->get();

        $ageGroupLabel = $medicalRecordsByAgeGroup->pluck('age_group')->toArray();
        $ageGroupData = $medicalRecordsByAgeGroup->pluck('record_count')->toArray();

        $reportByAgeGroup = [
            "label" => $ageGroupLabel,
            "data" => $ageGroupData
        ];

        return $reportByAgeGroup;
    }

    private function getReportByAgeGroupDateRange()
    {
        $medicalRecordsByAgeGroup = Patient::whereDate(
            'medical_records.created_at',
            '<=',
            $this->to
        )
            ->whereDate(
                'medical_records.created_at',
                '>=',
                $this->from
            )
            ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
            ->selectRaw("
        CASE
            WHEN TIMESTAMPDIFF(YEAR, patients.birthdate, CURDATE()) < 13 THEN 'children'
            WHEN TIMESTAMPDIFF(YEAR, patients.birthdate, CURDATE()) BETWEEN 13 AND 18 THEN 'teenager'
            WHEN TIMESTAMPDIFF(YEAR, patients.birthdate, CURDATE()) BETWEEN 19 AND 40 THEN 'adult'
            ELSE 'elderly'
        END as age_group,
        COUNT(medical_records.id) as record_count
    ")
            ->groupBy('age_group')
            ->get();

        $ageGroupLabel = $medicalRecordsByAgeGroup->pluck('age_group')->toArray();
        $ageGroupData = $medicalRecordsByAgeGroup->pluck('record_count')->toArray();

        $reportByAgeGroup = [
            "label" => $ageGroupLabel,
            "data" => $ageGroupData
        ];

        return $reportByAgeGroup;
    }

    private function getReportByTimeOfTheDayDate()
    {
        $medicalRecordsByAgeGroup = Patient::whereDate(
            'medical_records.created_at',
            $this->date
        )
            ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
            ->selectRaw("
        CASE
            WHEN HOUR(medical_records.created_at) BETWEEN 3 AND 7 THEN 'Morning (3 to 7)'
            WHEN HOUR(medical_records.created_at) BETWEEN 8 AND 13 THEN 'Noon (8 to 13)'
            WHEN HOUR(medical_records.created_at) BETWEEN 14 AND 18 THEN 'Evening (14 to 18)'
            WHEN HOUR(medical_records.created_at) BETWEEN 19 AND 23 THEN 'Night (19 to 2)'
            WHEN HOUR(medical_records.created_at) BETWEEN 0 AND 2 THEN 'Night (19 to 2)'
            ELSE 'invalid time'
        END as time_group,
        COUNT(medical_records.id) as record_count
    ")
            ->groupBy('time_group')
            ->get();

        $ageGroupLabel = $medicalRecordsByAgeGroup->pluck('time_group')->toArray();
        $ageGroupData = $medicalRecordsByAgeGroup->pluck('record_count')->toArray();

        $reportByAgeGroup = [
            "label" => $ageGroupLabel,
            "data" => $ageGroupData
        ];

        return $reportByAgeGroup;
    }

    private function getReportByTimeOfTheDayDateRange()
    {
        $medicalRecordsByAgeGroup = Patient::whereDate(
            'medical_records.created_at',
            '<=',
            $this->to
        )
            ->whereDate(
                'medical_records.created_at',
                '>=',
                $this->from
            )
            ->join('medical_records', 'patients.id', '=', 'medical_records.patient_id')
            ->selectRaw("
        CASE
            WHEN HOUR(medical_records.created_at) BETWEEN 3 AND 7 THEN 'Morning (3 to 7)'
            WHEN HOUR(medical_records.created_at) BETWEEN 8 AND 13 THEN 'Noon (8 to 13)'
            WHEN HOUR(medical_records.created_at) BETWEEN 14 AND 18 THEN 'Evening (14 to 18)'
            WHEN HOUR(medical_records.created_at) BETWEEN 19 AND 23 THEN 'Night (19 to 2)'
            WHEN HOUR(medical_records.created_at) BETWEEN 0 AND 2 THEN 'Night (19 to 2)'
            ELSE 'invalid time'
        END as time_group,
        COUNT(medical_records.id) as record_count
    ")
            ->groupBy('time_group')
            ->get();

        $ageGroupLabel = $medicalRecordsByAgeGroup->pluck('time_group')->toArray();
        $ageGroupData = $medicalRecordsByAgeGroup->pluck('record_count')->toArray();

        $reportByAgeGroup = [
            "label" => $ageGroupLabel,
            "data" => $ageGroupData
        ];

        return $reportByAgeGroup;
    }

    private function getReportByBPJSDate()
    {
        $patients = Patient::whereDate(
            'medical_records.created_at',
            $this->date
        )
            ->join('medical_records', 'medical_records.patient_id', '=', 'patients.id')
            ->selectRaw('patients.id, patients.NIK, COUNT(medical_records.id) AS record_count')
            ->groupBy('patients.id', 'patients.NIK')->get();


        $bpjsLabel = ["BPJS", "Non-BPJS"];
        $bpjsData = [0, 0];

        foreach ($patients as $patient) {
            if (BPJS::validatePatient($patient->NIK)) {
                $bpjsData[0] += $patient->record_count;
            } else {
                $bpjsData[1] += $patient->record_count;
            }
        }

        $reportByBPJS = [
            "label" => $bpjsLabel,
            "data" => $bpjsData
        ];

        return $reportByBPJS;
    }

    private function getReportByBPJSDateRange()
    {
        $patients = Patient::whereDate(
            'medical_records.created_at',
            '<=',
            $this->to
        )
            ->whereDate(
                'medical_records.created_at',
                '>=',
                $this->from
            )
            ->join('medical_records', 'medical_records.patient_id', '=', 'patients.id')
            ->selectRaw('patients.id, patients.NIK, COUNT(medical_records.id) AS record_count')
            ->groupBy('patients.id', 'patients.NIK')->get();


        $bpjsLabel = ["BPJS", "Non-BPJS"];
        $bpjsData = [0, 0];

        foreach ($patients as $patient) {
            if (BPJS::validatePatient($patient->NIK)) {
                $bpjsData[0] += $patient->record_count;
            } else {
                $bpjsData[1] += $patient->record_count;
            }
        }

        $reportByBPJS = [
            "label" => $bpjsLabel,
            "data" => $bpjsData
        ];

        return $reportByBPJS;
    }

    private function getReportOverTime()
    {
        $medicalRecords = MedicalRecord::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as record_count')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $monthLabel = $medicalRecords->pluck('month')->toArray();
        $monthlyData = $medicalRecords->pluck('record_count')->toArray();

        $reportOverTime = [
            "label" => $monthLabel,
            "data" => $monthlyData
        ];

        return $reportOverTime;
    }

    private function getReportBySpecializationDate()
    {
        $medicalRecordsBySpecialization = MedicalRecord::whereDate(
            'medical_records.created_at',
            $this->date
        )->join('doctor_profiles', 'medical_records.doctor_profile_id', '=', 'doctor_profiles.id')
            ->join('specializations', 'doctor_profiles.specialization_id', '=', 'specializations.id')
            ->select('specializations.id', 'specializations.name', DB::raw('COUNT(medical_records.id) as record_count'))
            ->groupBy('specializations.id', 'specializations.name')
            ->get();

        $specializationLabel = $medicalRecordsBySpecialization->pluck('name')->toArray();
        $specializationData = $medicalRecordsBySpecialization->pluck('record_count')->toArray();

        $reportBySpecialization = [
            "label" => $specializationLabel,
            "data" => $specializationData
        ];

        return $reportBySpecialization;
    }

    private function getReportBySpecializationDateRange()
    {
        $medicalRecordsBySpecialization = MedicalRecord::whereDate(
            'medical_records.created_at',
            '<=',
            $this->to
        )
            ->whereDate(
                'medical_records.created_at',
                '>=',
                $this->from
            )
            ->join('doctor_profiles', 'medical_records.doctor_profile_id', '=', 'doctor_profiles.id')
            ->join('specializations', 'doctor_profiles.specialization_id', '=', 'specializations.id')
            ->select('specializations.id', 'specializations.name', DB::raw('COUNT(medical_records.id) as record_count'))
            ->groupBy('specializations.id', 'specializations.name')
            ->get();

        $specializationLabel = $medicalRecordsBySpecialization->pluck('name')->toArray();
        $specializationData = $medicalRecordsBySpecialization->pluck('record_count')->toArray();

        $reportBySpecialization = [
            "label" => $specializationLabel,
            "data" => $specializationData
        ];

        return $reportBySpecialization;
    }
}