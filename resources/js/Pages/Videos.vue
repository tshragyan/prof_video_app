<template>
    <s-page heading="Dashboard">
        <s-drop-zone
        label="Upload"
        accessibilityLabel="Upload Video"
        accept=".mp4"
        multiple
        @input="uploadFile"
        @change="uploadFile"
        />
    </s-page>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
    message: String,
    videos: Array,
})

function uploadFile()
{
    console.log('file upload')
}

</script>
