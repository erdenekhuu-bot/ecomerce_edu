<script setup lang="ts">
import Welcome from '@/pages/Welcome.vue';
import { Link } from '@inertiajs/vue3';

import { Navigation } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';

const props = defineProps<{
    list: Array<{ id: number; name: string; price: string; image: string }>;
}>();
</script>

<template>
    <Welcome>
        <v-container class="overflow-visible">
            <section v-if="props.list.length > 0">
                <Swiper class="mySwiper" :modules="[Navigation]" navigation :spaceBetween="16" :slidesPerView="'auto'" :grabCursor="true">
                    <SwiperSlide v-for="item in props.list" :key="item.id" class="productSlide">
                        <Link :href="`/detail/${item.id}`">
                            <div class="m-4 w-[230px] rounded-xl">
                                <div class="flex justify-center">
                                    <img v-if="item.image" :src="'/' + item.image" alt="" />
                                </div>
                                <p class="font-bold">{{ item.name }}</p>
                                <p class="font-bold text-red-500">${{ Number(item.price) }}</p>
                            </div>
                        </Link>
                    </SwiperSlide>
                </Swiper>
            </section>

            <section v-else>Nothing to show service</section>
        </v-container>
    </Welcome>
</template>

<style scoped>
.productSlide {
    width: 230px;
}
.mySwiper {
    position: relative;
}

.mySwiper :deep(.swiper-wrapper) {
    display: flex;
}
.mySwiper :deep(.swiper-slide) {
    flex-shrink: 0;
}
</style>
