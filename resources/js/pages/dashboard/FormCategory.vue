<script setup lang="ts">
import CategoryController from '@/actions/App/Http/Controllers/Dashboard/CategoryController';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const csrf = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement).content;

const form = ref({
    name: '',
    description: '',
});
</script>

<template>
    <Head title="Category Form" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <!-- <Form action="/dashboard/category" method="post">
                    <input type="hidden" name="_token" :value="csrf" />
                    <v-text-field label="name" name="name" v-model="form.name" />

                    <v-text-field label="description" name="description" v-model="form.description" />

                    <v-btn class="mt-2" type="submit" block>Submit</v-btn>
                </Form> -->
                <Form v-bind="CategoryController.store.form">
                    <input type="hidden" name="_token" :value="csrf" />
                    <v-text-field label="name" name="name" v-model="form.name" />

                    <v-text-field label="description" name="description" v-model="form.description" />

                    <v-btn class="mt-2" type="submit" block>Submit</v-btn>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
