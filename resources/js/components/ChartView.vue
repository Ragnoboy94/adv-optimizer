<script setup>
import {ref, onMounted} from 'vue';
import {Chart} from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

const ads = ref([]);
const adId = ref(null);
const from = ref('');
const to = ref('');
const canvas = ref(null);
let chart;

onMounted(async () => {
    ads.value = await (await fetch('/api/ads')).json();
    adId.value = ads.value[0]?.id || null;
    await load();
});

async function load() {
    if (!adId.value) return;

    const p = new URLSearchParams({ad_id: adId.value});
    if (from.value) p.set('from', new Date(from.value).toISOString());
    if (to.value) p.set('to', new Date(to.value).toISOString());

    const points = await (await fetch('/api/metrics/changes?' + p.toString())).json();

    const budgetPts = points.map(p => ({x: new Date(p.t), y: p.budget}));
    const cpmPts = points.map(p => ({x: new Date(p.t), y: p.cpm}));

    if (chart) chart.destroy();
    chart = new Chart(canvas.value, {
        type: 'line',
        data: {
            datasets: [
                {label: 'Budget', data: budgetPts, yAxisID: 'y1'},
                {label: 'CPM', data: cpmPts, yAxisID: 'y2'},
            ]
        },
        options: {
            scales: {
                x: {type: 'time', time: {unit: 'hour'}},
                y1: {type: 'linear', position: 'left'},
                y2: {type: 'linear', position: 'right'},
            }
        }
    });
}
</script>


<template>
    <h2>График изменений</h2>
    <div class="flex">
        <label>Объявление
            <select v-model="adId">
                <option v-for="a in ads" :key="a.id" :value="a.id">{{ a.title }}</option>
            </select>
        </label>
        <label>From <input type="date" v-model="from"/></label>
        <label>To <input type="date" v-model="to"/></label>
        <button @click="load">Построить</button>
    </div>
    <canvas ref="canvas" width="900" height="360"></canvas>
</template>

<style scoped>

</style>
