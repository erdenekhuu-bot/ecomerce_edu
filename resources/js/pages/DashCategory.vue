<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { categorycreate, dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

type Category = {
    data: Array<{
        id: number;
        image: string;
        name: string;
        description: string;
    }>;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
const props = defineProps<{
    record: Category;
}>();
</script>

<template>
    <Head title="Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-center rounded-lg border border-[#19140035] p-2">
                <Link :href="categorycreate()"> Create category </Link>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">id</th>
                            <th class="text-center">image</th>
                            <th class="text-left">name</th>
                            <th class="text-left">description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in props.record.data" :key="item.id">
                            <td>{{ item.id }}</td>
                            <td>
                                <img v-if="item.image" :src="'/' + item.image" alt="" class="h-16 w-16 rounded object-cover" />
                                <span v-else class="text-gray-400">No Image</span>
                            </td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.description }}</td>
                        </tr>
                    </tbody>
                </v-table>
            </div>
        </div>
    </AppLayout>
</template>
