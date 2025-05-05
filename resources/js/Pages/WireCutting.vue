<template>

    <Head title="Wire Cutting" />
    <AuthenticatedLayout>
        <!-- Sidebar -->
        <Sidebar />

        <!-- Konten utama -->
        <div class="p-2">
            <div class="grid grid-cols-8 grid-rows-8 gap-2 p-4 h-[90vh]">
                <!-- Row 1, Col 1-8 -->
                <div class="col-span-8 row-span-1 bg-white rounded-xl shadow-md pt-0">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 my-4">
                        <h1 class="flex items-center justify-center text-2xl font-bold">Wire Cutting</h1>
                        <div class="bg-pink-500 text-white p-4 rounded-xl shadow">Last</div>
                        <div class="bg-orange-500 text-white p-4 rounded-xl shadow">Average</div>
                        <div class="bg-purple-500 text-white p-4 rounded-xl shadow">Min</div>
                        <div class="bg-blue-500 text-white p-4 rounded-xl shadow">Max</div>
                    </div>
                </div>

                <!-- Daily Chart -->
                <div class="col-span-2 row-span-7 bg-white rounded-xl shadow-md p-4">
                    <canvas ref="dailyChartRef" class="w-full h-full"></canvas>
                </div>

                <!-- Chart Per Cycle -->
                <div class="col-start-3 col-end-9 row-start-2 row-end-6 bg-white rounded-xl shadow-md p-4">
                    <canvas ref="lineChartRef" class="w-full h-full"></canvas>
                </div>

                <!-- Distribution Chart -->
                <div class="col-start-3 col-end-9 row-start-6 row-end-9 bg-white rounded-xl shadow-md p-4">
                    <canvas ref="barChartRef" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import { onMounted, ref } from 'vue'
import Chart from 'chart.js/auto'
import Sidebar from '@/Components/Sidebar.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const lineChartRef = ref(null)
const barChartRef = ref(null)
const dailyChartRef = ref(null)

onMounted(() => {
    // Line Chart
    new Chart(lineChartRef.value, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [
                {
                    label: '2021',
                    borderColor: '#3b82f6',
                    data: [65, 59, 80, 81, 56, 55, 70]
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Line Chart - Cycle Time per Month',
                    font: { size: 18 },
                    color: '#374151',
                    padding: { top: 10, bottom: 10 }
                }
            }
        }
    })

    new Chart(barChartRef.value, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [{
                label: '2021',
                backgroundColor: '#3b82f6',
                data: [40, 55, 75, 81, 56, 68, 70, 60, 80, 90]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Bar Chart - Comparison of Cycle Time',
                    font: { size: 18 },
                    color: '#374151',
                    padding: { top: 10, bottom: 10 }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    barPercentage: 1.0,
                    categoryPercentage: 1.0,
                }
            }
        }
    })


    // Horizontal Bar Chart (Daily)
    new Chart(dailyChartRef.value, {
        type: 'bar',
        data: {
            labels: ['10 Apr 2025', '11 Apr 2025', '12 Apr 2025', '13 Apr 2025'],
            datasets: [{
                label: 'Daily Output',
                data: [120, 90, 150, 70],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Daily Output by Machine',
                    font: { size: 18 },
                    color: '#374151',
                    padding: { top: 10, bottom: 10 }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    })
})
</script>
