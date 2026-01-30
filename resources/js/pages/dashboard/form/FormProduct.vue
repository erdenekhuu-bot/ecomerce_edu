<script setup lang="ts">
import ProductController from '@/actions/App/Http/Controllers/Dashboard/ProductController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
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
    categories: Array<{ id: number; name: string; description: string }>;
}>();
</script>

<template>
    <Head title="Product Form" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <Form
                    v-bind="ProductController.store.form()"
                    v-slot="{ errors, processing }"
                    :force-form-data="true"
                    :reset-on-success="['name', 'slug', 'category_id', 'description', 'price']"
                >
                    <v-text-field name="name" label="Product name" style="width: 30%" />
                    <InputError :message="errors.name" />
                    <v-text-field name="slug" label="Product slug" style="width: 30%" />
                    <InputError :message="errors.slug" />
                    <v-file-input name="image" label="Product image" style="width: 20%" show-size />
                    <InputError :message="errors.image" />
                    <v-textarea name="description" label="Product description" style="width: 50%" />
                    <InputError :message="errors.description" />
                    <v-select name="category_id" label="Category" :items="props.categories" item-title="name" item-value="id" style="width: 30%" />
                    <InputError :message="errors.category_id" />
                    <v-text-field name="price" label="Product price" type="number" style="width: 30%" />
                    <InputError :message="errors.price" />
                    <Button type="submit" class="mt-2 w-full" tabindex="3" :disabled="processing">
                        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                        Submit
                    </Button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
