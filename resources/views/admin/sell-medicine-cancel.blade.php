{{-- resources/views/prescriptions/payment-cancelled.blade.php --}}

@extends(backpack_view('blank'))

@section('content')
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Cancelled Card --}}
                <div class="card shadow-lg rounded-3">
                    <div class="card-body text-center">

                        {{-- Cancel Icon --}}
                        <div class="mb-4">
                            <i class="la la-times-circle text-danger" style="font-size: 4rem;"></i>
                        </div>

                        {{-- Title & Message --}}
                        <h2 class="mb-3 text-danger">Payment Cancelled</h2>
                        <p class="text-muted">
                            Your payment was not completed. You can review your prescription and try again.
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

                        {{-- CTA Buttons --}}
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