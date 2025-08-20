import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

try {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY || 'local',
        wsHost: import.meta.env.VITE_PUSHER_HOST ?? window.location.hostname,
        wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
        forceTLS: false,
        disableStats: true,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
    });
} catch (e) {
    window.Echo = { channel: () => ({ listen() {} }) };
}
