import createApp from '@shopify/app-bridge';

export function initShopifyAppBridge(props) {
    if (!props.host) {
        console.warn("⚠️ host not provided — app likely not inside Shopify Admin");
        return null;
    }

    return createApp({
        apiKey: import.meta.env.VITE_SHOPIFY_API_KEY,
        host: props.host,
        forceRedirect: true,
    });
}
