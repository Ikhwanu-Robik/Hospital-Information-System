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
                    },
                },
            ];
        },
    });
}

document
    .getElementById("add_prescription_button")
    .addEventListener("click", addPrescriptionForm);

function createPrescriptionFieldsDiv() {
    // Create the wrapper div
    const prescriptionDiv = document.createElement("div");
    prescriptionDiv.className = "prescription";

    // Create the div for @algolia/autocomplete to put the dynamic input
    const medicineInputDiv = document.createElement("div");
    medicineInputDiv.id = "medicine_div";
    medicineInputDiv.style.display = "inline";

    // Create the input for medicine_id that will mirror
    // the selected medicine from algolia's generated input
    const medicineIdInput = document.createElement("input");
    medicineIdInput.type = "hidden";
    medicineIdInput.name = "medicine_id[]";
    medicineIdInput.id = "medicine_id";

    // Label + input for Dose
    const doseLabel = document.createElement("label");
    doseLabel.setAttribute("for", "dose");
    doseLabel.textContent = "Dose";

    const doseInput = document.createElement("input");
    doseInput.type = "number";
    doseInput.name = "dose_amount[]";
    doseInput.id = "dose";

    // Label + input for Frequency
    const frequencyLabel = document.createElement("label");
    frequencyLabel.setAttribute("for", "frequency");
    frequencyLabel.textContent = "Frequency";

    const frequencyInput = document.createElement("input");
    frequencyInput.type = "text";
    frequencyInput.name = "frequency[]";
    frequencyInput.id = "frequency";

    // Append elements to the wrapper div
    prescriptionDiv.appendChild(medicineInputDiv);
    prescriptionDiv.appendChild(medicineIdInput);
    prescriptionDiv.appendChild(doseLabel);
    prescriptionDiv.appendChild(doseInput);
    prescriptionDiv.appendChild(frequencyLabel);
    prescriptionDiv.appendChild(frequencyInput);

    applyAutocomplete(medicineInputDiv, medicineIdInput);

    return prescriptionDiv;
}

function addPrescriptionForm(event) {
    event.preventDefault();
    event.stopPropagation();

    let prescriptionFieldsContainer = document.getElementById(
        "prescription-fields-container"
    );
    prescriptionFieldsContainer.appendChild(createPrescriptionFieldsDiv());
}
