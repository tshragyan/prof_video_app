<template>
    <s-app-nav>
        <s-link href="/" rel="home">Home</s-link>
        <s-link href="/templates">Templates</s-link>
        <s-link href="/settings">Settings</s-link>
    </s-app-nav>
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
