<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlesWords extends Model
{
    protected $table = 'articles_words';

    protected $fillable = [
        'arti_id',
        'ws_id',
    ];
}
