<html>

<head>
   <meta name="doctor-profile-id" content="{{ Auth::user()->doctorProfile()->first()->id }}">
   <meta name="medicines" content="{{ json_encode($medicines) }}">
   @vite('resources/css/autocomplete-custom.css')
   @vite('resources/js/useAutoComplete.js')
   @vite('resources/css/diagnosis-form.css')
</head>

{{--
TODO: make a page for doctor to:
1. accept a patient automatically if in schedule,
and not handling any patient
(event broadcasting)
(doctor doesn't need to refresh)
(how did I make this without queueing system?)

2. create a medical record for the patient
3. create a medicine prescription for the patient
(doctor can see a list of available medicines)
(or just the list of medicines?)
(or just a search bar of the medicine name with autocomplete?)
4. dismiss the patient once finished
(button)
--}}

<body>
   <h1>Diagnose A Patient</h1>

   <div class="card" id="patient-data">
      <div class="card-header">
         <h3 class="card-title">Patient</h3>
      </div>
      <div class="table-responsive">
         <table class="table card-table table-vcenter text-nowrap datatable">
            <tbody>
               <tr>
                  <th>MRN</th>
                  <td id="medical-record-number">MRN-2025-000001</td>
               </tr>
               <tr>
                  <th>Full Name</th>
                  <td id="full-name">Tiansi Nandika Ramadani</td>
               </tr>
               <tr>
                  <th>Birthdate</th>
                  <td id="birthdate">12 March 2008</td>
               </tr>
               <tr>
                  <th>Gender</th>
                  <td id="gender">Female</td>
               </tr>
               <tr>
                  <th>Address</th>
                  <td id="address">Weru Brother</td>
               </tr>
               <tr>
                  <th>Married</th>
                  <td id="status">No</td>
               </tr>
               <tr>
                  <th>Phone</th>
                  <td id="phone">0816738823</td>
               </tr>
               <tr>
                  <th>BPJS</th>
                  <td id="BPJS_number">No</td>
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
               {{-- @foreach ($patient->medicalRecords as $medicalRecord) --}}
               <tr>
                  <td>Budi Santoso Utomo</td>
                  <td>Cardiologist</td>
                  <td>Nyeri kepala di sebelah belakang</td>
                  <td>Hipertensi</td>
                  <td>
                     <ul>
                        {{-- @foreach ($medicalRecord->prescriptionRecord->prescriptionMedicines as
                        $prescriptionMedicine) --}}
                        <li>Panadol x 5 | 3 times a day, before meal</li>
                        {{-- @endforeach --}}
                     </ul>
                  </td>
               </tr>
               {{-- @endforeach --}}
            </tbody>
         </table>
      </div>
   </div>

   <form action="" method="post" id="medical_record_form">
      @csrf
      <input type="hidden" name="medical_record_number" value="MRN-2025-000001">
      <label for="complaint">Complaint</label>
      <input type="text" name="complaint" id="complaint">
      <label for="diagnosis">Diagnosis</label>
      <input type="text" name="diagnosis" id="diagnosis">
      <button id="add_prescription_button">Add Prescription</button>
      <div id="prescription-fields-container">

      </div>
      <button type="submit">Save</button>
   </form>
</body>

</html>