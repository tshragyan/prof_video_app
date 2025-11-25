<script setup>
import { onMounted, getCurrentInstance } from 'vue'
import { TitleBar } from '@shopify/app-bridge/actions'

const appBridge = getCurrentInstance().appContext.config.globalProperties.$appBridge

onMounted(() => {
    if (appBridge) {
        TitleBar.create(appBridge, {
            title: "Dashboard",
        });
    } else {
        console.warn("App Bridge is NULL – page opened directly, not inside Shopify")
    }
});
</script>

<template>
    <h1>Shopify App Works!</h1>
</template>
