<script setup lang="ts">
import Welcome from '@/pages/Welcome.vue';

</script>

<template>
    <Welcome>
        <section class="w-full h-[300px] bg-cover bg-center flex items-center justify-center" :style="`background-image: url(${bannerUrl})`">
        <h1 class="text-4xl font-bold text-white">Get in Touch</h1>
    </section>
        <section class="p-8">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl font-semibold mb-4">Contact Information</h2>
                <p class="mb-2">Email: info@example.com</p>
            <p class="mb-2">Phone: +1 (555) 123-4567</p>
            <p class="mb-2">Address: 123 Main Street, City, Country</p>
        </div>
    </section>
    </Welcome>
</template>