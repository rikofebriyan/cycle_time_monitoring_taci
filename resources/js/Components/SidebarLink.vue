<template>
    <a href="#" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded transition">
        <span>
            <i :class="'fas fa-' + icon"></i>
        </span>
        <span class="text-sm font-medium">{{ label }}</span>
    </a>
</template>

<script setup>
defineProps({
    icon: String,
    label: String
})
</script>
