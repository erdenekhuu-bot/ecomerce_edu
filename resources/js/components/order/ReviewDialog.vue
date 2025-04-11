<template>
    <v-dialog
        class="z-1020"
        v-model="dialog"
        :max-width="options.width"
        :style="{ zIndex: options.zIndex }"
        @keydown.esc="close"
        scrollable
    >
        <v-card>
            <v-card-title class="fs-16 fw-600 bg-grey-lighten-3 p-4 d-flex">
                {{ $t("rate_and_review_purchased_product") }}
                <button class="ms-auto" @click.stop="close" type="button">
                    <i class="las la-close fs-20"></i>
                </button>
            </v-card-title>
            <v-card-text class="pa-4 black-text">
                <div v-if="productId">
                    <div v-if="!product" class="h-270px"></div>
                    <div class="px-5 py-4 " v-else>
                        <v-alert
                        class="mb-4"                          
                            icon="las la-info-circle"
                            type="info"
                            variant="tonal"
                            v-if="hasOldReview"
                        >
                            {{
                                $t(
                                    "youve_already_reviewed_this_product_you_can_update_the_review"
                                )
                            }}
                        </v-alert>
                        <v-form>
                            <div>{{ $t("rating") }}</div>
                            <v-rating
                                class="product-review"
                                empty-icon="las la-star"
                                full-icon="las la-star active"
                                half-icon="las la-star half half"
                                background-color=""
                                v-model="review.rating"
                                hover
                                length="5"
                                size="12"
                            ></v-rating>
                            <div class="mt-3">{{ $t("comment") }}</div>
                            <v-textarea
                                v-model="review.comment"
                                hide-details="auto"
                                variant="outlined"
                            ></v-textarea>
                            <p v-for="error of v$.review.comment.$errors" :key="error.$uid" class="text-red">
                                {{error.$message }}
                            </p>
                            
                            <div class=" mb-3">
                            <!-- <v-avatar size="160" class="">
                                    <img :src="review.previewAvatar" @error="imageFallback($event)">
                                </v-avatar> -->
                                <v-img
                                :src="review.previewAvatar"
                                width="20%"
                                class="bg-grey-lighten-2 mt-4 mb-2 "
                                ></v-img>
                                <input
                                type="file"
                                id="avatar-input"
                                accept="image/png, image/jpg, image/jpeg"
                                @change="previewThumbnail"
                            />
                            </div>

                        </v-form>
                    </div>
                </div>
                <div class="text-center fs-15 py-4" v-else>
                    {{ $t("this_product_is_not_available") }}
                </div>
            </v-card-text>
            <v-card-actions class="pt-3 mb-4">
                <v-spacer></v-spacer>
                <v-btn
                    color="grey-darken-4"
                    text
                    class=""
                    elevation="0"
                    @click.native="close"
                    >{{ $t("close") }}</v-btn
                >
                <v-btn
                    class="px-5 text-white btn-primary"
                    elevation="0"
                    :loading="submitting"
                    :disabled="submitting"
                    @click.native="submit"
                    >{{ $t("submit") }}</v-btn
                ></v-card-actions
            >
        </v-card>
    </v-dialog>
</template>

<script>
import { useVuelidate } from '@vuelidate/core';
import { required } from "@vuelidate/validators";

export default {
    name: "ReviewDialog",
    data() {
        return {
            dialog: false,
            submitting: false,
            productId: null,
            product: null,
            hasOldReview: false,
            v$: useVuelidate(),
            review: {
                rating: null,
                comment: null,
                avatar: "",
            previewAvatar: "",
            },
            options: {
                color: "grey-lighten-3",
                width: 800,
                zIndex: 200,
            },
        };
    },
    validations: {
        review: {
            comment: {
                required,
            },
        },
    },
    computed: {
        commentErrors() {
            const errors = [];
            if (!this.v$.review.comment.$dirty) return errors;
            !this.v$.review.comment.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
    },
    methods: {
        open(productId) {
            this.dialog = true;
            this.hasOldReview = false;
            this.product = null;
            this.review.rating = null;
            this.review.comment = null;

            this.productId = productId;
            if (productId) this.getReviewStatus(productId);
        },
        async getReviewStatus(productId) {
            const res = await this.call_api(
                "get",
                `user/review/check/${productId}`
            );
            if (res.data.success) {
                this.product = res.data.product;
                this.review = res.data.oldReview;
                this.review.previewAvatar = res.data.oldReview.image;
                if (!res.data.oldReview.rating) {
                    this.review.rating = 5;
                } else {
                    this.hasOldReview = true;
                }
            } else {
                this.snack({ message: res.data.message });
            }
        },
        previewThumbnail(event) {
            this.review.avatar = event.target.files[0]
			if (event.target.files && event.target.files[0]) {
				const reader = new FileReader();
				reader.onload = (e) => {

					this.review.previewAvatar = e.target.result;
				};
				reader.readAsDataURL(event.target.files[0]);

			}
		},
        async submit() {
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            this.review.product_id = this.productId;
            this.submitting = true;
            let formData = new FormData();
            for ( var key in this.review ) {
                formData.append(key, this.review[key]);
            }

            const res = await this.call_api(
                "post",
                "user/review/submit",
                formData, true
            );

            if (res.data.success) {
                this.snack({ message: res.data.message });
            } else {
                this.snack({ message: res.data.message, color: "red" });
            }
            this.submitting = false;
            this.dialog = false;
        },
        close() {
            this.dialog = false;
        },
    },
};
</script>
