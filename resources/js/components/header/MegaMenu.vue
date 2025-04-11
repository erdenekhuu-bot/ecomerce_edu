<template>
    <div class="mega-menu aiz-main-wrap">
        <v-menu
            offset-y
            min-width="100%"
            open-on-hover
            class="mega-menu-cat"
            transition="v-scroll-x-transition"
            :elevation="6"
        >
            <template v-slot:activator="{ props }">
                <div class="custom-padding">
                    <div class="custom-space">
                        <img
                            :src="static_asset(`/assets/img/Group.png`)"
                            alt=""
                        />
                    </div>
                    <div
                        @click="goToCategoryPage"
                        v-bind="props"
                        class="custom-font"
                    >
                        {{ $t("all_categories") }}
                    </div>
                </div>
            </template>
            <v-card
                style="
                    box-shadow: 0px 10px 20px -20px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
                        0px 10px 20px 1px
                            var(
                                --v-shadow-key-penumbra-opacity,
                                rgba(0, 0, 0, 0.14)
                            ),
                        0px 10px 20px -20px var(--v-shadow-key-ambient-opacity, rgba(0, 0, 0, 0.12));
                "
                class=""
            >
                <div class="v-container">
                    <v-row v-if="categories.length" class="mega-menu-row">
                        <v-col
                            class="px-0"
                            cols="12"
                            md="4"
                            lg="3"
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
                            <div outlined class="pa-6" v-else>
                                <v-row align="center">
                                    <v-col cols="8">
                                        <h3 class="mb-3">
                                            <router-link
                                                :to="{
                                                    name: 'Category',
                                                    params: {
                                                        categorySlug:
                                                            category.slug,
                                                    },
                                                }"
                                                class="text-reset"
                                                >{{
                                                    category.name
                                                }}</router-link
                                            >
                                        </h3>
                                        <div
                                            v-if="category.children.data.length"
                                        >
                                            <ul class="c-scrollbar-light">
                                                <li
                                                    v-for="(
                                                        children, j
                                                    ) in category.children.data"
                                                    :key="j"
                                                >
                                                    <v-hover v-slot="{ hover }">
                                                        <router-link
                                                            :class="[
                                                                'text-reset me-1 opacity-80 category-item-text',
                                                                {
                                                                    'text-decoration-underline':
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
                                                                    category
                                                                        .children
                                                                        .data
                                                                        .length
                                                                "
                                                                class=""
                                                                >,</span
                                                            ></router-link
                                                        >
                                                    </v-hover>
                                                </li>
                                            </ul>
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: [],
            menu: "assets/hamburger.jpg",
        };
    },
    methods: {
        goToCategoryPage() {
            this.$router.push({ name: "AllCategories" });
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

<style scoped>
.custom-padding {
    display: flex;
    align-items: center;
    padding: 12px 10px;
    background-color: #e5de00;
    color: black;
    font-weight: 500;
    &:hover {
        cursor: pointer;
    }
}
.custom-font {
    font-size: 13px !important;
    font-weight: bold;
}
.custom-space {
    margin: 0 10px;
}

.mega-menu {
    position: relative;
    display: inline-block;
}

.text-h5 {
    font-size: 18px;
    margin: 0;
}

.v-card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    padding: 16px;
}

.v-list {
    padding: 0;
}

.v-list-item-title {
    font-size: 14px;
}
ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
</style>
