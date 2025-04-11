<template>

  <v-stepper
    class="order-steps mb-4 mb-sm-0"
    v-if="!is_empty_obj(orderDetails)"
    elevation="0"
    :model-value="getCurrentStatus()"
    alt-labels
  >
    <v-stepper-header>
      <template
        v-for="(step, i) in steps"
        :key="`${i}-step`"
      >
        <v-stepper-item
        v-if="getStepStatus(step.status)"
          :step="i+1"
          complete-icon="las la-check"
          error-icon="las la-times"
          :rules="[getCancelStatus]"
          color="green"
          :class="[{' v-stepper-item--selected':getStepStatus(step.status) }]"
        >
          {{ getLevel(step.level) }}
        </v-stepper-item>

        <v-stepper-item
        v-else
          :step="i+1"
          error-icon="las la-times"
          :rules="[getCancelStatus]"
          color="grey"
        >
          {{ getLevel(step.level) }}
        </v-stepper-item>

        <v-divider
          v-if="i !== steps.length - 1"
          :key="i"
          :class="[{'complete':getStepStatusForDivider(step.status) }]"
        ></v-divider>
      </template>
    </v-stepper-header>
  </v-stepper>

</template>

<script>
export default {
  props: {
    orderDetails: { type: Object, default: {} },
  },
  data: () => ({}),
  computed: {
    steps() {
      return [
        {
          level: this.$i18n.t("order_placed"),
          status: "order_placed",
        },
        {
          level: this.$i18n.t("confirmed"),
          status: "confirmed",
        },
        {
          level: this.$i18n.t("processed"),
          status: "processed",
        },
        {
          level: this.$i18n.t("shipped"),
          status: "shipped",
        },
        {
          level: this.$i18n.t("delivered"),
          status: "delivered",
        },
      ];
    },
  },
  methods: {
    getLevel(level) {
      return this.orderDetails.delivery_status == "cancelled"
        ? this.$i18n.t("cancelled")
        : level;
    },
    getCancelStatus() {
      return this.orderDetails.delivery_status == "cancelled" ? false : true;
    },
    getCurrentStatus() {
      return (
        this.steps.findIndex(
          (step) => step.status == this.orderDetails.delivery_status
        ) 
      );
    },
    getStepStatus(status) {
      let activeIndex = this.steps.findIndex(
        (step) => step.status == this.orderDetails.delivery_status
      );
      let currentIndex = this.steps.findIndex((step) => step.status == status);
      return currentIndex <= activeIndex;
    },
    getStepStatusForDivider(status) {
      let activeIndex = this.steps.findIndex(
        (step) => step.status == this.orderDetails.delivery_status
      );
      let currentIndex = this.steps.findIndex((step) => step.status == status);
      return currentIndex < activeIndex;
    },

  },
};
</script>