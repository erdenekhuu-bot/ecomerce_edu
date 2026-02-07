<script setup lang="ts">
import { Category, Service } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Welcome from '../Welcome.vue';

const props = defineProps<{
    bannerUrl: string;
    category: Category;
    service: Service;
    musicbanner: string;
    products: Array<{
        id: number;
        name: string;
        slug: string;
        description: string;
        price: string;
        image: string;
    }>;
}>();

const selectedImage = ref<string | null>(null);
const getImage = (imageUrl: string) => {
    selectedImage.value = imageUrl;
};
</script>
<template>
    <Welcome>
        <main class="w-full bg-white text-black">
            <v-container>
                <section v-if="service.length > 0">
                    <div class="flex">
                        <div class="w-1/4 border border-[#19140035]">
                            <p
                                v-for="value in service"
                                :key="value.id"
                                class="p-4 text-sm hover:cursor-pointer active:font-bold"
                                @click="getImage(value.image)"
                            >
                                {{ value.name }}
                            </p>
                        </div>
                        <div class="w-3/4 p-4" v-if="selectedImage || service[0].image">
                            <v-img cover :src="selectedImage || service[0].image" class="object-cover" />
                        </div>
                    </div>
                </section>
                <section v-else>
                    <template>Nothing to show service</template>
                </section>
            </v-container>

            <v-container>
                <section v-if="props.category.length > 0" class="">
                    <p class="text-2xl font-bold">Browse By Category</p>
                    <div class="flex flex-wrap justify-between">
                        <div v-for="item in props.category" :key="item.id" class="m-4 h-[145px] w-[170px] rounded-lg border p-4">
                            <div class="text-center">
                                <Link :href="`/category/list/${item.id}`">
                                    <span class="font-bold">{{ item.name }}</span>
                                    <div class="flex justify-center">
                                        <img v-if="item.image" :src="'/' + item.image" alt="" class="" />
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
                <section v-else>
                    <template>Nothing to show service</template>
                </section>
            </v-container>
            <v-container>
                <section v-if="props.products.length > 0">
                    <div class="flex justify-between">
                        <div v-for="item in props.products" :key="item.id">
                            <Link :href="`/detail/${item.id}`">
                                <div class="m-4 w-[230px] rounded-xl">
                                    <div class="flex justify-center">
                                        <img v-if="item.image" :src="'/' + item.image" alt="" class="" />
                                    </div>
                                    <p class="font-bold">{{ item.name }}</p>
                                    <p class="font-bold text-red-500">${{ Number(item.price) }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </section>
                <section v-else>
                    <template>Nothing to show service</template>
                </section>
            </v-container>
            <v-container>
                <v-img cover :src="musicbanner" class="object-cover" />
            </v-container>
            <v-container>
                <section>Nothing to show product</section>
            </v-container>
        </main>
    </Welcome>
</template>
