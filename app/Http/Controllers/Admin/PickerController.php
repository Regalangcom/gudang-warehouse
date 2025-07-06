<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangmasukModel;
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

        $details = StockOpnameRequestDetailModel::with('barang')
            ->where('stock_id', $id)
            ->get();

        // Menggunakan perhitungan sama dengan LapStokBarangController
        $totalStocks = [];
        foreach ($details as $detail) {
            // Pastikan barang_kode tersedia
            $barangKode = $detail->barang->barang_kode;
            if (empty($barangKode)) {
                $barangKode = $detail->barang_id; // Fallback jika barang_kode kosong
            }

            // 1. Ambil stok awal dari tbl_barang
            $stokawal = $detail->barang->barang_stok;

            // 2. Hitung jumlah barang masuk
            $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                ->where('tbl_barangmasuk.barang_kode', '=', $barangKode)
                ->sum('tbl_barangmasuk.bm_jumlah');

            // 3. Hitung jumlah barang keluar
            $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                ->where('tbl_barangkeluar.barang_kode', '=', $barangKode)
                ->sum('tbl_barangkeluar.bk_jumlah');

            // 4. Hitung total stok: stok awal + masuk - keluar
            $totalstok = $stokawal + $jmlmasuk - $jmlkeluar;

            $totalStocks[$detail->stock_detail_id] = $totalstok;
        }

        $data["details"] = $details;
        $data["totalStocks"] = $totalStocks;

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
    // public function updateStock(Request $request, $id)
    // {
    //     $detail = StockOpnameRequestDetailModel::findOrFail($id);

    //     // Cek apakah user memiliki stock opname
    //     $stockOpname = StockOpnameRequestModel::findOrFail($detail->stock_id);
    //     if ($stockOpname->user_id != Session::get('user')->user_id) {
    //         return response()->json(['error' => 'Tindakan tidak diizinkan'], 403);
    //     }



    //     // Ambil nilai stok sistem dan stok aktual
    //     $stockSystem = $detail->stock_system;
    //     $stockIn = $request->stock_in;

    //     // Hitung selisih
    //     $selisih = $stockIn - $stockSystem;

    //     // Update stock_in dan set is_checked
    //     $detail->update([
    //         'stock_in' => $stockIn,
    //         'is_checked' => true
    //     ]);

    //     $barang = BarangModel::findOrFail($detail->barang_id);

    //     // Update stok barang dengan menambahkan selisih ke stok yang ada
    //     // Ini akan menambah atau mengurangi stok sesuai dengan selisih yang ditemukan
    //     $newStock = $barang->barang_stok + $selisih;
    //     if ($newStock < 0) {
    //         $newStock = 0; // Pastikan stok tidak negatif
    //     }

    //     $barang->update([
    //         'barang_stok' => $newStock
    //     ]);

    //     return response()->json([
    //         'success' => 'Stock berhasil diupdate',
    //         'stockSystem' => $stockSystem,
    //         'stockIn' => $stockIn,
    //         'selisih' => $selisih
    //     ]);
    // }

    // Update Stock - Set stok aktual
    // public function updateStock(Request $request, $id)
    // {
    //     $detail = StockOpnameRequestDetailModel::findOrFail($id);

    //     // Cek apakah user memiliki stock opname
    //     $stockOpname = StockOpnameRequestModel::findOrFail($detail->stock_id);
    //     if ($stockOpname->user_id != Session::get('user')->user_id) {
    //         return response()->json(['error' => 'Tindakan tidak diizinkan'], 403);
    //     }

    //     // Ambil kode barang dari detail
    //     $barangKode = $detail->barang_kode ?? $detail->barang_id;

    //     // Hitung total stok menggunakan metode dari kode pertama
    //     $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
    //         ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
    //         ->where('tbl_barangmasuk.barang_kode', '=', $barangKode)
    //         ->sum('tbl_barangmasuk.bm_jumlah');

    //     $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
    //         ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
    //         ->where('tbl_barangkeluar.barang_kode', '=', $barangKode)
    //         ->sum('tbl_barangkeluar.bk_jumlah');

    //     // Hitung stok sistem berdasarkan selisih masuk dan keluar
    //     $stockSystem = $jmlmasuk - $jmlkeluar;

    //     // Ambil stok aktual
    //     $stockIn = $request->stock_in;


    //     // $totalStocks[$detail->stock_detail_id] = $stockIn;
    //     // Hitung selisih
    //     $selisih = $stockIn - $stockSystem;

    //     // Update stock_in, stock_system, dan stock_adjustment di detail
    //     $detail->update([
    //         'stock_in' => $stockIn,
    //         'is_checked' => true,
    //     ]);

    //     // Ambil data barang
    //     // $barang = BarangModel::findOrFail($detail->barang_id);

    //     // PENTING: Kita tidak mengubah barang_stok (stok awal) di tabel barang
    //     // Tapi kita bisa menyimpan penyesuaian di tabel lain atau menggunakan field lain

    //     // Opsional: Jika Anda memiliki tabel penyesuaian stok terpisah, simpan di sana
    //     // StockAdjustmentModel::create([
    //     //     'barang_id' => $detail->barang_id,
    //     //     'adjustment_value' => $selisih,
    //     //     'stock_opname_id' => $detail->stock_id,
    //     //     'created_by' => Session::get('user')->user_id
    //     // ]);

    //     return response()->json([
    //         'success' => 'Stock berhasil diupdate',
    //         'stockSystem' => $stockSystem,
    //         'stockIn' => $stockIn,
    //         'selisih' => $selisih
    //     ]);
    // }

    public function updateStock(Request $request, $id)
    {
        $detail = StockOpnameRequestDetailModel::findOrFail($id);

        // Cek apakah user memiliki stock opname
        $stockOpname = StockOpnameRequestModel::findOrFail($detail->stock_id);
        if ($stockOpname->user_id != Session::get('user')->user_id) {
            return response()->json(['error' => 'Tindakan tidak diizinkan'], 403);
        }

        // Ambil kode barang dari detail
        $barangKode = $detail->barang_kode ?? $detail->barang_id;

        // Hitung total stok menggunakan metode dari kode pertama
        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
            ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
            ->where('tbl_barangmasuk.barang_kode', '=', $barangKode)
            ->sum('tbl_barangmasuk.bm_jumlah');

        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
            ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
            ->where('tbl_barangkeluar.barang_kode', '=', $barangKode)
            ->sum('tbl_barangkeluar.bk_jumlah');

        // Hitung stok sistem berdasarkan selisih masuk dan keluar
        $stockSystem = $jmlmasuk - $jmlkeluar;

        // Ambil stok aktual
        $stockIn = $request->stock_in;

        // Hitung selisih
        $selisih = $stockIn - $stockSystem;

        // Update stock_in, stock_system, dan stock_adjustment di detail
        $detail->update([
            'stock_in' => $stockIn,
            'stock_system' => $detail->stock_system + $selisih,
            'is_checked' => true,
        ]);

        // Ambil data barang
        $barang = BarangModel::findOrFail($detail->barang_id);

        // Hitung selisih antara stok aktual dan stok sistem
        $selisih = $stockIn - $stockSystem;

        // Update stok barang: jika selisih positif, tambah stok; jika negatif, kurangi stok
        $newStock = $barang->barang_stok + $selisih;

        // Pastikan stok tidak negatif
        if ($newStock < 0) {
            $newStock = 0;
        }

        // Update stok barang di database
        $barang->update([
            'barang_stok' => $newStock
        ]);

        // Update stok_in di tabel detail
        $detail->update([
            'stock_in' => $stockIn,
            'is_checked' => true, // Tanda bahwa stok telah diperiksa
        ]);

        // Kirim respons sukses
        return response()->json([
            'success' => 'Stok berhasil diupdate',
            'stockSystem' => $stockSystem,
            'stockIn' => $stockIn,
            'selisih' => $selisih,
            'newStock' => $newStock
        ]);
    }


    // // Get total stock for AJAX request
    // public function getTotalStock(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'barang_kode' => 'required'
    //     ]);

    //     $barangKode = $request->barang_kode;

    //     try {
    //         // Calculate total stock using the same logic as in updateStock
    //         $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
    //             ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
    //             ->where('tbl_barangmasuk.barang_kode', '=', $barangKode)
    //             ->sum('tbl_barangmasuk.bm_jumlah');

    //         $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
    //             ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
    //             ->where('tbl_barangkeluar.barang_kode', '=', $barangKode)
    //             ->sum('tbl_barangkeluar.bk_jumlah');

    //         // Calculate total stock
    //         $totalStock = $jmlmasuk - $jmlkeluar;

    //         return response()->json([
    //             'success' => true,
    //             'totalStock' => $totalStock
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error calculating total stock: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
}
