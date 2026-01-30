<script setup lang="ts">
import ProductController from '@/actions/App/Http/Controllers/Dashboard/ProductController';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
const props = defineProps<{
    detail: {
        id: number;
        name: string;
        slug: string;
        description: string;
        category_id: number;
        price: string;
        image: string;
        attribute: number;
    };
}>();
</script>
<template>
    <Head title="Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <Form :action="ProductController.update(props.detail.id).url" method="put" v-slot="{ errors, processing }" :force-form-data="true">
                    <v-text-field name="name" label="Product name" style="width: 30%" v-model="props.detail.name" />
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
