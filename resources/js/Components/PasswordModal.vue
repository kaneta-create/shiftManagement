<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia'

const isShow = ref(false);
const toggleStatus = () => {
    isShow.value = !isShow.value
}


</script>

<template>
     <div v-show="isShow" class="modal" id="modal-1"> 
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
            <h2 class="modal__title" id="modal-1-title">
                パスワード登録
            </h2>
            <!-- <button class="modal__close" aria-label="Close modal" data-micromodal-close></button> -->
            </header>
            <main class="modal__content" id="modal-1-content">
                
            </main>
            <footer class="modal__footer">
                <button @click="toggleStatus" type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
                <button class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">更新</button>
            </footer>
        </div>
        </div>
    </div>
    <td @click="toggleStatus">jjj</td>
    
</template>