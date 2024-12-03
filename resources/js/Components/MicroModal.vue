<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const isShow = ref(false);
const toggleStatus = () => {
    isShow.value = !isShow.value
}

const props = defineProps({
    authority: String,
    id: Number,
    role: String
});

const form = reactive({
    'authority': null,
    'id': null,
    'role' : null
})

onMounted(() => {
    form.id = props.id,
    form.role = props.role,
    form.authority = props.authority
 });

const emit = defineEmits(['update:authority']);

const updateAuthority = id => {
    Inertia.patch(route('admins.update', {admin:id}), form);
    // toggleStatus()
}

</script>

<template>
     <div v-show="isShow" class="modal" id="modal-1"> 
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
            <h2 class="modal__title" id="modal-1-title">
                権限変更
            </h2>
            <!-- <button class="modal__close" aria-label="Close modal" data-micromodal-close></button> -->
            </header>
            <form @submit.prevent="updateAuthority(props.id)">
            <main class="modal__content" id="modal-1-content">
                <div class="flex flex-col space-y-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="authority" value="1" v-model="form.authority" v-if="form.authority==1" class="form-radio text-blue-600">
                    <span class="ml-2">一般ユーザー権限</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="authority" value="2"  v-model="form.authority" class="form-radio text-blue-600">
                    <span class="ml-2">シフト作成者権限</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="authority" value="3"  v-model="form.authority" class="form-radio text-blue-600">
                    <span class="ml-2">管理者権限</span>
                </label>
                <input type="hidden" name="id" v-model="form.id" value="props.id">
                <input type="hidden" name="role" v-model="form.role" value="props.role">
                </div>
            </main>
            <footer class="modal__footer">
                <button @click="toggleStatus" type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
                <button class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">更新</button>
            </footer>
            </form>
        </div>
        </div>
    </div>
    <td @click="toggleStatus">{{ props.authority }}</td>
</template>