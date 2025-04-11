<template>
    <v-container class="py-9">
        <template v-if="loading">
            <div class="mx-auto col-12 col-md-8 mt-4">
                <v-skeleton-loader
                    type="image"
                    height="145"
                ></v-skeleton-loader>
            </div>
        </template>
        <template v-else>
            <div class="mx-auto col-12 col-md-8 mt-4">
                <v-row class="gutters-20">

                    <v-col cols="12" md="9" class="pt-0">
                        <h3 class="px-0 pt-0">{{ blogDetails.title }}</h3>
                        <v-card-text class="px-0">
                            <div class="row justify-content-sm-between">
                                <div class="col-sm-6">
                                    <div class="grey--text text-truncate">
                                        <i>{{ blogDetails.category }}</i>
                                    </div>
                                    <span class="text-truncate">{{
                                        blogDetails.created_at
                                    }}</span>
                                </div>
                                <div class="col-sm-6 text-sm-right">
                                    <SocialShare :title="blogDetails.title" />
                                </div>
                            </div>
                        </v-card-text>

                        <div class="position-relative">
                            <img
                                :src="blogDetails.banner"
                                class="img-fit rounded"
                                alt="Banner"
                                @error="imageFallback($event)"
                            />
                        </div>

                        <v-divider class="mx-4"></v-divider>
                        <div
                            class="py-4 blog-details-description text-justify"
                            v-html="blogDetails.description"
                        ></div>
                    </v-col>
                    
                 
                    <v-col cols="12" md="3" class="mt-2 mt-md-0">
                        <h4 class="fw-700 fs-14 mb-4 border-bottom pb-3">
                            {{ $t("recent_blogs") }}
                        </h4>
                        <div
                            class="my-2"
                            v-for="(blogRecent, iR) in recentBlogs"
                            :key="iR"
                        >
                            <router-link
                                :to="{
                                    name: 'BlogDetails',
                                    params: { slug: blogRecent.slug },
                                }"
                                class="text-reset fs-14 fw-600"
                            >
                                <img
                                    :src="blogRecent.banner"
                                    class="img-fit rounded"
                                    alt="Banner"
                                    @error="imageFallback($event)"
                                />
                                <h4 class="pa-0 text-truncate-2">
                                    {{ blogRecent.title }}
                                </h4>

                                <v-card-text class="px-0 pt-1">
                                    <div class="grey--text text-truncate">
                                        <i>{{ blogRecent.category }}</i>
                                    </div>
                                    <span class="text-truncate">{{
                                        blogRecent.created_at
                                    }}</span>
                                </v-card-text>
                            </router-link>
                        </div>
                    </v-col>
                </v-row>  
                <!-- facebook comment -->
                <!-- <fb-comment :url="appURL" />  -->
            </div>
        </template>
    </v-container>
</template>

<script>
// import {  watch } from 'vue';
import { mapGetters } from "vuex";
import SocialShare from "../components/product/SocialShare.vue";
import { useHead } from '@unhead/vue'


export default {

    data: () => ({
        metaTitle: '',
        metaDescription:'',
        loading: true,
        blogDetails: {},
        recentBlogs: [{}, {}, {}, {}, {}],
    }),
    components: {
        SocialShare,
    },

    computed:{
        ...mapGetters("app",[
            "appUrl"
        ]),
        appURL: function(){
            return this.appUrl + '/' + this.blogDetails.slug
        }
    },
    watch: {
    metaTitle(newTitle) {
      this.updateHead(newTitle, this.metaDescription);
    },
    metaDescription(newDescription) {
      this.updateHead(this.metaTitle, newDescription);
    }
  
  },

  methods:{
    updateHead(title, description) {
      useHead({
        title: title,

        meta: [
          { name: 'description', content: description }
        ]
      });
    }
  },
    async created() {
        const res = await this.call_api(
            "get",
            `blog/details/${this.$route.params.slug}`
        );
        if (res.data.success) {
            this.blogDetails = res.data.data;
            this.metaTitle = res.data.data.meta_title;
            this.metaDescription = res.data.data.meta_description;
            this.recentBlogs = res.data.recentBlogs.data;
            this.loading = false;
        }
    },
};
</script> 