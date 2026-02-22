<script setup lang="ts">
import Welcome from '@/pages/Welcome.vue';

const props = defineProps<{
    product: {
        name: string;
        description: string;
        price: string;
        image: string;
        image1: string;
        image2: string;
        image3: string;
        image4: string;
        category_name: string;
    };
    related: {
        id: number;
        name: string;
        price: string;
        image: string;
    }[];
}>();
</script>
<template>
    <Welcome>
        <v-container>
            <v-row justify="center">
                <v-col cols="auto">
                    <v-img cover :src="'/' + props.product.image1" class="object-cover" :width="200" />
                    <v-img cover :src="'/' + props.product.image2" class="object-cover" :width="200" />
                    <v-img cover :src="'/' + props.product.image3" class="object-cover" :width="200" />
                    <v-img cover :src="'/' + props.product.image4" class="object-cover" :width="200" />
                </v-col>
                <v-col cols="auto">
                    <v-img cover :src="'/' + props.product.image" class="object-cover" :width="700" />
                </v-col>
                <v-col cols="">
                    <h1 class="text-4xl font-bold text-white">Product Details</h1>
                    <p class="text-2xl font-bold">{{ props.product.name }}</p>
                    <p class="">${{ Number(props.product.price) }}</p>
                    <p class="my-2">{{ props.product.description }}</p>
                    <v-divider />
                </v-col>
            </v-row>
            <section>
                <p class="m-8 text-3xl font-semibold">{{ props.product.category_name }}</p>
                <v-row>
                    <v-col v-for="item in props.related" :key="item.id" cols="auto">
                        <v-card class="w-64">
                            <v-img cover :src="'/' + item.image" class="object-cover" :width="250" />
                            <v-card-title>{{ item.name }}</v-card-title>
                            <v-card-subtitle>${{ Number(item.price) }}</v-card-subtitle>
                        </v-card>
                    </v-col>
                </v-row>
            </section>
        </v-container>
    </Welcome>
</template>
