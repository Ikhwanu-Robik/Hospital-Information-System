@if ($crud->hasAccess('direct patient') || true)
  <a href="{{ url($crud->route.'/'.$entry->getKey().'/print-patient-card') }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-print"></i> Print Card</a>
@endif