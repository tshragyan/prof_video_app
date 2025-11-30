<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.shopify.com/shopifycloud/polaris.js"></script>
    <script type="module" src="https://cdn.shopify.com/storefront/web-components.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@shopify/polaris@9.0.0/build/esm/styles.css">
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body>
{{config('shopify.api_key')}}
{{request('host')}}
<s-app-provider
    api-key="{{ config('shopify.api_key') }}"
    host="{{ request('host') }}"
>
    <s-app-nav>
        <s-app-nav-item
            label="Dashboard"
            icon="HomeIcon"
            href="/"
        ></s-app-nav-item>

        <s-app-nav-item
            label="Products"
            icon="Package2Icon"
            href="/products"
        ></s-app-nav-item>

        <s-app-nav-item
            label="Settings"
            icon="SettingsIcon"
            href="/settings"
        ></s-app-nav-item>
    </s-app-nav>

    @inertia

</s-app-provider>
</body>
</html>
