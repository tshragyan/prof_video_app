<template>
    <s-modal id="instagram-modal" heading="Import from Instagram">
        <s-text-field
            label="Pleas put reel url here"
            placeholder="Copy And Past Instagram Reel Url here"
            v-model="reelUrl.value"
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
            Save
        </s-button>
    </s-modal>
</template>

<script setup>
import axios from "axios";
import {ref} from "vue";
import {initShopifyAppBridge} from "../shopify";
import {getSessionToken} from '@shopify/app-bridge-utils';

let reelUrl =ref('')

async function importVideo(e) {
    const form = new FormData();
    let app = initShopifyAppBridge();
    let token = await getSessionToken(app);
    form.append('url', reelUrl.value)

    console.log("sending from instagram")
    console.log(import.meta.env.VITE_APP_URL)

    const response = await axios.post("https://videocrat.com/api/video/import-from-instagram", form, {
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${token}`,
        },
        onUploadProgress: (progressEvent) => {
        },
    });
    console.log('uploaded', response)

}
</script>
