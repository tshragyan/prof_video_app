<template>
    <AppLayout>
        <div>
            <p>{{ shop }}</p>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({
    message: String,
    shop: String,
})
</script>
