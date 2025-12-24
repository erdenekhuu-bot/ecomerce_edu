<script setup lang="ts">
import Nav from '@/components/navigation/Nav.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    bannerUrl: string;
    category: Array<{
        id: number;
        image: string;
        name: string;
        description: string;
    }>;
    service: Array<{
        id: number;
        image: string;
        name: string;
        description: string;
    }>;
}>();

const selectedImage = ref<string | null>(null);
const getImage = (imageUrl: string) => {
    selectedImage.value = imageUrl;
};
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
                <section v-if="service.length > 0">
                    <div class="flex">
                        <div class="w-1/4 border border-[#19140035]">
                            <p v-for="value in service" :key="value.id" class="p-4 text-sm" @click="getImage(value.image)">
                                {{ value.name }}
                            </p>
                        </div>
                        <div class="w-3/4 p-4" v-if="selectedImage || service[0].image">
                            <v-img cover :src="selectedImage || service[0].image" class="object-cover" />
                        </div>
                    </div>
                </section>
                <section v-else>
                    <div class="flex">
                        <div class="w-1/4 border border-[#19140035]">
                            <p class="p-4 text-sm">Woman's Fashion</p>
                            <p class="p-4 text-sm">Men's Fashion</p>
                            <p class="p-4 text-sm">Electronics</p>
                            <p class="p-4 text-sm">Home & Lifestyle</p>
                            <p class="p-4 text-sm">Medicine</p>
                            <p class="p-4 text-sm">Sports & Outdoor</p>
                            <p class="p-4 text-sm">Baby's & Toys</p>
                            <p class="p-4 text-sm">Groceries & Pets</p>
                            <p class="p-4 text-sm">Health & Beauty</p>
                        </div>
                        <div class="w-3/4 p-4">
                            <v-img cover :src="props.bannerUrl" class="object-cover" />
                        </div>
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
