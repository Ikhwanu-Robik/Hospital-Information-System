@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
        <h1>
            Patient Medicine Usage Report
        </h1>

        <div class="card m-4 p-4">
            <form method="GET" action="{{ url()->current() }}" class="mb-3">
                <div class="mb-3">
                    <label class="form-label">Filter Type</label>
                    <select id="filterType" class="form-select">
                        <option value="">-- Select filter type --</option>
                        <option value="date">By Date</option>
                        <option value="range">By Date Range</option>
                    </select>
                </div>

                {{-- Single Date --}}
                <div id="dateFilter" class="mb-3 d-none">
                    <label for="date" class="form-label">Select Date</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

                {{-- Date Range --}}
                <div id="rangeFilter" class="row g-2 mb-3 d-none">
                    <div class="col">
                        <label for="from" class="form-label">From</label>
                        <input type="date" id="from" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col">
                        <label for="to" class="form-label">To</label>
                        <input type="date" id="to" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="la la-filter"></i> Apply Filter
                </button>
                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
            </form>

            @push('after_scripts')
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const filterType = document.getElementById("filterType");
                        const dateFilter = document.getElementById("dateFilter");
                        const rangeFilter = document.getElementById("rangeFilter");

                        function toggleFilters() {
                            dateFilter.classList.add("d-none");
                            rangeFilter.classList.add("d-none");

                            if (filterType.value === "date") {
                                dateFilter.classList.remove("d-none");
                                // clear the value of range filter input
                                // so the server will not take the wrong the data
                                document.querySelector('input[name="from"]').value = "";
                                document.querySelector('input[name="to"]').value = "";
                            } else if (filterType.value === "range") {
                                rangeFilter.classList.remove("d-none");
                                // same as above
                                document.querySelector('input[name="date"]').value = "";
                            }
                        }

                        filterType.addEventListener("change", toggleFilters);

                        // Restore selection based on query params
                        if ("{{ request('date') }}") {
                            filterType.value = "date";
                        } else if ("{{ request('from') }}" || "{{ request('to') }}") {
                            filterType.value = "range";
                        }

                        toggleFilters();
                    });
                </script>
            @endpush
        </div>
    </section>
@endsection

@section('content')
    <div id="metas">
        <meta name="dispensions-per-month" content="{{ json_encode($dispensionsPerMonth) }}">
        <meta name="dispensions-per-specialization" content="{{ json_encode($dispensionsPerSpecialization) }}">
        <meta name="dispensions-per-medicine" content="{{ json_encode($dispensionsPerMedicine) }}">
    </div>

    <div class="row">
        <div class="col-md-10 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Summary</h5>
                </div>
                <div class="card-body row">
                    <div id="total-prescription" class="col-md-4 mb-2">
                        <h4>Total Prescription</h4>
                        <span>{{ $totalPrescription }}</span>
                    </div>
                    <div id="total-medicines-dispensed" class="col-md-4 mb-2">
                        <h4>Total Medicines Dispensed</h4>
                        <span>{{ $totalMedicineDispensed }}</span>
                    </div>
                    <div id="cost-overview" class="col-md-4 mb-2">
                        <h4>Cost Overview</h4>
                        <span>{{ $costOverview != "N/A" ? "Rp. " . number_format($costOverview, 2, '.') : $costOverview }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Trends</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Medicines Dispensed per Specialization</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="dispensePerSpecializationChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Medicines Dispensed</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="dispenseTypeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Most Expensive Medicines Dispensed</h5>
                </div>
                <div class="card-body">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Dispensed Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($expensiveMedicines)
                                @foreach ($expensiveMedicines as $expensiveMedicine)
                                    <tr>
                                        <td>{{ $expensiveMedicine->name }}</td>
                                        <td>{{ "Rp. " . number_format($expensiveMedicine->price, 2, '.') }}</td>
                                        <td>{{ $expensiveMedicine->dispensed_amount }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    {{-- Example: Using Chart.js for quick visualizations --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example placeholder dataset for Gender
        const dispensionsPerMonth = JSON.parse(document.querySelector('meta[name="dispensions-per-month"]').content);
        const dispensionsPerSpecialization = JSON.parse(document.querySelector('meta[name="dispensions-per-specialization"]').content);
        const dispensionsPerMedicine = JSON.parse(document.querySelector('meta[name="dispensions-per-medicine"]').content);

        if (dispensionsPerSpecialization) {
            new Chart(document.getElementById('dispensePerSpecializationChart'), {
                type: 'pie',
                data: {
                    labels: dispensionsPerSpecialization.label,
                    datasets: [{
                        data: dispensionsPerSpecialization.data
                    }]
                }
            });
        }

        if (dispensionsPerMedicine) {
            new Chart(document.getElementById('dispenseTypeChart'), {
                type: 'pie',
                data: {
                    labels: dispensionsPerMedicine.label,
                    datasets: [{
                        data: dispensionsPerMedicine.data
                    }]
                }
            });
        }

        if (dispensionsPerMonth) {
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: dispensionsPerMonth.label, // x-axis labels
                    datasets: [{
                        label: 'Trend',
                        data: dispensionsPerMonth.data, // y-axis values
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.1)',
                        fill: true,
                        tension: 0.3 // smooth curves
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Medicine Dispensed'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Add similar Chart.js configs for the other sections...
    </script>
@endpush