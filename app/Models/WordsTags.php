<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordsTags extends Model
{
    protected $table = 'words_tags';

    protected $fillable = [
        'ws_id',
        'ts_id',
    ];

    public function words()
    {
        return $this->belongsTo(Words::class, 'ws_id', 'id');
    }

    public function tags()
    {
        return $this->belongsTo(Tags::class, 'ts_id', 'id');
    }
}
