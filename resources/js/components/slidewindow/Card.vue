<template>
    <div class="popup-card">
        <span>{{ day }}</span>
        <p class="circle-icon">{{ value }}</p>
    </div>
</template>
<script>
export default {
    props: {
        day: {
            required: true,
        },
        value: {
            required: true,
        },
    },
};
</script>
