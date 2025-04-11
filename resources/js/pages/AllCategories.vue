<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <v-container class="pt-20">
        <v-row v-if="categories.length">
            <b class="px-2">Нүүр</b> /
            <b class="px-2">{{ $t("all_categories") }}</b>
        </v-row>
    </v-container>
    <div class="pb-6">
        <v-container>
            <v-row v-if="categories.length">
                <v-col
                    cols="12"
                    md="6"
                    v-for="(category, i) in categories"
                    :key="i"
                >
                    <div v-if="loading">
                        <v-skeleton-loader
                            type="image"
                            class=""
                            height="235"
                        ></v-skeleton-loader>
                    </div>
                    <v-card outlined class="pa-6" v-else variant="outlined">
                        <v-row align="center">
                            <v-col cols="4">
                                <router-link
                                    :to="{
                                        name: 'Category',
                                        params: { categorySlug: category.slug },
                                    }"
                                    class="text-reset"
                                >
                                    <img
                                        class="img-fluid"
                                        :src="category.banner"
                                        :alt="category.name"
                                        @error="imageFallback($event)"
                                    />
                                </router-link>
                            </v-col>
                            <v-col cols="8">
                                <h3 class="mb-3">
                                    <router-link
                                        :to="{
                                            name: 'Category',
                                            params: {
                                                categorySlug: category.slug,
                                            },
                                        }"
                                        class="text-reset"
                                        >{{ category.name }}</router-link
                                    >
                                </h3>
                                <div v-if="category.children.data.length">
                                    <v-hover
                                        v-slot="{ hover }"
                                        v-for="(children, j) in category
                                            .children.data"
                                        :key="j"
                                    >
                                        <v-hover v-slot="{ hover }">
                                            <router-link
                                                :class="[
                                                    'text-reset me-1 opacity-80 category-item-text',
                                                    {
                                                        'primary--text text-decoration-underline':
                                                            hover,
                                                    },
                                                ]"
                                                :to="{
                                                    name: 'Category',
                                                    params: {
                                                        categorySlug:
                                                            children.slug,
                                                    },
                                                }"
                                                >{{ children.name
                                                }}<span
                                                    v-if="
                                                        j + 1 !==
                                                        category.children.data
                                                            .length
                                                    "
                                                    class=""
                                                    >,</span
                                                ></router-link
                                            >
                                        </v-hover>
                                    </v-hover>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    head: {
        title: "All Categories",
    },
    computed: {
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    data: () => ({
        loading: true,
        categories: [{}, {}, {}, {}],
    }),
    methods: {
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
    },
    async created() {
        const res = await this.call_api("get", "all-categories");
        if (res.data.success) {
            this.categories = res.data.data;
            this.loading = false;
        }
    },
};
</script>
<style>
.pt-20 {
    padding-top: 2rem;
}
.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}
</style>
