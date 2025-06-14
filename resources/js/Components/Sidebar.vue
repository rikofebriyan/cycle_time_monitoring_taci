<template>
    <div>
        <!-- Hamburger Button -->
        <button @click="toggleSidebar" class="p-2 m-3 z-50 fixed top-0 left-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Overlay -->
        <div v-if="isOpen" @click="closeSidebar" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity">
        </div>

        <!-- Sidebar -->
        <aside :class="[
            'fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-50 transition-transform duration-300',
            isOpen ? 'translate-x-0' : '-translate-x-full'
        ]">
            <div class="p-4 flex justify-between items-center border-b">
                <h2 class="text-lg font-bold">TACI</h2>
                <button @click="closeSidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="mt-4 space-y-1 px-4">

                <SidebarLink :href="'/tabel_ct'" icon="table" label="Database" />
                <SidebarLink :href="'/CrimpingConnector'" icon="tools" label="Crimping Connector" />
                <SidebarLink :href="'/CrimpingEyelet'" icon="anchor" label="Crimping Eyelet" />
                <SidebarLink :href="'/WireCutting'" icon="cut" label="Wire Cutting" />
            </nav>


            <!-- <div class="mt-6 px-4">
                <button class="w-full bg-blue-500 text-white py-2 rounded mb-2">Documentation</button>
                <button class="w-full bg-purple-600 text-white py-2 rounded">Free Download</button>
            </div> -->
        </aside>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import SidebarLink from '@/Components/SidebarLink.vue'

const isOpen = ref(false)
const toggleSidebar = () => (isOpen.value = !isOpen.value)
const closeSidebar = () => (isOpen.value = false)
</script>
