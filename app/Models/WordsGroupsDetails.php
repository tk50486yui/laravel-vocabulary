<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordsGroupsDetails extends Model
{
    protected $table = 'words_groups_details';

    protected $fillable = [
        'ws_id',
        'wg_id',
    ];

    public function words()
    {
        return $this->belongsTo(Words::class, 'ws_id', 'id');
    }

    public function words_groups()
    {
        return $this->belongsTo(WordsGroups::class, 'wg_id', 'id');
    }
}
