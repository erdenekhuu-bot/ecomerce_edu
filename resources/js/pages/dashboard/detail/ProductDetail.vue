<script setup lang="ts">
import ProductController from '@/actions/App/Http/Controllers/Dashboard/ProductController';
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
    categories: Array<{
        id: number;
        name: string;
        description: string;
    }>;
}>();
const size = ['XS', 'S', 'M', 'L', 'XL'];
const color = ['Red', 'Blue', 'Green', 'Black', 'White'];
</script>
<template>
    <Head title="Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <Form :action="ProductController.update(props.detail.id).url" method="put" v-slot="{ errors, processing }" :force-form-data="true">
                    <v-text-field name="name" label="Product name" style="width: 30%" v-model="props.detail.name" />
                    <v-text-field name="price" label="Product price" type="number" style="width: 30%" v-model="props.detail.price" />
                    <v-textarea name="description" label="Product description" style="width: 50%" v-model="props.detail.description" />
                    <v-select
                        name="category_id"
                        label="Category"
                        :items="props.categories"
                        item-title="name"
                        item-value="id"
                        style="width: 30%"
                        v-model="props.detail.category_id"
                    />
                    <v-file-input name="image" label="Product image" style="width: 20%" show-size />
                    <v-select name="size" label="Size" style="width: 30%" :items="size" />
                    <v-select name="color" label="Color" style="width: 30%" :items="color" />
                    <v-text-field name="total" label="Total count" type="number" style="width: 30%" />
                    <v-text-field name="stock" label="Stock" type="number" style="width: 30%" />
                    <v-textarea name="additional_info" label="Additional info" style="width: 50%" />
                    <v-text-field name="rate" label="Rate" type="number" style="width: 30%" />

                    <Button type="submit" class="mt-2 w-full" tabindex="3" :disabled="processing">
                        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                        Submit
                    </Button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
