<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'arti_title',
        'arti_content',
        'arti_order',
        'cate_id',
    ];

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'cate_id', 'id');
    }
}
