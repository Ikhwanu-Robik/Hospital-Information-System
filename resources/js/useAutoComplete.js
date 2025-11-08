import "@algolia/autocomplete-theme-classic";
import { autocomplete } from "@algolia/autocomplete-js";

let medicines = document.querySelector('meta[name="medicines"]').content;
const items = JSON.parse(medicines);

function applyAutocomplete(elementDisplay, elementData) {
    autocomplete({
        container: elementDisplay,
        placeholder: "Medicine name",
        classNames: {
            root: "aa-inline-root",
            form: "aa-inline-form",
            input: "aa-inline-input",
            submitButton: "aa-hide-button",
        },
        defaultActiveItemId: 0,
        getSources({ query }) {
            return [
                {
                    sourceId: "suggestions",
                    getItems() {
                        return items.filter(({ name }) =>
                            name.toLowerCase().includes(query.toLowerCase())
                        );
                    },
                    templates: {
                        item({ item }) {
                            return `${item.name}`;
                        },
                        noResults() {
                            return "No medicine matching.";
                        },
                    },
                    onSelect({ item, setQuery }) {
                        setQuery(item.name);
                        elementData.value = item.id;
                        removeWarningLabel(elementData.parentElement);
                    },
                },
            ];
        },
        onStateChange() {
            elementData.value = "";
            if (elementData.value == "") {
                let warningLabel = document.createElement("label");
                warningLabel.style = "color: red";
                warningLabel.className = "select-medicine-warning-label";
                warningLabel.textContent =
                    "you must select a medicine from the suggestion list";

                removeWarningLabel(elementData.parentElement);

                elementData.parentElement.prepend(warningLabel);
            }
        },
    });
}

function removeWarningLabel(parent) {
    let warningLabel = null;
    for (const child of parent.children) {
        if (child.className == "select-medicine-warning-label") {
            warningLabel = child;
        }
    }
    if (warningLabel != null) {
        parent.removeChild(warningLabel);
    }
}

document
    .getElementById("add_prescription_button")
    .addEventListener("click", addPrescriptionForm);

function createPrescriptionFields() {
    const wrapperDiv = createWrapperDiv();

    // medicineNameInput is to search the medicine by name,
    // medicineIdInput holds the id of the searched
    // medicine name and is the one to be sent to the server
    const medicineNameInput = createMedicineNameInput();
    const medicineIdInput = createMedicineIdInput();
    const doseInput = createDoseInput();
    const frequencyInput = createFrequencyInput();

    // do not reorder,
    // the code inside addPrescriptionForm()
    // depends on this order
    wrapperDiv.appendChild(medicineNameInput);
    wrapperDiv.appendChild(medicineIdInput);
    wrapperDiv.appendChild(doseInput);
    wrapperDiv.appendChild(frequencyInput);

    return wrapperDiv;
}

function createWrapperDiv() {
    const wrapperDiv = document.createElement("div");
    wrapperDiv.className = "prescription";

    return wrapperDiv;
}

function createMedicineNameInput() {
    const medicineInputDiv = document.createElement("div");
    medicineInputDiv.id = "medicine_div";
    medicineInputDiv.style.display = "inline";

    const medicineLabel = document.createElement("label");
    medicineLabel.textContent = "Medicine";

    medicineInputDiv.appendChild(medicineLabel);

    return medicineInputDiv;
}

function createMedicineIdInput() {
    const medicineIdInput = document.createElement("input");
    medicineIdInput.type = "hidden";
    medicineIdInput.name = "medicine_id[]";
    medicineIdInput.id = "medicine_id";

    return medicineIdInput;
}

function createDoseInput() {
    const doseInputDiv = document.createElement("div");

    const doseLabel = document.createElement("label");
    doseLabel.setAttribute("for", "dose");
    doseLabel.textContent = "Dose";

    const doseInput = document.createElement("input");
    doseInput.type = "number";
    doseInput.name = "dose_amount[]";
    doseInput.id = "dose";

    doseInputDiv.appendChild(doseLabel);
    doseInputDiv.appendChild(doseInput);

    return doseInputDiv;
}

function createFrequencyInput() {
    const frequencyInputDiv = document.createElement("div");

    const frequencyLabel = document.createElement("label");
    frequencyLabel.setAttribute("for", "frequency");
    frequencyLabel.textContent = "Frequency";

    const frequencyInput = document.createElement("input");
    frequencyInput.type = "text";
    frequencyInput.name = "frequency[]";
    frequencyInput.id = "frequency";

    frequencyInputDiv.appendChild(frequencyLabel);
    frequencyInputDiv.appendChild(frequencyInput);

    return frequencyInputDiv;
}

function addPrescriptionForm(event) {
    event.preventDefault();
    event.stopPropagation();

    let prescriptionFields = createPrescriptionFields();
    let medicineNameInput = prescriptionFields.childNodes[0];
    let medicineIdInput = prescriptionFields.childNodes[1];
    applyAutocomplete(medicineNameInput, medicineIdInput);

    let prescriptionFieldsContainer = document.getElementById(
        "prescription-fields-container"
    );
    prescriptionFieldsContainer.appendChild(prescriptionFields);
}
