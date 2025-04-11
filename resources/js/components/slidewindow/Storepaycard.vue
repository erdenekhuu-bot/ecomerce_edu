<template>
    <div class="card">
        <p>
            <img
                :src="static_asset('/assets/img/icons/2.png')"
                alt=""
                height="25"
            />
        </p>
        <span>{{ day }}</span>
    </div>
</template>
<script>
export default {
    props: {
        image: {
            required: true,
        },
        value: { required: true },
        day: { required: true },
    },
};
</script>
<style scoped>
.card {
    border-radius: 15px !important;
    font-size: 12px !important;
    font-weight: 500;
    background-color: rgb(225, 225, 232) !important;
    display: flex !important;
    color: black !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 0.8rem 1.1rem;
}
</style>
