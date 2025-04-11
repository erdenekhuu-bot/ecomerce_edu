<template>
  <div class="">
    <v-container>
      <v-row no-gutters>
        <v-col
          lg="3"
          class="d-none d-lg-block"
        >
          <sidemenu></sidemenu>
        </v-col>
        <v-col
          cols="12"
          lg="9"
          class="pt-0"
        >
          <router-view></router-view>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import sidemenu from "./SideMenu.vue";
export default {
  components: {
    sidemenu,
  },
  computed: {
    ...mapGetters("auth", ["currentUser"]),
  },
  methods: {
    ...mapActions(["auth/logout"]),
    async logout() {
      const res = await this.call_api("get", "auth/logout");
      this["auth/logout"]();
      this.resetCart();
      this.resetWishlist();
      this.$router.push({ name: "Home" }).catch(() => {});
    },
  },
  created() {
    if (this.currentUser.user_type == "customer") {
      this.logout();
      this.snack({
        message: this.$i18n.t("You are not permitted to access this!"),
        color: "red",
      });
      this.$router.push({ name: "DeliveryBoyLogin" }).catch(() => {});
    }
  },
};
</script>

<style lang="scss" scoped></style>
