import "./app";
import Swal from "sweetalert2";

let prescriptionRecordId = document.querySelector(
    'meta[name="prescription-record-id"]'
).content;

let isStripePaymentProcessedBroadcasted = false;

window.Echo.channel(`Medicine.Dispense.${prescriptionRecordId}`).listen(
    "StripePaymentProcessed",
    async (e) => {
        isStripePaymentProcessedBroadcasted = true;
        let paymentStatusIcon = e.paymentStatus == "paid" ? "success" : "error";

        Swal.fire({
            title: `Payment ${e.paymentStatus}`,
            icon: paymentStatusIcon,
            text: "you are good to leave this page 👌",
        });
    }
);

// this is a fallback function to be called in case
// the event is broadcasted before this page finished loading
setTimeout(async () => {
    if (!isStripePaymentProcessedBroadcasted) {
        try {
            let response = await fetch(
                `http://127.0.0.1:8000/prescriptions/${prescriptionRecordId}`,
                {
                    method: "GET",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    credentials: "same-origin",
                }
            );

            if (!response.ok) {
                Swal.fire({
                    title: "Error fetching prescriptions status",
                    icon: "error",
                    text: response.statusText,
                });

                console.error(await response.json());
            } else {
                let body = await response.json();
                let paymentStatusIcon =
                    body.payment_status == "SUCCESSFUL" ? "success" : "error";

                Swal.fire({
                    title: `Payment ${body.payment_status}`,
                    icon: paymentStatusIcon,
                    text: "you are good to leave this page 👌",
                });
            }
        } catch (e) {
            console.error(e);
        }
    }
}, 5000);
