import Swal from "sweetalert2";

const errorMessageJson = JSON.parse(document.querySelector('meta[name="validation-errors"]').content);
let errorText = '';

errorMessageJson.forEach(message => {
    errorText += message;
    errorText += '\n';
});

Swal.fire({
    title: "Wrong Input",
    text: errorText,
    icon: "error",
});
