<script setup lang="ts">
import Nav from '@/components/navigation/Nav.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <v-app>
        <Nav title="Exclusive" />

        <v-main>
            <slot />
        </v-main>

        <v-footer style="background-color: black; color: white">
            <v-container>
                <v-row align="center" justify="space-between">
                    <v-col>
                        <div class="flex flex-col justify-around gap-2">
                            <p class="my-4 text-lg">Exclusive</p>
                            <p class="text-sm">Subscribe</p>
                            <p class="text-sm">Get 10% off your first order</p>
                        </div>
                    </v-col>

                    <v-col>
                        <div class="flex flex-col justify-around gap-2">
                            <p class="my-4 text-lg">Support</p>
                            <p class="text-sm">111 Bijoy sarani, Dhaka, DH 1515, Bangladesh.</p>
                            <p class="text-sm">exclusive@gmail.com</p>
                            <p class="text-sm">+88015-88888-9999</p>
                        </div>
                    </v-col>

                    <v-col>
                        <div class="flex flex-col justify-around gap-2">
                            <p class="my-4 text-lg">Account</p>
                            <p class="text-sm">My account</p>
                            <p class="text-sm">Login / Register</p>
                            <p class="text-sm">Cart</p>
                            <p class="text-sm">Wishlist</p>
                            <p class="text-sm">Shop</p>
                        </div>
                    </v-col>

                    <v-col>
                        <div class="flex flex-col justify-around gap-2">
                            <p class="my-4 text-lg">Quick link</p>
                            <p class="text-sm">Privacy Policy</p>
                            <p class="text-sm">Terms of Use</p>
                            <p class="text-sm">FAQ</p>
                            <p class="text-sm">Contact</p>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-footer>
    </v-app>
</template>
