<script setup>
import { ref, onMounted } from 'vue';

const rules = ref([]);
async function load(){ rules.value = await (await fetch('/api/rules')).json(); }
onMounted(load);
function shortActions(a){ return a.map(x=>`${x.target} ${x.op} ${x.value}`).join('; '); }
async function toggle(r){ await fetch(`/api/rules/${r.id}/toggle`,{method:'POST'}); await load(); }
async function del(r){ if(!confirm('Удалить правило?'))return; await fetch(`/api/rules/${r.id}`,{method:'DELETE'}); await load(); }
async function createEmpty(){
    const body={name:'Новое правило',is_active:true,scope_ad_id:null,evaluation_window_minutes:60,condition_tree:{type:'group',op:'AND',children:[]},actions:[{target:'cpm',op:'decrease',value:1}]};
    await fetch('/api/rules',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
    await load();
}
</script>

<template>
    <div class="flex" style="justify-content:space-between">
        <h2>Правила</h2>
        <button @click="createEmpty">+ Создать</button>
    </div>
    <table>
        <thead>
        <tr>
            <th>ID</th><th>Название</th><th>Активно</th><th>Окно, мин</th><th>Действия</th><th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="r in rules" :key="r.id">
            <td>{{ r.id }}</td>
            <td><router-link :to="`/rules/${r.id}`">{{ r.name }}</router-link></td>
            <td>{{ r.is_active ? 'Да' : 'Нет' }}</td>
            <td>{{ r.evaluation_window_minutes }}</td>
            <td><code>{{ shortActions(r.actions) }}</code></td>
            <td class="flex">
                <button @click="toggle(r)">Вкл/Выкл</button>
                <button @click="del(r)">Удалить</button>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<style scoped>

</style>
