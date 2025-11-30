import createApp from '@shopify/app-bridge';
import { getSessionToken } from '@shopify/app-bridge-utils';

export function initShopifyAppBridge(props) {
    const url = new URL(window.location.href);

    const app = createApp({
        apiKey: import.meta.env.VITE_SHOPIFY_API_KEY,
        host: url.searchParams.get('host'),
        forceRedirect: true,
    });

    getSessionToken(app).then(token => {
        if (window.axios) {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }
    });

    return app;
}

export async function apiRequest(url, method = "POST", data = {}) {
    const token = await getSessionToken(app);

    return axios({
        url,
        method,
        data,
        headers: {
            Authorization: `Bearer ${token}`
        }
    });
}

