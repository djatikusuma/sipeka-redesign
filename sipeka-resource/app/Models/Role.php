<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\Menu;
use App\Traits\Uuids;

class Role extends SpatieRole
{
    use Uuids;

    protected $keyType = 'string';
    public $incrementing = false;

    // casting
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];

    public function menus() {
        return $this->belongsToMany(Menu::class, 'role_has_menus', 'role_id', 'menu_id');
    }

    public function addMenus(Menu ...$arrayMenu) {
        foreach($arrayMenu as $menu) {
            $this->addMenu($menu->id);
        }
    }

    public function addMenu(Menu $menu) {
        $this->menus()->attach($menu->id);
    }

    public function syncMenus($menus) {
        $this->menus()->sync($menus);
    }
}
