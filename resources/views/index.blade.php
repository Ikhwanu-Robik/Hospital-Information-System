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
            max-width: 600px;
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
            flex: 1;
            min-width: 200px;
            padding: 16px 32px;
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

        .btn-staff {
            background-color: #00a86b;
            color: white;
        }

        .btn-staff:hover {
            background-color: #008856;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 168, 107, 0.3);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #000;
        }

        .modal h2 {
            color: #1a3a52;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .staff-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .staff-btn {
            padding: 20px;
            background-color: #f0f4f8;
            border: 2px solid #e0e8f0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #1a3a52;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .staff-btn:hover {
            background-color: #00a86b;
            color: white;
            border-color: #00a86b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 168, 107, 0.2);
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

            .staff-buttons {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 30% auto;
                padding: 30px 20px;
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
            <button class="btn btn-staff" onclick="openStaffModal()">Staff</button>
        </div>
    </div>

    <!-- Staff Modal -->
    <div id="staffModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeStaffModal()">&times;</span>
            <h2>Select Staff Type</h2>
            <div class="staff-buttons">
                <button class="staff-btn" onclick="handleStaffSelection('Doctor')">Doctor</button>
                <button class="staff-btn" onclick="handleStaffSelection('Administration Officer')">Administration Officer</button>
                <button class="staff-btn" onclick="handleStaffSelection('Pharmacist')">Pharmacist</button>
                <button class="staff-btn" onclick="handleStaffSelection('Sysadmin')">Sysadmin</button>
            </div>
        </div>
    </div>

    <script>
        const staffModal = document.getElementById('staffModal');

        function openStaffModal() {
            staffModal.style.display = 'block';
        }

        function closeStaffModal() {
            staffModal.style.display = 'none';
        }

        function handlePatientClick() {
            window.location.href = "{{ route('check-up-queue-form') }}";
        }

        function handleStaffSelection(staffType) {
            switch (staffType) {
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

            closeStaffModal();
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target === staffModal) {
                closeStaffModal();
            }
        }
    </script>
</body>
</html>