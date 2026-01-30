<script setup lang="ts">
import Welcome from '@/pages/Welcome.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    list: Array<{ id: number; name: string; slug: string; description: string; price: string; image: string }>;
}>();
</script>
<template>
    <Welcome>
        <v-container>
            <section v-if="props.list.length > 0">
                <div class="flex justify-between">
                    <div v-for="item in props.list" :key="item.id">
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
    </Welcome>
</template>
