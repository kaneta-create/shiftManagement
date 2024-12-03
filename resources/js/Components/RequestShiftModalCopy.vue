<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const isShow = ref(false);
const toggleStatus = () => {
    isShow.value = !isShow.value;
}

const props = defineProps({
    date: String,
    full_date: String,
    Ymd_date: String,
    userId: Number
});

// 変更を保持するためのオブジェクト
const pendingUpdates = reactive({});

const form = reactive({
    isDayOff: '',
    clock_in: '',
    clock_out: '',
    date: '',
    employee_id: props.userId
});

onMounted(() => {
    form.date = props.Ymd_date;
});

watch(() => props.Ymd_date, (newDate) => {
    form.date = newDate;
});

const emit = defineEmits(['update:authority']);

// 一時的に変更を保持
const storePendingUpdate = () => {
    pendingUpdates[form.date] = { ...form };
    toggleStatus(); // モーダルを閉じる
};

// 一括送信
const updateActualShifts = () => {
    Inertia.put(route('actualShifts.bulkUpdate'), { shifts: pendingUpdates });
};

watch(form, (newForm) => {
    if (newForm.clock_in && newForm.clock_out && newForm.clock_in >= newForm.clock_out) {
        console.error(`${form.date} の出勤時間は退勤時間より前に設定してください。`);
    }
});
</script>

<template>
    <div v-show="isShow" class="modal" id="modal-1"> 
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
                <h2 class="modal__title" id="modal-1-title">シフト変更</h2>
            </header>
            <form @submit.prevent="storePendingUpdate">
                <main class="modal__content" id="modal-1-content">
                    <div class="flex flex-col space-y-2">
                        <div>
                            <p>{{ props.full_date }}</p>
                            <div class="mr-2 mt-4">
                                <input name="isDayOff" type="radio" value=0 v-model="form.isDayOff"><span class="mr-2">出勤</span>
                                <input name="isDayOff" type="radio" value=1 v-model="form.isDayOff"><span>休日</span>
                            </div>
                            <div class="mt-2">
                                <label for="clock_in">出勤時間</label>
                                <input :disabled="form.isDayOff == 1" id="clock_in" name="clock_in" type="time" v-model="form.clock_in">
                            </div>
                            <div class="mt-2">
                                <label for="clock_out">退勤時間</label>
                                <input :disabled="form.isDayOff == 1" id="clock_out" name="clock_out" type="time" v-model="form.clock_out">
                            </div>
                            <div v-if="form.clock_in && form.clock_out && form.clock_in >= form.clock_out" class="text-red-500">出勤時間は退勤時間より前に設定してください。</div>
                        </div>
                    </div>
                </main>
                <footer class="modal__footer">
                    <div class="flex justify-between">
                        <button @click="toggleStatus" type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
                        <button class="text-white bg-indigo-500 border-0 text-sm px-4 py-3 focus:outline-none hover:bg-indigo-600 rounded">変更を保存</button>
                        <!-- <button @click="updateActualShifts" class="bg-green-500 text-white px-4 py-2 mt-4">一括更新</button> -->
                    
                    </div>
                </footer>
            </form>
        </div>
        </div>
    </div>
    
    <!-- シフト変更の一括送信ボタン -->

    <td @click="new Date(props.Ymd_date) >= new Date().setHours(0,0,0,0) ? toggleStatus() : null" class="text-center">{{ props.date }}</td>
</template>
