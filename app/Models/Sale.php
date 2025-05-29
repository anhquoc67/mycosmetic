<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales'; // đúng tên bảng trong database
    public $timestamps = false; // nếu không có created_at và updated_at
}
