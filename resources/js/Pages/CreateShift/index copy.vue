<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import CreateShiftModal from '@/Components/CreateShiftModal.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

const props = defineProps({
    shifts: Array,
    month: Array,
    firstMonth: Array,
    names : Array,
    totalWorkingTimes: Object,
    userId : Number
})


const workDay = (employee_name, name, date, attendance_date) => {
    if(name === employee_name && date === attendance_date){
        return true;
    }
}
const selectMonth = reactive({
  selectedMonth: props.month[0][1].firstMonth
});

let dayInfos = ref([]);
let dayShiftInfos = ref([]);
const updateDayInfos = () => {
    dayInfos.value = [];
    dayShiftInfos.value = [];
    props.month.forEach(day => {
        if (selectMonth.selectedMonth === props.month[0][1].firstMonth) {
            if (day[1]) {
                dayInfos.value.push({
                    Ymd_date: day[1].Ymd_date,
                    full_date: day[1].full_date,
                    date: day[1].date,
                    day_of_week: day[1].day_of_week,
                    year: day[1].year,
                    firstMonth: day[1].firstMonth,
                    secondMonth: day[1].secondMonth,
                    thirdMonth: day[1].thirdMonth
                });
            }
        } else if (selectMonth.selectedMonth === props.month[0][1].secondMonth) {
            if (day[2]) {
                dayInfos.value.push({
                    Ymd_date: day[2].Ymd_date,
                    full_date: day[2].full_date,
                    date: day[2].date,
                    day_of_week: day[2].day_of_week,
                    year: day[2].year,
                    firstMonth: day[2].firstMonth,
                    secondMonth: day[2].secondMonth,
                    thirdMonth: day[2].thirdMonth
                });
            }
        } else {
            if (day[3]) {
                dayInfos.value.push({
                    Ymd_date: day[3].Ymd_date,
                    full_date: day[3].full_date,
                    date: day[3].date,
                    day_of_week: day[3].day_of_week,
                    year: day[3].year,
                    firstMonth: day[3].firstMonth,
                    secondMonth: day[3].secondMonth,
                    thirdMonth: day[3].thirdMonth
                });
            }
        }
    });
};


const updateShift = () => {
    dayShiftInfos.value = [];
    props.shifts.forEach((shift, index) => {
        if (selectMonth.selectedMonth === props.month[0][1].firstMonth) {
            if (shift[0]) {
                dayShiftInfos.value.push({
                    employee_id:shift[0].employee_id,
                    clock_in:shift[0].clock_in,
                    clock_out:shift[0].clock_out,
                    day_of_week:shift[0].day_of_week,
                    employee_name:shift[0].employee_name,
                    date:shift[0].date                  
                });
            }
        } else if (selectMonth.selectedMonth === props.month[0][1].secondMonth) {
            if (shift[1]) {
                dayShiftInfos.value.push({
                    employee_id:shift[1].employee_id,
                    clock_in:shift[1].clock_in,
                    clock_out:shift[1].clock_out,
                    day_of_week:shift[1].day_of_week,
                    employee_name:shift[1].employee_name,
                    date:shift[1].date     
                });
            }
        } else {
            if (shift[2]) {
                dayShiftInfos.value.push({
                    employee_id:shift[2].employee_id,
                    clock_in:shift[2].clock_in,
                    clock_out:shift[2].clock_out,
                    day_of_week:shift[2].day_of_week,
                    employee_name:shift[2].employee_name,
                    date:shift[2].date     
                });
            }
        }
    });
};

watch(() => selectMonth.selectedMonth, () => {
    updateDayInfos();
    updateShift(); // 実行したい別の関数
}, { immediate: true });

console.log(props.totalWorkingTimes[2][1]['total_working_hours'])
const workTime = ref(['']);

const totalTime = employee_id => {
    let workTime = [];
        if (selectMonth.selectedMonth === props.month[0][1].firstMonth) {
            workTime = props.totalWorkingTimes[1][employee_id]['total_working_hours']
        } else if (selectMonth.selectedMonth === props.month[0][1].secondMonth) {
            workTime = props.totalWorkingTimes[2][employee_id]['total_working_hours']
        } else {
            workTime = props.totalWorkingTimes[3][employee_id]['total_working_hours']
        }
        return Math.floor(workTime);
}
const totalWorkDay = ref(['']);

const countTotalWorkingDay = (employee_id) => {
    let totalWorkDay = [];
        if (selectMonth.selectedMonth === props.month[0][1].firstMonth) {
            totalWorkDay = props.totalWorkingTimes[1][employee_id]['attendance_count']
        } else if (selectMonth.selectedMonth === props.month[0][1].secondMonth) {
            totalWorkDay = props.totalWorkingTimes[2][employee_id]['attendance_count']
        } else {
            totalWorkDay = props.totalWorkingTimes[3][employee_id]['attendance_count']
        }
    return totalWorkDay;
}
</script>


<template>
    <Head title="シフト表" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">シフト表</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col text-centerw-full mb-8">
                            <FlashMessage/>
                            <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">シフト表</h1>
                            <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-300">
                                            <div>
                                                <select v-model="selectMonth.selectedMonth" class="border-none text-center py-1">
                                                    <option :value="props.month[0][1].firstMonth">{{ props.month[0][1].firstMonth }}</option>
                                                    <option :value="props.month[0][1].secondMonth">{{ props.month[0][1].secondMonth }}</option>
                                                    <option :value="props.month[0][1].thirdMonth">{{ props.month[0][1].thirdMonth }}</option>
                                                </select>
                                            </div>
                                            <div class="border-t border-gray-300 "></div>
                                            <div>名前</div>
                                        </th>
                                        <!-- 他のヘッダ要素 -->
                                        <th v-for="(day, index) in dayInfos" :key="index" class="border border-gray-300 text-center">
                                            <div class="text-center">
                                                {{ day.date }}
                                            </div>
                                            <div class="border-t border-gray-300 mt-1"></div>
                                            <div>{{ day.day_of_week }}</div>
                                        </th>
                                        <th class="border text-xs border-gray-300">
                                            <div class="">時間</div>
                                            <div class="border-t border-gray-300 mt-1"></div>
                                            <div class="vertical-text">日数</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(name, index) in props.names" :key="index">
                                        <td class="border border-gray-300 text-sm px-2 py-2">{{ name.employee_name }}</td>
                                        <td v-for="(day, dayIndex) in dayInfos" :key="dayIndex" class="border border-gray-30 py-2">
                                            <div v-for="(shift, shiftIndex) in dayShiftInfos" :key="shiftIndex">
                                                <div v-if="workDay(shift.employee_name, name.employee_name, day.date, shift.date)" class="text-center text-xs">
                                                    <div v-if="!(shift.clock_in === 900 && shift.clock_out === 900)">
                                                        <CreateShiftModal
                                                         :date="day.date" :full_date="day.full_date" :Ymd_date="day.Ymd_date" :userId="props.userId" :clock_in_time="shift.clock_in" :clock_out_time="shift.clock_out" :employee_id="name.employee_id"/>

                                                    </div>    
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 text-center px-2 py-2">
                                            <div class="text-xs">{{ totalTime(name.employee_id)}}</div>
                                            <div class="border-t text-xs">{{ countTotalWorkingDay(name.employee_id) }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>