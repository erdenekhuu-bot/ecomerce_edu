<template>
  <span>
    <a
      :href="goTo"
      v-if="externalLink"
      :class="appendClass"
    >
      <slot />
    </a>
    <router-link
      :to="goTo"
      :class="appendClass"
      v-else
    >
      <slot />
    </router-link>
  </span>
</template>

<script>
export default {
  name: "DynamicLink",
  props: {
    to: {
      required: true,
      validator: (prop) => typeof prop === "string" || prop === null,
    },
    appendClass: {
      default: "",
    },
  },
  data: () => ({
    externalLink: false,
  }),
  computed: {
    goTo() {
      this.externalLink =
        typeof this.to === "string" && this.to.slice(0, 4) == "http"
          ? true
          : false;
      return typeof this.to === "string" ? this.to : "";
    },
  },
};
</script>
