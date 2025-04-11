<template>
    <div class="price">
        <div>
            <img :src="static_asset('/assets/img/icons/1.svg')" alt="" />
            <p>{{ payment }}</p>
        </div>
        <b>{{ price + "₮" }}</b>
    </div>
</template>
<script>
export default {
    props: {
        image: {
            required: true,
        },
        payment: {
            required: true,
        },
        price: {
            required: true,
        },
    },
};
</script>
<style scoped>
.price {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    margin: 15px 20px;
    border-radius: 10px;
    background: rgb(242, 242, 242);
    & div {
        display: flex;
        align-items: center;
    }
    & div p {
        padding: 0 10px;
        font-weight: bold;
    }
}
</style>
