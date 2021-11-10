<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Menus;
use App\Models\MstUnor;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // create permissions
            $permissionDashboardRead = Permission::create(['name' => 'dashboard.index']);
            $permissionUsersCreate = Permission::create(['name' => 'users.create']);
            $permissionUsersRead = Permission::create(['name' => 'users.index']);
            $permissionUsersUpdate = Permission::create(['name' => 'users.update']);
            $permissionUsersDelete = Permission::create(['name' => 'users.delete']);
            $permissionRolesCreate = Permission::create(['name' => 'roles.create']);
            $permissionRolesRead = Permission::create(['name' => 'roles.index']);
            $permissionRolesUpdate = Permission::create(['name' => 'roles.update']);
            $permissionRolesDelete = Permission::create(['name' => 'roles.delete']);
            $permissionPermissionCreate = Permission::create(['name' => 'permissions.create']);
            $permissionPermissionRead = Permission::create(['name' => 'permissions.index']);
            $permissionPermissionUpdate = Permission::create(['name' => 'permissions.update']);
            $permissionPermissionDelete = Permission::create(['name' => 'permissions.delete']);
            $permissionMenuCreate = Permission::create(['name' => 'menus.create']);
            $permissionMenuRead = Permission::create(['name' => 'menus.index']);
            $permissionMenuUpdate = Permission::create(['name' => 'menus.update']);
            $permissionMenuDelete = Permission::create(['name' => 'menus.delete']);
            $permissionEventCreate = Permission::create(['name' => 'event.create']);
            $permissionEventRead = Permission::create(['name' => 'event.index']);
            $permissionEventUpdate = Permission::create(['name' => 'event.update']);
            $permissionEventDelete = Permission::create(['name' => 'event.delete']);
            $permissionUnorCreate = Permission::create(['name' => 'unor.create']);
            $permissionUnorRead = Permission::create(['name' => 'unor.index']);
            $permissionUnorUpdate = Permission::create(['name' => 'unor.update']);
            $permissionUnorDelete = Permission::create(['name' => 'unor.delete']);


            Role::create(['name' => 'coordinator'])
                ->givePermissionTo([
                    $permissionDashboardRead,
                    $permissionEventCreate,
                    $permissionEventUpdate,
                    $permissionEventRead,
                    $permissionEventDelete
                ]);


            Role::create(['name' => 'superadmin'])
                ->givePermissionTo(Permission::all());


            // Menu
            $menuDashboard = Menu::create([
                'route' => $permissionDashboardRead->name,
                'label' => 'Dashboard',
                'order' => 0,
                'permission' => $permissionDashboardRead->name,
                'icon' => '
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M13.6855025,18.7082217 C15.9113859,17.8189707 18.682885,17.2495635 22,17 C22,16.9325178 22,13.1012863 22,5.50630526 L21.9999762,5.50630526 C21.9999762,5.23017604 21.7761292,5.00632908 21.5,5.00632908 C21.4957817,5.00632908 21.4915635,5.00638247 21.4873465,5.00648922 C18.658231,5.07811173 15.8291155,5.74261533 13,7 C13,7.04449645 13,10.79246 13,18.2438906 L12.9999854,18.2438906 C12.9999854,18.520041 13.2238496,18.7439052 13.5,18.7439052 C13.5635398,18.7439052 13.6264972,18.7317946 13.6855025,18.7082217 Z" fill="#000000"/>
                        <path d="M10.3144829,18.7082217 C8.08859955,17.8189707 5.31710038,17.2495635 1.99998542,17 C1.99998542,16.9325178 1.99998542,13.1012863 1.99998542,5.50630526 L2.00000925,5.50630526 C2.00000925,5.23017604 2.22385621,5.00632908 2.49998542,5.00632908 C2.50420375,5.00632908 2.5084219,5.00638247 2.51263888,5.00648922 C5.34175439,5.07811173 8.17086991,5.74261533 10.9999854,7 C10.9999854,7.04449645 10.9999854,10.79246 10.9999854,18.2438906 L11,18.2438906 C11,18.520041 10.7761358,18.7439052 10.4999854,18.7439052 C10.4364457,18.7439052 10.3734882,18.7317946 10.3144829,18.7082217 Z" fill="#000000" opacity="0.3"/>
                    </g>
                </svg></span>
            ',
            ]);

            $menuUser = Menu::create([
                'route' => $permissionUsersRead->name,
                'label' => 'Manajemen Pengguna',
                'order' => 1,
                'permission' => $permissionUsersRead->name,
                'icon' => '
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                    </g>
                </svg></span>
            ',
            ]);

            $menuUnor = Menu::create([
                'route' => $permissionUnorRead->name,
                'label' => 'Manajemen Unor',
                'order' => 1,
                'permission' => $permissionUnorRead->name,
                'icon' => '
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M5,4 L19,4 C19.5522847,4 20,4.44771525 20,5 L20,19 C20,19.5522847 19.5522847,20 19,20 L5,20 C4.44771525,20 4,19.5522847 4,19 L4,5 C4,4.44771525 4.44771525,4 5,4 Z M9,7 C8.44771525,7 8,7.44771525 8,8 L8,11 C8,11.5522847 8.44771525,12 9,12 C9.55228475,12 10,11.5522847 10,11 L10,8 C10,7.44771525 9.55228475,7 9,7 Z M12,15 C11.1715729,15 10.5,15.6715729 10.5,16.5 L10.5,18 L13.5,18 L13.5,16.5 C13.5,15.6715729 12.8284271,15 12,15 Z M15,7 C14.4477153,7 14,7.44771525 14,8 L14,11 C14,11.5522847 14.4477153,12 15,12 C15.5522847,12 16,11.5522847 16,11 L16,8 C16,7.44771525 15.5522847,7 15,7 Z" fill="#000000"/>
                    </g>
                </svg></span>
            ',
            ]);

            $menuAccessControl = Menu::Create([
                'route' => '',
                'label' => 'Manajemen Akses',
                'order' => 2,
                'icon' => '
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3"/>
                        <path d="M14.5,11 C15.0522847,11 15.5,11.4477153 15.5,12 L15.5,15 C15.5,15.5522847 15.0522847,16 14.5,16 L9.5,16 C8.94771525,16 8.5,15.5522847 8.5,15 L8.5,12 C8.5,11.4477153 8.94771525,11 9.5,11 L9.5,10.5 C9.5,9.11928813 10.6192881,8 12,8 C13.3807119,8 14.5,9.11928813 14.5,10.5 L14.5,11 Z M12,9 C11.1715729,9 10.5,9.67157288 10.5,10.5 L10.5,11 L13.5,11 L13.5,10.5 C13.5,9.67157288 12.8284271,9 12,9 Z" fill="#000000"/>
                    </g>
                </svg></span>
            ',
                'permission' => $permissionPermissionRead->name
            ]);

            $menuMenu = Menu::create([
                'route' => $permissionMenuRead->name,
                'label' => 'Manajemen Menu',
                'order' => 3,
                'permission' => $permissionMenuRead->name,
                'icon' => '
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M4.95312427,14.3025791 L3.04687573,13.6974209 C4.65100965,8.64439903 7.67317997,6 12,6 C16.32682,6 19.3489903,8.64439903 20.9531243,13.6974209 L19.0468757,14.3025791 C17.6880467,10.0222676 15.3768837,8 12,8 C8.62311633,8 6.31195331,10.0222676 4.95312427,14.3025791 Z M12,8 C12.5522847,8 13,7.55228475 13,7 C13,6.44771525 12.5522847,6 12,6 C11.4477153,6 11,6.44771525 11,7 C11,7.55228475 11.4477153,8 12,8 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                        <path d="M5.73243561,6 L9.17070571,6 C9.58254212,4.83480763 10.6937812,4 12,4 C13.3062188,4 14.4174579,4.83480763 14.8292943,6 L18.2675644,6 C18.6133738,5.40219863 19.2597176,5 20,5 C21.1045695,5 22,5.8954305 22,7 C22,8.1045695 21.1045695,9 20,9 C19.2597176,9 18.6133738,8.59780137 18.2675644,8 L14.8292943,8 C14.4174579,9.16519237 13.3062188,10 12,10 C10.6937812,10 9.58254212,9.16519237 9.17070571,8 L5.73243561,8 C5.38662619,8.59780137 4.74028236,9 4,9 C2.8954305,9 2,8.1045695 2,7 C2,5.8954305 2.8954305,5 4,5 C4.74028236,5 5.38662619,5.40219863 5.73243561,6 Z M12,8 C12.5522847,8 13,7.55228475 13,7 C13,6.44771525 12.5522847,6 12,6 C11.4477153,6 11,6.44771525 11,7 C11,7.55228475 11.4477153,8 12,8 Z M4,19 C2.34314575,19 1,17.6568542 1,16 C1,14.3431458 2.34314575,13 4,13 C5.65685425,13 7,14.3431458 7,16 C7,17.6568542 5.65685425,19 4,19 Z M4,17 C4.55228475,17 5,16.5522847 5,16 C5,15.4477153 4.55228475,15 4,15 C3.44771525,15 3,15.4477153 3,16 C3,16.5522847 3.44771525,17 4,17 Z M20,19 C18.3431458,19 17,17.6568542 17,16 C17,14.3431458 18.3431458,13 20,13 C21.6568542,13 23,14.3431458 23,16 C23,17.6568542 21.6568542,19 20,19 Z M20,17 C20.5522847,17 21,16.5522847 21,16 C21,15.4477153 20.5522847,15 20,15 C19.4477153,15 19,15.4477153 19,16 C19,16.5522847 19.4477153,17 20,17 Z" fill="#000000"/>
                    </g>
                </svg></span>
            ',
            ]);


            $menuPermission = Menu::create([
                'route' => $permissionPermissionRead->name,
                'label' => 'Permission',
                'permission' => $permissionPermissionRead->name,
            ]);
            $menuRole = Menu::create([
                'route' => $permissionRolesRead->name,
                'permission' => $permissionRolesRead->name,
                'label' => 'Role',
            ]);
            $menuAccessControl->appendNode($menuPermission);
            $menuAccessControl->appendNode($menuRole);


            $menuPengajuan = Menu::create([
                'route' => $permissionEventRead->name,
                'label' => 'Manajemen Kegiatan',
                'order' => 4,
                'permission' => $permissionEventRead->name,
                'icon' => '
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M11.5,5 L18.5,5 C19.3284271,5 20,5.67157288 20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L11.5,8 C10.6715729,8 10,7.32842712 10,6.5 C10,5.67157288 10.6715729,5 11.5,5 Z M5.5,17 L18.5,17 C19.3284271,17 20,17.6715729 20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 C4,17.6715729 4.67157288,17 5.5,17 Z M5.5,11 L18.5,11 C19.3284271,11 20,11.6715729 20,12.5 C20,13.3284271 19.3284271,14 18.5,14 L5.5,14 C4.67157288,14 4,13.3284271 4,12.5 C4,11.6715729 4.67157288,11 5.5,11 Z" fill="#000000" opacity="0.3"/>
                        <path d="M4.82866499,9.40751652 L7.70335558,6.90006821 C7.91145727,6.71855155 7.9330087,6.40270347 7.75149204,6.19460178 C7.73690043,6.17787308 7.72121098,6.16213467 7.70452782,6.14749103 L4.82983723,3.6242308 C4.62230202,3.44206673 4.30638833,3.4626341 4.12422426,3.67016931 C4.04415337,3.76139218 4,3.87862714 4,4.00000654 L4,9.03071508 C4,9.30685745 4.22385763,9.53071508 4.5,9.53071508 C4.62084305,9.53071508 4.73759731,9.48695028 4.82866499,9.40751652 Z" fill="#000000"/>
                    </g>
                </svg></span>
            ',
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            dd($e);

            DB::rollback();
        }
    }
}
