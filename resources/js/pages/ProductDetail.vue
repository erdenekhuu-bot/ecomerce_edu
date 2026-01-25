<script setup lang="ts">
import Welcome from '@/pages/Welcome.vue';

</script>
<template>
    <Welcome>
        <section class="w-full h-[300px] bg-cover bg-center flex items-center justify-center" :style="`background-image: url(${bannerUrl})`">
        <h1 class="text-4xl font-bold text-white">Product Details</h1>
    </section>
    </Welcome>
</template>