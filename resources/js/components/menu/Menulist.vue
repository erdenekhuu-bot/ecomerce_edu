<template>
    <div>
        <h2 class="fw-700 fs-14 mb-3 menu-background">{{ title }}</h2>
        <div>
            <v-checkbox
                true-icon="las la-check"
                class="mt-1"
                hide-details
                label="{{ labels }}"
            />
        </div>
    </div>
</template>
<script>
export default {
    props: {
        title: { required: true },
        labels: { required: true },
    },
};
</script>
<style scoped>
.menu-background {
    background-color: #ffffff;
    padding: 0.75rem;
}
</style>
