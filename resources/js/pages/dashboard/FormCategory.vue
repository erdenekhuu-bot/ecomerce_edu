<script setup lang="ts">
import CategoryController from '@/actions/App/Http/Controllers/Dashboard/CategoryController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Category Form" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <Form v-bind="CategoryController.store.form()" v-slot="{ errors, processing }" :reset-on-success="['name', 'description']">
                    <v-text-field name="name" label="Category name" />
                    <InputError :message="errors.name" />
                    <v-file-input style="width: 20%" label="Category icon" name="image" counter multiple show-size />
                    <InputError :message="errors.image" />
                    <v-text-field name="description" label="Description" />
                    <InputError :message="errors.description" />
                    <v-select name="meta" style="width: 30%" label="Meta attribute" :message="errors.meta" :items="['products', 'services']" />
                    <Button type="submit" class="mt-2 w-full" tabindex="3" :disabled="processing">
                        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                        Submit
                    </Button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
