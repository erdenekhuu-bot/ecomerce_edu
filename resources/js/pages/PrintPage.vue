<template>
    <v-container class="demo-print-header">
        <h4>
            {{ $store.state.app.appName }}
        </h4>
        <ul class="list-unstyled ps-0 fs-13 mb-4">
            <li class="py-2">
                <div>
                    {{ data.contact_info?.contact_address }}
                </div>
            </li>
            <li class="py-2">
                {{ $t("email") + ": " }}

                {{ data.contact_info?.contact_email }}
            </li>
            <li class="py-2">
                {{ $t("phone") + ": " }}

                {{ data.contact_info?.contact_phone }}
            </li>
        </ul>

        <v-data-table
            class="border px-4 pt-3"
            :headers="headers"
            :items="tableData"
            hide-default-footer
            disable-pagination
        >
            <template v-slot:[`item.photo`]="{ item }">
                <v-container>
                    <img
                        class="d-block fw-600"
                        :src="item.photos[0]"
                        alt=""
                        width="80"
                /></v-container>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <span class="d-block fw-600 my-4">{{ item.name }}</span>
            </template>

            <template v-slot:[`item.price`]="{ item }">
                <span class="d-block fw-600">{{
                    format_price(item.base_price)
                }}</span>
            </template>

            <template v-slot:[`item.tax`]="{ item }">
                <span class="d-block fw-600">{{ item.unit }}</span>
            </template>

            <template v-slot:[`item.total`]="{ item }">
                <span class="d-block fw-600">{{
                    format_price(item.base_price)
                }}</span>
            </template>
        </v-data-table>

        <div class="my-4">
            <h4>{{ $t("description") }}</h4>
        </div>
        <div class="my-4 d-flex" style="justify-content: center">
            <div v-html="productProps.description"></div>
        </div>
    </v-container>
</template>

<script>
export default {
    props: {
        data: { required: false },
        productProps: { required: false },
    },
    computed: {
        headers() {
            return [
                {
                    title: "Зураг",
                    align: "center",
                    sortable: false,
                    value: "photo",
                },
                {
                    title: "Product Name",
                    sortable: false,
                    value: "name",
                },
                {
                    title: "Нэгж үнэ",
                    sortable: false,
                    align: "center",
                    value: "price",
                },
                {
                    title: "Unit tax",
                    sortable: false,
                    align: "center",
                    value: "tax",
                },
                {
                    title: "Нийт",
                    sortable: false,
                    align: "center",
                    value: "total",
                },
            ];
        },
        tableData() {
            return [this.productProps];
        },
    },
};
</script>
