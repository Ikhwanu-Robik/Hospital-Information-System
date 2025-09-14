import "./app";
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", () => {
    fetchPatientData();
});

let doctorProfileId = document.querySelector(
    'meta[name="doctor-profile-id"]'
).content;

window.Echo.private(`CheckUp.Doctors.${doctorProfileId}`).listen(
    "PatientWishToMeetDoctor",
    (e) => {
        Swal.fire({
            title: "Patient Coming",
            icon: "info",
        });

        fillPatientData(e.queueId, e.patient, e.medicalRecords);
    }
);

async function fetchPatientData() {
    Swal.fire({
        title: "Fetching...",
    });

    const response = await fetch(
        `http://127.0.0.1:8000/api/doctors/${doctorProfileId}/patients/earliest`
    );

    if (!response.ok && response.status != 404) {
        Swal.fire({
            title: `Error Fetching Data`,
            text: response.statusText,
            icon: "error",
        });
    }
    else if (!response.ok && response.status == 404) {
        let json = await response.json();

        Swal.fire({
            title: `Error Fetching Data`,
            text: json.message,
            icon: "error",
        });
    } else {
        Swal.fire({
            title: "Patient Fetched",
            icon: "success",
        });

        const result = await response.json();
        fillPatientData(result.queueId, result.patient, result.medicalRecords);
    }
}

function fillPatientData(queueId, patient, medicalRecords) {
    // fill the patient's data
    let queueIdInput = document.querySelector('input[name="queue_id"]');
    queueIdInput.value = queueId;

    let queueIdSkipInput = document.querySelector('input[name="queue_id_skip"]');
    queueIdSkipInput.value = queueId;

    let mrn = document.getElementById("medical-record-number");
    mrn.textContent = patient.medical_record_number;

    let formMrn = document.querySelector(
        'form input[name="medical_record_number"]'
    );
    formMrn.value = patient.medical_record_number;

    let full_name = document.getElementById("full-name");
    full_name.textContent = patient.full_name;

    let birthdate = document.getElementById("birthdate");
    birthdate.textContent = patient.birthdate;

    let gender = document.getElementById("gender");
    gender.textContent = patient.gender;

    let address = document.getElementById("address");
    address.textContent = patient.address;

    let status = document.getElementById("status");
    status.textContent = patient.marriage_status ? "yes" : "no";

    let phone = document.getElementById("phone");
    phone.textContent = patient.phone;

    let bpjs_number = document.getElementById("BPJS_number");
    bpjs_number.textContent =
        patient.BPJS_number != null ? patient.BPJS_number : "no";

    // fill the patient's medical records
    let medicalRecordsTableBody = document.getElementById(
        "medical-records-tbody"
    );
    medicalRecords.forEach((medicalRecord) => {
        let tr = document.createElement("tr");

        medicalRecordsTableBody.textContent = "";
        // TODO: doctor can't accept patient when still checking up a patient

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
                        medicineName + " x " + dose + " | " + intakeFrequency;

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
