import "./app";
import Swal from "sweetalert2";

let prescriptionRecordId = document.querySelector(
    'meta[name="prescription-record-id"]'
).content;

window.Echo.channel(`Medicine.Dispense.${prescriptionRecordId}`).listen(
    "StripePaymentProcessed",
    async (e) => {
        let paymentStatusIcon = e.paymentStatus == "paid" ? "success" : "error";

        Swal.fire({
            title: `Payment ${e.paymentStatus}`,
            icon: paymentStatusIcon,
            text: "you are good to leave this page 👌"
        });
    }
);
