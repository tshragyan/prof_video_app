<template>
    <s-modal id="tik-tok-modal" heading="Details">
        <s-drop-zone
            label="Upload"
            accessibilityLabel="Upload Video"
            accept="video/*"
            multiple
            @input="uploadFile"
            @change="uploadFile"
            border="none"
        />

        <s-button slot="secondary-actions" commandFor="video-uploader-modal" command="--hide">
            Close
        </s-button>
        <s-button
            slot="primary-action"
            variant="primary"
            commandFor="video-uploader-modal"
            command="--hide"
        >
            Save
        </s-button>
    </s-modal>
</template>

<script setup>
import axios from "axios";

async function uploadFile(e) {
    const video = e.target.files[0]
    const form = new FormData();
    form.append("video", video);
    console.log("sending from tiktok")
    const response = await axios.post("https://videocrat.com/api/video/upload", form, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
        onUploadProgress: (progressEvent) => {
        },
    });
    console.log('uploaded', response)

}
</script>
