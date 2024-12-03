<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    day_of_week_name: Object,
    userRole: Object,
    errors: Object
});
const dayOfWeekArray = Object.values(props.day_of_week_name);

const form = reactive({
    "employee_number" : "",
    '月曜日': { start_time: '', end_time: '' ,dayOfNameNumber : '1'},
    '火曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : '2'},
    '水曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : '3'},
    '木曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : '4'},
    '金曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : '5'},
    '土曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : '6'},
    '日曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : '7'}
})

// onMounted(() => {
//       dayOfWeekArray.forEach((dayOfWeek, key) => {
//         form[dayOfWeek].dayOfNameNumber = key+1; // keyを
//       });
// });
   
watch(form, (newForm) => {
    Object.keys(newForm).forEach(day => {
        const startTime = newForm[day].start_time;
        const endTime = newForm[day].end_time;

        if (startTime && endTime && startTime >= endTime) {
          console.error(`${day} の出勤時間は退勤時間より前に設定してください。`);
        }
        
      }); 
})
const notClick = () => {
    if (startTime && endTime && startTime >= endTime) {
        return true;
    }
}

const storeDefaultShift = () => {
    router.post('/defaultShifts', form);
}

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
    <Head title="シフト登録" />

    <AuthenticatedLayout :userRole="props.userRole.authority">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">シフト登録</h2>
        </template> -->

        <div class="py-4 bg-gray-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h1 class="sm:text-4xl text-center text-3xl font-mono title-font mb-4 text-gray-900">シフト登録</h1>
                        <p class="lg:w-2/3 mx-auto text-center leading-relaxed text-sm text-gray-600">社員番号を入力して時間を選択してください</p>

                    </div>
                    <form @submit.prevent="storeDefaultShift">
                        <!-- 社員番号 -->
                        <div class="text-center mb-4 mt-8">
                            <label for="employee_number" class="mr-2">社員番号:</label>
                            <input type="text" id="employee_number" required name="employee_number" v-model="form.employee_number" class="border border-gray-500 p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 rounded"/>
                            <div v-if="props.errors.employee_number" class="text-red-600">
                                *{{ props.errors.employee_number }}
                            </div>
                        </div>

                        <!-- 曜日リスト -->
                        <div class="lg:w-3/4 w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                                <tr>
                                <th class="px-6 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-tl rounded-bl">
                                    曜日
                                </th>
                                <th
                                    class="px-6 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500"
                                >
                                    出勤時間
                                </th>
                                <th
                                    class="px-6 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-tr rounded-br"
                                >
                                    退勤時間
                                </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                v-for="(dayOfWeek, key) in props.day_of_week_name"
                                :key="key"
                                class="even:bg-gray-50 odd:bg-white"
                                >
                                <td class="border-y-2 border-gray-200 px-6 py-3">{{ dayOfWeek }}</td>
                                <td class="border-y-2 border-gray-200 px-6 py-3 relative">
                                    <select
                                    id="clock_in"
                                    v-model="form[dayOfWeek].start_time"
                                    class="w-full border border-gray-400 rounded p-2 focus:ring-2 focus:ring-indigo-400"
                                    >
                                    <option value="" disabled>-- 選択 --</option>
                                    <option
                                        v-for="time in timeOptions"
                                        :key="time"
                                        :value="time"
                                    >{{ time }}</option>
                                    </select>
                                    <div
                                    v-if="
                                        form[dayOfWeek].start_time &&
                                        form[dayOfWeek].end_time &&
                                        form[dayOfWeek].start_time >= form[dayOfWeek].end_time
                                    "
                                    class="absolute bg-yellow-400 text-white text-xs rounded p-1 mt-1 -top-8 left-0 text-nowrap"
                                    >
                                    出勤時間は退勤時間より前に設定してください。
                                    </div>
                                </td>
                                <td class="border-y-2 border-gray-200 px-6 py-3">
                                    <select
                                    id="clock_out"
                                    v-model="form[dayOfWeek].end_time"
                                    class="w-full border border-gray-400 rounded p-2 focus:ring-2 focus:ring-indigo-400"
                                    >
                                    <option value="" disabled>-- 選択 --</option>
                                    <option
                                        v-for="time in timeOptions"
                                        :key="time"
                                        :value="time"
                                    >{{ time }}</option>
                                    </select>
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>

                        <!-- 登録ボタン -->
                        <div class="mt-6">
                            <button
                            id="register-button"
                            dusk="register-button"
                            class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                            >
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
