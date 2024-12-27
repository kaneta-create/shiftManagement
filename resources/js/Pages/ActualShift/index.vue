<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import RequestShiftModal from '@/Components/RequestShiftModal.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Inertia } from '@inertiajs/inertia'
import { constant } from 'lodash';

const props = defineProps({
    shifts: Array,
    month: Array,
    firstMonth: Array,
    names : Array,
    totalWorkingTimes: Object,
    userId : Number,
    userName: String,
    userRole: Object,
    errors: Object
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
        // console.log(shift[0])
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
        // return Math.floor(workTime);
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
    router.put(
        `/actualShifts/${id}`,  // URLを直接指定
        { shiftUpdates: receivedShiftUpdates.value },  // 送信するデータ
        {
            onBefore: () => {
                confirm('変更しますか？');  // 変更前の確認
            },
            onFinish: () => {
                clearChangedShift();  // 変更内容をクリア
                  // ページを再読み込み
            }
        }
    )
    location.reload();
};

const clearChangedShift = () => {
    receivedShiftUpdates.value = '';
    location.reload();
}

function removeShift(index) {
  receivedShiftUpdates.value.splice(index, 1);
//   receivedShiftUpdates.value = [];
}

const isHovered = ref(false); // ホバー状態を管理

</script>
<style>
@media print{
    nav {
        display: none;
    }

    #explanation,#change_shift, #printBtn, h2, nav{
        display: none;
    }
     
   table{
    color:black;
   }

   #th1, #th2, #th3, #td1, #td2, #td3{
    color: black;
    border-color:  rgb(160, 160, 160);
   }

}
</style>
<template>
    <Head title="シフト表" />

    <AuthenticatedLayout :userRole="props.userRole.authority">
        <!-- <template id="title" #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">シフト表</h2>
        </template> -->

        <div class="py-4 bg-gray-300">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden sm:rounded-lg">
                    <div id="printB" class="p-6 text-gray-900">
                        <div class="flex flex-col text-center w-full mb-8">
                            <FlashMessage/>
                            <form @submit.prevent="updateActualShift(props.userId)">
                            <h1 class="sm:text-4xl text-3xl font-mono title-font mb-4 text-gray-900">シフト表</h1>
                            <p id="explanation" class="lg:w-2/3 mx-auto text-center leading-relaxed text-sm text-gray-600">変更したい日付を選択して入力してください</p>
                            
                            <div id="change_shift" class="mb-4">
                                <div v-if="receivedShiftUpdates && receivedShiftUpdates.length > 0" class="mb-4">
                                    <h3 class="mt-8 mb-4 text-xl">変更内容</h3>
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-l">名前</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">日付</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">出勤時間</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500">退勤時間</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 text-nowrap rounded-r">
                                                削除
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(shift, index) in receivedShiftUpdates" :key="index" :class="{ 'bg-gray-100' : index % 2 == 1}">
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ props.userName }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.date }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.clock_in ? shift.clock_in : '休日' }}</td>
                                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ shift.clock_out ? shift.clock_out : '休日' }}</td>
                                            <td class="border-b-2 border-gray-200 w-10 text-center">
                                                <button type="button"@click="removeShift(index)" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-nowrap">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>

                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="flex justify-around mt-2">
                                        <button class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                                            変更を確定
                                        </button>
                                        <button type="button" @click="clearChangedShift" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
                                            変更をクリア
                                        </button>
                                    </div>
                                    
                                </div>
                                <div v-if="receivedShiftUpdates.length == 0" class="flex justify-end mt-4">
                                    <button type="button" id="printBtn" @click="printPage" class="bg-indigo-600 hover:bg-indigo-700 flex text-white py-2 px-4 rounded">
                                        印刷
                                    </button>
                                </div>
                                
                            </div>
                            <div class="print_shift">
                            <div class="overflow-x-auto">
                                  <!-- ホバー時に表示するツールチップ -->
                                <div v-if="isHovered" class="absolute bg-black text-white text-xs rounded py-1 px-2">
                                    クリックして月を変更できます
                                </div>
                            <table id="table1"class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th id="th1" class="border border-gray-300 bg-gray-100">
                                            <div>
                                               
                                                <select v-model="selectMonth.selectedMonth" @mouseover="isHovered = true" @mouseleave="isHovered = false" class="border-none text-center py-1 bg-gray-100">
                                                    <option :value="props.month[0][1].firstMonth">{{ props.month[0][1].firstMonth }}</option>
                                                    <option :value="props.month[0][1].secondMonth">{{ props.month[0][1].secondMonth }}</option>
                                                    <option :value="props.month[0][1].thirdMonth">{{ props.month[0][1].thirdMonth }}</option>
                                                </select>
                                            </div>
                                            <!-- <div class="border-t border-gray-300 "></div>
                                            <div>名前</div> -->
                                        </th>
                                        <!-- 他のヘッダ要素 -->
                                        
                                        <th id="th2" v-for="(day, index) in dayInfos" :key="index"  class="border border-gray-300 text-center bg-gray-500 text-white">
                                            <div :class="{'hover:bg-indigo-500' : day.date > new Date().getDate()}" class="border-b flex justify-center items-center">
                                                <RequestShiftModal 
                                                     :date="day.date" :shifts="dayShiftInfos" :day_of_week="day.day_of_week" :full_date="day.full_date" :Ymd_date="day.Ymd_date" :userId="props.userId" @updateShiftData="handleShiftDataUpdate"/>
                                            </div>
                                            <div id="date" class=" text-center">{{ day.day_of_week }}</div>
                                        </th>
                                        <th id="th3" class="border text-xs border-gray-300 bg-gray-500 text-white">
                                            <div class="flex justify-around items-center">時間</div>
                                            <div class="border-t border-gray-300 my-1"></div>
                                            <div class="vertical-text flex justify-around">日数</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(name, index) in props.names" :key="index" class="hover:bg-blue-200">
                                        <td id="td1" class="border border-gray-300 text-sm px-2 py-2 bg-gray-500 text-white">{{ name.employee_name }}</td>
                                        <td id="td2" v-for="(day, dayIndex) in dayInfos" :key="dayIndex" class="border border-gray-30 py-2">
                                            <div v-for="(shift, shiftIndex) in dayShiftInfos" :key="shiftIndex">
                                                <div v-if="workDay(shift.employee_name, name.employee_name, day.date, shift.date)" class="text-center text-xs">
                                                    <div v-if="!(shift.clock_in === 900 && shift.clock_out === 900)">
        
                                                    <!-- <div>{{ shift.clock_in === 0 ? '0000' : shift.clock_in  }}</div> -->
                                                    <div>{{ shift.clock_in.toString().padStart(4, '0') }}</div>
                                                    <div id="clockOut"class="border-t">{{ shift.clock_out.toString().padStart(4, '0') }}</div>
                                                    </div>    
                                                </div>
                                            </div>
                                        </td>
                                        <td id="td3" class="border border-gray-300 text-center px-2 py-2">
                                            <div class="text-xs">{{ totalTime(name.employee_id)}}</div>
                                            <div id="employee_name" class="border-t text-xs">{{ countTotalWorkingDay(name.employee_id) }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>