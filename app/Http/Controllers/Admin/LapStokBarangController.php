<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\StockOpnameRequestDetailModel;
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
                ->addColumn('selisih', function ($row) use ($request) {
                    // Ambil selisih dari StockOpnameRequestDetailModel
                    $selisih = $this->getSelisih($row->barang_kode);

                    // Format hasil selisih dengan penandaan warna
                    if ($selisih > 0) {
                        return '<span class="text-success">+' . number_format($selisih, 2) . '</span>';
                    } elseif ($selisih < 0) {
                        return '<span class="text-danger">' . number_format($selisih, 2) . '</span>';
                    } else {
                        return '<span>' . number_format($selisih, 2) . '</span>';
                    }
                })

                ->addColumn('totalstok', function ($row) use ($request) {

                    // 1. Ambil stok awal dari tbl_barang
                    $stokawal = $row->barang_stok;
                    $jmlmasuk = $this->calculateIncomingStock($row, $request);
                    $jmlkeluar = $this->calculateOutgoingStock($row, $request);


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
                    // $totalstok = $stokawal;
                    $totalstok = $stokawal + $jmlmasuk - $jmlkeluar;

                    // Return formatted total stock
                    if ($totalstok == 0) {
                        return '<span>' . number_format($totalstok, 2) . '</span>';
                    } elseif ($totalstok > 0) {
                        return '<span class="text-success">' . number_format($totalstok, 2) . '</span>';
                    } else {
                        return '<span class="text-danger">' . number_format($totalstok, 2) . '</span>';
                    }
                })
                ->rawColumns(['stokawal', 'jmlmasuk', 'jmlkeluar', 'selisih', 'totalstok'])
                ->make(true);
        }
    }

    private function calculateIncomingStock($row, $request)
    {
        if ($request->tglawal) {
            return BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])
                ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)
                ->sum('tbl_barangmasuk.bm_jumlah');
        } else {
            return BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode)
                ->sum('tbl_barangmasuk.bm_jumlah');
        }
    }

    private function calculateOutgoingStock($row, $request)
    {
        if ($request->tglawal) {
            return BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)
                ->sum('tbl_barangkeluar.bk_jumlah');
        } else {
            return BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangkeluar.customer_id')
                ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode)
                ->sum('tbl_barangkeluar.bk_jumlah');
        }
    }
    private function getSelisih($barangKode)
    {
        // Ambil selisih dari StockOpnameRequestDetailModel berdasarkan barang_kode
        $detail = StockOpnameRequestDetailModel::where('stock_in', $barangKode)
            ->latest() // Ambil yang terbaru
            ->first();

        // Pastikan detail ditemukan dan return selisihnya
        if ($detail) {
            return $detail->selisih;  // Mengambil nilai selisih
        }

        return 0;  // Jika tidak ada selisih, kembalikan 0
    }
}
