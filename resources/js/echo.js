import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

const options = {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
};

window.Echo = new Echo({
    ...options,
    client: new Pusher(options.key, options)
});
