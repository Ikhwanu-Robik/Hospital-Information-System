<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Information System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    .hero {
      min-height: 80vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 2rem;
    }
    .hero h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #0d6efd;
    }
    .hero p {
      color: #6c757d;
      margin-bottom: 2rem;
    }
    .role-btn {
      width: 200px;
      margin: 10px;
      padding: 1rem;
      font-size: 1.1rem;
      border-radius: 1rem;
    }
    .features {
      background-color: white;
      padding: 4rem 2rem;
    }
    .feature-card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }
    .feature-card:hover {
      transform: translateY(-5px);
    }
    .modal-header {
      background-color: #0d6efd;
      color: white;
      border-top-left-radius: 0.5rem;
      border-top-right-radius: 0.5rem;
    }
  </style>
</head>
<body>
  <section class="hero">
    <h1>Welcome to Hospital Information System</h1>
    <p>Who are you?</p>
    <div class="d-flex flex-column flex-sm-row justify-content-center">
      <a href="{{ route('check-up-queue-form') }}" class="btn btn-primary role-btn">Patient</a>
      <button class="btn btn-success role-btn" data-bs-toggle="modal" data-bs-target="#staffModal">Staff</button>
    </div>
  </section>

  <!-- Staff Modal -->
  <div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staffModalLabel">Select Your Staff Role</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <div class="d-grid gap-3">
            <a href="{{ route('doctor.diagnosis-form') }}" class="btn btn-outline-primary">Doctor</a>
            <a href="{{ backpack_url('patient') }}" class="btn btn-outline-success">Administration Officer</a>
            <a href="{{ backpack_url('medicine') }}" class="btn btn-outline-info">Pharmacist</a>
            <a href="{{ backpack_url('dashboard') }}" class="btn btn-outline-danger">System Administrator</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Features Section -->
  <section class="features text-center">
    <div class="container">
      <h2 class="mb-5 text-primary">Main Features</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card feature-card p-4">
            <h5>Outpatient Check-up</h5>
            <p>Manage and record patient visits efficiently for outpatient care.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card p-4">
            <h5>Patient Queuing System</h5>
            <p>Real-time queue management to optimize patient flow.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card p-4">
            <h5>Pharmacist Menu</h5>
            <p>Manage prescriptions, inventory, and drug dispensing process.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card p-4">
            <h5>BPJS Integration</h5>
            <p>Seamlessly connect with BPJS services for insurance management.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card p-4">
            <h5>Stripe Integration</h5>
            <p>Accept online payments securely via Stripe platform.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card feature-card p-4">
            <h5>Visit & Medicine Reports</h5>
            <p>Generate insightful reports on patient visits and medicine usage.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
