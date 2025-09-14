@extends(backpack_view('blank'))

@section('header')
    <section class="container-fluid">
        <h1>
            <span class="text-capitalize">Printer Set</span>
        </h1>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2>Queue app default printer set to {{ $printer }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
    