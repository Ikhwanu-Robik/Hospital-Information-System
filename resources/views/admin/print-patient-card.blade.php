@extends(backpack_view('blank'))

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Print Patient Card</span>
        </h2><datalist></datalist>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Preview</h3>
                </div>
                <div class="card-body">
                    <div id="preview-printable">
                        @include('components.patient-card')
                    </div>
                    <span onclick="printDiv('preview-printable')" class="btn btn-sm btn-link text-capitalize"><i
                            class="la la-print"></i> Print</a>

                    <script>
                        function printDiv(divId) {
                            var printContents = document.getElementById(divId).innerHTML;
                            var originalContents = document.body.innerHTML;

                            document.body.innerHTML = printContents;

                            window.print();

                            document.body.innerHTML = originalContents;
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection