<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'cate_name',
        'cate_parent_id',
        'cate_level',
        'cate_order',
    ];

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'cate_parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(Categories::class, 'cate_parent_id', 'id');
    }
}
