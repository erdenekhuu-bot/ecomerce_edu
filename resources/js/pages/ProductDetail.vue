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
        category_name: string;
    };
}>();
</script>
<template>
    <Welcome>
        <v-container>
            <v-row>
                <v-col>
                    <v-img cover :src="'/' + props.product.image" class="object-cover" />
                </v-col>
                <v-col>
                    <h1 class="text-4xl font-bold text-white">Product Details</h1>
                    <p class="font-bold">${{ Number(props.product.price) }}</p>
                    <p class="">{{ props.product.description }}</p>
                </v-col>
            </v-row>
            <section>
                <p class="font-semibold">{{ props.product.category_name }}</p>
            </section>
        </v-container>
    </Welcome>
</template>
