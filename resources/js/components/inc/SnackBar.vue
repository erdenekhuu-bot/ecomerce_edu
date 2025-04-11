<template>
    <v-snackbar v-model="showing" :timeout="timeout" :color="color">
        {{ message }}
        <template v-slot:action="{ attrs }">
            <v-btn small icon fab v-bind="attrs" @click="showing = false">
                <i class="las la-times la-2x"></i>
            </v-btn>
        </template>
    </v-snackbar>
</template>
<script>
export default {
    data() {
        return {
            showing: false,
            message: "",
            color: "dark",
            timeout: 2000,
        };
    },
    created() {
        this.$store.watch(
            (state) => state.snackbar.message,
            () => {
                const msg = this.$store.state.snackbar.message;
                if (msg !== "") {
                    this.showing = true;
                    this.message = this.$store.state.snackbar.message;
                    this.color = this.$store.state.snackbar.color;
                    this.timeout = this.$store.state.snackbar.timeout;
                    this.$store.commit("snackbar/createSnack", { message: "" });
                }
            }
        );
    },
};
</script>
