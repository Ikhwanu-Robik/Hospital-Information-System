<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Medicine Prescriptions</title>
    @vite('resources/js/qzTrayThermalPrint.js')
    @vite('resources/css/print-medicine-prescriptions.css')
    <meta name="page-name" content="print-medicine-prescription">
    <meta name="printer-name" content="{{ $printerName }}">
    <meta name="prescriptions" content="{{ json_encode(session('prescriptions')) }}">
</head>

<body>
    <div class="print-container">
        <h2 class="print-title">
            🖨️ Printing Medicine Prescription
        </h2>

        <div class="print-actions">
            <a href="{{ route('doctor.diagnosis-form') }}" class="btn btn-primary">⬅ Go Back</a>

            <div class="print-retry">
                <span class="no-print-text">No print?</span>
                <button id="printButton" class="btn btn-outline-danger">Print Again</button>
            </div>
        </div>
    </div>
</body>

</html>