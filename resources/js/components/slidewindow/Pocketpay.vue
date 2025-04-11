<template>
    <div class="popup-window-pocket">
        <section class="custom-flex">
            <button class="btn">✖</button>
            <img
                :src="static_asset('/assets/img/icons/pocket-zero.png')"
                alt=""
                height="10"
            />
            <button class="btn">Урьдчилгаагүй</button>
            <button class="btn">Шимтгэлгүй</button>
        </section>
        <section class="custom-flex">
            <Storepaycard :day="'30 хоног'" />
            <Storepaycard :day="'45 хоног'" />
            <Storepaycard :day="'60 хоног'" />
            <Storepaycard :day="'90 хоног'" />
        </section>
        <section style="padding: 5px 20px">
            <Pricepocket :price="0" :day="'Одоо төлөх'" />
            <Pricepocket :price="11000" :day="'15 дахь хоног'" />
            <Pricepocket :price="11000" :day="'30 дахь хоног'" />
        </section>
        <div class="order">
            Та энэхүү бараа үйлчилгээний төлбөрийг 30-90 хоногийн хугацаатай
            зээлээр авах боломжтой
        </div>
        <section class="custom-order">
            <p style="font-size: 20px; font-weight: bold">Заавар</p>

            <p style="padding: 4px 0">• Бараагаа сагсандаа нэмнэ.</p>
            <p style="padding: 4px 0">• Хүргэлтийн мэдээлэл оруулна.</p>
            <div style="display: flex; align-items: center">
                • Төлбөрийн нөхцөл дээрээс
                <img
                    :src="static_asset('/assets/img/icons/pocket.png')"
                    alt=""
                    width="30"
                    style="margin: 0 4px"
                />
                pocket сонгоно.
            </div>
            <p style="padding: 4px 0">
                • Гарч ирсэн QR кодыг Pocket аппликейшний Pocket Zero орон
                төлбөр төлнө.
            </p>
        </section>
    </div>
</template>
<script>
import Storepaycard from "./Storepaycard.vue";
import Pricepocket from "./Pricepocket.vue";
export default {
    components: {
        Storepaycard,
        Pricepocket,
    },
    data: () => ({}),
    methods: {},
};
</script>
<style scoped>
.custom-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0.5rem;
    border-bottom: 1px solid #f1f1f1;
    padding: 15px 20px;
}
.btn {
    position: relative;
    margin: 0 0.5rem;
    font-size: 10px;
    font-family: "Open Sans", sans-serif;
    font-style: normal;
    text-align: center;
    border-radius: 5px;
    outline: none;
    -webkit-transition: all 0.2s linear;
    transition: all 0.2s linear;
    &:hover {
        border: 1px solid #f1f1f1;
    }
}
.order {
    margin: 15px 20px;
    text-align: center;
    border: 1px solid rgb(236, 49, 85);
    padding: 1rem 0.5rem;
    border-radius: 10px;
    font-size: 12px;
}
.custom-order {
    margin: 8px 18px;
    padding: 20px 25px 30px;
    border-radius: 10px;
    border: 1px dashed rgb(202, 202, 202);
}
</style>
