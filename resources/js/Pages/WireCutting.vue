<template>

    <Head title="Wire Cutting" />
    <AuthenticatedLayout>
        <Sidebar />

        <div class="p-2">
            <div class="grid grid-cols-8 grid-rows-8 gap-2 p-4 h-[90vh]">
                <!-- Summary Cards -->
                <div class="col-span-8 row-span-1 bg-white rounded-xl shadow-md pt-0">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 my-4">
                        <h1 class="flex items-center justify-center text-2xl font-bold">Wire Cutting</h1>
                        <div class="bg-pink-500 text-white p-4 rounded-xl shadow">
                            Last: {{ summary.last }}
                        </div>
                        <div class="bg-orange-500 text-white p-4 rounded-xl shadow">
                            Average: {{ summary.avg }}
                        </div>
                        <div class="bg-purple-500 text-white p-4 rounded-xl shadow">
                            Min: {{ summary.min }}
                        </div>
                        <div class="bg-blue-500 text-white p-4 rounded-xl shadow">
                            Max: {{ summary.max }}
                        </div>
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
import { ref, onMounted } from 'vue'
import Chart from 'chart.js/auto'
import axios from 'axios'
import Sidebar from '@/Components/Sidebar.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

// Chart Refs
const lineChartRef = ref(null)
const barChartRef = ref(null)
const dailyChartRef = ref(null)

// Data
const cuttingData = ref([])
const summary = ref({
    last: 0,
    avg: 0,
    min: 0,
    max: 0
})

// Fetch Data & Init Charts
onMounted(async () => {
    try {
        const response = await axios.get('/cutting-lead-wire')
        cuttingData.value = response.data

        const timestamps = cuttingData.value.map(item => item.TIMESTAMP)
        const ctValues = cuttingData.value.map(item => item.CT)

        // Summary Stats
        summary.value.last = ctValues[0] ?? 0
        summary.value.avg = (ctValues.reduce((a, b) => a + b, 0) / ctValues.length).toFixed(2)
        summary.value.min = Math.min(...ctValues)
        summary.value.max = Math.max(...ctValues)

        // Line Chart - CT vs Timestamp
        // Format timestamp untuk menghilangkan mili detik
        const formattedTimestamps = timestamps.map(timestamp => {
            const date = new Date(timestamp)
            return date.toLocaleString('id-ID', { // Format sesuai kebutuhan
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            })
        })

        new Chart(lineChartRef.value, {
            type: 'line',
            data: {
                labels: formattedTimestamps,  // Menggunakan timestamp yang sudah diformat
                datasets: [{
                    label: 'Cycle Time',
                    borderColor: '#3b82f6',
                    data: ctValues
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Cycle Time per Timestamp',
                        font: { size: 18 },
                        color: '#374151',
                        padding: { top: 10, bottom: 10 }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Timestamp (Tanpa Millisecond)'
                        }
                    }
                }
            }
        })


        // Bar Chart - CT Distribution (dibulatkan ke bawah ke bilangan bulat)
        const ctFrequencyMap = {}

        // Hitung frekuensi CT setelah dibulatkan
        ctValues.forEach(ct => {
            const roundedCT = Math.floor(ct) // kalau ingin dibulatkan: Math.round(ct)
            if (!ctFrequencyMap[roundedCT]) ctFrequencyMap[roundedCT] = 0
            ctFrequencyMap[roundedCT] += 1
        })

        // Urutkan nilai CT bulatnya
        const sortedCT = Object.keys(ctFrequencyMap).sort((a, b) => parseInt(a) - parseInt(b))
        const counts = sortedCT.map(key => ctFrequencyMap[key])

        new Chart(barChartRef.value, {
            type: 'bar',
            data: {
                labels: sortedCT, // CT bilangan bulat (tanpa koma)
                datasets: [{
                    label: 'Frekuensi CT (dibulatkan)',
                    backgroundColor: '#3b82f6',
                    data: counts
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribusi Frekuensi Cycle Time (Tanpa Koma)',
                        font: { size: 18 },
                        color: '#374151',
                        padding: { top: 10, bottom: 10 }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Cycle Time (dibulatkan ke bawah)'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Kejadian'
                        }
                    }
                }
            }
        })



        // Daily Chart - Rata-rata CT per tanggal
        const dailyMap = {}

        cuttingData.value.forEach(item => {
            // Ekstrak tanggal saja (tanpa waktu)
            const date = new Date(item.TIMESTAMP)
            const dateOnly = date.toISOString().split('T')[0]

            if (!dailyMap[dateOnly]) {
                dailyMap[dateOnly] = []
            }
            dailyMap[dateOnly].push(item.CT)
        })

        const dailyLabels = Object.keys(dailyMap)
        const dailyValues = dailyLabels.map(date => {
            const values = dailyMap[date]
            const average = values.reduce((a, b) => a + b, 0) / values.length
            return parseFloat(average.toFixed(1))
        })

        new Chart(dailyChartRef.value, {
            type: 'bar',
            data: {
                labels: dailyLabels, // Tanggal-tanggal tanpa jam
                datasets: [{
                    label: 'Avg CT per Day',
                    data: dailyValues,
                    backgroundColor: '#3b82f6'
                }]
            },
            options: {
                indexAxis: 'y', // y-axis = tanggal
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Average CT',
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
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Average CT'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                }
            }
        })

    } catch (error) {
        console.error('Gagal mengambil data:', error)
    }
})
</script>
