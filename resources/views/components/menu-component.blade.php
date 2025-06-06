
@php
    $topLevelMenus = $menuItems->where('level', 1)->sortBy('order');
@endphp

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach ($topLevelMenus as $item)
            @php
                $Pid = $item->id;
                $subMenus = $menuItems->where('parent', $Pid)->sortBy('order')->all();
                $countSubMenus = count($subMenus);
            @endphp

            @if ($countSubMenus > 0)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="{{ $item->icon }} nav-icon"></i>
                        <p>
                            {{ $item ->name }}
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">{{ $countSubMenus }}</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($subMenus as $smn)
                            <li class="nav-item">
                                <a href="{{ Route::has($smn->route) ? route($smn->route) : '#' }}" class="nav-link">
                                    <i class="{{ $smn->icon }} nav-icon"></i>
                                    <p>{{ $smn->name }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ Route::has($item->route) ? route($item->route) : '#' }}" class="nav-link">
                        <i class="{{ $item->icon }} nav-icon"></i>
                        <p>{{ $item->name }}</p>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
<!-- End Sidebar Menu -->

