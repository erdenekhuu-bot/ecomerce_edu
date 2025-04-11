<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <v-container class="pt-20">
        <v-row v-if="blogs.length">
            <b class="px-2">Нүүр</b> / <b class="px-2">{{ title }}</b>
        </v-row>
    </v-container>
    <div>
        <v-container class="pt-0 all-blogs">
            <v-row no-gutters align="start">
                <v-col lg="3" class="w-lg-270px sticky-top">
                    <div
                        :class="[
                            'border-end filter-drawer',
                            {
                                'open c-scrollbar overflow-y-auto z-1020':
                                    filterDrawerOpen,
                            },
                        ]"
                    >
                        <div
                            class="border-bottom pa-5 d-lg-none d-flex align-center"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                            >
                                <path
                                    id="Path_2643"
                                    data-name="Path 2643"
                                    d="M20,5H18.829a3,3,0,0,0-5.659,0H4A1,1,0,0,0,4,7h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2ZM16,7a1,1,0,1,0-1-1A1,1,0,0,0,16,7ZM3,12a1,1,0,0,1,1-1H5.171a3,3,0,0,1,5.659,0H20a1,1,0,0,1,0,2H10.829a3,3,0,0,1-5.659,0H4A1,1,0,0,1,3,12Zm5,1a1,1,0,1,0-1-1A1,1,0,0,0,8,13ZM4,17a1,1,0,0,0,0,2h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2H18.829a3,3,0,0,0-5.659,0Zm13,1a1,1,0,1,1-1-1A1,1,0,0,1,17,18Z"
                                    transform="translate(-3 -3)"
                                    fill="#2a2e34"
                                    fill-rule="evenodd"
                                />
                            </svg>
                            <span class="ms-4 fw-600 fs-14 lh-1">{{
                                $t("filters")
                            }}</span>
                            <button
                                type="button"
                                @click.stop="
                                    toggleFilterDrawer(!filterDrawerOpen)
                                "
                                class="ms-4 fw-600 fs-20 lh-1 ms-auto"
                            >
                                <i class="la la-close fs-20"></i>
                            </button>
                            <!-- <button
                class="ms-auto"
                @click.stop="toggleFilterDrawer(!filterDrawerOpen)"
                type="button"
              >
                <i class="las la-close fs-20"></i>
              </button> -->
                        </div>
                        <div class="pa-5 min-100vh">
                            <div class="mb-5">
                                <v-form
                                    class="border rounded-pill flex-grow-1"
                                    @submit.stop.prevent="search()"
                                    rounded
                                >
                                    <v-row align="center" dense>
                                        <v-col class="">
                                            <v-text-field
                                                :placeholder="$t('search')"
                                                type="text"
                                                hide-details="auto"
                                                variant="plain"
                                                v-model="
                                                    queryParamBlog.searchKeyword
                                                "
                                                solo
                                                flat
                                                dense
                                                class="border-0 px-3 search-input-field"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="auto" class="">
                                            <v-btn
                                                size="small"
                                                icon="$vuetify"
                                                class="me-1 shadow-none"
                                                @click.stop.prevent="search()"
                                            >
                                                <i
                                                    class="las la-search fs-18 ts-05"
                                                ></i>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </div>

                            <div class="mb-5">
                                <h4
                                    class="fw-700 fs-14 mb-4 border-bottom pb-3"
                                >
                                    {{ $t("categories") }}
                                </h4>
                                <div>
                                    <ul class="list-unstyled ps-0">
                                        <div>
                                            <li class="my-2">
                                                <router-link
                                                    :to="{ name: 'AllBlogs' }"
                                                    :class="[
                                                        'text-reset fs-14',
                                                        {
                                                            'fw-600':
                                                                is_empty_obj(
                                                                    currentCategory
                                                                ),
                                                        },
                                                    ]"
                                                >
                                                    <span>{{
                                                        $t("all_categories")
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li
                                                v-for="(
                                                    category, i
                                                ) in blogCategories"
                                                :key="i"
                                                class="my-2"
                                            >
                                                <router-link
                                                    :to="{
                                                        name: 'AllBlogsFilter',
                                                        params: {
                                                            categorySlug:
                                                                category.slug,
                                                        },
                                                    }"
                                                    class="text-reset fs-14"
                                                    v-if="
                                                        is_empty_obj(
                                                            currentCategory
                                                        )
                                                    "
                                                    >{{
                                                        category.name
                                                    }}</router-link
                                                >
                                                <router-link
                                                    v-else
                                                    :to="{
                                                        name: 'AllBlogsFilter',
                                                        params: {
                                                            categorySlug:
                                                                category.slug,
                                                        },
                                                    }"
                                                    :class="[
                                                        'text-reset fs-14',
                                                        {
                                                            'fw-600':
                                                                currentCategory.slug ===
                                                                category.slug,
                                                        },
                                                    ]"
                                                    >{{
                                                        category.name
                                                    }}</router-link
                                                >
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h4
                                    class="fw-700 fs-14 mb-4 border-bottom pb-3"
                                >
                                    {{ $t("social_media") }}
                                </h4>
                                <div>
                                    <ul class="list-unstyled ps-0">
                                        <div>
                                            <li class="my-2">
                                                <SocialShare
                                                    :title="$t('all_blogs')"
                                                />
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                            </div>

                            <!-- Recent Blogs -->
                            <!-- <div class="mb-5">
                                <h4 class="fw-700 fs-14 mb-4 border-bottom pb-3">{{ $t('recent_blogs') }}</h4>
                                <div>
                                    <ul class="list-unstyled ps-0">
                                        <div>
                                             <li v-for="(blogRecent, iR) in recentBlogs" :key="iR" class="my-2">
                                                <router-link
                                                    v-if="blogRecent"
                                                    :to="{ name: 'BlogDetails', params: {slug: blogRecent.slug}}"
                                                    class="text-reset fs-14"
                                                >
                                                     <div class="product-box-three">
                                                        <div class="overflow-hidden rounded border">
                                                            <v-row align="center" no-gutters class="flex-nowrap pa-1">
                                                                <v-col cols="auto" class="flex-shrink-0">
                                                                    <div class="position-relative">
                                                                        <router-link
                                                                            :to="{ name: 'BlogDetails', params: {slug: blogRecent.slug}}"
                                                                            class="text-reset d-block lh-0 text-center"
                                                                        >
                                                                            <img
                                                                                :src="blogRecent.banner"
                                                                                :alt="blogRecent.title"
                                                                                @error="imageFallback($event)"
                                                                                class="size-60px"
                                                                                style="object-fit:cover"
                                                                            >
                                                                        </router-link>
                                                                    </div>
                                                                </v-col>
                                                                <v-col class="minw-0 flex-shrink-0">
                                                                    <div class="px-3 d-flex flex-column py-2">
                                                                        <h5 class="opacity-60 fw-400lh-1-6 fs-13 text-truncate-2 h-40px">
                                                                            <router-link
                                                                                :to="{ name: 'BlogDetails', params: {slug: blogRecent.slug}}"
                                                                                class="text-reset"
                                                                            >{{ blogRecent.title }}</router-link>

                                                                        </h5>
                                                                    </div>
                                                                </v-col>
                                                            </v-row>
                                                        </div>
                                                    </div>
                                                </router-link>
                                            </li>
                                        </div>

                                    </ul>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </v-col>
                <v-col lg="9" class="v-col-auto">
                    <div class="pt-5 ps-lg-7">
                        <v-row align="end" class="">
                            <v-col cols="12" sm class="pb-0 pt-3">
                                <div class="d-flex align-center">
                                    <div>
                                        <h1
                                            class="fs-18"
                                            v-if="searchKeyword.length > 0"
                                        >
                                            {{ $t("search_results_for") }} "{{
                                                searchKeyword
                                            }}"
                                        </h1>
                                        <h1
                                            class="fs-18"
                                            v-else-if="
                                                !is_empty_obj(currentCategory)
                                            "
                                        >
                                            {{ currentCategory.name }}
                                        </h1>
                                        <h1 class="fs-18 ml-2" v-else>
                                            {{ $t("all_blogs") }}
                                        </h1>
                                    </div>
                                    <div class="d-lg-none ms-auto ms-sm-0">
                                        <button
                                            class="ms-4 pa-2 border-gray-300 rounded border d-flex justify-center align-center"
                                            @click.stop="
                                                toggleFilterDrawer(
                                                    !filterDrawerOpen
                                                )
                                            "
                                            type="button"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="18"
                                                height="18"
                                                viewBox="0 0 18 18"
                                            >
                                                <path
                                                    id="Path_2643"
                                                    data-name="Path 2643"
                                                    d="M20,5H18.829a3,3,0,0,0-5.659,0H4A1,1,0,0,0,4,7h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2ZM16,7a1,1,0,1,0-1-1A1,1,0,0,0,16,7ZM3,12a1,1,0,0,1,1-1H5.171a3,3,0,0,1,5.659,0H20a1,1,0,0,1,0,2H10.829a3,3,0,0,1-5.659,0H4A1,1,0,0,1,3,12Zm5,1a1,1,0,1,0-1-1A1,1,0,0,0,8,13ZM4,17a1,1,0,0,0,0,2h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2H18.829a3,3,0,0,0-5.659,0Zm13,1a1,1,0,1,1-1-1A1,1,0,0,1,17,18Z"
                                                    transform="translate(-3 -3)"
                                                    fill="#2a2e34"
                                                    fill-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                        <div class="mb-7">
                            <v-row
                                class="row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-xl-3 md-gutters-10"
                                v-if="blogs.length > 0"
                            >
                                <v-col
                                    v-for="(blog, i) in blogs"
                                    :key="i"
                                    cols="8"
                                    sm="4"
                                >
                                    <div v-if="loading">
                                        <v-skeleton-loader
                                            type="image"
                                            class="pa-2"
                                            height="100"
                                        ></v-skeleton-loader>
                                    </div>
                                    <v-card
                                        class="mx-auto my-6 mx-lg-2 border"
                                        style="box-shadow: none"
                                        v-else
                                    >
                                        <template slot="progress">
                                            <v-progress-linear
                                                color="deep-purple"
                                                height="10"
                                                indeterminate
                                            ></v-progress-linear>
                                        </template>

                                        <v-img
                                            height="250"
                                            :src="blog.banner"
                                            cover
                                        ></v-img>

                                        <v-card-title
                                            style="
                                                font-size: 1rem;
                                                line-height: 1.6;
                                                white-space: normal;
                                            "
                                            class="text-truncate-2 py-0 my-4 blog-title"
                                            >{{ blog.title }}</v-card-title
                                        >

                                        <v-card-text class="pb-0">
                                            <span class="grey--text d-block"
                                                ><i>{{
                                                    blog.category
                                                }}</i></span
                                            >
                                            <span class="grey--text">{{
                                                blog.created_at
                                            }}</span>
                                        </v-card-text>

                                        <v-card-actions class="pt-1">
                                            <v-btn
                                                color="primary"
                                                text
                                                :to="{
                                                    name: 'BlogDetails',
                                                    params: { slug: blog.slug },
                                                }"
                                                class="text-capitalize"
                                            >
                                                {{ $t("read_full_blog") }}
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <div class="pa-4 text-center fs-20" v-else>
                                {{ $t("no_blog_found") }}
                            </div>
                        </div>
                        <div class="text-center" v-if="totalPages > 1">
                            <v-pagination
                                v-model="queryParamBlog.page"
                                :length="totalPages"
                                prev-icon="las la-angle-left"
                                next-icon="las la-angle-right"
                                :total-visible="7"
                                elevation="0"
                                @update:modelValue="pageSwitch"
                                class="my-4"
                            ></v-pagination>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import SocialShare from "../components/product/SocialShare.vue";
