<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\AksesModel;
use App\Models\Admin\StockOpnameRequestModel;
use App\Models\Admin\StockOpnameRequestDetailModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangkeluarModel;
use Carbon\Carbon;
use PDF;

class ArsipStockOpnameController extends Controller
{
    // Index - Halaman utama arsip stock opname
    public function index(Request $request)
    {
        $data["title"] = "Arsip Stock Opname";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
            ->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Arsip Stock Opname', 'tbl_akses.akses_type' => 'create'))
            ->count();

        // Tambahkan role_id untuk verifikasi di view
        $data["current_role"] = Session::get('user')->role_id;

        // Filter tanggal
        $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;

        // Ambil data stock opname berdasarkan filter tanggal
        $stockOpnameList = StockOpnameRequestModel::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status_request', 'approve') // Hanya yang sudah disetujui
            ->orderBy('created_at', 'DESC')
            ->get();

        $data['stockOpnameList'] = $stockOpnameList;

        return view('Admin.Laporan.Arsip.index', $data);
    }

    // Detail - Melihat detail arsip stock opname
    public function detail($id)
    {
        $data["title"] = "Detail Arsip Stock Opname";
        $data["stockOpname"] = StockOpnameRequestModel::with('user')->findOrFail($id);
        $data["details"] = StockOpnameRequestDetailModel::with('barang')
            ->where('stock_id', $id)
            ->get();

        // Hitung data untuk setiap detail
        foreach ($data["details"] as $detail) {
            $barangKode = $detail->barang->barang_kode;
            $stokAwal = $detail->barang->barang_stok;

            // Hitung jumlah barang masuk
            // $jmlmasuk = BarangmasukModel::where('barang_kode', $barangKode)
            //     ->where('created_at', '<=', $data["stockOpname"]->created_at)
            //     ->sum('bm_jumlah');

            // // Hitung jumlah barang keluar
            // $jmlkeluar = BarangkeluarModel::where('barang_kode', $barangKode)
            //     ->where('created_at', '<=', $data["stockOpname"]->created_at)
            //     ->sum('bk_jumlah');

            $jmlmasuk = BarangmasukModel::where('barang_kode', $barangKode)
                ->sum('bm_jumlah');

            // Hitung jumlah barang keluar
            $jmlkeluar = BarangkeluarModel::where('barang_kode', $barangKode)
                ->sum('bk_jumlah');
            // Hitung total stok sistem
            $totalStok = $stokAwal + $jmlmasuk - $jmlkeluar;

            // Simpan hasil perhitungan ke detail
            $detail->stok_awal = $stokAwal;
            $detail->jml_masuk = $jmlmasuk;
            $detail->jml_keluar = $jmlkeluar;
            $detail->stock_system = $totalStok;

            // Hitung selisih jika ada stok aktual
            if ($detail->stock_in !== null) {
                $detail->selisih = $detail->stock_in - $totalStok;
            }
        }

        return view('Admin.Laporan.Arsip.detail', $data);
    }

    // Print - Mencetak laporan arsip stock opname
    public function print($id)
    {
        $data["title"] = "Cetak Arsip Stock Opname";
        $data["stockOpname"] = StockOpnameRequestModel::with('user')->findOrFail($id);
        $data["details"] = StockOpnameRequestDetailModel::with('barang')
            ->where('stock_id', $id)
            ->get();

        // Hitung data untuk setiap detail (sama seperti di method detail)
        foreach ($data["details"] as $detail) {
            $barangKode = $detail->barang->barang_kode;
            $stokAwal = $detail->barang->barang_stok;


            $jmlmasuk = BarangmasukModel::where('barang_kode', $barangKode)
                ->sum('bm_jumlah');

            // Hitung jumlah barang keluar
            $jmlkeluar = BarangkeluarModel::where('barang_kode', $barangKode)
                ->sum('bk_jumlah');

            // // Hitung jumlah barang masuk
            // $jmlmasuk = BarangmasukModel::where('barang_kode', $barangKode)
            //     ->where('created_at', '<=', $data["stockOpname"]->created_at)
            //     ->sum('bm_jumlah');

            // // Hitung jumlah barang keluar
            // $jmlkeluar = BarangkeluarModel::where('barang_kode', $barangKode)
            //     ->where('created_at', '<=', $data["stockOpname"]->created_at)
            //     ->sum('bk_jumlah');

            // Hitung total stok sistem
            $totalStok = $stokAwal + $jmlmasuk - $jmlkeluar;

            // Simpan hasil perhitungan ke detail
            $detail->stok_awal = $stokAwal;
            $detail->jml_masuk = $jmlmasuk;
            $detail->jml_keluar = $jmlkeluar;
            $detail->stock_system = $totalStok;

            // Hitung selisih jika ada stok aktual
            if ($detail->stock_in !== null) {
                $detail->selisih = $detail->stock_in - $totalStok;
            }
        }

        return view('Admin.Laporan.Arsip.print', $data);
    }

    // PDF - Menghasilkan laporan PDF
    public function pdf($id)
    {
        $data["title"] = "PDF Arsip Stock Opname";
        $data["stockOpname"] = StockOpnameRequestModel::with('user')->findOrFail($id);
        $data["details"] = StockOpnameRequestDetailModel::with('barang')
            ->where('stock_id', $id)
            ->get();

        // Hitung data untuk setiap detail (sama seperti di method detail)
        foreach ($data["details"] as $detail) {
            $barangKode = $detail->barang->barang_kode;
            $stokAwal = $detail->barang->barang_stok;


            $jmlmasuk = BarangmasukModel::where('barang_kode', $barangKode)
                ->sum('bm_jumlah');

            // Hitung jumlah barang keluar
            $jmlkeluar = BarangkeluarModel::where('barang_kode', $barangKode)
                ->sum('bk_jumlah');

            // Hitung jumlah barang masuk
            // $jmlmasuk = BarangmasukModel::where('barang_kode', $barangKode)
            //     ->where('created_at', '<=', $data["stockOpname"]->created_at)
            //     ->sum('bm_jumlah');

            // // Hitung jumlah barang keluar
            // $jmlkeluar = BarangkeluarModel::where('barang_kode', $barangKode)
            //     ->where('created_at', '<=', $data["stockOpname"]->created_at)
            //     ->sum('bk_jumlah');

            // Hitung total stok sistem
            $totalStok = $stokAwal + $jmlmasuk - $jmlkeluar;

            // Simpan hasil perhitungan ke detail
            $detail->stok_awal = $stokAwal;
            $detail->jml_masuk = $jmlmasuk;
            $detail->jml_keluar = $jmlkeluar;
            $detail->stock_system = $totalStok;

            // Hitung selisih jika ada stok aktual
            if ($detail->stock_in !== null) {
                $detail->selisih = $detail->stock_in - $totalStok;
            }
        }

        $pdf = PDF::loadView('Admin.Arsip.pdf', $data);
        return $pdf->download('arsip-stock-opname-' . $id . '.pdf');
    }
}
