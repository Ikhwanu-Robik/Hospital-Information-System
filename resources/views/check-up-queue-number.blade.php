<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="page-name" content="check-up-queue-number">
    <meta name="queue-number" content="{{ $queueNumber }}">    
    <meta name="printer-name" content="{{ $printerName }}">
    <title>Queue Number</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/js/qzTrayThermalPrint.js')
</head>

<body>
    <nav class="mb-4">
        <a href="{{ route('check-up-queue-form') }}" class="btn btn-secondary">
            ← Back to Queue Registration
        </a>
    </nav>

    <div class="card text-center shadow-sm p-4 mx-auto d-flex flex-column justify-content-center align-items-center" style="max-width: 400px; height: 80vh;">
        <h1 class="h3 mb-3">Your Queue Number</h1>
        <span class="display-3 fw-bold text-primary">{{ $queueNumber }}</span>
    </div>
</body>

</html>