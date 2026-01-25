<script setup lang="ts">
import ProductController from '@/actions/App/Http/Controllers/Dashboard/ProductController';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

type Product = Array<{
    id: number;
    name: string;
    slug: string;
    image: string;
    description: string;
    price: number;
}>;

const props = defineProps<{
    products: Product;
}>();
</script>

<template>
    <Head title="Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-center rounded-lg border border-[#19140035] p-2">
                <Link :href="ProductController.create()"> Create product </Link>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <v-data-table-server
                    density="compact"
                    item-key="id"
                    :headers="[
                        { title: 'id', key: 'id' },
                        { title: 'image', key: 'image' },
                        { title: 'name', key: 'name' },
                        { title: 'description', key: 'description' },
                        { title: 'slug', key: 'slug' },
                    ]"
                    :items="props.products"
                    :items-length="props.products.length"
                >
                    <template v-slot:[`item.image`]="{ item }">
                        <img v-if="item.image" :src="'/' + item.image" alt="" class="h-16 w-16 rounded object-cover" />
                        <span v-else class="text-gray-400">No Image</span>
                    </template>
                </v-data-table-server>
            </div>
        </div>
    </AppLayout>
</template>
