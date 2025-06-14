<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabelController extends Controller
{
    public function getData(Request $request)
    {
        // Menangani parameter pagination, pencarian, dan pengurutan
        $perPage = $request->input('perPage', 10); // Default per page 10 data
        $page = $request->input('page', 1); // Default halaman pertama
        $searchQuery = $request->input('search', ''); // Default kosong
        $sortBy = $request->input('sortBy', 'id'); // Default urutkan berdasarkan ID
        $sortOrder = $request->input('sortOrder', 'desc'); // Default urutan descending

        // Menyiapkan query
        $query = DB::table('01_cutting_lead_wire_ct');

        // Jika ada query pencarian
        if (!empty($searchQuery)) {
            $query->where('model', 'like', "%$searchQuery%")
                ->orWhere('timestamp', 'like', "%$searchQuery%");
        }

        // Menambahkan pengurutan
        $query->orderBy($sortBy, $sortOrder);

        // Menjalankan query dengan pagination
        $data = $query->paginate($perPage);

        // Mengembalikan data dalam format JSON
        return response()->json($data);
    }
}
