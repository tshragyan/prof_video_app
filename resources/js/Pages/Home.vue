<template>
    <AppLayout>
        <div>
            <h1>Hello from Vue + Inertia!</h1>
            <p>{{ message }}</p>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({
    message: String
})
</script>
