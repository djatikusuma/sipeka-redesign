<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Link;
use App\Models\Menu as ModelsMenu;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

// use Lavary\Menu;

class MenuServiceProvider
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $userMenus = ModelsMenu::hydrate(\App\Models\Menu::orderBy('order', 'asc')->get()->toArray())->toTree();

        $generatedMenu = Menu::build($userMenus, function ($menuInstance, $menuItem) use ($request) {
            $this->menuGenerator(Auth::user(), $menuInstance, $menuItem, $request);
        })
            ->addClass('menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500')
            ->setAttribute('data-kt-menu', 'true');

        View::share('generatedMenu', $generatedMenu);

        return $next($request);
    }

    // generate menu
    public function menuGenerator($user, $menuInstance, $menuItem, $request, $isSubMenu = false)
    {
        if ($user->can($menuItem->permission)) {
            if (!empty($menuItem->children) && count($menuItem->children) > 0) {
                $subMenuInstance = Menu::new()
                    ->addParentClass('menu-item menu-accordion')
                    ->setParentAttribute('data-kt-menu-trigger', 'click')
                    ->addClass('menu-sub menu-sub-accordion');
                foreach ($menuItem->children as $subMenuItem) {
                    $this->menuGenerator($user, $subMenuInstance, $subMenuItem, $request);
                }
                $menuInstance->submenu(
                    $this->withIconSubmenu($menuItem->icon, $menuItem->label),
                    $subMenuInstance
                );

                // $menuInstance
            } else {
                if ($request->routeIs(explode('.', $menuItem->route)[0] . '.*')) {
                    $menuInstance->add(
                        Link::toRoute($menuItem->route, $this->withIcon($menuItem->icon, $menuItem->label))
                            ->addParentClass('menu-item')
                            ->addClass('menu-link active')
                    );
                    // return $menuInstance;
                } else {
                    $menuInstance->add(
                        Link::toRoute($menuItem->route, $this->withIcon($menuItem->icon, $menuItem->label))
                            ->addParentClass('menu-item')
                            ->addClass('menu-link')
                    );
                    // return $menuInstance;
                }
            }
        }
    }

    public function withIcon($icon, $label)
    {
        if (empty($icon)) {
            return '
                <span class="menu-bullet">
					<span class="bullet bullet-dot"></span>
				</span>
                <span class="menu-title">' . $label . '</span>
            ';
        } else {
            return '
                <span class="menu-icon"> ' . $icon . '</span>
                <span class="menu-title">' . $label . '</span>
            ';
        }
    }

    public function withIconSubmenu($icon, $label)
    {
        if (empty($icon)) {
            return '
                <span class="menu-link">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">' . $label . '</span>
                    <span class="menu-arrow"></span>
                </span>
            ';
        } else {
            return '
                <span class="menu-link">
                    <span class="menu-icon"> ' . $icon . '</span>
                    <span class="menu-title">' . $label . '</span>
                    <span class="menu-arrow"></span>
                </span>
            ';
        }
    }
}
