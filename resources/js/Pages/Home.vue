<template>

    <Head title="Wire Cutting" />
    <AuthenticatedLayout>
        <Sidebar />

        <div class="p-2">
            <div class="grid grid-cols-8 grid-rows-8 gap-2 p-4 h-[90vh]">
                <!-- Summary_01 Cards -->
                <div class="col-span-8 row-span-1 bg-white rounded-xl shadow-md pt-0">
                    <div class="flex justify-center items-center my-7">
                        <h1 class="text-4xl font-bold text-center">VISUALIZATION CYCLE TIME STATOR LINE</h1>
                    </div>

                </div>

                <!-- Daily Chart -->
                <div class="col-span-2 row-span-7 bg-white rounded-xl shadow-md p-4">
                    <canvas ref="dailyChartRef" class="w-full h-full"></canvas>
                </div>

                <!-- Chart Per Cycle -->
                <!-- Chart Per Cycle -->
                <div
                    class="col-start-3 col-end-5 row-start-2 row-end-6 bg-white rounded-xl shadow-md p-4 flex flex-col justify-between">
                    <div class="flex flex-col gap-2 mt-4">
                        <h1 class="text-2xl font-bold text-center">Wire Cutting</h1>

                        <div class="bg-pink-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Last:</span>
                            <span>{{ summary_01.last }}</span>
                        </div>
                        <div class="bg-orange-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Average:</span>
                            <span>{{ summary_01.avg }}</span>
                        </div>
                        <div class="bg-purple-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Min:</span>
                            <span>{{ summary_01.min }}</span>
                        </div>
                        <div class="bg-blue-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Max:</span>
                            <span>{{ summary_01.max }}</span>
                        </div>
                    </div>
                </div>



                <!-- Chart Per Cycle -->
                <div class="col-start-5 col-end-7 row-start-2 row-end-6 bg-white rounded-xl shadow-md p-4">
                    <div class="flex flex-col gap-2 mt-4">
                        <h1 class="text-2xl font-bold text-center">Crimping Connector</h1>

                        <div class="bg-pink-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Last:</span>
                            <span>{{ summary_02.last }}</span>
                        </div>
                        <div class="bg-orange-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Average:</span>
                            <span>{{ summary_02.avg }}</span>
                        </div>
                        <div class="bg-purple-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Min:</span>
                            <span>{{ summary_02.min }}</span>
                        </div>
                        <div class="bg-blue-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Max:</span>
                            <span>{{ summary_02.max }}</span>
                        </div>
                    </div>
                </div>

                <!-- Chart Per Cycle -->
                <div class="col-start-7 col-end-9 row-start-2 row-end-6 bg-white rounded-xl shadow-md p-4">
                    <div class="flex flex-col gap-2 mt-4">
                        <h1 class="text-2xl font-bold text-center">Crimping Eyelet</h1>

                        <div class="bg-pink-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Last:</span>
                            <span>{{ summary_03.last }}</span>
                        </div>
                        <div class="bg-orange-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Average:</span>
                            <span>{{ summary_03.avg }}</span>
                        </div>
                        <div class="bg-purple-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Min:</span>
                            <span>{{ summary_03.min }}</span>
                        </div>
                        <div class="bg-blue-500 text-white text-2xl p-4 rounded-xl shadow flex justify-between">
                            <span>Max:</span>
                            <span>{{ summary_03.max }}</span>
                        </div>
                    </div>
                </div>

                <!-- Distribution Chart -->
                <div class="col-start-3 col-end-5 row-start-6 row-end-9 bg-white rounded-xl shadow-md p-4">
                    <canvas ref="barChartRef" class="w-full h-full"></canvas>
                </div>

                <!-- Distribution Chart -->
                <div class="col-start-5 col-end-7 row-start-6 row-end-9 bg-white rounded-xl shadow-md p-4">
                    <canvas ref="barChartRef2" class="w-full h-full"></canvas>
                </div>

                <!-- Distribution Chart -->
                <div class="col-start-7 col-end-9 row-start-6 row-end-9 bg-white rounded-xl shadow-md p-4">
                    <canvas ref="barChartRef3" class="w-full h-full"></canvas>
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
const barChartRef = ref(null)
const barChartRef2 = ref(null)
const barChartRef3 = ref(null)

