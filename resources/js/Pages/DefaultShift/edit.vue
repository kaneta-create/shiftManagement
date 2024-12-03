<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router} from '@inertiajs/vue3';
import { reactive ,onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    defaultShifts: Object,
    employeeNumber: Number,
    employee_id: Number,
    userRole: Object,
    errors: Object
});
console.log(props.employeeNumber)
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
    console.log(defaultShift)
    
    Object.entries(day_of_week_name).forEach(([key, value]) => {
        if(defaultShift.day_of_week == key){
            form[value].start_time = defaultShift.clock_in.slice(0, -3);
            form[value].end_time = defaultShift.clock_out.slice(0, -3);
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

// const updateDefaultShift = id => {
//     router.put(route('defaultShifts.update', {defaultShift: id}), form);
// };
// const updateDefaultShift = id => {
//     router.put('/defaultShifts/${id}',  form);
// };
const updateDefaultShift = id => {
    router.put(`/defaultShifts/${id}`, form);
};

const generateTimeOptions = () => {
    const times = [];
    for (let hour = 0; hour < 24; hour++) {
        for (let minute = 0; minute < 60; minute += 15) {
            const formattedHour = String(hour).padStart(2, '0');
            const formattedMinute = String(minute).padStart(2, '0');
            times.push(`${formattedHour}:${formattedMinute}`);
        }
    }
    return times;
};

const timeOptions = generateTimeOptions();

</script>

<template>
    <Head title="出勤時間変更" />

    <AuthenticatedLayout :userRole="props.userRole.authority">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">勤務時間変更</h2>
        </template> -->

        <div class="py-4 bg-gray-300">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <h1 class="sm:text-4xl text-center text-3xl font-mono title-font mb-4 text-gray-900">シフト登録</h1>

                            <p class="lg:w-2/3 mx-auto text-center leading-relaxed text-sm text-gray-600">社員番号と時間を入力して勤務時間を変更してください</p>
                        </div>
                        <form @submit.prevent="updateDefaultShift(props.employee_id)">
                            <div class="text-center my-8">
                                <label for="employee_number" class="mr-2">社員番号:</label>
                                <input type="text" id="employee_number" required name="employee_number" v-model="form.employeeNumber" class="rounded">
                            </div>
                            <!-- エラーメッセージ -->
                           <div v-for="(day, index) in Object.keys(day_of_week_name)" :key="index">
                                <div v-if="props.errors && props.errors[`${day_of_week_name[day]}.start_time`]" class="text-red-600">
                                    *{{ props.errors[`${day_of_week_name[day]}.start_time`] }}
                                </div>
                                <div v-if="props.errors && props.errors[`${day_of_week_name[day]}.end_time`]" class="text-red-600">
                                   *{{ props.errors[`${day_of_week_name[day]}.end_time`] }}
                                </div>

                            </div>
                        <div class="lg:w-3/4 w-full mx-auto overflow-auto">
                            <table class="table-fixed w-full">
                                <thead>
                                <tr>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-tl rounded-bl">曜日</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">出勤時間</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">退勤時間</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">
                                        削除
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(dayOfWeek, key) in day_of_week_name" :key="key" :class="{'bg-gray-50' : key % 2 !== 1}">
                                    <td class="border-y-2 border-gray-200 px-4 py-3 text-center">{{ dayOfWeek }}</td>
                                    <td class="border-y-2 border-gray-200 px-4 py-3 relative">
                                        <select v-model="form[dayOfWeek].start_time" class="mb-2 rounded w-full">
                                            <option value="" disabled>-- 選択 --</option>
                                            <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                                        </select>
                                        <div v-if="form[dayOfWeek].start_time && form[dayOfWeek].end_time && form[dayOfWeek].start_time >= form[dayOfWeek].end_time"
                                            class="absolute mb-5 text-red-500 text-sm -bottom-6 left-0 whitespace-nowrap">
                                            *出勤時間は退勤時間より前に設定してください。
                                        </div>
                                    </td>
                                    <td class="border-y-2 border-gray-200 px-4 py-3">
                                        <select v-model="form[dayOfWeek].end_time" class="mb-2 rounded w-full">
                                            <option value="" disabled>-- 選択 --</option>
                                            <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                                        </select>
                                    </td>

                                    <td class="border-y-2 border-gray-200 px-4 py-3 w-full">
                                        <button 
                                        :disabled="!form[dayOfWeek].start_time" 
                                        :class="{
                                            'text-white bg-red-400 hover:bg-red-500': form[dayOfWeek].start_time,
                                            'text-gray-500 bg-gray-300 cursor-not-allowed': !form[dayOfWeek].start_time
                                        }"    
                                        type="button" 
                                        @click="timeClear(form[dayOfWeek].dayOfNameNumber)" 
                                        class="border-0 py-2 px-8 focus:outline-none rounded flex justify-center ml-8">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                        <!-- 登録ボタン -->
                        <div class="mt-6">
                            <button dusk="update-defaultShift-button" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                                更新
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

