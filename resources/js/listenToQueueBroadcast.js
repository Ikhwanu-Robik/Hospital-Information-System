import './app';

const overlay = document.getElementById('overlay');

overlay.addEventListener('click', () => {
    player.playText('Locket ready to call patient');

    // Hide overlay
    overlay.classList.add('hidden');
});

let locketId = document.querySelector(
    'meta[name="locket-id"]'
).content;

let player = new window.talkify.Html5Player();

window.Echo.channel(`Locket.${locketId}`).listen(
    'QueueReadyForBroadcast',
    (e) => {
        let queueNumberDisplay = document.getElementById('queue-number');
        queueNumberDisplay.textContent = e.queueNumber;

        let roomNumberDisplay = document.getElementById('room-number');
        roomNumberDisplay.textContent = e.roomNumber;

        player.playText(e.message);
    }
);