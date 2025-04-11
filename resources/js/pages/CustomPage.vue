<template>
    <div class="pb-6 custom-page-responsiveness">
        <v-container>
            <template v-if="loading">
                <v-skeleton-loader
                    type="table-heading, divider, list-item-three-line, image, article"
                ></v-skeleton-loader>
            </template>
            <template v-else>
                <h1 class="mb-7 mt-4">{{ page.title }}</h1>
                <div v-html="page.content"></div>
            </template>
        </v-container>
    </div>
</template>

<script>
import { useHead } from '@unhead/vue'

export default {

    data: () =>{
        return {
            loading: true,
            metaTitle: '',
            page: {}
        }
    },
    watch:{
        metaTitle(newTitle){
            this.updateHead(newTitle);
        }
    },
    methods:{
    updateHead(title) {
      useHead({
        title: title,
      });
    }
  },
    async created(){
        const res = await this.call_api("get", `page/${this.$route.params.pageSlug}`);
        if(res.data.success){
            this.metaTitle = res.data.data.title
            this.page = res.data.data
        }else{
            this.snack({
                message: res.data.message,
                color: "red"
            });
            this.$router.push({ name: "404" });
        }
        this.loading = false
    }
}
</script>