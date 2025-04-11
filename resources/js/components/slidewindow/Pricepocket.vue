<template>
    <div class="pocket-items">
        <img
            :src="static_asset('/assets/img/icons/splush-1.png')"
            alt=""
            height="25"
        />
        <p>{{ day }}</p>
        <b>{{ price + "₮" }} </b>
    </div>
</template>
<script>
export default {
    props: {
        price: { required: true },
        day: { required: true },
    },
};
</script>
<style scoped>
.pocket-items {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: calc((100% - 14px) / 3);
    padding: 0.7rem 1rem;
    background-color: #eeeeee;
    border-radius: 12px;
    font-size: 12px;
    margin-top: 10px;
}
</style>
