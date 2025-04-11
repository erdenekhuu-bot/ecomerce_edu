<template>
    <div class="custom-width">
        <v-container class="py-0">
            <div class="d-flex">
                <template v-if="loading">
                    <v-skeleton-loader
                        type="text"
                        class="w-100px px-3 mt-2"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                        type="text"
                        class="w-100px px-3 mt-2"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                        type="text"
                        class="w-100px px-3 mt-2"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                        type="text"
                        class="w-100px px-3 mt-2"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                        type="text"
                        class="w-100px px-3 mt-2"
                    ></v-skeleton-loader>
                </template>
                <template v-else>
                    <v-list class="cusom-style py-0">
                        <!--  Mega Menu -->
                        <v-list-item-title>
                            <MegaMenu></MegaMenu>
                        </v-list-item-title>

                        <v-list-item
                            v-for="(link, label, i) in data.header_menu"
                            :key="i"
                            class="flex-grow-0 flex-fill"
                        >
                            <!-- <v-list-item-title>
                                <dynamic-link
                                    :to="link"
                                    append-class="text-reset opacity-80 custom-font"
                                >
                                    {{ label }}
                                </dynamic-link>
                            </v-list-item-title> -->
                            <v-list-item-title>
                                <dynamic-link
                                    :to="link"
                                    append-class="text-reset custom-font"
                                >
                                    {{ label }}
                                </dynamic-link>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </template>
            </div>
        </v-container>
    </div>
</template>

<script>
import MegaMenu from "./MegaMenu.vue";
export default {
    components: {
        MegaMenu,
    },

    props: {
        loading: { type: Boolean, required: true, default: true },
        data: {
            type: Object,
            default: {},
        },
    },
};
</script>
<style>
.cusom-style {
    background-color: black;
    color: white;
    display: flex;
}
.custom-width {
    background-color: black;
    z-index: 1;
}
.custom-font {
    font-weight: 400;
    font-size: 13px !important;
}
</style>
