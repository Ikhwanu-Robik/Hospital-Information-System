import qz from "qz-tray";
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", async () => {
    let pageName = document.querySelector('meta[name="page-name"]').content;
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

function populatePrintSelect(printers) {
    let optionsHTML = "";
    for (let i = 0; i < printers.length; i++) {
        optionsHTML += `<option value="${printers[i]}">${printers[i]}</option>`;
    }
    document.querySelector("select#printers").innerHTML = optionsHTML;
}

async function allPrinters() {
    try {
        const printers = await qz.printers.find();
        populatePrintSelect(printers);
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

async function printQueueNumber(queueNumber) {
    try {
        let printerName = getQueueAppPrinter();
        if (printerName != "") {
            let config = qz.configs.create(printerName);
            let data = [
                "\x1B" + "\x40", // init
                "\x1B" + "\x61" + "\x31", // center align
                "QUEUE NO.",
                "\x0A", // line break
                "\x1B" + "\x45" + "\x0D", // bold on
                queueNumber,
                "\x1B" + "\x45" + "\x0A", // bold off
                "\x0A", // line break
                "\x1B" + "\x69", // cut paper (old syntax)
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

async function printMedicinePrescriptions(prescriptions) {
    try {
        let printerName = getQueueAppPrinter();
        if (printerName != "") {
            let config = qz.configs.create(printerName);
            let data = [
                "\x1B" + "\x40", // init
            ];
            prescriptions.forEach((prescription) => {
                let medicineName = prescription.medicine.name;
                let quantity = prescription.dose_amount;
                let frequency = prescription.frequency;

                data.push(medicineName + " x " + quantity + " | " + frequency);
                data.push("\x0A"); // line break
            });
            data.push("\x1B" + "\x69"); // cut paper (old syntax)

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
