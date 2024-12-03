<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import CreateShiftModal from '@/Components/CreateShiftModal.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    shifts: Array,
    month: Array,
    firstMonth: Array,
    names : Array,
    totalWorkingTimes: Object,
    userId : Number,
    userRole: Object
})

const show = ref(false);
const isModalShow = () => {
    show.value = !show.value
};
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

const printPage = () => {
    window.print();
};

const receivedShiftUpdates = ref([]);
const handleShiftDataUpdate = (updatedShifts) => {
  receivedShiftUpdates.value.push(...updatedShifts);
  console.log('親コンポーネントで受け取ったデータ: ', receivedShiftUpdates.value);
};

const updateActualShift = (id) => {
    Inertia.put(route('createShifts.store', {createShift: id}), 
        { shiftUpdates: receivedShiftUpdates.value },
        {
            onBefore: () => {
                confirm('変更しますか？');
            },
        }
    );
};

const clearChangedShift = () => {
    receivedShiftUpdates.value = '';
}

function removeShift(index) {
  receivedShiftUpdates.value.splice(index, 1);
}
</script>

<style>
@media print{
    nav {
        display: none;
    }

    #printBtn {
        display: none;
    }

    h2{
        display: none;
    }

}
</style>

<template>
    <Head title="シフト表" />

    <AuthenticatedLayout :userRole="props.userRole.authority">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">シフト表</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col text-centerw-full mb-8">
                            <FlashMessage/>
                            <form @submit.prevent="updateActualShift(props.userId)">

                            <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">シフト表</h1>
                            
                            <div class="mb-4">
                                <div v-if="receivedShiftUpdates && receivedShiftUpdates.length > 0" class="mb-4">
                                    <h3>変更内容</h3>
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">日付</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出勤時間</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">退勤時間</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">削除</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr  v-for="(shift, index) in receivedShiftUpdates" :key="index">
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.employee_name }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.date }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.clock_in ? shift.clock_in : '休日' }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.clock_out ? shift.clock_out : '休日' }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 w-10 text-center">
                                                <button type="button"@click="removeShift(index)" class="bg-red-500 text-white py-2 px-4 rounded">削除</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="flex justify-around mt-2">
                                        <button class="bg-green-500 text-white py-2 px-4 rounded">
                                            変更を確定
                                        </button>
                                        <button type="button" @click="clearChangedShift" class="bg-red-500 text-white py-2 px-4 rounded">
                                            変更をクリア
                                        </button>
                                    </div>
                                    
                                </div>
                                <button type="button" id="printBtn" @click="printPage" class="bg-blue-500 text-white py-2 px-4 rounded">
                                    印刷
                                </button>
                            </div>
                            <!-- <button class="text-white bg-indigo-500 border-0 text-sm px-4 py-3 focus:outline-none hover:bg-indigo-600 rounded">登録</button> -->

                            <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-300 bg-gray-100">
                                            <div>
                                                <select v-model="selectMonth.selectedMonth" class="border-none text-center py-1 bg-gray-100">
                                                    <option :value="props.month[0][1].firstMonth">{{ props.month[0][1].firstMonth }}</option>
                                                    <option :value="props.month[0][1].secondMonth">{{ props.month[0][1].secondMonth }}</option>
                                                    <option :value="props.month[0][1].thirdMonth">{{ props.month[0][1].thirdMonth }}</option>
                                                </select>
                                            </div>
                                            <div class="border-t border-gray-300 "></div>
                                            <div>名前</div>
                                        </th>
                                        <!-- 他のヘッダ要素 -->
                                        <th v-for="(day, index) in dayInfos" :key="index" class="bg-gray-100 border border-gray-300 text-center">
                                            <div class="text-center">
                                                <CreateShiftModal
                                                         :date="day.date" :full_date="day.full_date" :Ymd_date="day.Ymd_date" :userId="props.userId" :employeeInfo="props.names"  @updateShiftData="handleShiftDataUpdate"/>
                                            </div>
                                            <div class="border-t border-gray-300 mt-1"></div>
                                            <div>{{ day.day_of_week }}</div>
                                        </th>
                                        <th class="border text-xs border-gray-300 bg-gray-100">
                                            <div class="">時間</div>
                                            <div class="border-t border-gray-300 mt-1"></div>
                                            <div class="vertical-text">日数</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(name, index) in props.names" :key="index">
                                        <td class="border border-gray-300 text-sm px-2 py-2 bg-gray-100">{{ name.employee_name }}</td>
                                        <td v-for="(day, dayIndex) in dayInfos" :key="dayIndex" class="border border-gray-30 py-2">
                                            <div v-for="(shift, shiftIndex) in dayShiftInfos" :key="shiftIndex">
                                                <div v-if="workDay(shift.employee_name, name.employee_name, day.date, shift.date)" class="text-center text-xs">
                                                    <div v-if="!(shift.clock_in === 900 && shift.clock_out === 900)">
                                                        <div>{{ shift.clock_in }}</div>
                                                        <div class="border-t">{{ shift.clock_out }}</div>
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
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>