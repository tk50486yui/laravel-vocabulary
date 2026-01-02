<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'tags';

    protected $fillable =
        [
        'ts_name',
        'ts_storage',
        'ts_parent_id',
        'ts_level',
        'ts_order',
        'ts_description',
        'tc_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Tags::class, 'ts_parent_id', 'id');
    }

    public function tagsColor()
    {
        return $this->belongsTo(TagsColor::class, 'tc_id', 'id');
    }
}
