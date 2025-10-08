import Swal from "sweetalert2";

const form = document.getElementById('medical_record_form');

function isAllValid(validationResult) {
    let isAllValid = true;

    validationResult.forEach((validation) => {
        if (!validation.isValid) {
            isAllValid = false;
        }
    });

    return isAllValid;
}

function fireSweetAlertValidation(validationResult) {
    let validationMesssages = '';

    validationResult.forEach((validation) => {
        validationMesssages += validation.message;
        validationMesssages += '\n';
    });

    Swal.fire({
        title: 'Input Invalid',
        text: validationMesssages,
        icon: 'error'
    });
}

function medicineIdNotEmpty() {
    let medicineIdInputs = document.querySelectorAll('#medical_record_form input[name="medicine_id\[\]"]');

    let allInputsNotEmpty = true;
    if (medicineIdInputs.length != 0) {
        medicineIdInputs.forEach((input) => {
            if (input.value == '') {
                allInputsNotEmpty = false;
            }
        });
    }

    return {
        isValid: allInputsNotEmpty,
        message: allInputsNotEmpty ? '' : 'You must select a medicine from the suggestion'
    };
}

form.addEventListener('submit', (event) => {
    event.preventDefault();
    event.stopPropagation();

    clearInterval(window.doctorPingProcess);

    let validationResult = [];

    validationResult.push(medicineIdNotEmpty());

    if (!isAllValid(validationResult)) {
        fireSweetAlertValidation(validationResult);
    } else {
        form.submit();
    }
});