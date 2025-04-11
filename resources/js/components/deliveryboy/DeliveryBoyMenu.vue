<template>
    <v-list color="primary">
        <v-list-item
            v-for="(route, i) in routes"
            :key="i"
            :to="{ name: route.to }"
            class="mb-0"
        >
            <v-icon class="me-4 d-flex align-center">
                <i :class="route.icon" class="fs-17"></i>
            </v-icon>

            <v-list-item>
                <v-list-item-title class="fw-500 fs-14">
                    {{ route.label }}
                </v-list-item-title>
            </v-list-item>
        </v-list-item>
    </v-list>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    computed: {
        ...mapGetters("app", ["generalSettings", "getUnseenProductQuerries"]),
        ...mapGetters("affiliate", ["isAffiliatedUser"]),
        ...mapGetters("auth", ["isAuthenticated"]),
        routes() {
            return this.getMenuItems().sort((a, b) => a.order - b.order);
        },
    },
    methods: {
        ...mapActions("affiliate", ["fetchAffiliatedUser"]),
        getMenuItems() {
            let items = [
                {
                    label: this.$i18n.t("dashboard"),
                    order: 10,
                    icon: "las la-th-large",
                    image: "assets/img/icons/dashboard.svg",
                    to: "DeliveryBoyDashboard",
                },
                {
                    label: this.$i18n.t("assigned_delivery"),
                    order: 20,
                    icon: "las la-tasks",
                    to: "AssignedDeliveries",
                },
                {
                    label: this.$i18n.t("picked_up_delivery"),
                    order: 30,
                    icon: "las la-luggage-cart",
                    to: "PickedUpDeliveries",
                },
                {
                    label: this.$i18n.t("on_the_way_delivery"),
                    order: 40,
                    icon: "las la-truck-moving",
                    to: "OnTheWayDeliveries",
                },
                {
                    label: this.$i18n.t("pending_delivery"),
                    order: 50,
                    icon: "las la-clock",
                    to: "PendingDeliveries",
                },
                {
                    label: this.$i18n.t("completed_delivery"),
                    order: 60,
                    icon: "las la-check-circle",
                    to: "CompletedDeliveries",
                },
                {
                    label: this.$i18n.t("cancelled_delivery"),
                    order: 70,
                    icon: "las la-times-circle",
                    to: "CancelDeliveries",
                },
                {
                    label: this.$i18n.t("collections_list"),
                    order: 80,
                    icon: "las la-file-invoice-dollar",
                    to: "CollectionsList",
                },
                {
                    label: this.$i18n.t("earnings_list"),
                    order: 90,
                    icon: "las la-money-bill-wave",
                    to: "EarningsList",
                },
            ];

            return items;
        },
    },
    created() {
        this.getMenuItems();
    },
};
</script>
