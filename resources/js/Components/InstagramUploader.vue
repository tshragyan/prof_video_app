<template>
    <s-modal id="instagram-modal" heading="Import from Instagram">
        <s-text-field
            label="Pleas put reel url here (example: https://www.instagram.com/reel)"
            placeholder="Copy And Past Instagram Reel Url here"
            :value="reelUrl.value"
            @input="changeInput"
        />
        <s-spinner
            accessibilityLabel="Loading" size="large-100"
            v-show="loading"
        />
        <s-button slot="secondary-actions" commandFor="video-uploader-modal" command="--hide">
            Close
        </s-button>
        <s-button
            slot="primary-action"
            variant="primary"
            commandFor="video-uploader-modal"
            command="--hide"
            @click="importVideo"
        >
            Import
        </s-button>
    </s-modal>
</template>

<script setup>
import axios from "axios";
import {ref} from "vue";
import {initShopifyAppBridge} from "../shopify";
import {getSessionToken} from '@shopify/app-bridge-utils';

let reelUrl = ref('')
let loading = ref(false)
function changeInput(e) {
    reelUrl.value = e.target.value
}

async function importVideo(e) {
    let app = initShopifyAppBridge();
    let token = await getSessionToken(app);

    console.log("sending from instagram")
    console.log(import.meta.env.VITE_APP_URL)
    loading.value = true
    const response = await axios.post(`${import.meta.env.VITE_APP_URL}/api/vieo/import-from-instagram`, {
        "url" : reelUrl.value
    }, {
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${token}`,
        },
        onUploadProgress: (progressEvent) => {
        },
    });
    loading.value = false
    console.log('uploaded', response)

}
</script>
