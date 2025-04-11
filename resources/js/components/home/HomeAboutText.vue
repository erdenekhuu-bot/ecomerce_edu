<template>
    <div v-html="data" v-if="!loading"></div>
</template>

<script>
export default {
    data: () => ({
        loading: true,
        data: null,
    }),
    async created(){
        const res = await this.call_api("get", "setting/home/home_about_text");
        if (res.data.success) {
            this.data = res.data.data
            this.loading = false
        }
    }
}
</script>