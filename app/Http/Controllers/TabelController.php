<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TabelController extends Controller
{
    public function getData(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $searchQuery = $request->input('search', '');
        $sortBy = $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'desc');
        $type = $request->input('type', '01'); // default ke 01 jika tidak dikirim

        // Mapping tipe ke nama tabel
        $tableMap = [
            '01' => '01_cutting_lead_wire_ct',
            '02' => '02_crimping_connector_ct',
            '03' => '03_crimping_eyelet_ct',
        ];

        // Validasi type agar hanya dari yang diperbolehkan
        if (!array_key_exists($type, $tableMap)) {
            return response()->json(['error' => 'Invalid type parameter'], 400);
        }

        $tableName = $tableMap[$type];

        // Buat query builder
        $query = DB::table(DB::raw("[$tableName]")); // SQL Server: nama tabel harus di-wrap []

        // Filter pencarian jika ada
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('model', 'like', "%$searchQuery%")
                    ->orWhere('timestamp', 'like', "%$searchQuery%");
            });
        }

        // ✅ Filter CT antara 100 dan 400
        $query->whereBetween('CT', [100, 400]);

        // Urutkan
        $query->orderBy($sortBy, $sortOrder);

        // Ambil data paginasi
        $data = $query->paginate($perPage);

        return response()->json($data);
    }



    public function cuttingLeadWire(Request $request)
    {
        $query = DB::table('01_cutting_lead_wire_ct')
            ->select('id', 'TIMESTAMP', 'JIG_NUMBER', 'MODEL', 'CT')
            ->whereBetween('CT', [100, 400]);

        if ($request->has('date')) {
            // Jika ada tanggal dari request, filter berdasarkan tanggal tersebut (tanpa jam)
            $date = Carbon::parse($request->input('date'))->startOfDay();
            $query->whereDate('TIMESTAMP', $date);
        } else {
            // Jika tidak ada tanggal, ambil data 7 hari terakhir
            $query->where('TIMESTAMP', '>=', Carbon::now()->subDays(30));
        }

        // Ambil data, urutkan terbaru
        $rawData = $query->orderByDesc('TIMESTAMP')->get();

        // Bagi nilai CT dan bulatkan 1 angka di belakang koma
        $data = $rawData->map(function ($item) {
            $item->CT = round($item->CT / 10, 1);
            return $item;
        });

        return response()->json($data);
    }


    public function api_crimpingConnector(Request $request)
    {
        $query = DB::table('02_crimping_connector_ct')
            ->select('id', 'TIMESTAMP', 'JIG_NUMBER', 'MODEL', 'CT')
            ->whereBetween('CT', [100, 400]);

        if ($request->has('date')) {
            // Jika ada tanggal dari request, filter berdasarkan tanggal tersebut (tanpa jam)
            $date = Carbon::parse($request->input('date'))->startOfDay();
            $query->whereDate('TIMESTAMP', $date);
        } else {
            // Jika tidak ada tanggal, ambil data 7 hari terakhir
            $query->where('TIMESTAMP', '>=', Carbon::now()->subDays(30));
        }

        // Ambil data, urutkan terbaru
        $rawData = $query->orderByDesc('TIMESTAMP')->get();

        // Bagi nilai CT dan bulatkan 1 angka di belakang koma
        $data = $rawData->map(function ($item) {
            $item->CT = round($item->CT / 10, 1);
            return $item;
        });

        return response()->json($data);
    }
    public function api_crimpingEyelet(Request $request)
    {
        $query = DB::table('03_crimping_eyelet_ct')
            ->select('id', 'TIMESTAMP', 'JIG_NUMBER', 'MODEL', 'CT')
            ->whereBetween('CT', [100, 400]);

        if ($request->has('date')) {
            // Jika ada tanggal dari request, filter berdasarkan tanggal tersebut (tanpa jam)
            $date = Carbon::parse($request->input('date'))->startOfDay();
            $query->whereDate('TIMESTAMP', $date);
        } else {
            // Jika tidak ada tanggal, ambil data 7 hari terakhir
            $query->where('TIMESTAMP', '>=', Carbon::now()->subDays(30));
        }

        // Ambil data, urutkan terbaru
        $rawData = $query->orderByDesc('TIMESTAMP')->get();

        // Bagi nilai CT dan bulatkan 1 angka di belakang koma
        $data = $rawData->map(function ($item) {
            $item->CT = round($item->CT / 10, 1);
            return $item;
        });

        return response()->json($data);
    }

    public function total_data_01(): JsonResponse
    {
        $query = "
            SELECT id, TIMESTAMP, JIG_NUMBER, MODEL, CT, 'Cutting Lead Wire' as source
            FROM [taci_cycle_time].[dbo].[01_cutting_lead_wire_ct]
            WHERE CT BETWEEN 100 AND 400
        ";

        $data = DB::select($query);

        return response()->json($data);
    }
}
