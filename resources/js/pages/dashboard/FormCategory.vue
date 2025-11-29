<script setup lang="ts">
import CategoryController from '@/actions/App/Http/Controllers/Dashboard/CategoryController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
                    <Label for="name">Category name</Label>
                    <Input id="name" type="text" autofocus :tabindex="1" autocomplete="name" name="name" placeholder="category" />
                    <InputError :message="errors.name" />
                    <Label for="description">Description</Label>
                    <Input
                        id="description"
                        type="text"
                        autofocus
                        :tabindex="2"
                        autocomplete="description"
                        name="description"
                        placeholder="description"
                    />
                    <InputError :message="errors.description" />

                    <Button type="submit" class="mt-2 w-full" tabindex="3" :disabled="processing">
                        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                        Save category
                    </Button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
