<script setup lang="ts">
import Nav from '@/components/navigation/Nav.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    bannerUrl: string;
    category: Array<{
        id: number;
        image: string;
        name: string;
        description: string;
    }>;
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
                        <v-img cover :src="props.bannerUrl" class="object-cover" />
                    </div>
                </section>
            </v-container>
            <v-container>
                <section v-if="props.category" class="">
                    <p class="text-2xl font-bold">Browse By Category</p>
                    <div class="flex">
                        <div v-for="item in props.category" :key="item.id" class="m-4 h-[145px] w-[170px] rounded-lg border p-4">
                            <div class="text-center">
                                <span class="font-bold">{{ item.name }}</span>
                                <div class="flex justify-center">
                                    <img v-if="item.image" :src="'/' + item.image" alt="" class="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </v-container>
        </main>
    </section>
</template>
