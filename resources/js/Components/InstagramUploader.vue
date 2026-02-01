<template>
    <s-modal
        id="instagram-modal"
        heading="Import from Instagram"
        accessibilityLabel="Import from Instagram"
        style="overflow:unset"
    >
        <s-stack
            v-show="!loading"
        >
            <s-checkbox
                label="Import Reels From User Page"
                :checked="allPageVideosImport"
                @change="changeAllPageVideosImport"
            />
            <s-checkbox
                label="Import Single Reel"
                :checked="singleVideoImport"
                @change="changeSingleVideoImport"
            />
            <s-grid
                gridTemplateColumns="repeat(6, 1fr)"
                gap="large"
                justifyContent="center space-between"
            >
                <s-grid-item gridColumn="span 5" border="none" align-items="center">

                    <s-text-field
                        :placeholder="singleVideoImport ? reelInputLabel : userPageAllReelsInputLabel"
                        v-model="reelUrl"
                        @input="changeInput"
                        v-show="!loading"
                        :error="errors.input?.length ? errors.input : ''"
                    />
                </s-grid-item>
                <s-grid-item gridColumn="span " border="none">
                    <s-button
                        variant="primary"
                        @click="importVideo"
                    >
                        Import
                    </s-button>
                </s-grid-item>

            </s-grid>
            <s-stack
                direction="inline"
                gap="base"
                v-show="!loading && videos.length"
                padding="large none"
            >

                <s-checkbox
                    label="Import all videos"
                    :checked="importAllVideos"
                    @change="changeImportAllVideos"
                    v-show="!loading && videos.length"
                />

                <s-grid
                    gridTemplateColumns="repeat(3, 1fr)"
                    gap="large"
                    justifyContent="center space-between"
                    v-show="!loading && videos.length"
                    alignContent="center"
                >
                    <s-grid-item gridColumn="span 1" border="none" borderStyle="dashed"
                                 v-for="(video, index) in videos"
                                 :key="index"
                                 v-show="!loading">
                        <s-stack
                            direction="block"
                            gap="base"
                            alignItems="center"
                            justifyContent="center"
                        >
                            <video :src="video.url" controls="true" muted loop :data-id="video.id" autoplay
                                   width="90%"></video>
                            <s-tooltip
                                :id="video.id"
                            >
                                {{ video.title }}
                            </s-tooltip>
                            <s-text
                                :interestFor="video.id"
                            >
                                {{ video.title.length ? video.title.slice(0, 20) + '...' : video.title }}
                            </s-text>
                            <s-checkbox
                                label="Import this video"
                                :checked="video.is_selected"
                                @change="changeVideoSelected"
                                :data_id="video.id"
                            />
                            <s-divider color="string" v-show="index % 3 == 6"/>
                        </s-stack>
                    </s-grid-item>

                </s-grid>
            </s-stack>
            <s-clickable
                border="base"
                padding="base"
                background="strong"
                borderRadius="base"
                v-show="!smallLoading && hasMoreVideos"
                @click="loadMoreVideos"
            >
                Load More Videos From This Customer
            </s-clickable>

            <s-stack
                v-show="smallLoading && hasMoreVideos"
                direction="block"
                gap="base"
                alignItems="center"
                justifyContent="center">
                <s-spinner
                    accessibilityLabel="Loading products"
                    size="large"
                />
            </s-stack>
        </s-stack>

        <s-stack
            alignItems="center"
            gap="base"
            padding="large"
            v-show="loading"
        >
            <s-spinner accessibilityLabel="Loading products" size="large"/>
            <s-text>Importing video...</s-text>
        </s-stack>
        <s-button
            slot="secondary-actions"
            commandFor="video-uploader-modal"
            command="--hide">
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

