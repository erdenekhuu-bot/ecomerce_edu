<script setup lang="ts">
import { Category, Service } from '@/types';
import { Link } from '@inertiajs/vue3';
import 'swiper/css';
import { Swiper, SwiperSlide } from 'swiper/vue';
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
                    <div class="flex justify-center gap-4">
                        <div class="w-1/3 border border-[#19140035]">
                            <p
                                v-for="value in service"
                                :key="value.id"
                                class="p-4 text-sm hover:cursor-pointer active:font-bold"
                                @click="getImage(value.image)"
                            >
                                {{ value.name }}
                            </p>
                        </div>
                        <div class="w-3/3 p-4" v-if="selectedImage || service[0].image">
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
                    <p class="m-8 text-3xl font-bold">Browse By Category</p>
                    <swiper
                        :slides-per-view="4"
                        :space-between="20"
                        :direction="'horizontal'"
                        :navigation="true"
                        :pagination="{ clickable: true }"
                        :loop="true"
                    >
                        <swiper-slide v-for="item in props.category" :key="item.id">
                            <Link :href="`/category/list/${item.id}`">
                                <v-card class="w-64">
                                    <div class="flex justify-center">
                                        <v-img cover :src="'/' + item.image" class="object-cover" />
                                    </div>
                                    <v-card-title class="text-center">{{ item.name }}</v-card-title>
                                </v-card>
                            </Link>
                        </swiper-slide>
                    </swiper>
                </section>
                <section v-else>
                    <template>Nothing to show service</template>
                </section>
            </v-container>
            <v-container>
                <section v-if="props.products.length > 0">
                    <div class="m-8 flex justify-between">
                        <p class="text-3xl font-bold">Best Selling Products</p>
                        <v-btn :width="150" variant="tonal" color="primary" :href="'/products'">View All</v-btn>
                    </div>
                    <swiper
                        :slides-per-view="4"
                        :space-between="20"
                        :direction="'horizontal'"
                        :navigation="true"
                        :pagination="{ clickable: true }"
                        :loop="true"
                    >
                        <swiper-slide v-for="item in props.products" :key="item.id">
                            <Link :href="`/detail/${item.id}`">
                                <v-card class="w-64">
                                    <div class="flex justify-center">
                                        <v-img cover :src="'/' + item.image" class="object-cover" />
                                    </div>
                                    <v-card-title class="text-center">{{ item.name }}</v-card-title>
                                    <v-card-subtitle class="text-center text-red-500">${{ Number(item.price) }}</v-card-subtitle>
                                </v-card>
                            </Link>
                        </swiper-slide>
                    </swiper>
                </section>
                <section v-else>
                    <template>Nothing to show service</template>
                </section>
            </v-container>
            <v-container>
                <v-img cover :src="musicbanner" class="object-cover" />
            </v-container>
            <v-container>
                <section v-if="props.products.length > 0">
                    <div class="m-8 flex justify-between">
                        <p class="text-3xl font-bold">Explore Our Products</p>
                        <v-btn :width="150" variant="tonal" color="primary" :href="'/products'">View All</v-btn>
                    </div>
                    <div class="flex flex-wrap justify-between">
                        <div v-for="item in props.products" :key="item.id" class="m-4 w-64">
                            <Link :href="`/detail/${item.id}`">
                                <v-card>
                                    <div class="flex justify-center">
                                        <v-img cover :src="'/' + item.image" class="object-cover" />
                                    </div>
                                    <v-card-title class="text-center">{{ item.name }}</v-card-title>
                                    <v-card-subtitle class="text-center text-red-500">${{ Number(item.price) }}</v-card-subtitle>
                                </v-card>
                            </Link>
                        </div>
                    </div>
                </section>
                <section v-else>
                    <template>Nothing to show service</template>
                </section>
            </v-container>
        </main>
    </Welcome>
</template>
