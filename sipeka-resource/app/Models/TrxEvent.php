<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxEvent extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'topic',
        'meeting_id',
        'meeting_passcode',
        'meeting_duration',
        'meeting_date',
        'zoom_json',
        'field_json',
        'status',
        'file_certificate'
    ];

    // casting
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
        'meeting_date' => 'datetime:d F Y H:i'
    ];

    public function presences()
    {
        return $this->hasMany(TrxEventPresence::class, 'event_id');
    }
}
