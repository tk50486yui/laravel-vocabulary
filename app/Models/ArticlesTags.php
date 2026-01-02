<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlesTags extends Model
{
    protected $table = 'articles_tags';

    protected $fillable = [
        'arti_id',
        'ts_id',
    ];

    public function articles()
    {
        return $this->belongsTo(Articles::class, 'arti_id', 'id');
    }

    public function tags()
    {
        return $this->belongsTo(Tags::class, 'ts_id', 'id');
    }
}
