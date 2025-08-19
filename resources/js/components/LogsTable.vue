<script setup>
import { ref, onMounted } from 'vue';
const rows=ref([]), ads=ref([]), adId=ref(null), from=ref(''), to=ref(''), pagination=ref({page:1,next:null,prev:null});
onMounted(async()=>{ ads.value = await (await fetch('/api/ads')).json(); await load(); });
function url(pageToken=null){ const p=new URLSearchParams(); if(adId.value)p.set('ad_id',adId.value); if(from.value)p.set('from',new Date(from.value).toISOString()); if(to.value)p.set('to',new Date(to.value).toISOString()); if(pageToken)p.set('page',pageToken); return '/api/logs?'+p.toString(); }
async function load(pageToken=null){ const d=await (await fetch(url(pageToken))).json(); rows.value=d.data; pagination.value={page:d.current_page,next:d.next_page_url?d.current_page+1:null,prev:d.prev_page_url?d.current_page-1:null}; }
function move(dir){ const t=dir>0?pagination.value.next:pagination.value.prev; if(t) load(t); }
</script>

<template>
    <h2>Логи</h2>
    <div class="flex">
        <label>Объявление
            <select v-model="adId">
                <option :value="null">Все</option>
                <option v-for="a in ads" :key="a.id" :value="a.id">{{ a.title }}</option>
            </select>
        </label>
        <label>From <input type="datetime-local" v-model="from"/></label>
        <label>To <input type="datetime-local" v-model="to"/></label>
        <button @click="load">Применить</button>
    </div>
    <table>
        <thead>
        <tr><th>Время</th><th>Правило</th><th>Объявление</th><th>Budget</th><th>CPM</th><th>Сообщение</th></tr>
        </thead>
        <tbody>
        <tr v-for="row in rows" :key="row.id">
            <td>{{ new Date(row.triggered_at).toLocaleString() }}</td>
            <td>{{ row.rule?.name || ('#'+row.rule_id) }}</td>
            <td>{{ row.ad?.title || ('#'+row.ad_id) }}</td>
            <td>{{ row.before_budget }} → <b>{{ row.after_budget }}</b></td>
            <td>{{ row.before_cpm }} → <b>{{ row.after_cpm }}</b></td>
            <td>{{ row.message }}</td>
        </tr>
        </tbody>
    </table>
    <div class="flex" style="margin-top:8px">
        <button :disabled="!pagination.prev" @click="move(-1)">Назад</button>
        <span>Стр. {{ pagination.page }}</span>
        <button :disabled="!pagination.next" @click="move(1)">Вперёд</button>
    </div>
</template>

<style scoped>

</style>
