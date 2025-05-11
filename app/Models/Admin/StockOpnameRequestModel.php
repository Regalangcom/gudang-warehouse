<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockOpnameRequestModel extends Model
{
    protected $table = 'stock_opname_requests';
    protected $primaryKey = 'stock_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'stock_id',
        'request_code',
        'request_date',
        'status_request',
        'keterangan',
        'user_id',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'request_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function details()
    {
        return $this->hasMany(StockOpnameRequestDetailModel::class, 'stock_id', 'stock_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(UserModel::class, 'approved_by', 'user_id');
    }

    public static function generateRequestCode()
    {
        $prefix = 'SO-';
        $lastRequest = self::orderBy('created_at', 'desc')->first();

        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest->request_code, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->stock_id)) {
                $model->stock_id = (string) Str::uuid();
            }
            if (empty($model->request_code)) {
                $model->request_code = self::generateRequestCode();
            }
            if (empty($model->request_date)) {
                $model->request_date = now();
            }
        });
    }
}
