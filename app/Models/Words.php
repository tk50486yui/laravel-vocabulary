<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Words extends Model
{
    protected $table    = 'words';
    protected $fillable = [
        'ws_name',
        'ws_definition',
        'ws_pronunciation',
        'ws_slogan',
        'ws_description',
        'ws_is_important',
        'ws_is_common',
        'ws_forget_count',
        'ws_order',
        'cate_id',
    ];

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'cate_id', 'id');
    }

}
