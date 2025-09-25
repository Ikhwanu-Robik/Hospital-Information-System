{{-- resources/views/prescriptions/payment-success.blade.php --}}

@extends(backpack_view('blank'))

@section('content')
    <div id="vites">
        @vite('resources/js/listenToStripe.js')
    </div>
    <div id="metas">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="prescription-record-id" content="{{ $prescriptionRecordId }}">
    </div>

    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Success Card --}}
                <div class="card shadow-lg rounded-3">
                    <div class="card-body text-center">

                        {{-- Success Icon --}}
                        <div class="mb-4">
                            <i class="la la-clock text-success" style="font-size: 4rem;"></i>
                        </div>

                        {{-- Title & Message --}}
                        <h2 class="mb-3">Payment Submitted</h2>
                        <p class="text-muted">
                            Your payment was submitted successfully. {{ $patientHasBPJS ? "" : "Wait for confirmation for whether the payment is
                                successful or not."}}
                        </p>

                        @if($prescriptionMedicines)
                            {{-- Prescription Table --}}
                            <div class="table-responsive mt-4">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th class="text-end">Dose</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prescriptionMedicines as $item)
                                            <tr>
                                                <td>{{ $item->medicine->name }}</td>
                                                <td class="text-end">{{ $item->dose_amount }} x {{ $item->medicine->unit }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h3>Your prescription was unable to be loaded</h3>
                        @endif

                        {{-- CTA Button --}}
                        <div class="mt-4">
                            <a href="{{ route('sell-medicine') }}" class="btn btn-primary">
                                <i class="la la-home me-2"></i> Back to Dispense Page
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection