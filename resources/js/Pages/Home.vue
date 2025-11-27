<template>

    <div id="layout" style="background-color:#ebebeb">
        <button @click="openPicker" class="px-4 py-2 bg-black text-white rounded">
            Select Products
        </button>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
            </div>
        </div>
    </div>
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
