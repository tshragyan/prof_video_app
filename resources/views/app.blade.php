<!DOCTYPE html>
<html>
<head>
    <meta name="shopify-api-key" content="{{config('services.shopify.api_key')}}" />
    <script src="https://cdn.shopify.com/shopifycloud/app-bridge.js"></script>
    <script src="https://cdn.shopify.com/shopifycloud/polaris.js"></script>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body>
<s-app-provider
    api-key="{{ config('services.shopify.api_key') }}"
    host="{{ request('host') }}"
>
    <s-app-nav>
        <s-link
            href="/"
        >
        </s-link>

        <s-link
            href="/products"
        >
        </s-link>

        <s-link
            href="/settings"
        >
        </s-link>
    </s-app-nav>

    @inertia

</s-app-provider>
</body>
</html>
