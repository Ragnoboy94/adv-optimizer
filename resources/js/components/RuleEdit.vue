<script setup>
import {ref, reactive, onMounted, watch} from 'vue';
import {useRoute} from 'vue-router';

const id = useRoute().params.id;
const form = reactive({
    name: '',
    is_active: true,
    scope_ad_id: null,
    evaluation_window_minutes: 60,
    condition_tree: {type: 'group', op: 'AND', children: []},
    actions: []
});
const ads = ref([]);
const groupOp = ref('AND');
const conds = ref([]);
const rawJson = ref('');
onMounted(async () => {
    ads.value = await (await fetch('/api/ads')).json();
    const data = await (await fetch(`/api/rules/${id}`)).json();
    Object.assign(form, data);
    init();
});

function init() {
    groupOp.value = form.condition_tree?.op || 'AND';
    conds.value = (form.condition_tree?.children || []).map(c => ({...c}));
    rawJson.value = JSON.stringify(form.condition_tree, null, 2);
}

function addCond() {
    conds.value.push({type: 'predicate', metric: 'spent', op: '>', value: 10});
}

function addAction() {
    form.actions.push({target: 'cpm', op: 'decrease', value: 1});
}

watch([conds, groupOp], () => {
    form.condition_tree = {type: 'group', op: groupOp.value, children: conds.value};
    rawJson.value = JSON.stringify(form.condition_tree, null, 2);
}, {deep: true});

function applyRaw() {
    try {
        const p = JSON.parse(rawJson.value);
        form.condition_tree = p;
        init();
    } catch (e) {
        alert('Неверный JSON условий');
    }
}

async function save() {
    const r = await fetch(`/api/rules/${id}`, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(form)
    });
    if (!r.ok) return alert('Ошибка сохранения');
    alert('Сохранено');
}
</script>

<template>
    <div class="flex" style="justify-content:space-between;align-items:center">
        <h2>Правило #{{ id }}</h2>
        <div class="flex">
            <button @click="save">Сохранить</button>
            <router-link to="/rules">
                <button>Назад</button>
            </router-link>
        </div>
    </div>
    <div class="flex">
        <label>Название <input v-model="form.name" style="min-width:300px"/></label>
        <label>Активно <input type="checkbox" v-model="form.is_active"/></label>
        <label>Ad scope
            <select v-model="form.scope_ad_id">
                <option :value="null">Все</option>
                <option v-for="a in ads" :key="a.id" :value="a.id">{{ a.title }}</option>
            </select>
        </label>
        <label>Окно (мин)<input type="number" min="1" max="1440" v-model.number="form.evaluation_window_minutes"
                                style="width:90px"/></label>
    </div>
    <h3>Условия</h3>
    <div class="flex">
        <label>Оператор группы
            <select v-model="groupOp">
                <option>AND</option>
                <option>OR</option>
            </select>
        </label>
        <button @click="addCond">+ условие</button>
    </div>
    <table v-if="conds.length">
        <thead>
        <tr>
            <th>Metric</th>
            <th>Op</th>
            <th>Value</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(c,idx) in conds" :key="idx">
            <td>
                <select v-model="c.metric">
                    <option value="spent">Spent</option>
                    <option value="clicks">Clicks</option>
                    <option value="views">Views</option>
                    <option value="budget">Budget</option>
                    <option value="cpm">Cpm</option>
                </select>
            </td>
            <td>
                <select v-model="c.op">
                    <option>></option>
                    <option>>=</option>
                    <option><</option>
                    <option><=</option>
                    <option>==</option>
                    <option>!=</option>
                </select>
            </td>
            <td><input type="number" step="0.0001" v-model.number="c.value"/></td>
            <td>
                <button @click="conds.splice(idx,1)">x</button>
            </td>
        </tr>
        </tbody>
    </table>
    <h3>Действия</h3>
    <div>
        <button @click="addAction">+ действие</button>
    </div>
    <table v-if="form.actions?.length">
        <thead>
        <tr>
            <th>Target</th>
            <th>Op</th>
            <th>Value</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(a,idx) in form.actions" :key="idx">
            <td><select v-model="a.target">
                <option value="budget">Budget</option>
                <option value="cpm">Cpm</option>
            </select></td>
            <td><select v-model="a.op">
                <option value="increase">increase</option>
                <option value="decrease">decrease</option>
                <option value="set">set</option>
            </select></td>
            <td><input type="number" step="0.0001" v-model.number="a.value"/></td>
            <td>
                <button @click="form.actions.splice(idx,1)">x</button>
            </td>
        </tr>
        </tbody>
    </table>
    <details>
        <summary>Raw JSON условий</summary>
        <textarea v-model="rawJson" rows="8" style="width:100%"></textarea>
        <div class="flex">
            <button @click="applyRaw">Применить JSON</button>
        </div>
    </details>
</template>

<style scoped>

</style>
