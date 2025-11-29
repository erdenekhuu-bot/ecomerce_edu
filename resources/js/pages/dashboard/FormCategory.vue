<script setup lang="ts">
import CategoryController from '@/actions/App/Http/Controllers/Dashboard/CategoryController';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

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
                <Form v-bind="CategoryController.store.form" v-slot="{ errors, processing }" :reset-on-success="['name', 'description']">
                    <!-- <v-text-field label="name" name="name" v-model="form.name" :message="errors.name" />
                    <InputError :message="errors.name" /> -->
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" name="email" autofocus :tabindex="1" autocomplete="email" placeholder="email@example.com" />
                    <InputError :message="errors.email" />
                    <v-text-field label="description" name="description" v-model="form.description" :message="errors.description" />
                    <InputError :message="errors.description" />
                    <v-btn class="mt-2" type="submit" block :disabled="processing">
                        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                        Save
                    </v-btn>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
