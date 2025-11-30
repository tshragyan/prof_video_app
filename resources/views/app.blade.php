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
    <s-app-nav>
        <s-link href="/" rel="home">Home</s-link>
        <s-link href="/templates">Templates</s-link>
        <s-link href="/settings">Settings</s-link>
    </s-app-nav>

    @inertia

</body>
</html>
