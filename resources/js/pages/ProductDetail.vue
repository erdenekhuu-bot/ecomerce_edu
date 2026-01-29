<script setup lang="ts">
import Welcome from '@/pages/Welcome.vue';

const props = defineProps<{
    product: {
        name: string;
        slug: string;
        description: string;
        category_id: number;
        price: string;
        image: string;
    };
}>();
</script>
<template>
    <Welcome>
        <v-container>
            <h1 class="text-4xl font-bold text-white">Product Details</h1>
            <v-img cover :src="'/' + product.image" class="object-cover" />
        </v-container>
    </Welcome>
</template>
