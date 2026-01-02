<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordsGroups extends Model
{
    protected $table = 'words_groups';

    protected $fillable = [
        'wg_name',
    ];
}