let reelUrl = ref('https://www.instagram.com/zarina_fashion/reels/')
let loading = ref(false)
let singleVideoImport = ref(false)
let allPageVideosImport = ref(true)
let permissionGot = ref(false)
let importAllVideos = ref(false)
let maxId = ref('QVFBSmJJeEU0VFFEdWgzb3BOcXRscUNRYkF0ampIS2E3aklFUEZBejZCanhVM2xjbzItTm91ZThFM3N4RWlEQXhFbll0WXVNYi16eFF3OThEMzhTTXhIZg==')
let hasMoreVideos = ref(false)
let smallLoading = ref(false)
let videos = ref([])
let selectedVideosCount = ref(videos.value.length)

let reelInputLabel = "https://www.instagram.com/reel/reelcode"
let userPageAllReelsInputLabel = "https://www.instagram.com/nickname/ or nickname"

let errors = ref({})

function changeInput(e) {
    reelUrl.value = e.target.value
}

async function saveVideos() {
    // let app = initShopifyAppBridge();
    // let token = await getSessionToken(app);
    let token = 'token'
    loading.value = true
    let data = videos.value.filter(i => {
        return i.is_selected
    })

    console.log(data, 'data')
    let response = await axios.post(`${import.meta.env.VITE_APP_URL}/api/video/save-imported-videos`, {
        videos: data
    }, {
        headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
        },
        onUploadProgress: (progressEvent) => {
        },
    });
    loading.value = false
}

