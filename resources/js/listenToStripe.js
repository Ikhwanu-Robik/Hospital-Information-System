import axios from "axios";
import "./app";
import Swal from "sweetalert2";

let prescriptionRecordId = document.querySelector(
    'meta[name="prescription-record-id"]'
).content;

window.Echo.channel(`Medicine.Dispense.${prescriptionRecordId}`)
    .subscribed(() => {
        axios.get(`/api/medicine-dispense-status/${prescriptionRecordId}`);
    })
    .listen(
    "StripePaymentProcessed",
    async (e) => {
        let paymentStatusIcon = e.paymentStatus == "SUCCESSFUL" ? "success" : "error";

        Swal.fire({
            title: `Payment ${e.paymentStatus}`,
            icon: paymentStatusIcon,
            text: "you are good to leave this page 👌",
        });

        document.getElementById("payment-status-msg").textContent =
            "Payment " + e.paymentStatus;
        document.getElementById("payment-desc-msg").textContent =
            "You are good to leave this page 👌";
    }
);
