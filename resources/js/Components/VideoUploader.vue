<template>
    <s-modal id="video-uploader-modal" heading="Upload Video">
        <s-drop-zone
            label="Upload"
            accessibilityLabel="Upload Video"
            accept="video/*"
            multiple
            @input="uploadFile"
            border="none"
        />
        <s-divider />

        <video src=""></video>

        <s-stack direction="inline" gap="base">
            <s-box v-for="(video, index) in videos" :key="index">
                <video :src="video.src" :data-id="video.id" controls width="200"></video>
            </s-box>
        </s-stack>

        <s-button slot="secondary-actions" commandFor="video-uploader-modal" command="--hide">
            Close
        </s-button>
        <s-button
            slot="primary-action"
            variant="primary"
            commandFor="video-uploader-modal"
            command="--hide"
            @click="saveVideos"
        >
            Save
        </s-button>
    </s-modal>
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";
import {initShopifyAppBridge} from "../shopify";
import { getSessionToken } from '@shopify/app-bridge-utils';

let videos = ref([])

async function uploadFile(e) {
    const video = e.target.files[0]
    const form = new FormData();
    form.append("video", video);
    videos.value.push(
        {
            id: videos.value.length + 1,
            file: video,
            src: URL.createObjectURL(video)
        }
    )
    console.log(videos)
}


async function saveVideos() {
    console.log(import.meta.env.VITE_APP_URL)

    const form = new FormData();
    const data = []
    videos.value.forEach((video, i) => {
        form.append('videos[]', video.file)
    });
    let app = initShopifyAppBridge();
    let token = getSessionToken(app);

    console.log(form)
    const response = await axios.post("api/video/upload", form, {
        headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
        },
        onUploadProgress: (progressEvent) => {
        },
    });
    console.log('uploaded', response)
}

</script>
