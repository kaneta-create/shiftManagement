<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    day_of_week_name: Object
});
const dayOfWeekArray = Object.values(props.day_of_week_name);

const form = reactive({
    "employee_number" : "",
    '月曜日': { start_time: '', end_time: '' ,dayOfNameNumber : ''},
    '火曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '水曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '木曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '金曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '土曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''},
    '日曜日': { start_time: '', end_time: ''  ,dayOfNameNumber : ''}
})

onMounted(() => {
      dayOfWeekArray.forEach((dayOfWeek, key) => {
        form[dayOfWeek].dayOfNameNumber = key+1; // keyを
      });
});
   
watch(form, (newForm) => {
    Object.keys(newForm).forEach(day => {
        const startTime = newForm[day].start_time;
        const endTime = newForm[day].end_time;

        if (startTime && endTime && startTime >= endTime) {
          console.error(`${day} の出勤時間は退勤時間より前に設定してください。`);
        }
      }); 
})

const storeDefaultShift = () => {
    Inertia.post('/defaultShifts', form);
}


</script>

<template>
    <Head title="DefaultShift" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">シフト登録</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="storeDefaultShift">
                            <div class="text-center">
                                <label for="employee_number" class="mr-2">社員番号:</label>
                                <input type="text" id="employee_number" required name="employee_number" v-model="form.employee_number">
                            </div>
                            
                            <!-- 曜日ごとの時間設定 -->
                            <div class="mt-2">
                                <div v-for="(dayOfWeek, key) in props.day_of_week_name" :key="key">
                                    <div class="flex justify-between items-center">
                                        <label :for="dayOfWeek" class="block text-sm font-medium text-gray-700">{{ dayOfWeek }} 出勤時間：　</label>
                                        出勤時間：　<input type="time" step="900" :id="dayOfWeek" :name="'start_time_' + dayOfWeek" v-model="form[dayOfWeek].start_time" class="mr-36 mb-2">
                                        退勤時間：　<input type="time" step="900" min="form[dayOfWeek].start_time" :id="dayOfWeek" :name="'end_time_' + dayOfWeek" v-model="form[dayOfWeek].end_time" class="mr-36 mb-2">
                                        
                                    </div>
                                    <div v-if="form[dayOfWeek].start_time && form[dayOfWeek].end_time && form[dayOfWeek].start_time >= form[dayOfWeek].end_time"
                                            class="text-red-500">出勤時間は退勤時間より前に設定してください。</div>
                                </div>
                            </div>
                            
                            
                            <button class="mt-3 flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
