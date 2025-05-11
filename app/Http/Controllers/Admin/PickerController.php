<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\StockOpnameRequestModel;
use App\Models\Admin\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PickerController extends Controller
{
    public function index(Request $request)
    {
        $data["title"] = "Request Stock Opname";
        return view('Admin.Picker.index', $data);
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            // Jika user adalah picker, hanya tampilkan data yang dibuat oleh picker tersebut
            if (Session::get('user')->role_id == 3) { // role_id 3 adalah picker
                $data = StockOpnameRequestModel::with(['barang', 'user'])
                    ->where('user_id', Session::get('user')->user_id)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            } else {
                // Untuk admin, tampilkan semua data
                $data = StockOpnameRequestModel::with(['barang', 'user'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('request_code', function ($row) {
                    return $row->request_code;
                })
                ->addColumn('tanggal', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y');
                })
                ->addColumn('barang', function ($row) {
                    return $row->barang ? $row->barang->barang_nama : '-';
                })
                ->addColumn('picker', function ($row) {
                    return $row->user ? $row->user->user_nmlengkap : '-';
                })
                ->addColumn('stock_system', function ($row) {
                    return $row->status_request == 'approve' ? number_format($row->stock_system, 2) : '-';
                })
                ->addColumn('stock_in', function ($row) {
                    if ($row->status_request == 'approve') {
                        return '<input type="checkbox" class="stock-checkbox" data-id="' . $row->stock_id . '" ' .
                            ($row->stock_in !== null ? 'checked' : '') . '>';
                    }
                    return '-';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status_request == 'pending') {
                        return '<span class="badge bg-warning">Pending</span>';
                    } elseif ($row->status_request == 'approve') {
                        return '<span class="badge bg-success">Approved</span>';
                    } else {
                        return '<span class="badge bg-danger">Rejected</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';

                    // Detail button for all roles
                    $buttons .= '<a href="' . route('picker.detail', $row->stock_id) . '" class="btn btn-info btn-sm">Detail</a>';

                    // Approve and Reject buttons only for Super Admin
                    if (Session::get('user')->role_id == 1 && $row->status_request == 'pending') {
                        $buttons .= '<button type="button" class="btn btn-success btn-sm" onclick="approveRequest(\'' . $row->stock_id . '\')">Approve</button>';
                        $buttons .= '<button type="button" class="btn btn-danger btn-sm" onclick="rejectRequest(\'' . $row->stock_id . '\')">Reject</button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['status', 'action', 'stock_in'])
                ->make(true);
        }
    }

    public function tambah()
    {
        $data["title"] = "Tambah Request Stock Opname";
        return view('Admin.Picker.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Ambil semua barang yang aktif
            $barang = BarangModel::where('barang_status', 1)->get();

            // Generate request code untuk batch ini
            $requestCode = StockOpnameRequestModel::generateRequestCode();

            // Buat request untuk setiap barang
            foreach ($barang as $item) {
                StockOpnameRequestModel::create([
                    'stock_id' => (string) Str::uuid(),
                    'request_code' => $requestCode,
                    'barang_id' => $item->barang_id,
                    'stock_system' => $item->barang_stok,
                    'status_request' => 'pending',
                    'keterangan' => $request->keterangan,
                    'user_id' => Session::get('user')->user_id
                ]);
            }

            DB::commit();

            Session::flash('status', 'success');
            Session::flash('msg', 'Request stock opname berhasil dibuat dengan kode: ' . $requestCode);

            return redirect()->route('picker.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('status', 'error');
            Session::flash('msg', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function detail($id)
    {
        $data["title"] = "Detail Request Stock Opname";
        $data["stockOpname"] = StockOpnameRequestModel::with(['barang', 'user', 'approver'])
            ->findOrFail($id);

        // Pastikan picker hanya bisa melihat request miliknya sendiri
        if (Session::get('user')->role_id == 3 && $data["stockOpname"]->user_id != Session::get('user')->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('Admin.Picker.detail', $data);
    }

    public function approve(Request $request)
    {
        try {
            DB::beginTransaction();

            $stockOpname = StockOpnameRequestModel::findOrFail($request->stock_id);

            // Validasi status
            if ($stockOpname->status_request != 'pending') {
                throw new \Exception('Request ini tidak dapat disetujui karena statusnya sudah ' . $stockOpname->status_request);
            }

            // Update status dan data approval
            $stockOpname->update([
                'status_request' => 'approve',
                'approved_by' => Session::get('user')->user_id,
                'approved_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Request berhasil disetujui.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request)
    {
        try {
            $stockOpname = StockOpnameRequestModel::findOrFail($request->stock_id);

            // Validasi status
            if ($stockOpname->status_request != 'pending') {
                throw new \Exception('Request ini tidak dapat ditolak karena statusnya sudah ' . $stockOpname->status_request);
            }

            // Update status
            $stockOpname->update([
                'status_request' => 'reject',
                'approved_by' => Session::get('user')->user_id,
                'approved_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Request berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStock(Request $request)
    {
        try {
            $stockOpname = StockOpnameRequestModel::findOrFail($request->stock_id);

            // Validasi status
            if ($stockOpname->status_request != 'approve') {
                throw new \Exception('Stock hanya dapat diinput untuk request yang sudah disetujui');
            }

            // Validasi user
            if (Session::get('user')->role_id != 3 || $stockOpname->user_id != Session::get('user')->user_id) {
                throw new \Exception('Anda tidak memiliki akses untuk menginput stock ini');
            }

            // Update stock fisik (1 jika checkbox dicentang, 0 jika tidak)
            $stockOpname->update([
                'stock_in' => $request->checked ? 1 : 0
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Stock berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function history($id)
    {
        try {
            $stockOpname = StockOpnameRequestModel::with(['barang', 'user'])
                ->findOrFail($id);

            // Pastikan picker hanya bisa melihat request miliknya sendiri
            if (Session::get('user')->role_id == 3 && $stockOpname->user_id != Session::get('user')->user_id) {
                throw new \Exception('Unauthorized action.');
            }

            // Get history data
            $history = [
                [
                    'tanggal' => Carbon::parse($stockOpname->created_at)->format('d/m/Y H:i'),
                    'keterangan' => 'Request stock opname dibuat',
                    'stock_sebelum' => '-',
                    'stock_sesudah' => '-',
                    'user' => $stockOpname->user->user_nmlengkap
                ]
            ];

            if ($stockOpname->approved_at) {
                $history[] = [
                    'tanggal' => Carbon::parse($stockOpname->approved_at)->format('d/m/Y H:i'),
                    'keterangan' => 'Request stock opname ' . ($stockOpname->status_request == 'approve' ? 'disetujui' : 'ditolak'),
                    'stock_sebelum' => '-',
                    'stock_sesudah' => '-',
                    'user' => $stockOpname->approver->user_nmlengkap
                ];
            }

            if ($stockOpname->stock_in !== null) {
                $history[] = [
                    'tanggal' => Carbon::parse($stockOpname->updated_at)->format('d/m/Y H:i'),
                    'keterangan' => 'Input stock fisik',
                    'stock_sebelum' => number_format($stockOpname->stock_system, 2),
                    'stock_sesudah' => $stockOpname->stock_in ? 'Ada' : 'Tidak Ada',
                    'user' => $stockOpname->user->user_nmlengkap
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
