<template>
    <div class="row px-3 align-center py-2">
        <div class="col-sm-12 col-md-4 pb-0 pb-md-2">
            <div class="d-flex align-center"> 
                <v-avatar size="60">
                    <img
                        :src="conversation.receiver_image"
                        class="border border-4"
                        @error="imageFallback($event)"
                    />
                </v-avatar> 
                <div class="ml-3">
                    <h5>{{ conversation.receiver_name }}</h5>
                    <div>{{ conversation.latest_message_time }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-8">
            <div class="d-flex align-center">
                <div>
                    <h4>
                        <router-link :to="`/user/product-query/`+conversation.slug" class="text-dark">
                            {{ conversation.title }} <span v-if="conversation.sender_viewed == 0" class="custom-new-badge">({{ $t("new") }})</span>
                        </router-link>
                    </h4>
                    <div>{{ conversation.latest_message.message }}</div>
                </div>
            </div>
        </div>
    </div>
</template> 
<script>
export default {
    props: ["conversation"]
}
</script>