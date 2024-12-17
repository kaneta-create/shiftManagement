<script setup>
</script>

<template>
    <div v-if="$page.props.flash.status === 'success'" 
    class="bg-blue-400 text-white text-lg rounded p-4 text-center mb-2">
    {{ $page.props.flash.message }}
    </div>
    <div v-if="$page.props.flash.status === 'danger'" 
    class="bg-red-400 text-white text-lg rounded p-4 text-center mb-2">
    {{ $page.props.flash.message }}
    </div>
</template>