import "./bootstrap";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const userIdMeta = document.querySelector('meta[name="user-id"]');
if (userIdMeta) {
    const userId = userIdMeta.getAttribute("content");

    window.Echo = new Echo({
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: false,
        wsHost: window.location.hostname,
        wsPort: 6001,
        wssPort: 6001,
        disableStats: true,
        enabledTransports: ["ws", "wss"],
    });

    window.Echo.channel(`cart.${userId}`).listen(".CartUpdated", (e) => {
        console.log("Received CartUpdated:", e);

        const badge = document.getElementById("cart-badge-count");
        if (badge) {
            badge.textContent = e.count;
        }
    });
}
