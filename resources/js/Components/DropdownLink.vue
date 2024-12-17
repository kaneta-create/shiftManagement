<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link
        class="block w-full px-4 py-2 text-left text-sm leading-5 text-white hover:text-gray-700"
    >
        <slot />
    </Link>
</template>
