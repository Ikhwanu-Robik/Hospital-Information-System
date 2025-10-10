import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/autocomplete-custom.css',
                'resources/css/diagnosis-form.css',
                'resources/css/locket-page.css',
                'resources/css/print-medicine-prescriptions.css',
                'resources/js/app.js',
                'resources/js/doctorPing.js',
                'resources/js/listenToPatient.js',
                'resources/js/listenToQueueBroadcast.js',
                'resources/js/listenToStripe.js',
                'resources/js/qzTrayThermalPrint.js',
                'resources/js/useAutoComplete.js',
                'resources/js/validateMedicineForm.js',
                'resources/js/validationErrorAlert.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
