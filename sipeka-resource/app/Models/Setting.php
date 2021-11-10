<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_id',
        'app_name'
    ];

    public function zoom()
    {
        return $this->belongsTo(MstZoom::class);
    }
}
