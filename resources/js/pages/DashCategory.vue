<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { categorycreate, dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

type Category = {
    data: Array<{
        id: number;
        image: string;
        name: string;
        description: string;
        meta: string;
    }>;
    last_page: number;
    current_page: number;
    total: number;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const changePage = (page: number) => {
    router.get(
        '/category',
        { page },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const props = defineProps<{
    record: Category;
}>();

const page = ref(props.record.current_page);
const totalPages = ref(props.record.total);
</script>

<template>
    <Head title="Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-center rounded-lg border border-[#19140035] p-2">
                <Link :href="categorycreate()"> Create category </Link>
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
                        { title: 'meta attribute', key: 'meta' },
                    ]"
                    :items="props.record.data"
                    :items-length="props.record.total"
                    :page="props.record.current_page"
                    :items-per-page="5"
                    @update:page="changePage"
                >
                    <template #item.image="{ item }">
                        <img v-if="item.image" :src="'/' + item.image" alt="" class="h-16 w-16 rounded object-cover" />
                        <span v-else class="text-gray-400">No Image</span>
                    </template>
                </v-data-table-server>
            </div>
        </div>
    </AppLayout>
</template>
