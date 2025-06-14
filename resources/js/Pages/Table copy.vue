<template>

    <Head title="Wire Cutting" />
    <AuthenticatedLayout>
        <!-- Sidebar -->
        <Sidebar />
        <div class="container mx-auto p-4 text-sm">

            <!-- Tombol untuk Download CSV -->
            <div class="my-4">
                <button @click="downloadCSV" class="bg-green-500 text-white p-2 rounded">
                    Download CSV
                </button>
                <!-- Tombol untuk Download Excel -->
                <button @click="downloadExcel" class="bg-blue-500 text-white p-2 rounded ml-2">
                    Download Excel
                </button>
            </div>

            <div class="mb-4 flex items-center justify-between">
                <input v-model="searchQuery" @input="fetchData" type="text" placeholder="Search..."
                    class="p-2 border border-gray-300 rounded" />
                <select v-model="perPage" @change="fetchData" class="p-2 border border-gray-300 rounded">
                    <option value="15">15 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>

            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('id')">ID</th>
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('timestamp')">Timestamp</th>
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('model')">Model</th>
                        <th class="border px-4 py-2 cursor-pointer" @click="sortTable('ct')">CT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in data.data" :key="index">
                        <td class="border px-4 py-2">{{ row.id }}</td>
                        <td class="border px-4 py-2">{{ row.TIMESTAMP }}</td>
                        <td class="border px-4 py-2">{{ row.MODEL }}</td>
                        <td class="border px-4 py-2">{{ row.CT }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4 flex justify-between items-center">
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

<script setup>
import { onMounted, ref } from 'vue'
import Sidebar from '@/Components/Sidebar.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<script>
import * as XLSX from 'xlsx';  // Import library xlsx

export default {
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
        };
    },
    computed: {
        pageNumbers() {
            const pages = [];
            // Menampilkan halaman sekitar current_page
            const startPage = Math.max(this.data.current_page - 2, 1);
            const endPage = Math.min(this.data.current_page + 2, this.data.last_page);
            for (let i = startPage; i <= endPage; i++) {
                pages.push(i);
            }
            return pages;
        }
    },
    methods: {
        fetchData() {
            // Fetch the data from Laravel API
            const params = {
                page: this.currentPage,
                perPage: this.perPage,
                search: this.searchQuery,
                sortBy: this.sortBy,
                sortOrder: this.sortOrder,
            };

            axios
                .get('http://localhost:8000/getdata', { params }) // Update URL sesuai dengan route API
                .then((response) => {
                    this.data = response.data;
                })
                .catch((error) => {
                    console.error("There was an error fetching the data: ", error);
                });
        },

        changePage(page) {
            if (page >= 1 && page <= this.data.last_page) {
                this.currentPage = page;
                this.fetchData();
            }
        },

        sortTable(column) {
            if (this.sortBy === column) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortOrder = 'asc';
            }
            this.fetchData();
        },

        downloadCSV() {
            // Membuat CSV dari data
            const headers = ['ID', 'Timestamp', 'Model', 'CT'];
            const rows = this.data.data.map(row => [
                row.id,
                row.TIMESTAMP,
                row.MODEL,
                row.CT
            ]);

            // Gabungkan header dan data
            const csvContent = [
                headers.join(','),  // Menambahkan header
                ...rows.map(row => row.join(','))  // Menambahkan baris data
            ].join('\n');  // Pisahkan baris dengan newline

            // Membuat link untuk download CSV
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'data.csv';  // Nama file CSV
            link.click();  // Klik link untuk mendownload
        },

        downloadExcel() {
            // Mengonversi data ke format Excel
            const ws = XLSX.utils.json_to_sheet(this.data.data.map(row => ({
                ID: row.id,
                Timestamp: row.TIMESTAMP,
                Model: row.MODEL,
                CT: row.CT
            })));

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

            // Menyimpan dan mengunduh file Excel
            XLSX.writeFile(wb, 'data.xlsx');
        }
    },
    mounted() {
        this.fetchData();
    },
};
</script>
