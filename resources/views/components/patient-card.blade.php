{{-- resources/views/patients/id_card.blade.php --}}
<div class="d-flex justify-content-center my-4">
    <div class="card shadow-sm border rounded-4"
        style="width: 350px; height: 220px; font-size: 0.85rem; background-color: #ffffff; color: #000000;">
        <div class="card-body p-3">
            <div class="d-flex">
                {{-- Patient Photo Placeholder --}}
                <div class="me-3 text-center">
                    <div class="rounded border d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 100px; font-size: 0.75rem; background-color: #f8f9fa; color: #000;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24"
                            aria-label="Medical Cross" role="img" fill="currentColor">
                            <title>Medical Cross</title>
                            <path
                                d="M10 4a2 2 0 0 0-2 2v2H6a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-2V6a2 2 0 0 0-2-2h-2z" />
                        </svg>

                    </div>
                </div>

                {{-- Patient Details --}}
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">{{ $patient->full_name }}</h6>
                    <p class="mb-1"><strong>NIK:</strong> {{ $patient->NIK }}</p>
                    <p class="mb-1"><strong>Birthdate:</strong> {{ $patient->birthdate }}</p>
                    <p class="mb-1"><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                    <p class="mb-1"><strong>BPJS:</strong> {{ $patient->BPJS_number }}</p>
                </div>
            </div>

            <hr class="my-2" style="border-color: #000000;">

            {{-- Footer Info --}}
            <div class="row text-start">
                <div class="col-6">
                    <p class="mb-1"><strong>Status:</strong> {{ $patient->marriage_status ? 'married' : 'single' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $patient->phone }}</p>
                </div>
                <div class="col-6">
                    <p class="mb-0"><strong>Address:</strong></p>
                    <small>{{ $patient->address }}</small>
                </div>
            </div>
        </div>
    </div>
</div>