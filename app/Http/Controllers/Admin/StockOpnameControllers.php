<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\StockOpnameRequestModel;
use App\Models\Admin\StockOpnameRequestDetailModel;
use App\Models\Admin\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;


//   "debug" // @@dd()


class StockOpnameControllers extends Controller
{

    private string $approved, $rejected;

    public function __construct()
    {
        $this->approved = "approve";
        $this->rejected = "reject";
    }

    // Index - Halaman utama untuk Super Admin
    public function index()
    {
        $data["title"] = "Stock Opname";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
            ->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Stock Opname', 'tbl_akses.akses_type' => 'create'))
            ->count();

        // Tambahkan role_id untuk verifikasi di view
        $data["current_role"] = Session::get('user')->role_id;

        return view('Admin.StockOpname.index', $data);
    }

    // Show - Halaman detail stock opname
    public function show($id)
    {
        $data["title"] = "Detail Stock Opname";
        $data["stockOpname"] = StockOpnameRequestModel::with('user')->findOrFail($id);
        $data["details"] = StockOpnameRequestDetailModel::with('barang')
            ->where('stock_id', $id)
            ->get();

        // Hitung total stok untuk setiap detail
        foreach ($data["details"] as $detail) {
            $barangKode = $detail->barang->barang_kode;

            $stokAwal = $detail->barang->barang_stok;

            // Hitung jumlah barang masuk
            $jmlmasuk = BarangmasukModel::where('barang_kode', $barangKode)
                ->sum('bm_jumlah');

            // Hitung jumlah barang keluar
            $jmlkeluar = BarangkeluarModel::where('barang_kode', $barangKode)
                ->sum('bk_jumlah');

            // Hitung total stok (stok awal + masuk - keluar)
            $totalStok = $stokAwal + $jmlmasuk - $jmlkeluar;

            // Set nilai stock_system sebagai total stok
            $detail->stock_system = $totalStok;
        }

        return view('Admin.StockOpname.show', $data);
    }

    // Data - Untuk DataTables
    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $data = StockOpnameRequestModel::with('user')
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
                ->addColumn('requester', function ($row) {
                    return $row->user ? $row->user->user_nmlengkap : '-';
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
                    $array = array(
                        "stock_id" => $row->stock_id,
                        "request_code" => $row->request_code,
                        "status_request" => $row->status_request
                    );

                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id')
                        ->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_menu.menu_judul' => 'Stkopname', 'tbl_akses.akses_type' => 'update'))
                        ->count();

                    // Tombol Lihat Detail
                    $button .= '<div class="g-2">';
                    $button .= '<a class="btn btn-info btn-sm" href="' . route('stock-opname.show', $row->stock_id) . '"><span class="fe fe-eye text-white fs-14"></span></a>';

                    // Tombol Approve/Reject jika status pending dan memiliki hak edit
                    if ($row->status_request == 'pending' && $hakEdit > 0) {
                        $button .= ' <a class="btn modal-effect btn-success btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#ApproveModal" onclick=approve(' . json_encode($array) . ')><span class="fe fe-check text-white fs-14"></span></a>';
                        $button .= ' <a class="btn modal-effect btn-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#RejectModal" onclick=reject(' . json_encode($array) . ')><span class="fe fe-x text-white fs-14"></span></a>';
                    }

                    $button .= '</div>';

                    return $button;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    // Update Status - Approve atau Reject
    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $stockOpname = StockOpnameRequestModel::findOrFail($id);

        if ($request->status == "approved") {
            $status = $this->approved;
        } else {
            $status = $this->rejected;
        }

        // Update status
        $stockOpname->update([
            'status_request' => $status,
            'approved_by' => $request->status == 'approved' ? Session::get('user')->user_id : null,
            'approved_at' => $request->status == 'approved' ? now() : null,
            'keterangan' => $request->keterangan ?? ''
        ]);

        // Jika status disetujui, tambahkan semua barang ke detail
        if ($request->status == 'approved') {
            // Ambil semua produk
            $products = BarangModel::all();

            // Tambahkan ke detail
            foreach ($products as $product) {
                StockOpnameRequestDetailModel::create([
                    'stock_id' => $stockOpname->stock_id,
                    'barang_id' => $product->barang_id,
                    'stock_system' => $product->barang_stok,
                    'stock_in' => null,
                    'is_checked' => false
                ]);
            }
        }

        return response()->json(['success' => 'Status berhasil diupdate']);
    }
}
