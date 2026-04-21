<script lang="ts" setup>
defineProps({
    title: {
        type: String,
        required: true,
        default: ''
    }
})
</script>

<template>
    <div class="nav">
        <a>{{ title }}</a>
    </div>
</template>

<style scoped>
.nav {
    background: rgba(var(--color-card-rgb), 0.5);
    box-shadow: 0 0 5px var(--color-shader);
    backdrop-filter: blur(13px);
    width: calc(80% - 50px);
    border-radius: 7px;
    padding: 15px 25px;
    position: fixed;
    margin: 0 auto;
    z-index: 1000;
    top: 10px;
    right: 0;
    left: 0;
}
.nav a {
    color: var(--color-font);
    font-size: 1.3rem;
}
</style>
