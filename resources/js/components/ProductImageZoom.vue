<template>
    <div class="image-container" @mousemove="zoom" @mouseleave="resetZoom">
        <img :src="imageSrc" ref="image" class="product-image" />
        <div class="zoom-lens" ref="lens" v-if="showLens"></div>
        <div class="zoom-result" v-if="showLens" ref="result">
            <img :src="imageSrc" ref="resultImage" class="result-image" />
        </div>
    </div>
</template>

<script>
export default {
    name: "ProductImageZoom",
    props: {
        imageSrc: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            showLens: false,
            cx: 1,
            cy: 1,
        };
    },
    methods: {
        zoom(event) {
            const image = this.$refs.image;
            const lens = this.$refs.lens;
            const result = this.$refs.result;
            const resultImage = this.$refs.resultImage;

            this.showLens = true;

            const pos = this.getCursorPos(event, image);
            let x = pos.x - lens.offsetWidth / 2;
            let y = pos.y - lens.offsetHeight / 2;

            if (x > image.width - lens.offsetWidth)
                x = image.width - lens.offsetWidth;
            if (x < 0) x = 0;
            if (y > image.height - lens.offsetHeight)
                y = image.height - lens.offsetHeight;
            if (y < 0) y = 0;

            lens.style.left = x + "px";
            lens.style.top = y + "px";

            this.cx = result.offsetWidth / lens.offsetWidth;
            this.cy = result.offsetHeight / lens.offsetHeight;

            resultImage.style.width = image.width * this.cx + "px";
            resultImage.style.height = image.height * this.cy + "px";
            resultImage.style.left = -(x * this.cx) + "px";
            resultImage.style.top = -(y * this.cy) + "px";
        },
        getCursorPos(event, image) {
            const a = image.getBoundingClientRect();
            const x = event.pageX - a.left;
            const y = event.pageY - a.top;
            return { x: x - window.pageXOffset, y: y - window.pageYOffset };
        },
        resetZoom() {
            this.showLens = false;
        },
    },
};
</script>

<style scoped>
.image-container {
    position: relative;
    width: 100%;
    height: 100%;
}
.product-image {
    width: 100%;
    height: 100%;
}
.zoom-lens {
    position: absolute;
    border: 1px solid #d4d4d4;
    width: 100px;
    height: 100px;
    opacity: 0.4;
    background-color: #ffffff;
    cursor: move;
}
.zoom-result {
    position: absolute;
    top: 0;
    /* right: -400px; */
    width: 100%;
    height: 100%;
    border: 1px solid #d4d4d4;
    overflow: hidden;
}
.result-image {
    position: absolute;
}
</style>
