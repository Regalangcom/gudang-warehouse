jadi role yang ada saat ini adalah super admin , picker , dan kepala gudang,

dari saat ini sudah memiliki input data barang yang tercatat , lalu saat user picker login maka terdapat satu fitur yaitu data penyesuaian , jadi fitur ini di gunakan agar saaat picker melakukan stockOpname maka picker me request data ke super admin , lalu super admin menerima req dari picker dan mengirimkan data product yang tercatat beserta stock nya bro , sehingga output nya nanti picker bisa mendapatkan data field id , nama product , dan colum stock , namu coloum stock disini masih berisi field kosong , sehingga saat picker trigger melalui checkbox maka field stock tersebut bisa terlihat stock yang tercatat bro

MENGATUR HAK AKSES
VIEW:
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'view', NOW(), NOW());`

CREATE
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'create', NOW(), NOW());`

UPDATE
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'update', NOW(), NOW());`

DELETE
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'delete', NOW(), NOW());`

ADMIN :
username : superadmin2
pw : 123456789

PICKER : Hudas
pw : 12345678

Kepala gudang
pw : 12345678

req ajak pake

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\WebModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class LapStokBarangController extends Controller
{
    public function index(Request $request)
    {
        $data["title"] = "Lap Stok Barang";
        return view('Admin.Laporan.StokBarang.index', $data);
    }

    public function print(Request $request)
    {
        $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')->orderBy('barang_id', 'DESC')->get();

        $data["title"] = "Print Stok Barang";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        return view('Admin.Laporan.StokBarang.print', $data);
    }

    public function pdf(Request $request)
    {
        $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')->orderBy('barang_id', 'DESC')->get();

        $data["title"] = "PDF Stok Barang";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        $pdf = PDF::loadView('Admin.Laporan.StokBarang.pdf', $data);

        if ($request->tglawal) {
            return $pdf->download('lap-stok-' . $request->tglawal . '-' . $request->tglakhir . '.pdf');
        } else {
            return $pdf->download('lap-stok-semua-tanggal.pdf');
        }
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')->orderBy('barang_id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('stokawal', function ($row) {
                    $result = '<span class="">' . $row->barang_stok . '</span>';

                    return $result;
                })
                ->addColumn('jmlmasuk', function ($row) use ($request) {
                    if ($request->tglawal == '') {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    } else {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                    }

                    $result = '<span class="">' . $jmlmasuk . '</span>';

                    return $result;
                })
                ->addColumn('jmlkeluar', function ($row) use ($request) {
                    if ($request->tglawal) {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                    } else {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                    }

                    $result = '<span class="">' . $jmlkeluar . '</span>';

                    return $result;
                })
                ->addColumn('totalstok', function ($row) use ($request) {
                    // Use the current stock value from BarangModel instead of recalculating
                    // This will ensure it reflects stock opname adjustments
                    $totalstok = $row->barang_stok;
                    
                    // If filtering by date, still calculate incoming/outgoing for that period
                    if ($request->tglawal) {
                        // Calculate stock movements for the selected period only
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                            ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                            ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])
                            ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)
                            ->sum('tbl_barangmasuk.bm_jumlah');
                            
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                            ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                            ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                            ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)
                            ->sum('tbl_barangkeluar.bk_jumlah');
                            
                        // For date-filtered reports, use the period movement
                        $totalstok = $jmlmasuk - $jmlkeluar;
                    }
                    if ($totalstok == 0) {
                        $result = '<span class="">' . $totalstok . '</span>';
                    } else if ($totalstok > 0) {
                        $result = '<span class="text-success">' . $totalstok . '</span>';
                    } else {
                        $result = '<span class="text-danger">' . $totalstok . '</span>';
                    }


                    return $result;
                })
                ->rawColumns(['stokawal', 'jmlmasuk', 'jmlkeluar', 'totalstok'])->make(true);
        }
    }
}
