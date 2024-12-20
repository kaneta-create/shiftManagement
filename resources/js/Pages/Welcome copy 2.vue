<script setup>
import { ref } from 'vue';
import Shepherd from 'shepherd.js';

// Shepherd.js インスタンス
const tour = ref(null);

const startTour = () => {
  // Shepherd.js の初期化
  tour.value = new Shepherd.Tour({
    defaultStepOptions: {
      cancelIcon: { enabled: true },
      classes: 'shepherd-theme-arrows',
      scrollTo: { behavior: 'smooth', block: 'center' },
    },
  });

  // ステップ 1 を追加
  tour.value.addStep({
    id: 'step1',
    text: 'このボタンをクリックすると始まります。',
    attachTo: { element: '#step1', on: 'bottom' },
    buttons: [
      {
        text: '次へ',
        action: tour.value.next,
      },
    ],
  });

  // ステップ 2 を追加
  tour.value.addStep({
    id: 'step2',
    text: '次に、このセクションを確認してください。',
    attachTo: { element: '#step2', on: 'top' },
    buttons: [
      {
        text: '戻る',
        action: tour.value.back,
      },
      {
        text: '終了',
        action: tour.value.complete,
      },
    ],
  });

  // ツアー開始
  tour.value.start();
};
</script>

<template>
    <div>
      <button @click="startTour">ツアーを開始</button>
      <div id="step1" class="step">Step 1: ここをクリックしてください。</div>
      <div id="step2" class="step">Step 2: ここに移動します。</div>
    </div>
</template>

<style>
.step {
  margin: 20px;
  padding: 10px;
  border: 1px solid #ddd;
  background-color: #f9f9f9;
}
</style>