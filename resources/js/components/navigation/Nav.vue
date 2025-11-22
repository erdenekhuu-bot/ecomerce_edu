<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Link } from '@inertiajs/vue3';
defineProps<{
    title: string;
}>();
</script>

<template>
    <header class="bg-white p-4 text-[#1b1b18]">
        <nav class="flex items-center justify-between gap-4">
            <b class="">Exclusive</b>
            <div class="flex items-center gap-10">
                <Link class="hover:underline">Home</Link>
                <Link class="hover:underline">Contact</Link>
                <Link class="hover:underline">About</Link>
                <div class="relative">
                    <input
                        placeholder="What are you looking for?"
                        class="inline-block w-64 rounded-sm border border-[#19140035] py-1.5 pr-10 pl-5 text-sm leading-normal hover:border-[#1915014a]"
                    />
                    <Link class="absolute top-1/2 right-2 -translate-y-1/2 transform text-gray-400 hover:text-gray-600">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 20L16.2223 16.2156M18.3158 11.1579C18.3158 13.0563 17.5617 14.8769 16.2193 16.2193C14.8769 17.5617 13.0563 18.3158 11.1579 18.3158C9.2595 18.3158 7.43886 17.5617 6.0965 16.2193C4.75413 14.8769 4 13.0563 4 11.1579C4 9.2595 4.75413 7.43886 6.0965 6.0965C7.43886 4.75413 9.2595 4 11.1579 4C13.0563 4 14.8769 4.75413 16.2193 6.0965C17.5617 7.43886 18.3158 9.2595 18.3158 11.1579V11.1579Z"
                                stroke="black"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                        </svg>
                    </Link>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <Link>
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 27C11.5523 27 12 26.5523 12 26C12 25.4477 11.5523 25 11 25C10.4477 25 10 25.4477 10 26C10 26.5523 10.4477 27 11 27Z"
                            stroke="black"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M25 27C25.5523 27 26 26.5523 26 26C26 25.4477 25.5523 25 25 25C24.4477 25 24 25.4477 24 26C24 26.5523 24.4477 27 25 27Z"
                            stroke="black"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path d="M3 5H7L10 22H26" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M10 16.6667H25.59C25.7056 16.6667 25.8177 16.6267 25.9072 16.5535C25.9966 16.4802 26.0579 16.3782 26.0806 16.2648L27.8806 7.26479C27.8951 7.19222 27.8934 7.11733 27.8755 7.04552C27.8575 6.97371 27.8239 6.90678 27.7769 6.84956C27.73 6.79234 27.6709 6.74625 27.604 6.71462C27.5371 6.68299 27.464 6.66661 27.39 6.66666H8"
                            stroke="black"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        /></svg
                ></Link>
                <Link>
                    <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.75 0.75C2.989 0.75 0.75 2.966 0.75 5.7C0.75 7.907 1.625 13.145 10.238 18.44C10.3923 18.5339 10.5694 18.5835 10.75 18.5835C10.9306 18.5835 11.1077 18.5339 11.262 18.44C19.875 13.145 20.75 7.907 20.75 5.7C20.75 2.966 18.511 0.75 15.75 0.75C12.989 0.75 10.75 3.75 10.75 3.75C10.75 3.75 8.511 0.75 5.75 0.75Z"
                            stroke="black"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </Link>
            </div>
            <Link
                v-if="$page.props.auth.user"
                :href="dashboard()"
                class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a]"
            >
                Dashboard
            </Link>
            <div class="flex gap-6" v-else>
                <Link :href="login()" class="text-sm leading-normal text-[#1b1b18] hover:underline"> Log in </Link>
                <Link :href="register()" class="text-sm leading-normal text-[#1b1b18] hover:underline"> Register </Link>
            </div>
        </nav>
    </header>
</template>
