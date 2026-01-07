<template>
    <s-modal id="instagram-modal" heading="Details">
        <s-text-field
            label="Reel's Url"
            value="Jaded Pixel"
            placeholder="Copy And Past Instagram Reel's Url here"
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

async function uploadFile(e)
{
    const video = e.target.files[0]
    const form = new FormData();

    console.log(import.meta.env.VITE_APP_URL)
    try {
        const response = await axios.post(import.meta.env.VITE_APP_URL +  "/api/video/upload", form, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            onUploadProgress: (progressEvent) => {
            },
        });
        console.log('uploaded', response)
    } catch (error) {
        console.log('upload error', error)
    }

}
</script>
