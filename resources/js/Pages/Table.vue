<template>

    <Head title="Database" />
    <AuthenticatedLayout>
        <!-- Sidebar -->
        <Sidebar />

        <div class="container mx-auto p-4 text-sm">

            <div class="mb-4 flex items-center justify-between">
                <input v-model="searchQuery" @input="fetchData" type="text" placeholder="Search..."
                    class="p-2 border border-gray-300 rounded" />

                <!-- Tombol untuk Download CSV -->
                <div>
                    <button @click="downloadCSV" class="bg-green-500 text-white p-2 rounded">
                        Download CSV
                    </button>
                    <!-- Tombol untuk Download Excel -->
                    <button @click="downloadExcel" class="bg-blue-500 text-white p-2 rounded ml-2">
                        Download Excel
                    </button>
                </div>
            </div>

            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('id')">ID</th>
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('tanggal')">Tanggal</th>
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('waktu')">Waktu</th>
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('model')">Model</th>
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('ct')">CT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in data.data" :key="index">
                        <td class="border px-4 py-2">{{ row.id }}</td>
                        <td class="border px-4 py-2">
                            <div>{{ row.date }}</div>
                        </td>

                        <td class="border px-4 py-2">
                            <div>{{ row.time }}</div>
                        </td>
                        <td class="border px-4 py-2">{{ row.MODEL }}</td>
                        <td class="border px-4 py-2">{{ row.CT }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4 flex justify-between items-center">

                <select v-model="perPage" @change="fetchData" class="p-2 border border-gray-300 rounded">
                    <option value="15">15 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
                <div class="text-sm text-gray-600">
                    Showing {{ data.from }} to {{ data.to }} of {{ data.total }} entries
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="changePage(data.current_page - 1)" :disabled="data.current_page === 1"
                        class="bg-blue-500 text-white p-2 rounded disabled:bg-gray-300">
                        Previous
                    </button>

                    <!-- Halaman tengah: Menampilkan nomor halaman -->
                    <div class="flex space-x-2">
                        <button v-for="page in pageNumbers" :key="page" @click="changePage(page)"
                            :class="{ 'bg-blue-500 text-white': page === data.current_page, 'bg-gray-300': page !== data.current_page }"
                            class="p-2 rounded">
                            {{ page }}
                        </button>
                    </div>

                    <button @click="changePage(data.current_page + 1)" :disabled="data.current_page === data.last_page"
                        class="bg-blue-500 text-white p-2 rounded disabled:bg-gray-300">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script>
import Sidebar from '@/Components/Sidebar.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import * as XLSX from 'xlsx';  // Import library xlsx

export default {
    components: {
        Sidebar,
        AuthenticatedLayout
    },
    data() {
        return {
            searchQuery: '',
            perPage: 15,
            currentPage: 1,
            data: {
                data: [],
                from: 0,
                to: 0,
                total: 0,
                current_page: 1,
                last_page: 1,
            },
            sortBy: 'id',
            sortOrder: 'desc',
        }
    },
    computed: {
        pageNumbers() {
            const pages = []
            const startPage = Math.max(this.data.current_page - 2, 1)
            const endPage = Math.min(this.data.current_page + 2, this.data.last_page)
            for (let i = startPage; i <= endPage; i++) {
                pages.push(i)
            }
            return pages
        }
    },
    methods: {
        fetchData() {
            const params = {
                page: this.currentPage,
                perPage: this.perPage,
                search: this.searchQuery,
                sortBy: this.sortBy,
                sortOrder: this.sortOrder,
            }

            axios
                .get('/getdata', { params })
                .then((response) => {
                    // Pisahkan timestamp menjadi dua kolom: tanggal dan jam
                    this.data = response.data;
                    this.data.data.forEach(row => {
                        const timestamp = new Date(row.TIMESTAMP);

                        // Format tanggal (YYYY-MM-DD)
                        row.date = timestamp.toISOString().split('T')[0];

                        // Format waktu (HH:mm:ss.SSS) dan hilangkan 'Z'
                        row.time = timestamp.toISOString().split('T')[1].slice(0, 8); // Mengambil hanya HH:mm:ss
                    });
                })
                .catch((error) => {
                    console.error("There was an error fetching the data: ", error)
                })
        },


        changePage(page) {
            if (page >= 1 && page <= this.data.last_page) {
                this.currentPage = page
                this.fetchData()
            }
        },

        sortTable(column) {
            if (this.sortBy === column) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
            } else {
                this.sortBy = column
                this.sortOrder = 'asc'
            }
            this.fetchData()
        },

        downloadCSV() {
            const headers = ['ID', 'Date', 'Time', 'Model', 'CT']
            const rows = this.data.data.map(row => [
                row.id,
                row.date,  // Tanggal
                row.time,  // Jam
                row.MODEL,
                row.CT
            ])

            const csvContent = [
                headers.join(','),
                ...rows.map(row => row.join(','))
            ].join('\n')

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
            const link = document.createElement('a')
            link.href = URL.createObjectURL(blob)
            link.download = 'ct_stator.csv'
            link.click()
        },

        downloadExcel() {
            const grouped = {};
            const summaryData = [];

            // 1. Kelompokkan data berdasarkan MODEL
            this.data.data.forEach(row => {
                const model = row.MODEL;
                if (!grouped[model]) {
                    grouped[model] = [];
                }
                grouped[model].push({
                    ID: row.id,
                    Date: row.date,
                    Time: row.time,
                    Model: model,
                    CT: parseFloat(row.CT) // pastikan CT adalah number
                });
            });

            const wb = XLSX.utils.book_new();

            // 2. Proses tiap grup
            for (const model in grouped) {
                const rows = grouped[model];
                const ctValues = rows
                    .map(r => parseFloat(r.CT))
                    .filter(ct => !isNaN(ct));

                const min = Math.min(...ctValues);
                const max = Math.max(...ctValues);
                const avg = ctValues.length > 0
                    ? parseFloat((ctValues.reduce((a, b) => a + b, 0) / ctValues.length).toFixed(2))
                    : 0;
                const count = ctValues.length;

                // Tambahkan ke ringkasan global
                summaryData.push({
                    Model: model,
                    Count: count,
                    'Min CT': min,
                    'Max CT': max,
                    'Avg CT': avg
                });

                // Gabungkan data asli dan summary
                const finalRows = [...rows,
                { ID: '', Date: '', Time: '', Model: 'Min', CT: min },
                { ID: '', Date: '', Time: '', Model: 'Max', CT: max },
                { ID: '', Date: '', Time: '', Model: 'Average', CT: avg },
                { ID: '', Date: '', Time: '', Model: 'Count', CT: count }
                ];

                const ws = XLSX.utils.json_to_sheet(finalRows);
                XLSX.utils.book_append_sheet(wb, ws, model);
            }

            // 3. Tambahkan sheet ringkasan
            const summarySheet = XLSX.utils.json_to_sheet(summaryData);
            XLSX.utils.book_append_sheet(wb, summarySheet, 'Summary');
            wb.SheetNames.unshift(wb.SheetNames.pop()); // Pindahkan Summary ke awal

            // 4. Simpan file
            XLSX.writeFile(wb, 'summary_ct_stator.xlsx');
        }

    },
    mounted() {
        this.fetchData()
    }
}
</script>
