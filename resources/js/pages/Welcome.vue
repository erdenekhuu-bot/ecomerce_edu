<script setup lang="ts">
import Nav from '@/components/navigation/Nav.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    bannerUrl: string;
}>();
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <section class="">
        <Nav title="My Shop" />

        <v-container>
            <main class="w-full bg-white text-black">
                <section class="flex">
                    <div class="border border-[#19140035] pr-4">
                        <p class="p-4">Woman's Fashion</p>
                        <p class="p-4">Men's Fashion</p>
                        <p class="p-4">Electronics</p>
                        <p class="p-4">Home & Lifestyle</p>
                        <p class="p-4">Medicine</p>
                        <p class="p-4">Sports & Outdoor</p>
                        <p class="p-4">Baby's & Toys</p>
                        <p class="p-4">Gcroceris & Pets</p>
                        <p class="p-4">Health & Beauty</p>
                    </div>
                    <div class="w-2/2 p-4">
                        <v-img cover :src="bannerUrl" class="object-cover" />
                    </div>
                </section>
            </main>
        </v-container>
    </section>
</template>
