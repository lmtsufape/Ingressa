import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const scheme = import.meta.env.VITE_REVERB_SCHEME ?? 'https';
const host = import.meta.env.VITE_REVERB_HOST ?? window.location.hostname;

// Porta padrão de acordo com o esquema
const port = Number(
  import.meta.env.VITE_REVERB_PORT ?? (scheme === 'https' ? 443 : 80)
);

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,

  wsHost: host,
  wsPath: import.meta.env.VITE_REVERB_WS_PATH ?? '/reverb',

  forceTLS: scheme === 'https',

  // Se for HTTPS, foca em WSS (443). Se for HTTP, foca em WS (80).
  wsPort: scheme === 'https' ? 80 : port,
  wssPort: scheme === 'https' ? port : 443,

  // Se seu site é HTTPS, você pode limitar só a wss (mais seguro):
  enabledTransports: scheme === 'https' ? ['wss'] : ['ws'],
});

