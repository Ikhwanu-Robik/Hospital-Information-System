let doctorPingInterval = document.querySelector('meta[name="doctor-ping-interval"]').content;
let doctorProfileId = document.querySelector('meta[name="doctor-profile-id"]').content;

setInterval(() => {
    fetch(`/doctors/${doctorProfileId}/ping`, {
        method: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    });
}, doctorPingInterval);