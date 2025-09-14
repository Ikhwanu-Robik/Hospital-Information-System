@extends(backpack_view('blank'))

@section('header')
    @vite('resources/js/qzTrayThermalPrint.js')
    <meta name="page-name" content="set-queue-number-printer">

    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Choose Printer For Queue Number</span>
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label for="printers">Choose Printer That Support ESC/POS</label>
                </div>
                <div class="card-body">
                    @if ($currentPrinter)
                        <span>Current Printer : {{ $currentPrinter }}</span>
                    @endif
                    <form action="{{ route('queue.printer.set') }}" method="post">
                        @csrf
                        <select name="printer" id="printers"></select>
                        <button type="submit" class="btn btn-primary">Select</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection