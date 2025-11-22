<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

type User = {
    data: Array<{
        id: number;
        name: string;
        email: string;
        created_at: string;
    }>;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const props = defineProps<{
    users: User;
}>();
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">id</th>
                            <th class="text-left">username</th>
                            <th class="text-left">email</th>
                            <th class="text-left">created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in props.users.data" :key="item.id">
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.email }}</td>
                            <td>{{ item.created_at }}</td>
                        </tr>
                    </tbody>
                </v-table>
            </div>
        </div>
    </AppLayout>
</template>
