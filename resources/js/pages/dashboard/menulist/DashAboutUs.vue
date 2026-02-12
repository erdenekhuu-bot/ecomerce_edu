<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { aboutscreate, dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: dashboard().url }];
</script>

<template>
    <Head title="About Us" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-center rounded-lg border border-[#19140035] p-2">
            <Link :href="aboutscreate()"> Create category </Link>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"></div>
    </AppLayout>
</template>