async function loadMoreVideos() {
    // let app = initShopifyAppBridge();
    // let token = await getSessionToken(app);
    let token = 'token'

    console.log("loading more videos")
    smallLoading.value = true
    let response = []

    // try {
        response = await axios.post(`${import.meta.env.VITE_APP_URL}/api/video/import-from-instagram-user-page`, {
            url: reelUrl.value,
            maxid: maxId.value
        }, {
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`,
            },
            onUploadProgress: (progressEvent) => {
            },
        });
        console.log(response, 'response');
        videos.value = [
            ...videos.value,
            ...response.data.data
        ]

        maxId.value = response.data.next_page_id || ''
        hasMoreVideos.value = response.data.available_more || false
    console.log(maxId.value, 'maxId.value', hasMoreVideos.value, 'hasMoreVideos.value')
        smallLoading.value = false

    // } catch (e) {
    // }


}

async function importVideo(e) {
    // let app = initShopifyAppBridge();
    // let token = await getSessionToken(app);

    if (!reelUrl.value.length) {
        errors.value = {
            ...errors.value,
            'input': 'Please fill this field'
        }
        return
    } else {
        errors.value = {
            ...errors.value,
            'input': ''
        }
    }

    let token = 'aaaass';

    console.log("sending from instagram")
    loading.value = true
    await new Promise((resolve => {
        setTimeout(function () {
            resolve()
        }, 1000)
    }))
    let response
    if (singleVideoImport.value) {
        try {
            response = await axios.post(`${import.meta.env.VITE_APP_URL}/api/vieo/import-from-instagram`, {
                "url": reelUrl.value
            }, {
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`,
                },
                onUploadProgress: (progressEvent) => {
                },
            });
            videos.value = [
                ...videos.value,
                ...response.data.data,
            ]
            maxId.value = response.data.next_page_id || ''
            hasMoreVideos.value = response.data.available_more || false
            loading.value = false

        } catch (e) {
        }
    } else {
        try {
            response = await axios.post(`${import.meta.env.VITE_APP_URL}/api/video/import-from-instagram-user-page`, {
                url: reelUrl.value
            }, {
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`,
                },
                onUploadProgress: (progressEvent) => {
                },
            });

            loading.value = false
            selectedVideosCount.value += response.data.data.length
            maxId.value = response.data.next_page_id || ''
            hasMoreVideos.value = response.data.available_more || false
            videos.value = [
                ...videos.value,
                ...response.data.data
            ]
            console.log(videos.value)
        } catch (e) {
            loading.value = false
            response = [
                {
                    "id": "3807780796938181622_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQO6WhFtdAM9histt4jw4STYtR_7ize-Vi1YRTTcR0BfLXgfhlY9lZmBXUn0i6V6RXfzeYOedDsp4pTgOLsAoBUYV0qRVMiAU-4YoAc.mp4?_nc_cat=105&_nc_oc=Adlz4JI2woHKGVzuFyCs2zM9X_p5zit1uR4qLX5ioLCo1eIrzGNneUrPkeuOFvNIBhc&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=orV6_T0pLyYQ7kNvwFy6r5g&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTEzODQzMzAwNDg1NTY4NywiYXNzZXRfYWdlX2RheXMiOjMsInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjo3LCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=77115b99a634945d&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC8yMDQ3QUJBMDJGNEYyRDZDQjVEQzI4QTNERUQ4NEQ4NF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HT3NCZlNTUlVkbUZDeW9IQUpqel9kWGF4MUFRYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmjobX7cLZhQQVAigCQzMsF0Af3S8an753GBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfqXk9DWtT8All5-vs9pwU05utl5TlK_k1rVcAtqPqQfsA&oe=696AAAFA",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/613540478_18549653107008048_2341663970608031141_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=105&ig_cache_key=MzgwNzc4MDc5NjkzODE4MTYyMjE4NTQ5NjUzMTAxMDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=V5TMei3U_toQ7kNvwHpLzbp&_nc_oc=AdlxP2qWzsF-GnuX9PGE9j5ajWlNCp-xmA3p4JGBGUn0jqWgFeI81uZ7UYigoJBADks&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfohshnOsh5lKcMWpp5sD4jZtivvOL5f5uaYYbUccAm9dQ&oe=696EB8F2"
                },
                {
                    "id": "3805411193838716789_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQOpmbs8ujrKpxyAIdJ_RM-YVuSUhdJJnDacJdPYhis-PwA2zOCMQutfQkxygxqsLAx9w5pvv6H_w2HP7gLxaL1DV-wEnM9JjurXnX0.mp4?_nc_cat=108&_nc_oc=AdmoWKuYjb4Y36LOtMTopcLLcvO09yQEFt1Jk3IiQCqF_4B-4Cf5-ryyuwq9wIEe6nc&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=uWxpRllh6IIQ7kNvwEeScXh&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTk2NzE3ODkzNzE2NDI3NywiYXNzZXRfYWdlX2RheXMiOjcsInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjoxNCwidXJsZ2VuX3NvdXJjZSI6Ind3dyJ9&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=a0cbf54c4cf27cc3&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC81NjQzQ0RCNUEzRTU4RDBBODgxODEyQkVGNzlBNEJCQl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HQnlWZXlROG9mYnlPeFFGQU1vRzAxUWhUX1l3YnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm6oeTnPHI_gYVAigCQzMsF0AsVP3ztkWiGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfpB9AIBoLxFjYXTp_d_zeKPJxUp9azjvqCYtdWUwTzLLQ&oe=696ABD15",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/611358433_18549092350008048_7137109146036117037_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=107&ig_cache_key=MzgwNTQxMTE5MzgzODcxNjc4OTE4NTQ5MDkyMzQ3MDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=V-vrOvHIyhAQ7kNvwFMkJWe&_nc_oc=AdkZTOW5uT5ucCO86U4jnmwRK1mQJqrW6KqfgdAjljO_ChY33_E87B5I0wBCh7R2oAk&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfppCjU1grNSAjzkMaR3bU4TYr5Bc6lqL2UBe7vPEjfqAA&oe=696E8653"
                },
                {
                    "id": "3803998262181825586_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQOARUuvLqpoehClSyCV25A3IoQF2bc4oGVu0xK5I5SwFsIHwZBVZsJ1GzKb1DRxkuxwYj1iAaUJ5uqqqAmXm93fLI84nj__nMLT1JA.mp4?_nc_cat=109&_nc_oc=AdkzN6qsOoNtV95tXcyRfuYFYEaDcpbzDk2hw6-XxRFFgsZ9bbG-LZRyp8Mz_1Mm8uI&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=0JohQ_otrloQ7kNvwE1PQ-b&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjM5MTIyMzUzNDY2MTQzMywiYXNzZXRfYWdlX2RheXMiOjksInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjo3MiwidXJsZ2VuX3NvdXJjZSI6Ind3dyJ9&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=f3e2cb58e579bf54&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9FNzQ4M0NCQjZBOEYzM0MyMTMwREREMjQzRTlEMTE4M192aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTDFNYUNSN0dSazdxQWNEQVBSSk5vZ0ZzSGNPYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm8rzl1cOzvwgVAigCQzMsF0BSOFHrhR64GBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfqCAHb_nx2V-zB8BoPKBPZhiOYAhcNOQWDSTUnQBMecrg&oe=696AAB66",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/612026121_18548755660008048_7287485144307342848_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=101&ig_cache_key=MzgwMzk5ODI2MjE4MTgyNTU4NjE4NTQ4NzU1NjU0MDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=l5ve_UUJAhAQ7kNvwFr3SsW&_nc_oc=AdmR0tfBmf-6mpREwF-foOMMFGNkqC7T-b_GySZgFUAqdOGpS8Uy928s4FASulzT02s&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfocGWv5uihGdoumVORyl8sejlbVcsHkClqAbA_W56ZTSg&oe=696E88F8"
                },
                {
                    "id": "3803955395983904355_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQOmllL94M-XibQdmmxgTI7PeVzwPOXNYOpZUgo-plF-JsqEEx8ch1dlYBNmHXlzpeSIGuGhnP6HmATWy7mmKk6ShP0dl_97x0CWsjo.mp4?_nc_cat=106&_nc_oc=Adl4kFOCT3DHooXiVNA7HMQKbVVxgeAmmjlbRoq5XDLXXvAyoRre0_oL8-3g_q8DVjE&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=vrHfhtqcJFsQ7kNvwFn-G1X&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTQxNzYzOTE0NzA2NTU4MiwiYXNzZXRfYWdlX2RheXMiOjksInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjo2NSwidXJsZ2VuX3NvdXJjZSI6Ind3dyJ9&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=27923503657daaa1&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC81NzQ5MTVEQjgwMzQxNTY4RThFOEFFQkFEM0Y3QTVCNV92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTmYzWnlTdjBEbXFsRnNFQUJacnFJVVJWZXA2YnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm3MO-3LrVhAUVAigCQzMsF0BQUzMzMzMzGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfpXdN8VMU-Au3tlqF_3k29Kh7Jju6H-SR7YujBsyymjxQ&oe=696AC417",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.71878-15/609894003_1422257152618648_1990884541343447670_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=104&ig_cache_key=MzgwMzk1NTM5NTk4MzkwNDM1NQ%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjY0MHgxMTM2LnNkci5DMyJ9&_nc_ohc=FmVIe5PC36UQ7kNvwE6dHiF&_nc_oc=AdlXlQTU3bt3QgTIrqMUXaOD4uuDTacqtnpaMMH4pMzCghYaZQ0hY72c-xi2Cay0RLw&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_Afpn2lZbRgc9j03ddIYaPFmv61vxPsydeGwo8W5BvlVrOw&oe=696EA04D"
                },
                {
                    "id": "3802067662973767435_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQOHAkWQ1bZ9c6uux__LPq3IH67OviWTFjjk-1epeKZ-CMqPA5UyQW8dSoZ1JGnxxLqM9FZkU_E_VFw_tQ6kJyD8xL_PvEgavFuKEJ4.mp4?_nc_cat=108&_nc_oc=AdnAznqrcH0ugy2YwgeTG_fmJ8Qj2qHOIsr9eh1iI7tjxtbWTtvtz_ECq73udK1CthU&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=t_xcC9OR408Q7kNvwHgav3i&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTUyNjQyNTQyNTI4NTg2NiwiYXNzZXRfYWdlX2RheXMiOjExLCJ2aV91c2VjYXNlX2lkIjoxMDA5OSwiZHVyYXRpb25fcyI6MTgsInVybGdlbl9zb3VyY2UiOiJ3d3cifQ%3D%3D&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=3841374db2a0ad57&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9ENzRGNjgzMjU4RUY3OUY2NEZFODk2QTBFQzMwNDU4MF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTTZkWlNRVHM1VGttQW9GQUJmY2UzRE05dE5SYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm1MuA1NORtgUVAigCQzMsF0Ay5mZmZmZmGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_Afpcz5KZIwuMDq3G1jjx0pYnfPijZCV2N0YTw0Ug3ppt7A&oe=696A9E11",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/610657847_18548291911008048_7790835484704217890_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=108&ig_cache_key=MzgwMjA2NzY2Mjk3Mzc2NzQzNTE4NTQ4MjkxOTA1MDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjIxNjB4Mzg0MC5zZHIuQzMifQ%3D%3D&_nc_ohc=apST0_vy22QQ7kNvwEEhh49&_nc_oc=Adma06qhVybopvH-6qTSX2-LN50DR_X9pkeoc0c-MxvntF1RlRcZTwZL_GrA_7kC-Q8&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfrKvogbWASjgec_IhGsyTFfxGrH60IdesL-wzR9hk7VhA&oe=696E8F31"
                },
                {
                    "id": "3801338874367378652_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQPa6HHb6nH5vA5Yif52ewRzZju3jn8jjxxKswAsgX1ch_tmXZjeoejOhhj2VDdNIqEuYJUx09wFksLdJDjRuvCupMt6vSkUpfaVL_M.mp4?_nc_cat=108&_nc_oc=Adm8n9fZo1mOB2c7zvLDYbcay2fBFH0ugp2BhgFkIEQD1ExjVbT0oStFZxrMZVG0bys&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=8qZhyXbq2JwQ7kNvwGSrJNW&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjU4NzkxMjE5ODIzMzc5MCwiYXNzZXRfYWdlX2RheXMiOjEyLCJ2aV91c2VjYXNlX2lkIjoxMDA5OSwiZHVyYXRpb25fcyI6NTEsInVybGdlbl9zb3VyY2UiOiJ3d3cifQ%3D%3D&ccb=17-1&vs=6cdf9f837acf86fc&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC8wNzQ2Q0FCNTQ3MTYwNDFGQTU0REY0RUJENTM0ODBCQ192aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HUDcxVGlRY2lqY09oOU1FQUM2T2FDLVpORmxEYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm_PrItKjsmAkVAigCQzMsF0BJx64UeuFIGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&oh=00_AfrX8vXQT79culpt3__h3XTNx7OfCae0a6k_xdQJn8GsAw&oe=696A9379",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/610963998_18548113090008048_5876793454311591613_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=100&ig_cache_key=MzgwMTMzODg3NDM2NzM3ODY1MjE4NTQ4MTEzMDg3MDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=-LzXFEIcxEIQ7kNvwHzEynV&_nc_oc=Adm90YVTv7lIoePn3Iw8LKPf0od8xsEJXxgRaq7TVmAX21KKAND2a8jm4Sl0ATtGiRE&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_Afo-B0VTd0PahCpgY9_mAPvNAvnM_ah8RTjqtjrYawmTYA&oe=696E82DB"
                },
                {
                    "id": "3801111216295610040_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQNoAct7OC9OWiPuh8kYVvt5GDViQGRQnnK1ygkzSZ4jXtGn01SJ60H3LswxPHSR2p594jMlHH9aOFsw-de1yDZShBEqCdOzyGnvM5A.mp4?_nc_cat=101&_nc_oc=Adl6W5-gze4wiXtA37dnxzJ613UoW_l2d1_O19VPkcNVRXx8cjDCvELYCUWjXSBNapQ&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=96DOkbRznRsQ7kNvwGGs0jc&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTUwMjI1MTk3NDIxNTg2OCwiYXNzZXRfYWdlX2RheXMiOjEzLCJ2aV91c2VjYXNlX2lkIjoxMDA5OSwiZHVyYXRpb25fcyI6MTIsInVybGdlbl9zb3VyY2UiOiJ3d3cifQ%3D%3D&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=647705748aaa0432&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC8xQTQxQkY2QzUxREEyRjdERDYwQkEyNjg3RjUyREZBN192aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HSlRNVHlTeElHUXozSTBDQUhmWFVVaE9CV3dFYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm-KLKtMmSqwUVAigCQzMsF0AoMzMzMzMzGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_Afq8QNJmEvldmEyncmazbR0tNORU7n7yd46Xx5-Aw6MP6Q&oe=696A915A",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/610878059_18548045566008048_3840692969787517937_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=105&ig_cache_key=MzgwMTExMTIxNjI5NTYxMDA0MDE4NTQ4MDQ1NTYwMDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=S1AZFlbOlm4Q7kNvwFWn8o_&_nc_oc=AdndYEj1TG8LS7kQYDfO7W3d9Fg6CdqPJtgss5MBU8c3b5GIAytRO170gfIqds5x-o4&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfrsCcXPM1nXOiaHJpWDow_dVnbS556wmUigHQAu3Vy-rw&oe=696EB54D"
                },
                {
                    "id": "3799670676605730921_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQODuNdz8bcASzW1501Ki1_5LKvdjZd-_eYNz_oFXXNCvKRdUnNuRsLqE7ZTScyKWC23MuH4XMquoCgVzcxRaDtO6rhlaxJygRJc4KI.mp4?_nc_cat=107&_nc_oc=AdlppONDNvxohM1clSodZwL0KlPVDXpS-A-qQjTLIk6ip8dz1dkKA-dDYN8-FRP42Lc&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=C-QkuriHHMkQ7kNvwH2ZKoN&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTgxNDExOTY0OTI0Mzg0NCwiYXNzZXRfYWdlX2RheXMiOjE1LCJ2aV91c2VjYXNlX2lkIjoxMDA5OSwiZHVyYXRpb25fcyI6MjMsInVybGdlbl9zb3VyY2UiOiJ3d3cifQ%3D%3D&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=943540a0ae9bd2ad&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC83QjQ0MTY2OTE0ODNCMUYzQjA4QUExRjY1RjU2NkRCNl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HSnljRkNRMGJZSGo5eW9IQUtHOTVfX3ZQY2tzYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmiMuv1NT7uAYVAigCQzMsF0A37tkWhysCGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfoWui1TMwdAy3Rxzq9selONvaOfXEJxeAbbrt5D5FSLBg&oe=696AB510",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.71878-15/607356481_1396996498837082_7906879320091136389_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=111&ig_cache_key=Mzc5OTY3MDY3NjYwNTczMDkyMQ%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjY0MHgxMTM2LnNkci5DMyJ9&_nc_ohc=36zZsiQvaU4Q7kNvwHO1tKn&_nc_oc=Adn3l3cYMtgwzu1-6PQYNJ30-5m_YFBQpCIIJFAYNqlhN4layznUt98PAstm9xAWkkE&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfqVACiXdxlFu08NqIPVwJuWNdS2Fza-9RhHJ34_ph9UxQ&oe=696EA5CA"
                },
                {
                    "id": "3799151712989463730_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQN3eVxcgQlC0LM4qbvgeSnOvcavYdBgYCd2SHJ9xK8eobGL87Ppgs2Y4ztU9z1snY_CbrXeEAj1m5mXuFX5rOCUBrTJ1f9ilDHLgLE.mp4?_nc_cat=111&_nc_oc=Adk4UItVwkDfz6e0pnNnLvKeRw1cwuG03WIhHWbP0ZeDW8OIVOoOm9ekfuUxya85jPg&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=TrginVhndSMQ7kNvwEkxQjs&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6NzQyODk3NjU1MDMyMjM2LCJhc3NldF9hZ2VfZGF5cyI6MTUsInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjoyMywidXJsZ2VuX3NvdXJjZSI6Ind3dyJ9&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=c3145098e5627984&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC80OTQyNjg1QjZEMEZCQkVFMzNCM0NFNTQ4NDZGRTVCQV92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTHNtTmlSaVVkOWZaZ1VGQUVYOGM1VWJ5QjhaYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm2Kap7arq0QIVAigCQzMsF0A3ogxJul41GBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfoVRUX1v2e0tvTc2dICyk1aV-7Tki0R9lRK2kxLmI13pg&oe=696AB66C",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/609266011_18547446217008048_7084967358383195262_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=103&ig_cache_key=Mzc5OTE1MTcxMjk4OTQ2MzczMDE4NTQ3NDQ2MjE0MDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjExNzB4MjA4MC5zZHIuQzMifQ%3D%3D&_nc_ohc=zmua8f7iT64Q7kNvwHaCc-o&_nc_oc=AdnWBMezddIuREGAAXEBU__qB-sTO-_i6BXMeXV_F30y-3PjkjhgKS-VnLxIpyaxS3A&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfoDaiEWDku_HPicjG23IqKPmV5yensSg8T9qKp7vdltRA&oe=696EB5EC"
                },
                {
                    "id": "3796781125897943543_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQNGGTS2wWydqqC1nIhKMP7btILPh95djMto3gsx2hZgOPWn-Ljw-30d1kLrPQTytC0VRYjexGbxm1F7MU9zKb8Sfg6Xc837JDxl5iA.mp4?_nc_cat=105&_nc_oc=AdmX7b0Jnmk4GuyPSigp2js6zSPFDtxHc_E-8KAf5a5rrk-NU5SacuGru-_Q2VSAbuA&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=YPtFJ0_xLyIQ7kNvwFFeO4x&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6NjY0NzM1NDUwMDU2OTk0LCJhc3NldF9hZ2VfZGF5cyI6MTksInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjo3NCwidXJsZ2VuX3NvdXJjZSI6Ind3dyJ9&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=eba47dba368ce5f3&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC80RDQ5NkZBMEVDMUQzQkQxNEYwMDNDNzA4REFBQzk5OV92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HR0kxSmlRRmF2MVRXbDhEQUxkUUJ0cFFIVTRhYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmxKT2hdmkrgIVAigCQzMsF0BSgo9cKPXDGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_Afp920izaAJ1qlql25EhxieCP_LYb5jyjVytUqz577n0NA&oe=696A978E",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/606942165_18546861565008048_572990812681746723_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=102&ig_cache_key=Mzc5Njc4MTEyNTg5Nzk0MzU0MzE4NTQ2ODYxNTYyMDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=RrY5MNCg1A4Q7kNvwF1r2ks&_nc_oc=AdlnT-qZx46bhbEpgxFjIJfTGvvvjokrywFqEI-zBuYju6sx6XR6Bx8t904OfoMO93A&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfqiZ2u_HdPMwbcQRisF9lqnLKmDDfuCA25y3x6eNP9u_Q&oe=696E9A15"
                },
                {
                    "id": "3796083980630854265_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQMbX1qKDMABsV8Hj_pUX8PcWv-E-P0v2eL6bXtqWliUJMdVEK4R2B93f9XQ6lgeX4tKDzWzR2WrInl2t9UaEJOs8j6ZZ8NpuqYw8A0.mp4?_nc_cat=105&_nc_oc=AdlBsZozVLSEkjObYWbcQvvp0Iq8SwDpFmaZgozOfsG8yZwhv59bREFtZv8590TuBsU&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=i-ulmiadLPkQ7kNvwFzoCZ4&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuOTYwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6NjU3NDk5NDgwNjkyMTM0LCJhc3NldF9hZ2VfZGF5cyI6MTksInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjoxOSwidXJsZ2VuX3NvdXJjZSI6Ind3dyJ9&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=bf7a7f0b5935174b&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC8xODQ4RjkzOEMyMjNCNUM5MjBDMUY0OTgyRkRBQkQ4MV92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HQzZnSGlUdzRtSWFoMjBFQUVpZGtlYkF2ZDBfYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmzNbk78D_qgIVAigCQzMsF0Az90vGp--eGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfoNee5IsuocbhwctCNq6XmcEpF_Tc-oMypUeUefYFY2sg&oe=696AA02E",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/606002907_18546693136008048_4646486276212134194_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=104&ig_cache_key=Mzc5NjA4Mzk4MDYzMDg1NDI2NTE4NTQ2NjkzMTMwMDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=husfoBq0TdMQ7kNvwHnsTqq&_nc_oc=AdnXaiVdfGWAjIUOOQW9migoX7DP6Z-FLTMjfOe9-3DkstLEJW9A_BxohlW43kqF8GM&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfoCj9yvwn3vThaOcUepT0l6lRwBNIWWgNVYA60O_eQCQQ&oe=696EB125"
                },
                {
                    "id": "3791625006359616445_450440047",
                    "is_selected": true,
                    "url": "https://instagram.fevn1-1.fna.fbcdn.net/o1/v/t2/f2/m86/AQPVDa1FEGEAExbzbq-VRDdOVcgAsdk-RHZAwANVbDmIJHiCU5EwZTGAIkOsyXk9XZL34-sr-dOdoj8eFNxY-i9qxFJ68rKKrqQ4Zak.mp4?_nc_cat=109&_nc_oc=AdkGjURyP86dy6utsGNsX8MrCTX6nmLfBWbqostqsLMiCacRN8zCgLbsLfIXTTnQ5cY&_nc_sid=5e9851&_nc_ht=instagram.fevn1-1.fna.fbcdn.net&_nc_ohc=8dNYoxZCUZ4Q7kNvwGAIo60&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6NjQxNjkwNDU4OTY1MjA2LCJhc3NldF9hZ2VfZGF5cyI6MjYsInZpX3VzZWNhc2VfaWQiOjEwMDk5LCJkdXJhdGlvbl9zIjoxNiwidXJsZ2VuX3NvdXJjZSI6Ind3dyJ9&ccb=17-1&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&_nc_zt=28&vs=521d2e0f0a6d764d&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC8xRDQ2OEI0MUNGN0VDMTUwMDM1MTM0ODY1Q0U2MTg4RF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HQzBxX2lQbUxjd0ZrMjBGQUlaMEltRW51U3RuYnN0VEFRQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmrJPmvabnowIVAigCQzMsF0AwzMzMzMzNGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&oh=00_AfqMQjvtLjMlkfyirdiqOYOqDOuLxP42teip2RxY8yeq6A&oe=696A9902",
                    "thumbnail": "https://instagram.fevn1-1.fna.fbcdn.net/v/t51.82787-15/603905006_18545588476008048_8750124823014130607_n.jpg?stp=dst-jpg_e15_tt6&_nc_cat=104&ig_cache_key=Mzc5MTYyNTAwNjM1OTYxNjQ0NTE4NTQ1NTg4NDczMDA4MDQ4.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEwODB4MTkyMC5zZHIuQzMifQ%3D%3D&_nc_ohc=tzd4C_wRzIEQ7kNvwGEVcNz&_nc_oc=AdmQwzjAjgjHdaqeGkNI8rvLdv_hyje0ibBGKRlmYwxdp1dPwZotHk7Co9D5aZUdvWM&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fevn1-1.fna&_nc_gid=fOqTJDt0_fxVrHzyWXH-7g&oh=00_AfpYqhkWHRq0v15YoMBNkezaV5xXmayWofFXACCL0IBZmA&oe=696E8DBA"
                }
            ]
            selectedVideosCount.value += response.length
            videos.lavue = [
                ...videos.value,
                ...response
            ]
        }
    }
}

function changeVideoSelected(e) {
    let videoId = e.target.attributes.data_id.value
    let selected = true;

    videos.value.forEach(i => {
        if (i.id == videoId) {
            i.is_selected = !i.is_selected
            selected = i.is_selected
        }
    })

    if (selected) {
        selectedVideosCount.value = selectedVideosCount.value + 1
        if (selectedVideosCount.value == videos.value.length) {
            importAllVideos.value = true
        }
    } else {
        selectedVideosCount.value = selectedVideosCount.value - 1
        importAllVideos.value = false
    }
}

function changeSingleVideoImport(e) {
    singleVideoImport.value = e.target.value
    allPageVideosImport.value = !e.target.value
}

function changeAllPageVideosImport(e) {
    allPageVideosImport.value = e.target.value
    singleVideoImport.value = !e.target.value
}

function changeImportAllVideos(e) {
    importAllVideos.value = !importAllVideos.value
    videos.value.forEach(i => {
        i.is_selected = importAllVideos.value
    })
}
</script>
