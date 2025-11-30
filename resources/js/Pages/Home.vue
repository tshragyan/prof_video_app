<template>
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

        <div id="app"><!-- your Vue/Inertia app mounts here --></div>
    </s-app-provider>

    <s-page heading="Videocrat">
            <s-text>Shoppable Videos</s-text>
        <s-grid
            gridTemplateColumns="repeat(2, 1fr)"
            gap="small"
            justifyContent="center"
        >
            <s-grid-item gridColumn="span 1" border="base" borderStyle="dashed">
                Imported Videos
            </s-grid-item>
        </s-grid>
    </s-page>

</template>

<script setup>
import ProductPicker from '../Components/ProductPicker.vue'
import { ResourcePicker } from '@shopify/app-bridge/actions'

</script>
