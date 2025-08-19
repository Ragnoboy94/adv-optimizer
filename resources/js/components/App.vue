<script setup lang="ts">

</script>

<template>
    <div class="wrap">
        <nav class="nav">
            <router-link to="/rules">Правила</router-link>
            <router-link to="/logs">Логи</router-link>
            <router-link to="/charts">График</router-link>
        </nav>
        <main class="main">
            <router-view/>
        </main>
    </div>
</template>

<style>
.wrap {
    font: 14px/1.4 system-ui, sans-serif;
}

.nav {
    display: flex;
    gap: 12px;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.nav a {
    text-decoration: none;
    color: #333;
}

.nav a.router-link-active {
    font-weight: 600;
}

.main {
    padding: 16px;
}

table {
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #ddd;
    padding: 8px;
}

input, select, button, textarea {
    padding: 6px 8px;
}

.flex {
    display: flex;
    gap: 8px;
    align-items: center;
}
</style>
