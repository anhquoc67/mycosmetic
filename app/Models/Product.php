<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Bảng liên kết với model này
    protected $table = 'products';

    // khai báo trường khóa chính
    protected $primaryKey = 'id';

    // Các thuộc tính có thể được gán hàng loạt
    protected $fillable = ['id', 'name', 'unit', 'price', 'picture', 'description', 'image','brand', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    // Quan hệ với bảng transactions (nếu có)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
