<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrxEventPresence extends Model
{
    use HasFactory, Uuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'event_id',
        'form_json'
    ];

    // casting
    protected $casts = [
        'created_at' => 'datetime:d F Y',
        'updated_at' => 'datetime:d F Y'
    ];
}
