<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\StockOpnameRequestModel;
use App\Models\Admin\StockOpnameRequestDetailModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PickerController extends Controller
{
    // Index - Halaman utama untuk Picker
    public function index()
    {
        $data["title"] = "Stock Opname";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
            ->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Stock Opname', 'tbl_akses.akses_type' => 'create'))
            ->count();


        // Ambil semua request stock opname untuk user yang sedang login
        // $data["requests"] = StockOpnameRequestModel::with(['user', 'approver'])
        //     ->where('user_id', Session::get('user')->user_id)
        //     ->orderBy('created_at', 'DESC')
        //     ->get();
        // Tambahkan role_id untuk verifikasi di view
        $data["current_role"] = Session::get('user')->role_id;

        return view('Admin.Picker.index', $data);
    }

    // Create - Form tambah request
    public function create()
    {
        $data["title"] = "Tambah Request Stock Opname";
        return view('Admin.Picker.create', $data);
    }

    public function store(Request $request)
    {
        // Generate kode request jika tidak disediakan
        $requestCode = StockOpnameRequestModel::generateRequestCode();

        try {
            // Buat request dengan default product_id tertentu
            // Misalnya, bisa menggunakan produk default atau produk pertama dari database
            // $defaultProduct = BarangModel::first();

            // if (!$defaultProduct) {
            //     return redirect()->route('picker.index')
            //         ->with('error', 'Tidak dapat membuat request. Belum ada produk yang tersedia.');
            // }

            // Buat record stock opname dan simpan ke variabel
            $stockOpname = StockOpnameRequestModel::create([
                'request_code' => $requestCode,
                'request_date' => now(),
                'user_id' => Session::get('user')->user_id,
                'status_request' => 'pending',
                'keterangan' => $request->keterangan ?? '',
                // 'product_id' => $defaultProduct->barang_id, // Menggunakan produk default
                'stock_in' => 0 // Default stock_in = 0
            ]);

            // Contoh penggunaan record yang baru dibuat
            // Log::info('Stock Opname created', ['stock_id' => $stockOpname->stock_id]);

            // Redirect ke halaman detail jika Anda mau
            // return redirect()->route('picker.show', $stockOpname->stock_id)
            //    ->with('success', 'Request Stock Opname berhasil dibuat.');

            return redirect()->route('picker.index')
                ->with('success', 'Request Stock Opname berhasil dibuat. ID: ' . $stockOpname->stock_id);
        } catch (\Exception $e) {
            return redirect()->route('picker.index')
                ->with('error', 'Gagal membuat request: ' . $e->getMessage());
        }
    }

    // // Store - Simpan request baru
    // public function store(Request $request)
    // {
    //     // Generate kode request jika tidak disediakan
    //     $requestCode = StockOpnameRequestModel::generateRequestCode();

    //     // Buat request
    //     $stockOpname = StockOpnameRequestModel::create([
    //         'request_code' => $requestCode,
    //         'request_date' => now(),
    //         'user_id' => Session::get('user')->user_id,
    //         'status_request' => 'pending',
    //         'keterangan' => $request->keterangan ?? ''
    //     ]);

    //     return redirect()->route('picker.index')
    //         ->with('success', 'Request Stock Opname berhasil dibuat.');
    // }

    // Show - Detail request
    public function show($id)
    {
        $data["title"] = "Detail Stock Opname";
        $data["stockOpname"] = StockOpnameRequestModel::with(['user', 'approver'])->findOrFail($id);

        // Pastikan request milik user saat ini
        if ($data["stockOpname"]->user_id != Session::get('user')->user_id) {
            abort(403, 'Tindakan tidak diizinkan.');
        }

        $data["details"] = StockOpnameRequestDetailModel::with('barang')
            ->where('stock_id', $id)
            ->get();

        return view('Admin.Picker.show', $data);
    }

    // Get Stock Opname - DataTables
    public function getStockOpname(Request $request)
    {
        if ($request->ajax()) {
            $data = StockOpnameRequestModel::with(['user', 'approver'])
                ->where('user_id', Session::get('user')->user_id)
                ->orderBy('created_at', 'DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode', function ($row) {
                    return $row->request_code;
                })
                ->addColumn('tanggal', function ($row) {
                    return Carbon::parse($row->request_date)->format('d-m-Y');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status_request == 'pending') {
                        return '<span class="badge bg-warning">Pending</span>';
                    } elseif ($row->status_request == 'approve') {
                        return '<span class="badge bg-success">Disetujui</span>';
                    } elseif ($row->status_request == 'reject') {
                        return '<span class="badge bg-danger">Ditolak</span>';
                    } else {
                        return '<span class="badge bg-secondary">Tidak Diketahui</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $button = '<div class="g-2">';

                    // Tombol lihat detail
                    $button .= '<a class="btn btn-info btn-sm" href="' . route('picker.show', $row->stock_id) . '"><span class="fe fe-eye text-white fs-14"></span></a>';

                    $button .= '</div>';

                    return $button;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    // Update Stock - Set stok aktual
    public function updateStock(Request $request, $id)
    {
        $detail = StockOpnameRequestDetailModel::findOrFail($id);

        // Cek apakah user memiliki stock opname
        $stockOpname = StockOpnameRequestModel::findOrFail($detail->stock_id);
        if ($stockOpname->user_id != Session::get('user')->user_id) {
            return response()->json(['error' => 'Tindakan tidak diizinkan'], 403);
        }

        // Update stock_in dan hitung selisih
        $detail->update([
            'stock_in' => $request->stock_in,
            'is_checked' => true
        ]);

        $barang = BarangModel::findOrFail($detail->barang_id);

        if($request->stock_in > 0){
            $barang->update([
                'barang_stok' => $request->stock_in + $barang->barang_stok
            ]);
        }

        return response()->json(['success' => 'Stock berhasil diupdate']);
    }
}
