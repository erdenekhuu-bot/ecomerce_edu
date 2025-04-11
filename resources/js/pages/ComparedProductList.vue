<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <v-container class="pt-7 mb-4">
        <v-row
            align="start"
            v-if="Object.keys(getComparedListProducts).length > 0"
        >
            <v-col lg="12" cols="12" class="main-bar">
                <div class="ps-lg-7 ps-md-8 pt-4">
                    <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
                        {{ $t("my_compared_list") }}
                    </h1>
                    <v-row>
                        <v-col cols="auto" class="mr-auto"> </v-col>
                        <v-col cols="auto">
                            <v-btn
                                elevation="0"
                                depressed
                                class="mb-2 bg-primary"
                                @click="ResetComparedList"
                            >
                                {{ $t("reset_list") }}
                            </v-btn>
                        </v-col>
                    </v-row>
                    <v-card class="mb-4">
                        <v-table>
                            <template v-slot:default>
                                <tbody>
                                    <tr
                                        v-for="(
                                            specifications, key
                                        ) in getComparedListProducts"
                                        :key="key"
                                    >
                                        <th>
                                            <span
                                                v-if="
                                                    key !== 'slug' &&
                                                    key !== 'id'
                                                "
                                            >
                                                {{ $t(key).toUpperCase() }}
                                            </span>
                                        </th>
                                        <td
                                            v-for="(
                                                specification, index
                                            ) in specifications"
                                            :key="index"
                                        >
                                            <span v-if="key == 'image'">
                                                <img
                                                    :src="specification"
                                                    :class="[
                                                        'img-fit',
                                                        'size-130px',
                                                    ]"
                                                />
                                            </span>
                                            <span v-else-if="key == 'id'">
                                                <button
                                                    class="text-reset py-1 lh-1 align-center d-flex"
                                                    @click="
                                                        removeProduct({
                                                            id: specification,
                                                        })
                                                    "
                                                >
                                                    <i
                                                        class="las la-trash fs-20 ts-05 me-1"
                                                    ></i>
                                                </button>
                                            </span>
                                            <span v-else-if="key == 'slug'">
                                                <button
                                                    class="text-reset py-1 lh-1 align-center d-flex"
                                                    @click="
                                                        showAddToCartDialog({
                                                            status: true,
                                                            slug: specification,
                                                        })
                                                    "
                                                >
                                                    <i
                                                        class="las la-shopping-cart fs-20 ts-05 me-1"
                                                    ></i>
                                                    <span
                                                        class="fw-700 fs-13"
                                                        >{{
                                                            $t("buy_now")
                                                        }}</span
                                                    >
                                                </button>
                                            </span>
                                            <span
                                                v-else-if="
                                                    specification !== null
                                                "
                                            >
                                                {{ specifications[index] }}
                                            </span>
                                            <span v-else> --- </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-table>
                    </v-card>
                </div>
            </v-col>
        </v-row>
        <div v-else class="text-center">
            <div>{{ $t("compared_product_list_is_empty") }}</div>
        </div>
    </v-container>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    head: {
        title: "Compare Products List",
    },
    data: () => ({
        currentPage: 1,
        totalPages: 1,
    }),
    computed: {
        ...mapGetters("compareList", [
            "getComparedListProducts",
            "getcomparedProductsList",
            "getSpecificationKeysList",
        ]),
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    methods: {
        ...mapMutations("auth", ["showAddToCartDialog"]),
        ...mapActions("compareList", [
            "ResetComparedList",
            "fetchComparedListProducts",
            "RemoveComparedListProduct",
        ]),
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        removeProduct(data) {
            this.RemoveComparedListProduct(data);
            this.fetchComparedListProducts();
        },
    },
    created() {
        this.fetchComparedListProducts();
    },
};
</script>

<style scoped>
.bg-primary {
    background-color: var(--primary) !important;
    border-color: var(--primary) !important;
    color: var(--white) !important;
}
</style>
