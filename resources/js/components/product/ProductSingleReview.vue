<template>
    <div class="d-flex py-4">
        <v-avatar v-if="review.user.avatar">
            <img class="border" :src="review.user.avatar" :alt="review.user.name" >
        </v-avatar>
        <v-avatar v-else :color="get_random_color()"><span class="white--text text-h5" >{{ review.user.name.charAt(0)}}</span></v-avatar>
        <span class="ms-4">
            <div class="fw-700 lh-1">{{ review.user.name }}</div>
            <div class="fs-11 opacity-50">{{ review.time }}</div>
            <v-rating class="lh-1-5 mb-2" background-color="" empty-icon="las la-star" full-icon="las la-star active" half-icon="las la-star half half" hover half-increments readonly size="12" length="5" :model-value="review.rating" ></v-rating>
            <div class="fs-12 opacity-70">{{ review.comment }}</div>
            <div v-if="review.image">
            <img class="border" :src="review.image" :alt="review.user.name" width="15%">
        </div>
            <!-- <img :src=review.image alt="" class="border"> -->
        </span>
    </div>
</template>

<script>
export default {
    props: {
        isLoading: { type: Boolean, default: true },
        review: { type: Object, required: true, default:()=> {} }
    },
}
</script>