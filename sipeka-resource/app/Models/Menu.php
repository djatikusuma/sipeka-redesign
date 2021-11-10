<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = [
        'uuid',
        'label',
        'icon',
        'route',
        'permission',
        'order',
        'parent_id'
    ];

    // casting
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
}
