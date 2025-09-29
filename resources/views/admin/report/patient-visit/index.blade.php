@extends(backpack_view('blank'))

@section('header')
    <div class="d-none" id="metas">
        <meta name="report-by-gender" content="{{ json_encode($reportByGender) }}">
        <meta name="report-by-age-group" content="{{ json_encode($reportByAgeGroup) }}">
        <meta name="report-by-time-of-day" content="{{ json_encode($reportByTimeOfTheDay) }}">
        <meta name="report-by-bpjs" content="{{ json_encode($reportByBPJS) }}">
        <meta name="report-over-time" content="{{ json_encode($reportOverTime) }}">
        <meta name="report-by-specialization" content="{{ json_encode($reportBySpecialization) }}">
    </div>

    <section class="content-header">
        <h1>
            Patient Visit Report
            <br>
            <small>Different views and breakdowns</small>
        </h1>

        <div class="card m-4 p-4">
            <form method="GET" action="{{ url()->current() }}" class="mb-3">
                <div class="mb-3">
                    <label class="form-label">Filter Type</label>
                    <select id="filterType" class="form-select">
                        <option value="">-- Select filter type --</option>
                        <option value="date">By Date</option>
                        <option value="range">By Date Range</option>
                        {{-- <option value="this-week">This Week</option>
                        <option value="this-month">This Month</option> --}}
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

                {{-- This Week --}}
                {{-- <div id="thisWeekFilter" class="row g-2 mb-3 d-none">
                    <label for="timespan" class="form-label">This week</label>
                    <input type="hidden" id="timespan" name="week-span" class="form-control"
                        value="{{ request('this-week') }}">
                </div> --}}

                {{-- This Month --}}
                {{-- <div id="thisMonthFilter" class="row g-2 mb-3 d-none">
                    <label for="timespan" class="form-label">This Month</label>
                    <input type="hidden" id="timespan" name="month-span" class="form-control"
                        value="{{ request('this-month') }}">
                </div> --}}

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
    <div class="row">
        {{-- Section 6: Trend Over Time --}}
        <div class="col-md-10 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Visits Over Time</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <p>Monthly trend of visits in {{ now()->year }}</p>
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Section 1: By Gender --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Visits by Gender</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Section 2: By Age Group --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Visits by Age Group</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Section 3: By Time of Day --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Visits by Time of Day</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="timeChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Section 4: By BPJS --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Visits by BPJS</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="bpjsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Section 5: By Doctor Specialization --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Visits by Doctor Specialization</h5>
                </div>
                <div class="card-body">
                    {{-- chart or table here --}}
                    <canvas id="specializationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    {{-- Example: Using Chart.js for quick visualizations --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const reportByGender = JSON.parse(document.querySelector('meta[name="report-by-gender"]').content);
        const reportByAgeGroup = JSON.parse(document.querySelector('meta[name="report-by-age-group"]').content);
        const reportByTimeOfTheDay = JSON.parse(document.querySelector('meta[name="report-by-time-of-day"]').content);
        const reportByBPJS = JSON.parse(document.querySelector('meta[name="report-by-bpjs"]').content);
        const reportOverTime = JSON.parse(document.querySelector('meta[name="report-over-time"]').content);
        const reportBySpecialization = JSON.parse(document.querySelector('meta[name="report-by-specialization"]').content);

        // Example placeholder dataset for Gender
        if (reportByGender.label.length) {
            new Chart(document.getElementById('genderChart'), {
                type: 'pie',
                data: {
                    labels: reportByGender.label,
                    datasets: [{
                        data: reportByGender.data
                    }]
                }
            });
        } else {
            document.getElementById('genderChart').parentElement.textContent = "No data";
        }

        if (reportByAgeGroup.label.length) {
            new Chart(document.getElementById('ageChart'), {
                type: 'pie',
                data: {
                    labels: reportByAgeGroup.label,
                    datasets: [{
                        data: reportByAgeGroup.data
                    }]
                }
            });
        } else {
            document.getElementById('ageChart').parentElement.textContent = "No data";
        }

        if (reportByTimeOfTheDay.label.length) {
            new Chart(document.getElementById('timeChart'), {
                type: 'pie',
                data: {
                    labels: reportByTimeOfTheDay.label,
                    datasets: [{
                        data: reportByTimeOfTheDay.data
                    }]
                }
            });
        } else {
            document.getElementById('timeChart').parentElement.textContent = "No data";
        }

        if (reportByBPJS.label.length) {
            new Chart(document.getElementById('bpjsChart'), {
                type: 'pie',
                data: {
                    labels: reportByBPJS.label,
                    datasets: [{
                        data: reportByBPJS.data
                    }]
                }
            });
        } else {
            document.getElementById('bpjsChart').parentElement.textContent = "No data";
        }

        if (reportOverTime.label.length) {
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: reportOverTime.label, // x-axis labels
                    datasets: [{
                        label: 'Trend',
                        data: reportOverTime.data, // y-axis values
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
                                text: 'Time'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Count'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

        } else {
            document.getElementById('trendChart').parentElement.textContent = "No data";
        }

        if (reportBySpecialization.label.length) {
            new Chart(document.getElementById('specializationChart'), {
                type: 'pie',
                data: {
                    labels: reportBySpecialization.label,
                    datasets: [{
                        data: reportBySpecialization.data
                    }]
                }
            });
        } else {
            document.getElementById('specializationChart').parentElement.textContent = "No data";
        }

        // Add similar Chart.js configs for the other sections...
    </script>
@endpush