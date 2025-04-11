<template>
    <div>
        <div v-if="isLoading">
            <v-skeleton-loader
                type="image"
                class=""
                height="200"
            ></v-skeleton-loader>
        </div>
        <v-card  elevation="0" outlined class="text-center" v-else>
            <div class="position-relative lh-0">
                <img
                    class="mw-100 w-100"
                    :src="couponDetails.banner"
                    @error="imageFallback($event)"
                />
                <v-btn
                    @click.stop="copyCode(couponDetails.code)"
                    color="white"
                    elevation="0"
                    class="absolute-bottom-left ms-4 mb-4"
                >
                    {{ $t("copy_code") }}
                </v-btn>
            </div>
        </v-card>
    </div>
</template>

<script>
export default {
    props: {
        isLoading: { type: Boolean, required: true, default: true },
        couponDetails: { type: Object, required: true, default: {} }
    },
    methods: {
        copyCode(code) {
            const el = document.createElement("input");
            el.value = code;
            el.setAttribute("readonly", "");
            el.style.position = "absolute";
            el.style.left = "-9999px";
            document.body.appendChild(el);

            const selected =
                document.getSelection().rangeCount > 0
                    ? document.getSelection().getRangeAt(0)
                    : false;
            el.select();

            try {
                document.execCommand("copy");
                this.snack({
                    message: this.$i18n.t("code_copied_to_clipboard"),
                });
            } catch (err) {
                this.snack({ message: this.$i18n.t("something_went_wrong") });
            }
            document.body.removeChild(el);
        },
    },
}
</script>