<template>
    <s-page heading="Dashboard">
        <s-grid
            gridTemplateColumns="repeat(3, 1fr)"
            gap="small"
            justifyContent="center"
        >
            <s-grid-item gridColumn="span 1" border="base" borderStyle="dashed">
                <s-box padding="base" background="subdued" border="base" borderRadius="base">
                    <s-heading title="now You Are using Basic plan">Current Plan  <s-icon type="home" /></s-heading>

                </s-box>
            </s-grid-item>
            <s-grid-item gridColumn="span 1" border="base" borderStyle="dashed">
                Total Orders
            </s-grid-item>
            <s-grid-item gridColumn="span 1" border="base" borderStyle="dashed">
                <s-heading>Your shop</s-heading>
                {{shop}}
            </s-grid-item>
        </s-grid>
    </s-page>
</template>

<script setup>
import ProductPicker from '../Components/ProductPicker.vue'
import { ResourcePicker } from '@shopify/app-bridge/actions'
import { defineProps } from 'vue'

const props = defineProps({
    message: String,
    shop: String,
})

</script>
