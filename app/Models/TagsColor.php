<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagsColor extends Model
{
    protected $table = 'tags_color';

    protected $fillable =
        [
        'tc_color',
        'tc_background',
        'tc_border',
    ];
}
