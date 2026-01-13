<template>
    <s-modal id="video-uploader-modal" heading="Upload Video">
        <s-drop-zone
            label="Upload"
            accessibilityLabel="Upload Video"
            accept="video/*"
            multiple
            @input="uploadFile"
            border="none"
            v-show="!loading"
        />
        <s-divider/>

        <video
            src=""
            v-show="!loading"
        ></video>

        <s-stack
            direction="inline"
            gap="base"
            v-show="!loading"
        >
            <s-box
                v-for="(video, index) in videos"
                :key="index"
                v-show="!loading"
            >
                <video :src="video.src" :data-id="video.id" controls width="200"></video>
            </s-box>
        </s-stack>

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
            @click="saveVideos"
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

let videos = ref([])
const loading = ref(false)
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
}


async function saveVideos() {
    const form = new FormData();
    const data = []

    videos.value.forEach((video, i) => {
        form.append('videos[]', video.file)
    });

    let app = initShopifyAppBridge();
    let token = await getSessionToken(app);
    let response

    try {
        response = await axios.post(`${import.meta.env.VITE_APP_URL}/api/vid4eo/upload`, form, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${token}`,
            },
            onUploadProgress: (progressEvent) => {
            },
        });
        loading.value = false
    } catch (e){
        loading.value = false
        response = {
            'id': 6,
            'size': 5,
            'src': 'https://videocrat.com/storage/instagram/reels/3782546227448034289.mp4',
            'title': 'video_6'
        }
    }
}

</script>
