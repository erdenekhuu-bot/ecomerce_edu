<template>
    <div class="popup-window">
        <img
            :src="static_asset('/assets/img/icons/storepay-banner.jpg')"
            alt=""
        />
        <section class="flex" style="padding: 10px 0">
            <Card :day="'45 хоног'" :value="4" />
            <Card :day="'60 хоног'" :value="3" />
            <Card :day="'4 сард'" :value="5" />
        </section>
        <section>
            <Pricecard :payment="'Эхний төлөлт'" :price="74250" />
            <Pricecard :payment="'Эхний төлөлт'" :price="74250" />
            <Pricecard :payment="'Эхний төлөлт'" :price="74250" />
        </section>
        <div class="news">
            * Дээрх төлөлтүүд нийт үнийн дүнг хуваасан болно. Ямар ч хүүгүй,
            шимтгэлгүй
        </div>
        <section class="order">
            <p style="font-size: 20px; font-weight: bold">Заавар</p>

            <p style="padding: 4px 0">• Бараагаа сагсандаа нэмнэ.</p>
            <p style="padding: 4px 0">
                • Storepay - д бүртгэлтэй утасны дугаараа оруулна.
            </p>
            <div style="display: flex; align-items: center">
                • Төлбөрийн нөхцөл дээрээс
                <img
                    :src="static_asset('/assets/img/icons/storepay.png')"
                    alt=""
                    width="40"
                />
                store payStorePay сонгоно.
            </div>
        </section>
    </div>
</template>
<script>
import Card from "./Card.vue";
import Pricecard from "./Pricecard.vue";
export default {
    components: {
        Card,
        Pricecard,
    },
    computed: {
        checkWindow() {
            return this.$store.state.cart.storepay;
        },
    },
};
</script>
<style scoped>
.news {
    padding: 8px 18px;
    font-size: 12px;
    margin: 10px 0;
}
.order {
    margin: 8px 18px;
    padding: 20px 25px 30px;
    border-radius: 10px;
    border: 1px dashed rgb(202, 202, 202);
}
</style>
