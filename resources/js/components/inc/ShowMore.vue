<template>
    <div>
        <div
            :class="{ default: !expanded, expanded: expanded }"
            :style="`height:${height}px`"
        >
            <slot></slot>
        </div>
        <a
            v-if="!expanded"
            class="primary-text fw-700 fs-12 mt-3 d-inline-block c-pointer"
            @click="onClick"
            >{{ $t("show_more") }}</a
        >
        <a
            v-else
            class="primary-text fw-700 fs-12 mt-3 d-inline-block c-pointer"
            @click="onClick"
            >{{ $t("show_less") }}</a
        >
    </div>
</template>

<script>
export default {
    props: {
        height: {
            type: Number,
            default: 112,
        },
    },
    data: () => ({
        expanded: false,
        triggerShowMore: false,
    }),
    methods: {
        onClick() {
            this.expanded = !this.expanded;
        },
    },
};
</script>
<style scoped>
.default {
    overflow: hidden;
}
.expanded {
    height: auto !important;
}
</style>
