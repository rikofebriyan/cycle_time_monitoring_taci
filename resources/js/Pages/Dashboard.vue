<template>
    <div class="relative min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <Sidebar />

        <!-- Konten utama -->
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Chart Cards -->
                <div class="bg-white rounded-xl shadow-md p-4">
                    <h2 class="text-lg font-semibold text-orange-600 mb-2">Sales Value</h2>
                    <canvas id="lineChart" width="400" height="200"></canvas>
                </div>

                <div class="bg-white rounded-xl shadow-md p-4">
                    <h2 class="text-lg font-semibold text-pink-600 mb-2">Sales Value</h2>
                    <canvas id="barChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-pink-500 text-white p-4 rounded-xl shadow">Traffic</div>
                <div class="bg-orange-500 text-white p-4 rounded-xl shadow">New Users: 2,356</div>
                <div class="bg-purple-500 text-white p-4 rounded-xl shadow">Sales: 924</div>
                <div class="bg-blue-500 text-white p-4 rounded-xl shadow">Performance</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import Chart from 'chart.js/auto'
import Sidebar from '@/Components/Sidebar.vue'

onMounted(() => {
    const line = document.getElementById('lineChart')
    const bar = document.getElementById('barChart')

    new Chart(line, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [
                {
                    label: '2021',
                    borderColor: '#3b82f6',
                    data: [65, 59, 80, 81, 56, 55, 70]
                },
                {
                    label: '2020',
                    borderColor: '#f59e0b',
                    data: [45, 39, 60, 47, 88, 60, 80]
                }
            ]
        }
    })

    new Chart(bar, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [
                {
                    label: '2021',
                    backgroundColor: '#3b82f6',
                    data: [40, 55, 75, 81, 56]
                },
                {
                    label: '2020',
                    backgroundColor: '#ef4444',
                    data: [30, 25, 40, 60, 80]
                }
            ]
        }
    })
})
</script>
