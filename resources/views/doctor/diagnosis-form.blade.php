<html>

<head>
   <meta name="doctor-profile-id" content="{{ Auth::user()->doctorProfile()->first()->id }}">
   <meta name="medicines" content="{{ json_encode($medicines) }}">
   @vite('resources/js/listenToPatient.js')
   @vite('resources/css/autocomplete-custom.css')
   @vite('resources/js/useAutoComplete.js')
   @vite('resources/css/diagnosis-form.css')
   @vite('resources/js/validateMedicineForm.js')
</head>

<body>
   <div class="flex space-between">
      <h1>Diagnose A Patient</h1>
      <a href="{{ route('logout') }}" class="btn btn-secondary">Logout</a>
   </div>

   <div class="card" id="patient-data">
      <div class="card-header">
         <h3 class="card-title">Patient</h3>
      </div>
      <div class="table-responsive">
         <table class="table card-table table-vcenter text-nowrap datatable">
            <tbody>
               <tr>
                  <th>MRN</th>
                  <td id="medical-record-number"></td>
               </tr>
               <tr>
                  <th>Full Name</th>
                  <td id="full-name"></td>
               </tr>
               <tr>
                  <th>Birthdate</th>
                  <td id="birthdate"></td>
               </tr>
               <tr>
                  <th>Gender</th>
                  <td id="gender"></td>
               </tr>
               <tr>
                  <th>Address</th>
                  <td id="address"></td>
               </tr>
               <tr>
                  <th>Married</th>
                  <td id="status"></td>
               </tr>
               <tr>
                  <th>Phone</th>
                  <td id="phone"></td>
               </tr>
               <tr>
                  <th>BPJS</th>
                  <td id="BPJS_number"></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>

   <div class="card" id="patient-medical-records">
      <div class="card-header">
         <h3 class="card-title">Medical Records</h3>
      </div>
      <div class="table-responsive">
         <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
               <tr>
                  <th>Doctor</th>
                  <th>Doctor Specialization</th>
                  <th>Complaint</th>
                  <th>Diagnosis</th>
                  <th>Prescription</th>
               </tr>
            </thead>
            <tbody id="medical-records-tbody">
            </tbody>
         </table>
      </div>
   </div>

   <form action="{{ route('doctor.diagnosis') }}" method="post" id="medical_record_form">
      @csrf
      <input type="hidden" name="doctor_profile_id" value="{{ Auth::user()->doctorProfile()->first()->id }}">
      <input type="hidden" name="queue_id" value="">
      <input type="hidden" name="medical_record_number" value="">
      <label for="complaint">Complaint</label>
      <textarea name="complaint" id="complaint"></textarea>
      <label for="diagnosis">Diagnosis</label>
      <textarea name="diagnosis" id="diagnosis"></textarea>
      {{-- // TODO: move #add_prescription_button below #prescriptioin-field-container,
      also needs to modify the js--}}
      {{-- //TODO: consider rearranging the medicine prescription layout into table --}}
      <button id="add_prescription_button">Add Prescription</button>
      <div id="prescription-fields-container">

      </div>
      <button type="submit">Save</button>
   </form>
   <form action="{{ route('patient.check-up.skip') }}" method="post">
      @csrf
      <input type="hidden" name="doctor_profile_id" value="{{ Auth::user()->doctorProfile()->first()->id }}">
      <input type="hidden" name="queue_id_skip" value="">
      <label for="">If patient didn't come</label>
      <button type="submit">Skip</button>
   </form>
</body>

</html>