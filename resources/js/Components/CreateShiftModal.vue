<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const isShow = ref(false);
const toggleStatus = () => {
    isShow.value = !isShow.value
}

const props = defineProps({
    date: String,
    full_date: String,
    Ymd_date: String,
    userId : Number,
    shifts: Array,
    employeeInfo: Array
});

const filteredshifts = props.shifts.filter(shift => shift.full_date == props.Ymd_date && shift.employee_id == props.userId && shift.clock_in != shift.clock_out);
console.log(filteredshifts);
function formattedTime(time){
  if(time == 2400){
    return '24:00';
  }else{
    const hours = String(Math.floor(time / 100)).padStart(2, '0');
    const minutes = String(time % 100).padStart(2, '0');  
    return `${hours}:${minutes}`;
  }
    
  
}
let shiftInfos = [];
if (filteredshifts.length > 0) {
  if(filteredshifts[0].clock_out == 2400){
    filteredshifts[0].clock_out = 2359
  }
  shiftInfos = [
    {
      clock_in: formattedTime(filteredshifts[0].clock_in),
      clock_out: formattedTime(filteredshifts[0].clock_out) ,
      full_date: filteredshifts[0].full_date,
      isDayOff: '0'
    }
  ]
} else {
  shiftInfos = [
    {
      clock_in: null,
      clock_out: null,
      isDayOff: ''
    }
  ]
}

const form = reactive({
    isDayOff: shiftInfos[0].isDayOff ? '0' : '1',
    clock_in: shiftInfos[0].clock_in ? shiftInfos[0].clock_in : '',
    clock_out: shiftInfos[0].clock_out ? shiftInfos[0].clock_out : '',
    date:'',
    employee_name:'',
    employee_id: ''
})

watch(() => form.employee_name, (newName) => {
    const selectedEmployee = props.employeeInfo.find(employee => employee.employee_name === newName);
    if (selectedEmployee) {
        form.employee_id = selectedEmployee.employee_id;  // employee_id を更新
    }
});
onMounted(() => {
    form.date = props.Ymd_date;
});
watch(() => props.Ymd_date, (newDate) => {
    form.date = newDate;
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

const emit = defineEmits(['updateShiftData']);
const shiftUpdates = [];
const shiftDataUpdate = () => {
    if (form.isDayOff === '') {
        console.error('出勤か休日のいずれかを選択してください。');
        alert('出勤か休日のいずれかを選択してください。')
        return;
    }
    if(form.isDayOff == 0 && form.clock_in == ''){
      // console.error('出勤時間を入力してください。')
      alert('出勤時間を入力してください')
      return;
    } else if (form.isDayOff == 0 && form.clock_out == ''){
      // console.error('退勤時間を入力してください。')
      alert("退勤時間を入力してください")
      return;
    };
    let shiftData = {
        isDayOff: form.isDayOff,
        clock_in: form.clock_in,
        clock_out: form.clock_out,
        date: form.date,
        employee_id: form.employee_id,
        employee_name: form.employee_name
    };
        
    shiftUpdates.push(JSON.parse(JSON.stringify(shiftData)));
    toggleStatus(); // モーダルを閉じる
    // console.log(shiftUpdates);

    emit('updateShiftData', shiftUpdates);  
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
    times.push('23:59');
    return times;
};

const timeOptions = generateTimeOptions();
</script>

<template>
     <div v-show="isShow" class="modal" id="modal-1"> 
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="bg-white w-2/5 p-8 rounded-lg shadow-sm items-center" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="">
            <h2 class="text-2xl font-bold text-center border-b pb-2 text-indigo-500" id="modal-1-title">
                シフト変更
            </h2>
            </header>
            <main class="modal__content" id="modal-1-content">
            <div class="flex flex-col space-y-4">
                <!-- 日付 -->
                <div class="mb-4">
                <!-- <label class="text-gray-700 text-nowrap w-1/3 text-left ml-14">日付:</label> -->
                    <p class="text-center text-lg">{{ props.full_date }} ({{ props.shifts[0].day_of_week }}曜日)</p>
                </div>

                <!-- 従業員名 -->
                <div class="flex items-center">
                <label for="employee_name" class="text-gray-700 text-nowrap w-1/3 text-center">従業員名 ：</label>
                <select id="employee_name" name="employee_name" v-model="form.employee_name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" disabled>選択</option>
                    <option v-for="name in props.employeeInfo" :key="name.employee_id" :value="name.employee_name">
                    {{ name.employee_name }}
                    </option>
                </select>
                </div>

                <!-- 出勤 or 休日 -->
                <div class="flex items-center">
                <label class="text-gray-700 text-nowrap w-1/4 text-center ml-1">出勤 / 休日 ：</label>
                <div class="ml-1 w-full flex justify-around">
                    <div class="flex items-center">
                        <input name="isDayOff" type="radio" value="0" v-model="form.isDayOff" class="mr-1">
                        <span class="mr-4">出勤</span>
                    </div>
                    <div class="flex items-center">
                        <input name="isDayOff" type="radio" value="1" v-model="form.isDayOff" class="mr-1">
                        <span>休日</span>
                    </div>
                    
                </div>
                </div>

                <!-- 出勤時間 -->
                <div class="flex items-center">
                <label for="clock_in" class="text-gray-700 text-nowrap w-1/3 text-center">出勤時間 ：</label>
                <select v-model="form.clock_in" :disabled="form.isDayOff === '1'" :class="{'bg-gray-200': form.isDayOff === '1', 'cursor-not-allowed': form.isDayOff === '1'}" id="clock_in" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" disabled>選択</option>
                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                </select>
                </div>

                <!-- 退勤時間 -->
                <div class="flex items-center">
                <label for="clock_out" class="text-gray-700 text-nowrap w-1/3 text-center">退勤時間 ：</label>
                <select v-model="form.clock_out" :disabled="form.isDayOff === '1'" :class="{'bg-gray-200': form.isDayOff === '1', 'cursor-not-allowed': form.isDayOff === '1'}" id="clock_out" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" disabled>選択</option>
                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                </select>
                </div>

                <!-- エラー表示 -->
                <div v-if="form.clock_in && form.clock_out && form.clock_in >= form.clock_out" class="text-red-500 text-sm">
                出勤時間は退勤時間より前に設定してください。
                </div>
            </div>
            </main>
            <footer class="modal__footer mt-4">
            <div class="flex justify-between">
                <button @click="toggleStatus" type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
                <button type="button" @click="shiftDataUpdate" class="text-white bg-indigo-500 border-0 text-sm px-4 py-3 focus:outline-none hover:bg-indigo-600 rounded">保存</button>
            </div>
            </footer>
        </div>

        </div>
    </div>
    <!-- <td 
    @click="new Date(props.Ymd_date) >= new Date().setHours(0,0,0,0) ? toggleStatus() : null"  class="text-center">{{ props.date }}</td> -->
    <td>
        <button @click="new Date(props.Ymd_date) >= new Date().setHours(0,0,0,0) ? toggleStatus()  : null"  class="text-center" type="button">{{ props.date }}</button>
    </td>
</template>