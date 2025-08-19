import { createRouter, createWebHistory } from 'vue-router';
import RulesList from './components/RulesList.vue';
import RuleEdit from './components/RuleEdit.vue';
import LogsTable from './components/LogsTable.vue';
import ChartView from './components/ChartView.vue';

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/rules' },
        { path: '/rules', component: RulesList },
        { path: '/rules/:id', component: RuleEdit, props: true },
        { path: '/logs', component: LogsTable },
        { path: '/charts', component: ChartView },
    ],
});
