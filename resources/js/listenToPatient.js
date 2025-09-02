import "./app";
import Swal from "sweetalert2";

let doctorProfileId = document.querySelector(
    'meta[name="doctor-profile-id"]'
).content;

window.Echo.private(`CheckUp.Doctors.${doctorProfileId}`).listen(
    "PatientWishToMeetDoctor",
    (e) => {
        Swal.fire({
            title: 'Patient Coming',
            icon: 'info'
        });

        // fill the patient's data
        let mrn = document.getElementById("medical-record-number");
        mrn.textContent = e.patient.medical_record_number;

        let full_name = document.getElementById("full-name");
        full_name.textContent = e.patient.full_name;

        let birthdate = document.getElementById("birthdate");
        birthdate.textContent = e.patient.birthdate;

        let gender = document.getElementById("gender");
        gender.textContent = e.patient.gender;

        let address = document.getElementById("address");
        address.textContent = e.patient.address;

        let status = document.getElementById("status");
        status.textContent = e.patient.marriage_status ? "yes" : "no";

        let phone = document.getElementById("phone");
        phone.textContent = e.patient.phone;

        let bpjs_number = document.getElementById("BPJS_number");
        bpjs_number.textContent =
            e.patient.BPJS_number != null ? e.patient.BPJS_number : "no";

        // fill the patient's medical records
        let medicalRecordsTableBody = document.getElementById(
            "medical-records-tbody"
        );
        e.medicalRecords.forEach((medicalRecord) => {
            let tr = document.createElement("tr");

            const doctorNameTd = document.createElement("td");
            doctorNameTd.className = "doctor-name";
            doctorNameTd.textContent = medicalRecord.doctor_profile.full_name;

            const doctorSpecTd = document.createElement("td");
            doctorSpecTd.className = "doctor-specialization";
            doctorSpecTd.textContent =
                medicalRecord.doctor_profile.specialization.name;

            const complainTd = document.createElement("td");
            complainTd.className = "complain";
            complainTd.textContent = medicalRecord.complaint;

            const diagnosisTd = document.createElement("td");
            diagnosisTd.className = "diagnosis";
            diagnosisTd.textContent = medicalRecord.diagnosis;

            const prescriptionsTd = document.createElement("td");
            prescriptionsTd.className = "prescriptions";

            const ul = document.createElement("ul");

            if (medicalRecord.prescription_record) {
                medicalRecord.prescription_record.prescription_medicines.forEach(
                    (prescriptionMedicine) => {
                        const li = document.createElement("li");
                        li.className = "prescription";

                        let medicineName = prescriptionMedicine.medicine.name;
                        let dose = prescriptionMedicine.dose_amount;
                        let intakeFrequency = prescriptionMedicine.frequency;
                        li.textContent =
                            medicineName +
                            " x " +
                            dose +
                            " | " +
                            intakeFrequency;

                        ul.appendChild(li);
                    }
                );

                prescriptionsTd.appendChild(ul);
            } else {
                prescriptionsTd.textContent = "-";
            }

            tr.appendChild(doctorNameTd);
            tr.appendChild(doctorSpecTd);
            tr.appendChild(complainTd);
            tr.appendChild(diagnosisTd);
            tr.appendChild(prescriptionsTd);

            medicalRecordsTableBody.appendChild(tr);
        });
    }
);
