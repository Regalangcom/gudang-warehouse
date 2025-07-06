<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockOpnameRequestModel extends Model
{
    protected $table = 'tbl_stock_control';
    protected $primaryKey = 'stock_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'stock_id',
        'request_code',
        'stock_in',
        'status_request',
        'barang_stok_actual',
        'keterangan',
        'user_id',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'stock_in' => 'decimal:2',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // // Relasi ke barang
    // public function barang()
    // {
    //     return $this->belongsTo(BarangModel::class, 'product_id', 'barang_id');
    // }

    // Relasi ke user (requester)
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // Relasi ke user (approver)
    public function approver()
    {
        return $this->belongsTo(UserModel::class, 'approved_by', 'user_id');
    }

    // Generate kode request otomatis
    public static function generateRequestCode()
    {
        $prefix = 'SO-';
        $lastRequest = self::orderBy('created_at', 'desc')->first();

        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest->request_code ?? $prefix . '00000000', 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
    }

    // Boot method untuk otomatis mengisi UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->stock_id)) {
                $model->stock_id = (string) Str::uuid();
            }
        });
    }
}
