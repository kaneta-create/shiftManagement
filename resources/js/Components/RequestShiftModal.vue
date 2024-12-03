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
    errors: Object
});
// console.log(props.shifts, props.Ymd_date)
const form = reactive({
    isDayOff: '',
    clock_in: '',
    clock_out: '',
    date:'',
    employee_id: props.userId
})

console.log(props.isDayOff)

onMounted(() => {
    form.date = props.Ymd_date;
});


watch(() => props.Ymd_date, (newDate) => {
    form.date = newDate;
});
const emit = defineEmits(['updateShiftData']);
const shiftUpdates = [];
const shiftDataUpdate = () => {
    if (form.isDayOff === '') {
        console.error('出勤か休日のいずれかを選択してください。');
        return;
    }
    let shiftData = {
        isDayOff: form.isDayOff,
        clock_in: form.clock_in,
        clock_out: form.clock_out,
        date: form.date,
        employee_id: form.employee_id
    };
        
    shiftUpdates.push(JSON.parse(JSON.stringify(shiftData)));
    toggleStatus(); // モーダルを閉じる
    // console.log(shiftUpdates);

    emit('updateShiftData', shiftUpdates);  
};


watch(form, (newForm) => {
    Object.keys(newForm).forEach(day => {
        const startTime = newForm[day].start_time;
        const endTime = newForm[day].end_time;
            if (startTime && endTime && startTime >= endTime) {
                console.error(`${day} の出勤時間は退勤時間より前に設定してください。`);
            }        
        }); 
})

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
    <div v-show="isShow" class="modal" id="modal-1">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
          <header class="modal__header">
            <h2 class="modal__title text-xl text-center font-bold" id="modal-1-title">
              シフト変更
            </h2>
          </header>
          <main class="modal__content" id="modal-1-content">
            <div class="flex flex-col space-y-4">
              <p class="text-gray-600">{{ props.full_date }}</p>
  
              <div class="flex items-center space-x-4">
                <label class="flex items-center">
                  <input name="isDayOff" type="radio" value="0" v-model="form.isDayOff" class="mr-2">
                  <span>出勤</span>
                </label>
                <label class="flex items-center">
                  <input name="isDayOff" type="radio" value="1" v-model="form.isDayOff" class="mr-2">
                  <span>休日</span>
                </label>
              </div>
  
              <div v-if="form.isDayOff === ''" class="text-red-500 text-sm">
                *出勤か休日のいずれかを選択してください。
              </div>
  
              <div class="mt-4">
                <label for="clock_in" class="block text-gray-700">出勤時間</label>
                <select v-model="form.clock_in" id="clock_in" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                  <option value="" disabled>-- 選択 --</option>
                  <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                </select>
              </div>
  
              <div class="mt-4">
                <label for="clock_out" class="block text-gray-700">退勤時間</label>
                <select v-model="form.clock_out" id="clock_out" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                  <option value="" disabled>-- 選択 --</option>
                  <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                </select>
              </div>
  
              <div v-if="form.clock_in && form.clock_out && form.clock_in >= form.clock_out && form.clock_out !== '00:00'" class="text-red-500 text-sm">
                *出勤時間は退勤時間より前に設定してください。
              </div>
            </div>
          </main>
  
          <footer class="modal__footer mt-6">
            <div class="flex justify-end space-x-4">
              <button @click="toggleStatus" type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 focus:outline-none">
                閉じる
              </button>
              <button id="store" type="button" @click="shiftDataUpdate" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 focus:outline-none">
                保存
              </button>
            </div>
          </footer>
        </div>
      </div>
    </div>
    
    <td class="text-center">
        <button @click="new Date(props.Ymd_date) >= new Date().setHours(0,0,0,0) ? toggleStatus()  : null" type="button">{{ props.date }}</button>
    </td>
  </template>
  
