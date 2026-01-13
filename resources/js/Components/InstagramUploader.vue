<template>
    <s-modal id="instagram-modal" heading="Import from Instagram">
        <s-text-field
            label="Pleas put reel url here (example: https://www.instagram.com/reel)"
            placeholder="Copy And Past Instagram Reel Url here"
            :value="reelUrl.value"
            @input="changeInput"
            v-show="!loading"
        />
        <s-stack
            alignItems="center"
            gap="base"
            padding="large"
            v-show="loading"
        >
            <s-spinner accessibilityLabel="Loading products" size="large" />
            <s-text>Importing video...</s-text>
        </s-stack>
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
    await new Promise((resolve => {
        setTimeout(function() {
         resolve()
        }, 3000)
    }))
    try {
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
    } catch (e){
        loading.value = false
        const response = {
            'id': 6,
            'size': 5,
            'src': 'https://videocrat.com/storage/instagram/reels/3782546227448034289.mp4',
            'title': 'video_6'
        }
    }
    console.log('uploaded', response)

}
</script>
