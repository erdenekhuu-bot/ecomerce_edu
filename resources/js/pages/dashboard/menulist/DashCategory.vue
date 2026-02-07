<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { categorycreate, dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

type CategoryItem = {
    id: number;
    image: string;
    name: string;
    description: string;
    meta: string;
};

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: dashboard().url }];

const props = defineProps<{
    record: CategoryItem[];
}>();

const itemsPerPage = ref(10);
</script>

<template>
    <Head title="Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-center rounded-lg border border-[#19140035] p-2">
                <Link :href="categorycreate()"> Create category </Link>
            </div>

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <v-data-table
                    density="compact"
                    item-key="id"
                    :headers="[
                        { title: 'id', key: 'id' },
                        { title: 'image', key: 'image' },
                        { title: 'name', key: 'name' },
                        { title: 'description', key: 'description' },
                        { title: 'meta attribute', key: 'meta' },
                    ]"
                    :items="props.record"
                    v-model:items-per-page="itemsPerPage"
                    :items-per-page-options="[5, 10, 20, 50, -1]"
                >
                    <template v-slot:[`item.image`]="{ item }">
                        <img v-if="item.image" :src="'/' + item.image" alt="" class="h-16 w-16 rounded object-cover" />
                        <span v-else class="text-gray-400">No Image</span>
                    </template>
                </v-data-table>
            </div>
        </div>
    </AppLayout>
</template>
