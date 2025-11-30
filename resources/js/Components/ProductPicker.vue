<template>

    <s-button @click="openPicker" tone="critical" variant="primary"> Select Products</s-button>

</template>

<script setup>
import { useAttrs, inject } from 'vue'
import { ResourcePicker } from '@shopify/app-bridge/actions'

const appBridge = inject('appBridge')

function openPicker() {
    const picker = ResourcePicker.create(appBridge, {
        resourceType: ResourcePicker.ResourceType.Product,
        multiple: true,
    })

    picker.subscribe(ResourcePicker.Action.SELECT, ({ selection }) => {
        console.log('Selected:', selection)
    })

    picker.dispatch(ResourcePicker.Action.OPEN)
}
</script>
