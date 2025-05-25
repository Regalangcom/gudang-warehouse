// app/Http/Controllers/Admin/StockOpnameController.php

<?php

use App\Http\Controllers\Controller;
use App\Models\Admin\StockOpnameRequestModel;
use App\Models\Admin\WebModel;
use App\Models\Admin\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class StockOpnameControllers extends Controller
{
    public function index(Request $request)
    {
        $data["title"] = "Stock Opname";
        $data['web'] = WebModel::first();
        return view('Admin.StockOpname.index', $data);
    }

    public function show(Request $request, $id = null)
    {
        if ($request->ajax()) {
            $data = StockOpnameRequestModel::with(['user', 'approver'])
                ->orderBy('created_at', 'DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('request_date', function ($row) {
                    return $row->request_date->format('d/m/Y');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status_request == 'pending') {
                        return '<span class="badge bg-warning">Pending</span>';
                    } else if ($row->status_request == 'approve') {
                        return '<span class="badge bg-success">Disetujui</span>';
                    } else {
                        return '<span class="badge bg-danger">Ditolak</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('stock-opname.show', $row->stock_id) . '" class="btn btn-primary btn-sm">
                        <i class="fe fe-eye"></i> Detail
                    </a>';

                    // Add approve/reject buttons for pending requests
                    if ($row->status_request == 'pending') {
                        $btn .= '<button type="button" class="btn btn-success btn-sm ms-1" onclick="approveRequest(\'' . $row->stock_id . '\')">
                            <i class="fe fe-check"></i> Setujui
                        </button>';
                        $btn .= '<button type="button" class="btn btn-danger btn-sm ms-1" onclick="rejectRequest(\'' . $row->stock_id . '\')">
                            <i class="fe fe-x"></i> Tolak
                        </button>';
                    }

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data["title"] = "Detail Stock Opname";
        $data['web'] = WebModel::first();
        $data['request'] = StockOpnameRequestModel::with(['details', 'user', 'approver'])->findOrFail($id);
        return view('Admin.StockOpname.show', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approve,reject',
            'keterangan' => 'required_if:status,reject'
        ]);

        $stockOpname = StockOpnameRequestModel::findOrFail($id);
        $stockOpname->status_request = $request->status;
        $stockOpname->approved_by = Session::get('user')->user_id;
        $stockOpname->approved_at = now();
        $stockOpname->keterangan = $request->keterangan;
        $stockOpname->save();

        if ($request->status == 'approve') {
            // Generate detail barang untuk request ini
            $barangs = BarangModel::all();
            foreach ($barangs as $barang) {
                StockOpnameRequestModel::create([
                    'stock_id' => $stockOpname->stock_id,
                    'barang_id' => $barang->barang_id,
                    'stock_system' => $barang->barang_stok,
                    'qty_actual' => null // kosong, diisi oleh picker nanti
                ]);
            }
        }

        return redirect()->route('stock-opname.index')->with('success', 'Status request berhasil diperbarui');
    }
}
