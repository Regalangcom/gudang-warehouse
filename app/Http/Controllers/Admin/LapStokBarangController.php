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

                // ->addColumn('totalstok', function ($row) use ($request) {
                //     // 1. Ambil stok awal dari tbl_barang
                //     $stokawal = $row->barang_stok;

                //     // 2. Hitung jumlah barang masuk
                //     if ($request->tglawal == '') {
                //         $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                //             ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                //             ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)
                //             ->sum('tbl_barangmasuk.bm_jumlah');
                //     } else {
                //         $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                //             ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                //             ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])
                //             ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)
                //             ->sum('tbl_barangmasuk.bm_jumlah');
                //     }

                //     // 3. Hitung jumlah barang keluar
                //     if ($request->tglawal) {
                //         $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                //             ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                //             ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                //             ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)
                //             ->sum('tbl_barangkeluar.bk_jumlah');
                //     } else {
                //         $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                //             ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                //             ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)
                //             ->sum('tbl_barangkeluar.bk_jumlah');
                //     }

                //     // 4. Hitung stok sistem: stok awal + masuk - keluar
                //     $stockSystem = $stokawal + $jmlmasuk - $jmlkeluar;

                //     // 5. Dapatkan nilai stok aktual dari input picker
                //     $stockIn = $row->barang_stok_actual; // Pastikan $row memiliki kolom 'barang_stok_actual' yang diinputkan picker

                //     // 6. Hitung selisih antara stok sistem dan stok aktual
                //     $selisih = $stockIn - $stockSystem;

                //     // 7. Hitung total stok dengan selisih
                //     $totalstok = $stockSystem + $selisih;

                //     // Format tampilan total stok
                //     if ($totalstok == 0) {
                //         $result = '<span class="">' . $totalstok . '</span>';
                //     } else if ($totalstok > 0) {
                //         $result = '<span class="text-success">' . $totalstok . '</span>';
                //     } else {
                //         $result = '<span class="text-danger">' . $totalstok . '</span>';
                //     }

                //     return $result;
                // })


                ->addColumn('totalstok', function ($row) use ($request) {

                    // 1. Ambil stok awal dari tbl_barang
                    $stokawal = $row->barang_stok;

                    // 2. Hitung jumlah barang masuk
                    if ($request->tglawal == '') {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                            ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                            ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)
                            ->sum('tbl_barangmasuk.bm_jumlah');
                    } else {
                        $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                            ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                            ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])
                            ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)
                            ->sum('tbl_barangmasuk.bm_jumlah');
                    }

                    // 3. Hitung jumlah barang keluar
                    if ($request->tglawal) {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                            ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                            ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                            ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)
                            ->sum('tbl_barangkeluar.bk_jumlah');
                    } else {
                        $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                            ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                            ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)
                            ->sum('tbl_barangkeluar.bk_jumlah');
                    }

                    // 4. Hitung total stok: stok awal + masuk - keluar
                    $totalstok = $stokawal + $jmlmasuk - $jmlkeluar;

                    // Format tampilan total stok
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