export default {
    head: {
        title: "All Blogs",
    },
    computed: {
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    data: () => ({
        title: "Мэдээ",
        loading: true,
        searchKeyword: "",
        currentPage: 1,
        totalPages: 1,
        blogs: [{}, {}, {}],
        recentBlogs: [{}, {}, {}, {}, {}],
        filterDrawerOpen: false,
        blogCategories: [],
        queryParamBlog: {
            page: 1,
            categorySlug: null,
            searchKeyword: "",
        },
        currentCategory: {},
    }),
    components: {
        SocialShare,
    },

    methods: {
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        toggleFilterDrawer(status) {
            this.filterDrawerOpen = status;
        },
        pageSwitch(pageNumber) {
            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        page: this.queryParamBlog.page,
                    },
                })
                .catch(() => {});

            this.getBlogList({
                page: pageNumber,
            });
        },
        search() {
            this.$router
                .push({
                    name: "SearchBlogs",
                    params:
                        this.queryParamBlog.searchKeyword.length > 0
                            ? {
                                  searchKeyword:
                                      this.queryParamBlog.searchKeyword,
                              }
                            : {},
                    query: {
                        page: 1,
                    },
                })
                .catch(() => {});
        },
        async getBlogCategories() {
            const res = await this.call_api("get", `all-blog-categories`);
            if (res.data.success) {
                this.blogCategories = res.data.data;
                this.recentBlogs = res.data.recentBlogs.data;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },

        async getBlogList(obj) {
            this.loading = true;

            let params = { ...this.queryParamBlog, ...obj };
            let url = "all-blogs/search?";
            url += `&page=${this.queryParamBlog.page}`;
            url += params.categorySlug
                ? `&category_slug=${params.categorySlug}`
                : "";
            url += params.searchKeyword
                ? `&searchKeyword=${params.searchKeyword}`
                : "";

            const res = await this.call_api("get", url);

            if (res.data.success) {
                this.blogs = res.data.blogs.data;
                this.totalPages = res.data.totalPage;
                this.queryParamBlog.page = res.data.currentPage;
                this.currentCategory = res.data.currentCategory
                    ? res.data.currentCategory
                    : {};
            }
            if (params.searchKeyword.length > 0) {
                this.searchKeyword = params.searchKeyword;
            }
            this.loading = false;
        },
    },
    async created() {
        this.queryParamBlog.categorySlug =
            this.$route.params.categorySlug || this.queryParamBlog.categorySlug;
        this.queryParamBlog.searchKeyword =
            this.$route.params.searchKeyword ||
            this.queryParamBlog.searchKeyword;
        if (this.$route.query.page)
            this.queryParamBlog.page = this.$route.query.page;
        this.getBlogCategories();

        this.getBlogList({
            page: this.queryParamBlog.page,
            categorySlug: this.queryParamBlog.categorySlug,
            searchKeyword: this.queryParamBlog.searchKeyword,
        });
    },
};
</script>

<style scoped>
@media (max-width: 1263px) {
    .sticky-top {
        position: static;
    }
    .filter-drawer {
        position: fixed;
        width: 350px;
        max-width: 100vw;
        height: 100vh;
        visibility: hidden;
        right: -350px;
        top: 0;
        bottom: 0;
        background: #fff;
        z-index: 610;
        box-shadow: 0 0 50px rgb(0 0 0 / 16%);
        transition: all 0.3s;
        -webkit-transition: all 0.3s;
        width: 100%;
        right: 0;
        left: 0;
        height: 100vh;
    }
    .filter-drawer.open {
        right: 0;
        visibility: visible;
    }
}
@media (min-width: 1264px) {
    .w-lg-270px {
        width: 270px;
    }
}
.pt-20 {
    padding-top: 2rem;
}
.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}
</style>
