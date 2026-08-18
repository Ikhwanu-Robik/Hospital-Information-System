import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

const options = {
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
};

window.Pusher = new Pusher(options.key);

window.Echo = new Echo({
    broadcaster: 'pusher',
    ...options
});
