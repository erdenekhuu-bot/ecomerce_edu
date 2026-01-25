<script setup lang="ts">
import Nav from '@/components/navigation/Nav.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Category,Service } from '@/types';

const props = defineProps<{
    bannerUrl: string;
    category: Category;
    service: Service;
    musicbanner:string;
}>();

const selectedImage = ref<string | null>(null);
const getImage = (imageUrl: string) => {
    selectedImage.value = imageUrl;
};

</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <Nav title="Exclusive" />
    <section class="">
        
        <main class="w-full bg-white text-black">
            <v-container>
                <section v-if="service.length > 0">
                    <div class="flex">
                        <div class="w-1/4 border border-[#19140035]">
                            <p
                                v-for="value in service"
                                :key="value.id"
                                class="p-4 text-sm hover:cursor-pointer active:font-bold"
                                @click="getImage(value.image)"
                            >
                                {{ value.name }}
                            </p>
                        </div>
                        <div class="w-3/4 p-4" v-if="selectedImage || service[0].image">
                            <v-img cover :src="selectedImage || service[0].image" class="object-cover" />
                        </div>
                    </div>
                </section>
                <section v-else>
                   <template>Nothing to show service</template>
                </section>
            </v-container>
            <v-container>
                <section v-if="props.category.length > 0" class="">
                    <p class="text-2xl font-bold">Browse By Category</p>
                    <div class="flex">
                        <div v-for="item in props.category" :key="item.id" class="m-4 h-[145px] w-[170px] rounded-lg border p-4">
                            <div class="text-center">
                                <span class="font-bold">{{ item.name }}</span>
                                <div class="flex justify-center">
                                    <img v-if="item.image" :src="'/' + item.image" alt="" class="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section v-else> 
                    <template>Nothing to show service</template>
                </section>
            </v-container>
            <v-container>
                 <v-img cover :src="musicbanner" class="object-cover" />
            </v-container>
            <v-container>
                <section>
                   Nothing to show product
                </section>
            </v-container>
        </main>
    </section>
    <v-footer style="background-color: black; color: white;">
         <v-container>
            <v-row align="center" justify="space-between">
                <v-col>
                     <div class="flex flex-col gap-2 justify-around">
                        <p class="text-lg my-4">Exclusive</p>
                        <p class="text-sm">Subscribe</p>
                        <p class="text-sm">Get 10% off your first order</p>
                     </div>
                </v-col>
                 <v-col>
                   <div class="flex flex-col gap-2 justify-around">
                    <p class="text-lg my-4">Support</p>
                    <p class="text-sm">111 Bijoy sarani, Dhaka,  DH 1515, Bangladesh.</p>
                    <p class="text-sm">exclusive@gmail.com</p>
                    <p class="text-sm">+88015-88888-9999</p>
                   </div>
                </v-col>
                 <v-col>
                    <div class="flex flex-col gap-2 justify-around">
                        <p class="text-lg my-4">Account</p>
                        <p class="text-sm">My account</p>
                        <p class="text-sm">Login / Register</p>
                        <p class="text-sm">Cart</p>
                        <p class="text-sm">Wishlist</p>
                        <p class="text-sm">Shop</p>
                   </div>
                </v-col>
                 <v-col>
                    <div class="flex flex-col gap-2 justify-around">
                        <p class="text-lg my-4">Quick link</p>
                        <p class="text-sm">Privacy Policy</p>
                        <p class="text-sm">Terms of Use</p>
                        <p class="text-sm">FAQ</p>
                        <p class="text-sm">Contact</p>
                   </div>
                </v-col>
            </v-row>
        </v-container>
    </v-footer>
</template>
