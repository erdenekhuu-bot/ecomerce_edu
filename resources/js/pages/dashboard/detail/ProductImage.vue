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
        image: string;
        image1: string;
        image2: string;
        image3: string;
        image4: string;
    };
}>();
</script>
<template>
    <Head title="Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <v-img v-if="props.detail.image" :src="'/' + props.detail.image" alt="" class="h-46 w-46 rounded object-cover" />
                <Form :action="ProductController.update(props.detail.id).url" method="put" v-slot="{ errors, processing }" :force-form-data="true">
                    <v-file-input name="first" label="Product image 1" style="width: 20%" show-size />
                    <v-chip>Current: {{ props.detail.image1 ?? '—' }}</v-chip>

                    <v-file-input name="second" label="Product image 2" style="width: 20%" show-size />
                    <v-chip>Current: {{ props.detail.image2 ?? '—' }}</v-chip>

                    <v-file-input name="third" label="Product image 3" style="width: 20%" show-size />
                    <v-chip>Current: {{ props.detail.image3 ?? '—' }}</v-chip>

                    <v-file-input name="fourth" label="Product image 4" style="width: 20%" show-size />
                    <v-chip>Current: {{ props.detail.image4 ?? '—' }}</v-chip>

                    <Button type="submit" class="mt-2 w-full" tabindex="3" :disabled="processing">
                        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                        Submit
                    </Button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
