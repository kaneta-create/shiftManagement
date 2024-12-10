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
                    full_date:shift[0].full_date,
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
                    full_date:shift[1].full_date,
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
                    full_date:shift[2].full_date,
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
        return workTime;
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
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">シフト表</h2>
        </template> -->

        <div class="py-4 bg-gray-300">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-col text-center w-full mb-8">
                            <FlashMessage/>
                            <form @submit.prevent="updateActualShift(props.userId)">

                            <h1 class="sm:text-4xl text-3xl font-mono title-font mb-4 text-gray-900">シフト表</h1>
                            <p class="lg:w-2/3 mx-auto text-center leading-relaxed text-sm text-gray-600">変更したい日付を選択して入力してください</p>

                            
                            <div class="mb-4">
                                <div v-if="receivedShiftUpdates && receivedShiftUpdates.length > 0" class="mb-4">
                                    <h3 class="text-xl mt-8 mb-4">変更内容</h3>
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">名前</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">日付</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">出勤時間</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">退勤時間</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 text-nowrap">削除</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr  v-for="(shift, index) in receivedShiftUpdates" :key="index" :class="{ 'even:bg-gray-50 odd:bg-whit' : index % 2 == 1}">
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.employee_name }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.date }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.clock_in ? shift.clock_in : '休日' }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.clock_out ? shift.clock_out : '休日' }}</td>
                                            <td class=" border-b-2 border-gray-200 w-10 text-center">
                                                <button type="button"@click="removeShift(index)" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="flex justify-around mt-2">
                                        <button class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                                            変更を確定
                                        </button>
                                        <button type="button" @click="clearChangedShift" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                                            変更をクリア
                                        </button>
                                    </div>
                                    
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" id="printBtn" @click="printPage" class="bg-indigo-500 hover:bg-indigo-600 flex text-white py-2 px-4 rounded">
                                        印刷
                                    </button>
                                </div>
                            </div>
                            <!-- <button class="text-white bg-indigo-500 border-0 text-sm px-4 py-3 focus:outline-none hover:bg-indigo-600 rounded">登録</button> -->

                            <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-300 test-white bg-gray-100 ">
                                            <div>
                                                <select v-model="selectMonth.selectedMonth" class="border-none text-center py-1 bg-gray-50">
                                                    <option :value="props.month[0][1].firstMonth">{{ props.month[0][1].firstMonth }}</option>
                                                    <option :value="props.month[0][1].secondMonth">{{ props.month[0][1].secondMonth }}</option>
                                                    <option :value="props.month[0][1].thirdMonth">{{ props.month[0][1].thirdMonth }}</option>
                                                </select>
                                            </div>
                                            <!-- <div class="border-t border-white"></div> -->
                                            <!--<div class="bg-gray-500 text-white">名前</div> -->
                                        </th>
                                        <!-- 他のヘッダ要素 -->
                                        <th v-for="(day, index) in dayInfos" :key="index" class="bg-gray-500 border text-white border-gray-300 text-center">
                                            <div class="flex justify-center border-b hover:bg-indigo-500">
                                                <CreateShiftModal
                                                         :date="day.date" :shifts="dayShiftInfos" :full_date="day.full_date" :Ymd_date="day.Ymd_date" :userId="props.userId" :employeeInfo="props.names"  @updateShiftData="handleShiftDataUpdate"/>
                                            </div>
                                            <!-- <div class=" mt-1"></div> -->
                                            <div>{{ day.day_of_week }}</div>
                                        </th>
                                        <th class="border text-xs text-white border-gray-300 bg-gray-500">
                                            <div class="">時間</div>
                                            <div class="border-t border-gray-300 my-1"></div>
                                            <div class="vertical-text">日数</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(name, index) in props.names" :key="index" class="hover:bg-blue-200">
                                        <td class="border border-gray-300 text-sm px-2 py-2 bg-gray-500 text-white">
                                            {{ name.employee_name }}
                                        </td>
                                        <td
                                            v-for="(day, dayIndex) in dayInfos"
                                            :key="dayIndex"
                                            class="border border-gray-300 py-2 bg-gray-50"
                                        >
                                            <div v-for="(shift, shiftIndex) in dayShiftInfos" :key="shiftIndex">
                                            <div
                                                v-if="workDay(shift.employee_name, name.employee_name, day.date, shift.date)"
                                                class="text-center text-xs"
                                            >
                                                <div v-if="!(shift.clock_in === 900 && shift.clock_out === 900)">
                                                <div>{{ shift.clock_in.toString().padStart(4, '0') }}</div>
                                                <div class="border-t">{{ shift.clock_out.toString().padStart(4, '0') }}</div>
                                                </div>
                                            </div>
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 text-center px-2 py-2 bg-gray-50">
                                            <div class="text-xs">{{ totalTime(name.employee_id) }}</div>
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