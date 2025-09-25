@extends(backpack_view('blank'))

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Dispense Medicines</span>
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    @if ($errors->any())
                        <div class="card">
                            <div class="card-header">
                                <h2>There is an error in your input</h2>
                            </div>
                            <div class="card-body">
                                @foreach ($errors->all() as $error)
                                    <div>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="card-body">
                        <form action="{{ route('search-medicine') }}" method="get" class="d-flex gap-2">
                            @csrf
                            <div class="input-group">
                                <label for="patient_id">RX-</label>
                                <input type="number" name="patient_id" class="form-control"
                                 value="{{ request()->query('patient_id') }}">
                                <label for="doctor_profile_id">-</label>
                                <input type="number" name="doctor_profile_id" class="form-control"
                                 value="{{ request()->query('doctor_profile_id') }}">
                                <label for="id">-</label>
                                <input type="number" name="id" id="prescription_id" class="form-control"
                                    value="{{ request()->query('id') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search"></i> Search
                                </button>
                            </div>
                        </form>

                        @if (isset($prescriptionMedicines) && isset($prescription))
                            <div id="search-result" class="mt-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Medicines</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-nowrap datatable">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Generic Name</th>
                                                        <th>Drug Class</th>
                                                        <th>Form</th>
                                                        <th>Unit</th>
                                                        <th>Stock</th>
                                                        <th>Price</th>
                                                        <th>Batch #</th>
                                                        <th>Doses</th>
                                                        <th>Frequency</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $grandTotal = 0;
                                                    @endphp
                                                    @foreach ($prescriptionMedicines as $prescriptionMedicine)
                                                        <tr>
                                                            <td>{{ $prescriptionMedicine->medicine->name }}</td>
                                                            <td>{{ $prescriptionMedicine->medicine->generic_name }}</td>
                                                            <td>{{ optional($prescriptionMedicine->medicine->drugClass)->name }}
                                                            </td>
                                                            <td>{{ optional($prescriptionMedicine->medicine->medicineForm)->name }}
                                                            </td>
                                                            <td>{{ $prescriptionMedicine->medicine->unit }}</td>
                                                            <td>{{ $prescriptionMedicine->medicine->stock }}</td>
                                                            <td>{{ $prescriptionMedicine->medicine->price }}</td>
                                                            <td>{{ $prescriptionMedicine->medicine->batch_number }}</td>
                                                            <td>{{ $prescriptionMedicine->dose_amount }}</td>
                                                            <td>{{ $prescriptionMedicine->frequency }}</td>
                                                            @php
                                                                $subTotal = $prescriptionMedicine->dose_amount * $prescriptionMedicine->medicine->price;
                                                                $grandTotal += $subTotal;
                                                            @endphp
                                                            <td>{{ $subTotal }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="2"><b>Grand Total</b></td>
                                                        <td colspan="11"><b>{{ $grandTotal }}</b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <form action="{{ route('buy-medicines') }}" method="post" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $prescription->id }}">
                                            <button type="submit" class="btn btn-success">
                                                <i class="ti ti-shopping-cart"></i> Buy
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection