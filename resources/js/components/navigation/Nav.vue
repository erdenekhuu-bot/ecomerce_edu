<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Link } from '@inertiajs/vue3';
defineProps<{
    title: string;
}>();
</script>

<template>
    <header class="bg-white p-2 text-[#1b1b18]">
        <nav class="flex items-center justify-between gap-4">
            <b>Exclusive</b>
            <div class="flex gap-20">
                <b>Home</b>
                <b>Contact</b>
                <b>About</b>
            </div>
            <Link
                v-if="$page.props.auth.user"
                :href="dashboard()"
                class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a]"
            >
                Dashboard
            </Link>
            <template v-else>
                <Link
                    :href="login()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035]"
                >
                    Log in
                </Link>
                <Link
                    :href="register()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a]"
                >
                    Register
                </Link>
            </template>
        </nav>
    </header>
</template>
