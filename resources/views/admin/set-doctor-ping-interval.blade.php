@extends(backpack_view('blank'))

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Set Doctor Ping Interval</span>
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label for="ping-interval">Set the interval at which the doctor pings the server. This is useful for
                        checking
                        a doctor's online status.</label>
                </div>
                <div class="card-body">
                    <span>Current Interval : {{ isset($currentInterval) ? $currentInterval : "NULL" }}</span>
                    <form action="{{ route('doctors.ping-interval') }}" method="post">
                        @csrf
                        <input type="number" name="ping_interval" id="ping-interval">
                        <button type="submit" class="btn btn-primary">Set Interval</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection