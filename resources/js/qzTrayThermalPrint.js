import qz from "qz-tray";
import Swal from "sweetalert2";

const ESCPOSCommand = {
    init: "\x1B" + "\x40",
    line_break: "\x0A",
    left_align: "\x1B" + "\x61" + "\x30",
    center_align: "\x1B" + "\x61" + "\x31",
    bold_on: "\x1B" + "\x45" + "\x0D",
    bold_off: "\x1B" + "\x45" + "\x0A",
    cut_paper_old_syntax: "\x1B" + "\x69",
};

document.addEventListener("DOMContentLoaded", async () => {
    let pageName = document.querySelector('meta[name="page-name"]').content;

    setSecurity();

    await startConnection();
    if (pageName == "set-queue-number-printer") {
        await allPrinters();
    } else if (pageName == "check-up-queue-number") {
        let queueNumber = document.querySelector(
            'meta[name="queue-number"]'
        ).content;
        await printQueueNumber(queueNumber);
    } else if (pageName == "print-medicine-prescription") {
        let prescriptions = JSON.parse(
            document.querySelector('meta[name="prescriptions"]').content
        );
        await printMedicinePrescriptions(prescriptions);
    }
    await closeConnection();
});

function setSecurity() {
    qz.security.setCertificatePromise(function (resolve, reject) {
        fetch("http://127.0.0.1:8000/api/qztray/certificate", {
            cache: "no-store",
            headers: { "Content-Type": "text/plain" },
        }).then(function (data) {
            data.ok ? resolve(data.text()) : reject(data.text());
        });
    });

    qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
    qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
            fetch("http://127.0.0.1:8000/api/qztray/message/sign", {
                method: "POST",
                cache: "no-store",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message: toSign }),
            }).then(function (data) {
                data.ok ? resolve(data.text()) : reject(data.text());
            });
        };
    });
}

async function startConnection() {
    try {
        if (!qz.websocket.isActive()) {
            await qz.websocket.connect();
        }
    } catch (e) {
        console.error(e);
    }
}

async function closeConnection() {
    if (qz.websocket.isActive()) {
        await qz.websocket.disconnect();
    }
}

async function allPrinters() {
    try {
        const printers = await qz.printers.find();
        populatePrintSelect(printers);
    } catch (e) {
        console.error(e);
    }
}

function populatePrintSelect(printers) {
    let optionsHTML = "";
    for (let i = 0; i < printers.length; i++) {
        optionsHTML += `<option value="${printers[i]}">${printers[i]}</option>`;
    }
    document.querySelector("select#printers").innerHTML = optionsHTML;
}

async function printQueueNumber(queueNumber) {
    try {
        let printerName = getQueueAppPrinter();
        if (printerName != "") {
            let config = qz.configs.create(printerName);
            let data = [
                ESCPOSCommand.init,
                ESCPOSCommand.center_align,
                "QUEUE NO.",
                ESCPOSCommand.line_break,
                ESCPOSCommand.bold_on,
                queueNumber,
                ESCPOSCommand.bold_off,
                ESCPOSCommand.line_break,
                ESCPOSCommand.cut_paper_old_syntax,
            ];

            await qz.print(config, data);
        } else {
            Swal.fire({
                title: "Can't Print Queue Number",
                icon: "error",
                text: "please contact the hospital officer",
            });
        }
    } catch (e) {
        console.error(e);
    }
}

function getQueueAppPrinter() {
    let printerName = document.querySelector(
        'meta[name="printer-name"]'
    ).content;
    return printerName;
}

function getAge(dateString) {
    let today = new Date();
    let birthDate = new Date(dateString);

    let age = today.getFullYear() - birthDate.getFullYear();
    let monthDiff = today.getMonth() - birthDate.getMonth();

    if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < birthDate.getDate())
    ) {
        age--;
    }

    return age;
}

async function printMedicinePrescriptions(prescriptions) {
    try {
        let printerName = getQueueAppPrinter();

        let patientBirthdate = prescriptions.medical_record.patient.birthdate;
        let patientAge = getAge(patientBirthdate);

        if (printerName != "") {
            let config = qz.configs.create(printerName);
            let data = [ESCPOSCommand.init];

            data.push(prescriptions.code);
            ESCPOSCommand.line_break;

            data.push(ESCPOSCommand.center_align);
            data.push("RESEP OBAT");
            data.push("RS AMAL SEHAT");
            data.push(ESCPOSCommand.line_break);
            data.push(ESCPOSCommand.left_align);

            data.push(
                "dr." + prescriptions.medical_record.doctor_profile.full_name
            );
            data.push(ESCPOSCommand.line_break);

            data.push(
                "Untuk:" + prescriptions.medical_record.patient.full_name
            );
            data.push(ESCPOSCommand.line_break);

            data.push("Usia:" + patientAge);
            data.push(ESCPOSCommand.line_break);

            data.push("Diagnosis:" + prescriptions.medical_record.diagnosis);
            data.push(ESCPOSCommand.line_break);

            prescriptions.prescription_medicines.forEach((prescription) => {
                let medicineName = prescription.medicine.name;
                let quantity = prescription.dose_amount;
                let frequency = prescription.frequency;

                data.push(medicineName + " x " + quantity + " | " + frequency);
                data.push(ESCPOSCommand.line_break);
            });
            data.push(ESCPOSCommand.cut_paper_old_syntax);

            await qz.print(config, data);
        } else {
            Swal.fire({
                title: "Can't Print Queue Number",
                icon: "error",
                text: "please contact the hospital officer",
            });
        }
    } catch (e) {
        console.error(e);
    }
}

document
    .querySelector("button#printButton")
    .addEventListener("click", async () => {
        await startConnection();
        let prescriptions = JSON.parse(
            document.querySelector('meta[name="prescriptions"]').content
        );
        await printMedicinePrescriptions(prescriptions);
        await closeConnection();
    });
