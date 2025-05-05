<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <!-- Sidebar -->
        <Sidebar />

        <!-- Konten utama -->
        <div class="p-2">

            <div class="grid grid-cols-8 grid-rows-8 gap-2 p-4 min-h-screen">

                <!-- Row 1, Col 1-8 -->
                <div class="col-span-8 row-span-1 bg-red-300 rounded p-4">Card: Row 1, Col 1-8</div>

                <!-- Row 2-8, Col 1-2 -->
                <div class="col-span-2 row-span-7 bg-blue-300 rounded p-4">Card: Row 2-8, Col 1-2</div>

                <!-- Row 2-5, Col 3-8 -->
                <div class="col-start-3 col-end-9 row-start-2 row-end-6 bg-green-300 rounded p-4">Card: Row 2-5, Col 3-8
                </div>

                <!-- Row 6-8, Col 3-8 -->
                <div class="col-start-3 col-end-9 row-start-6 row-end-9 bg-yellow-300 rounded p-4">Card: Row 6-8, Col
                    3-8</div>

            </div>








            <!-- Summary Cards -->
            <!-- <div class="grid grid-cols-2 md:grid-cols-5 gap-4 my-4">
                <h1 class="flex items-center justify-center text-2xl font-bold mb-4">Dashboard</h1>
                <div class="bg-pink-500 text-white p-4 rounded-xl shadow">Last</div>
                <div class="bg-orange-500 text-white p-4 rounded-xl shadow">Average</div>
                <div class="bg-purple-500 text-white p-4 rounded-xl shadow">Min</div>
                <div class="bg-blue-500 text-white p-4 rounded-xl shadow">Max</div>
            </div> -->

            <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> -->
            <!-- Chart Cards -->
            <div class="bg-white rounded-xl shadow-md p-4">
                    <h2 class="text-lg font-semibold text-orange-600 mb-2">Real Time Cycle Time</h2>
                    <canvas id="lineChart" width="400" height="200"></canvas>
                </div>

                <div class="bg-white rounded-xl shadow-md p-4">
                    <h2 class="text-lg font-semibold text-pink-600 mb-2">Real Time Cycle Time</h2>
                    <canvas id="barChart" width="400" height="200"></canvas>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import Chart from 'chart.js/auto'
import Sidebar from '@/Components/Sidebar.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

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
