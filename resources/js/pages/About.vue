<script setup lang="ts">
import Nav from '@/components/navigation/Nav.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps<{
    bannerUrl: string;
}>();
</script>

<template>
    <Head title="About Us">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <Nav title="About Us" />
    <section class="w-full h-[300px] bg-cover bg-center flex items-center justify-center" :style="`background-image: url(${bannerUrl})`">
        <h1 class="text-4xl font-bold text-white">About Our Company</h1>
    </section>
    <section class="p-8">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-semibold mb-4">Our Story</h2>
            <p class="mb-2">
                We are committed to providing the best services to our customers. Our journey started in 2010 with a vision to revolutionize the industry.
            </p>
            <p class="mb-2">
                Over the years, we have expanded our offerings and built a strong team dedicated to excellence and innovation.
            </p>
        </div>
    </section>
</template>