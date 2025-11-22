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
        <Nav title="Exclusive" />

        <main class="w-full bg-white text-black">
            <v-container>
                <section class="flex">
                    <div class="border border-[#19140035] pr-4">
                        <p class="p-4 text-sm">Woman's Fashion</p>
                        <p class="p-4 text-sm">Men's Fashion</p>
                        <p class="p-4 text-sm">Electronics</p>
                        <p class="p-4 text-sm">Home & Lifestyle</p>
                        <p class="p-4 text-sm">Medicine</p>
                        <p class="p-4 text-sm">Sports & Outdoor</p>
                        <p class="p-4 text-sm">Baby's & Toys</p>
                        <p class="p-4 text-sm">Gcroceris & Pets</p>
                        <p class="p-4 text-sm">Health & Beauty</p>
                    </div>
                    <div class="w-2/2 p-4">
                        <v-img cover :src="bannerUrl" class="object-cover" />
                    </div>
                </section>
            </v-container>
        </main>
    </section>
</template>
