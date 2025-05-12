<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockOpnameRequestDetailModel extends Model
{
    protected $table = 'stock_opname_request_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'stock_id',
        'barang_id',
        'stock_system',
        'stock_in',
        'difference',
        'is_checked'
    ];

    protected $casts = [
        'stock_system' => 'decimal:2',
        'stock_in' => 'decimal:2',
        'difference' => 'decimal:2',
        'is_checked' => 'boolean'
    ];

    // Relasi ke request
    public function stockOpnameRequest()
    {
        return $this->belongsTo(StockOpnameRequestModel::class, 'stock_id', 'stock_id');
    }

    // Relasi ke barang
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    // Method untuk menghitung selisih
    public function calculateDifference()
    {
        if ($this->stock_in !== null) {
            $this->difference = $this->stock_in - $this->stock_system;
            $this->save();
        }
    }
}
