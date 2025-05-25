<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockOpnameRequestDetailModel extends Model
{
    protected $table = 'tbl_stock_control_details';
    protected $primaryKey = 'stock_detail_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'stock_detail_id',
        'stock_id',
        'barang_id',
        'stock_system',
        'stock_in',
        'is_checked',
        'stock_adjustment'
    ];

    protected $casts = [
        'stock_system' => 'decimal:2',
        'stock_in' => 'decimal:2',
        'is_checked' => 'boolean',
        'stock_adjustment' => 'decimal:2'
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

    // // Method untuk menghitung selisih
    // public function calculateDifference()
    // {
    //     if ($this->stock_in !== null) {
    //         $this->difference = $this->stock_in - $this->stock_system;
    //         $this->save();
    //     }
    // }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->stock_detail_id)) {
                $model->stock_detail_id = (string) Str::uuid();
            }
        });
    }
}