const dailyChartRef = ref(null)

// Data
const cuttingData_01 = ref([])
const cuttingData_02 = ref([])
const cuttingData_03 = ref([])
const summary_01 = ref({
    last: 0,
    avg: 0,
    min: 0,
    max: 0
})

const summary_02 = ref({
    last: 0,
    avg: 0,
    min: 0,
    max: 0
})

const summary_03 = ref({
    last: 0,
    avg: 0,
    min: 0,
    max: 0
})

// Fetch Data & Init Charts
onMounted(async () => {
    try {
        // Fetch data from the API Cutting Lead Wire
        const response_01 = await axios.get('/cutting-lead-wire')
        cuttingData_01.value = response_01.data
        const timestamps_01 = cuttingData_01.value.map(item => item.TIMESTAMP)
        const ctValues_01 = cuttingData_01.value.map(item => item.CT)

        // Fetch data from the API api_crimpingConnector
        const response_02 = await axios.get('/api_crimpingConnector')
        cuttingData_02.value = response_02.data
        const timestamps_02 = cuttingData_02.value.map(item => item.TIMESTAMP)
        const ctValues_02 = cuttingData_02.value.map(item => item.CT)

        // Fetch data from the API api_crimpingEyelet
        const response_03 = await axios.get('/api_crimpingEyelet')
        cuttingData_03.value = response_03.data
        const timestamps_03 = cuttingData_03.value.map(item => item.TIMESTAMP)
        const ctValues_03 = cuttingData_03.value.map(item => item.CT)

        // Summary_01 Stats
        summary_01.value.last = ctValues_01[0] ?? 0
        summary_01.value.avg = (ctValues_01.reduce((a, b) => a + b, 0) / ctValues_01.length).toFixed(2)
        summary_01.value.min = Math.min(...ctValues_01)
        summary_01.value.max = Math.max(...ctValues_01)

        // Summary_02 Stats
        summary_02.value.last = ctValues_02[0] ?? 0
        summary_02.value.avg = (ctValues_02.reduce((a, b) => a + b, 0) / ctValues_02.length).toFixed(2)
        summary_02.value.min = Math.min(...ctValues_02)
        summary_02.value.max = Math.max(...ctValues_02)

        // Summary_03 Stats
        summary_03.value.last = ctValues_03[0] ?? 0
        summary_03.value.avg = (ctValues_03.reduce((a, b) => a + b, 0) / ctValues_03.length).toFixed(2)
        summary_03.value.min = Math.min(...ctValues_03)
        summary_03.value.max = Math.max(...ctValues_03)





        // Bar Chart Distribusi CT
        const ctFrequencyMap_01 = {}
        ctValues_01.forEach(ct => {
            const roundedCT_01 = Math.floor(ct)
            if (!ctFrequencyMap_01[roundedCT_01]) ctFrequencyMap_01[roundedCT_01] = 0
            ctFrequencyMap_01[roundedCT_01] += 1
        })

        const sortedCT_01 = Object.keys(ctFrequencyMap_01).sort((a, b) => parseInt(a) - parseInt(b))
        const counts_01 = sortedCT_01.map(key => ctFrequencyMap_01[key])

        new Chart(barChartRef.value, {
            type: 'bar',
            data: {
                labels: sortedCT_01,
                datasets: [{
                    label: 'Frekuensi CT',
                    backgroundColor: '#3b82f6',
                    data: counts_01
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribusi Frekuensi Cycle Time',
                        font: { size: 18 },
                        color: '#374151',
                        padding: { top: 10, bottom: 10 }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Cycle Time'
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

        // Bar Chart Distribusi CT
        const ctFrequencyMap_02 = {}
        ctValues_02.forEach(ct => {
            const roundedCT_02 = Math.floor(ct)
            if (!ctFrequencyMap_02[roundedCT_02]) ctFrequencyMap_02[roundedCT_02] = 0
            ctFrequencyMap_02[roundedCT_02] += 1
        })

        const sortedCT_02 = Object.keys(ctFrequencyMap_02).sort((a, b) => parseInt(a) - parseInt(b))
        const counts_02 = sortedCT_02.map(key => ctFrequencyMap_02[key])

        new Chart(barChartRef2.value, {
            type: 'bar',
            data: {
                labels: sortedCT_02,
                datasets: [{
                    label: 'Frekuensi CT',
                    backgroundColor: '#3b82f6',
                    data: counts_02
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribusi Frekuensi Cycle Time',
                        font: { size: 18 },
                        color: '#374151',
                        padding: { top: 10, bottom: 10 }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Cycle Time'
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

        // Bar Chart Distribusi CT
        const ctFrequencyMap_03 = {}
        ctValues_03.forEach(ct => {
            const roundedCT_03 = Math.floor(ct)
            if (!ctFrequencyMap_03[roundedCT_03]) ctFrequencyMap_03[roundedCT_03] = 0
            ctFrequencyMap_03[roundedCT_03] += 1
        })

        const sortedCT_03 = Object.keys(ctFrequencyMap_03).sort((a, b) => parseInt(a) - parseInt(b))
        const counts_03 = sortedCT_03.map(key => ctFrequencyMap_03[key])

        new Chart(barChartRef3.value, {
            type: 'bar',
            data: {
                labels: sortedCT_03,
                datasets: [{
                    label: 'Frekuensi CT',
                    backgroundColor: '#3b82f6',
                    data: counts_03
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribusi Frekuensi Cycle Time',
                        font: { size: 18 },
                        color: '#374151',
                        padding: { top: 10, bottom: 10 }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Cycle Time'
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

        // Daily Chart
        // ===== DAILY CHART 3 API - MULTIPLE BARS PER DATE =====

        // 1. Gabungkan data harian dari semua proses
        const dailyMap = {}

        function groupByDate(dataArray, key) {
            dataArray.forEach(item => {
                const date = new Date(item.TIMESTAMP).toISOString().split('T')[0]
                if (!dailyMap[date]) dailyMap[date] = { cutting: [], connector: [], eyelet: [] }

                if (key === 'cutting') dailyMap[date].cutting.push(item.CT)
                if (key === 'connector') dailyMap[date].connector.push(item.CT)
                if (key === 'eyelet') dailyMap[date].eyelet.push(item.CT)
            })
        }

        groupByDate(cuttingData_01.value, 'cutting')
        groupByDate(cuttingData_02.value, 'connector')
        groupByDate(cuttingData_03.value, 'eyelet')

        // 2. Siapkan label tanggal dan dataset untuk chart
        const dailyLabels = Object.keys(dailyMap).sort()

        const cuttingDataset = []
        const connectorDataset = []
        const eyeletDataset = []

        dailyLabels.forEach(date => {
            const { cutting, connector, eyelet } = dailyMap[date]

            const average = (arr) => arr.length ? arr.reduce((a, b) => a + b, 0) / arr.length : 0

            cuttingDataset.push(Number(average(cutting).toFixed(1)))
            connectorDataset.push(Number(average(connector).toFixed(1)))
            eyeletDataset.push(Number(average(eyelet).toFixed(1)))
        })

        // 3. Render chart dengan 3 batang per tanggal
        new Chart(dailyChartRef.value, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [
                    {
                        label: 'Wire Cutting',
                        data: cuttingDataset,
                        backgroundColor: '#f97316'
                    },
                    {
                        label: 'Crimping Connector',
                        data: connectorDataset,
                        backgroundColor: '#3b82f6'
                    },
                    {
                        label: 'Crimping Eyelet',
                        data: eyeletDataset,
                        backgroundColor: '#a855f7'
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Average Cycle Time per Process',
                        font: { size: 18 },
                        color: '#374151',
                        padding: { top: 10, bottom: 10 }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        },
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Average CT'
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
