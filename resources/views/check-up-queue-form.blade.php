<!DOCTYPE html>
<html lang="en">

<head>
    @if($errors->any())
        <meta name="validation-errors" content="{{ json_encode($errors->all()) }}">
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Queue Number</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/js/validationErrorAlert.js')
</head>

<body class="p-4">
    <div class="container">
        <h1 class="mb-4 text-2xl font-semibold">Get Queue Number</h1>

        <form action="{{ route('join-check-up-queue') }}" method="post" class="card p-4 shadow-sm">
            @csrf
            {{-- // TODO: change manual input of MRN to a scan of patient card --}}
            <div class="mb-3">
                <label for="medical_record_number" class="form-label">Medical Record Number</label>
                <input type="text" name="medical_record_number" id="medical_record_number"
                    placeholder="Contoh : MRN-2025-000001" class="form-control" />
            </div>

            <label for="medical_record_number" class="form-label">Choose Doctor Specialization</label>
            <div class="grid-layout grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-4">
                @foreach ($specializations as $Specialization)
                    <button type="submit" name="specialization" value="{{ $Specialization->name }}"
                        class="card p-3 text-center hover:shadow-md transition">
                        {{ $Specialization->name }}
                    </button>
                @endforeach
            </div>
        </form>
    </div>
</body>

</html>