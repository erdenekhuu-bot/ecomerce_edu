<template>
    <v-container>
        <v-row>
            <v-col xl="10" class="mx-auto my-5 my-lg-16">
                <login-form />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import LoginForm from "../../components/auth/LoginForm.vue";
export default {
    components: { LoginForm },
};
</script>
