<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Information System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 60vw;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }

        .header {
            margin-bottom: 50px;
        }

        .logo {
            font-size: 48px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 32px;
            color: #1a3a52;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .subtitle {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 20px;
            margin-top: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            width: 13rem;
            height: 13rem;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-patient {
            background-color: #0066cc;
            color: white;
        }

        .btn-patient:hover {
            background-color: #0052a3;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 102, 204, 0.3);
        }

        .btn-locket {
            background-color: #0ecc00;
            color: white;
        }

        .btn-locket:hover {
            background-color: #087400;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 102, 204, 0.3);
        }

        .btn-doctor {
            background-color: #00b1cc;
            color: white;
        }

        .btn-doctor:hover {
            background-color: rgb(0, 65, 130);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 102, 204, 0.3);
        }

        .btn-admin {
            background-color: #6d00cc;
            color: white;
        }

        .btn-admin:hover {
            background-color: #4a008b;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 102, 204, 0.3);
        }

        .btn-pharmacist {
            background-color: #cc0000;
            color: white;
        }

        .btn-pharmacist:hover {
            background-color: #9a0000;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 102, 204, 0.3);
        }

        .btn-sysadmin {
            background-color: #998410;
            color: white;
        }

        .btn-sysadmin:hover {
            background-color: #695b0b;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 102, 204, 0.3);
        }

        @media (max-width: 600px) {
            .container {
                padding: 40px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏥</div>
            <h1>Hospital Information System</h1>
            <p class="subtitle">Select your role to access the system and manage your healthcare needs efficiently.</p>
        </div>

        <div class="button-group">
            <button class="btn btn-patient" onclick="handlePatientClick()">Patient</button>
            <button class="btn btn-locket" onclick="handleStaffSelection('Queue Locket Process Display')">Queue Locket Process Display</button>
            <button class="btn btn-doctor" onclick="handleStaffSelection('Doctor')">Doctor</button>
            <button class="btn btn-admin" onclick="handleStaffSelection('Administration Officer')">Administration Officer</button>
            <button class="btn btn-pharmacist" onclick="handleStaffSelection('Pharmacist')">Pharmacist</button>
            <button class="btn btn-sysadmin" onclick="handleStaffSelection('Sysadmin')">Sysadmin</button>
        </div>
    </div>

    <script>
        function handlePatientClick() {
            window.location.href = "{{ route('check-up-queue-form') }}";
        }

        function handleStaffSelection(staffType) {
            switch (staffType) {
                case 'Queue Locket Process Display':
                    window.location.href = "{{ route('locket.all') }}";
                    break;
                case 'Doctor':
                    window.location.href = "{{ route('doctor.diagnosis-form') }}";
                    break;
                case 'Administration Officer':
                    window.location.href = "{{ backpack_url('patient') }}";
                    break;
                case 'Pharmacist':
                    window.location.href = "{{ backpack_url('medicine') }}";
                    break;
                case 'Sysadmin':
                    window.location.href = "{{ backpack_url('dashboard') }}";
                    break;
            }
        }
    </script>
</body>
</html>