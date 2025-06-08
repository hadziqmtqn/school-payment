<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <li class="menu-item {{ $title == 'Dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>
            @foreach($listMenus as $listMenu)
                @if($listMenu) <!-- Pastikan menu tidak null -->
                @php
                    $isActive = $listMenu['url'] == url()->current() || collect($listMenu['subMenus'])
                        ->filter(fn($subMenu) => $subMenu['url'] == url()->current())
                        ->isNotEmpty();

                    $isActiveTitle = $listMenu['name'] == $title || collect($listMenu['subMenus'])
                        ->filter(fn($subMenu) => $subMenu['name'] == $title)
                        ->isNotEmpty();
                @endphp
                <li class="menu-item {{ $isActive || $isActiveTitle ? 'active' : '' }}">
                    <a href="{{ $listMenu['url'] != '#' ? $listMenu['url'] : 'javascript:void(0)' }}" class="menu-link {{ $listMenu['type'] == 'main_menu' && count($listMenu['subMenus']) > 0 ? 'menu-toggle' : '' }}">
                        <i class="menu-icon tf-icons mdi mdi-{{ $listMenu['icon'] }}"></i>
                        <div data-i18n="{{ $listMenu['name'] }}">{{ $listMenu['name'] }}</div>
                    </a>
                    @if(count($listMenu['subMenus']) > 0)
                        <ul class="menu-sub">
                            @foreach($listMenu['subMenus'] as $subMenu)
                                @if($subMenu) <!-- Pastikan submenu tidak null -->
                                <li class="menu-item {{ ($subMenu['url'] == url()->current()) || ($subMenu['name'] == $title) ? 'active' : '' }}">
                                    <a href="{{ $subMenu['url'] }}" class="menu-link">
                                        <i class="menu-icon tf-icons mdi mdi-{{ $subMenu['icon'] }}"></i>
                                        <div data-i18n="{{ $subMenu['name'] }}">{{ $subMenu['name'] }}</div>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside>