<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router} from '@inertiajs/vue3';
import { reactive ,onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    defaultShifts: Object,
    employeeNumber: Number,
    employee_id: Number,
    errors: Object
});

const day_of_week_name = {
    1: '月曜日',
    2: '火曜日',
    3: '水曜日',
    4: '木曜日',
    5: '金曜日',
    6: '土曜日',
    7: '日曜日'
};

const form =reactive({
    employeeNumber: props.employeeNumber,
    '月曜日': { start_time: '', end_time: '' ,dayOfNameNumber : ''},
    '火曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '水曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '木曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '金曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '土曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '日曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''}
})
onMounted(() => {
    Object.entries(day_of_week_name).forEach(([key, value]) => {
        form[value].dayOfNameNumber = key; 
        if(form[value].start_time == '00:00' && form[value].end_time == '00:00'){
            form[value].start_time = '';
            form[value].end_time = '';
        }
    });
});

// Object.entries(day_of_week_name).forEach(([key, value]) => {
//     if(form[value].end_time == "00:00:00"){
//         form[value].end_time = "24:00:00"
//     }
// })

props.defaultShifts.forEach(defaultShift => {
    Object.entries(day_of_week_name).forEach(([key, value]) => {
        if(defaultShift.day_of_week == key){
            // if(form[value].end_time == "00:00:00"){
            //     form[value].end_time = "24:00:00"
            // }
            form[value].start_time = defaultShift.clock_in;
            // if(defaultShift.clock_out == '00:00:00'){
            //     form[value].end_time = '24:00'
            // } else {
                form[value].end_time = defaultShift.clock_out;
            // }
            
            
        };
    });
});
const timeClear = (dayOfNameNumber) => {
    Object.entries(day_of_week_name).forEach(([key, value]) => {
        if(dayOfNameNumber == key){
            form[value].start_time = '';
            form[value].end_time = '';
        };
    });
};
console.log(form)
const updateDefaultShift = id => {
    router.put(route('defaultShifts.update', {defaultShift: id}), form);
};
</script>

<template>
    <Head title="出勤時間変更" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">勤務時間変更</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="updateDefaultShift(props.employee_id)">
                            <div class="text-center">
                                <label for="employee_number" class="mr-2">社員番号:</label>
                                <input type="text" id="employee_number" required name="employee_number" v-model="form.employeeNumber">
                            </div>

                        <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                            <table class="table-fixed w-full">
                                <thead>
                                <tr>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">曜日</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出勤時間</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">退勤時間</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                        削除
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr  v-for="(dayOfWeek, key) in day_of_week_name" :key="key">
                                    <td class="border-y-2 border-gray-200 px-4 py-3">{{ dayOfWeek }}</td>
                                    <td class="border-y-2 border-gray-200 px-4 py-3 relative">
                                        <input type="time" step="900" :id="dayOfWeek" :name="'start_time_' + dayOfWeek" v-model="form[dayOfWeek].start_time" class="mb-2">
                                            <div v-if="form[dayOfWeek].start_time && form[dayOfWeek].end_time &&  form[dayOfWeek].start_time >= form[dayOfWeek].end_time"
                                                class="absolute bg-yellow-400 text-white text-xs rounded p-1 mt-1 -top-8 left-0 text-nowrap">
                                                出勤時間は退勤時間より前に設定してください。
                                            </div>
                                        

                                    </td>
                                    <td class="border-y-2 border-gray-200 px-4 py-3">
                                        <input type="time" step="900" min="form[dayOfWeek].start_time" :id="dayOfWeek" :name="'end_time_' + dayOfWeek" v-model="form[dayOfWeek].end_time" class="mb-2">
                                    </td>
                                    <td class="border-y-2 border-gray-200 px-4 py-3">
                                        <button 
                                        :disabled="!form[dayOfWeek].start_time" 
                                        :class="{
                                            'text-white bg-red-500 hover:bg-red-600': form[dayOfWeek].start_time,
                                            'text-gray-500 bg-gray-300 cursor-not-allowed': !form[dayOfWeek].start_time
                                        }"    
                                        type="button" 
                                        @click="timeClear(form[dayOfWeek].dayOfNameNumber)" 
                                        class="border-0 py-2 px-8 focus:outline-none rounded">
                                            削除
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                        <!-- 登録ボタン -->
                        <div class="mt-6">
                            <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                                登録
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
