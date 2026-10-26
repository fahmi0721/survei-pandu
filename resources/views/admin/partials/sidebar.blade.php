<aside class="navbar navbar-vertical navbar-expand-lg"
       data-bs-theme="dark">

    <div class="container-fluid">

        {{-- Brand --}}
        <h1 class="navbar-brand navbar-brand-autodark">

            <a href="{{ route('admin.dashboard') }}"
               class="text-decoration-none text-white">

                <span class="fw-bold fs-3">ISMA</span>

                <span class="d-block small fw-normal">
                    Survey Management
                </span>

            </a>

        </h1>

        {{-- Mobile toggle --}}
        <div class="navbar-toggler"
             role="button"
             tabindex="0"
             data-bs-toggle="collapse"
             data-bs-target="#sidebar-menu"
             aria-controls="sidebar-menu"
             aria-expanded="false"
             aria-label="Toggle sidebar">

            <span class="navbar-toggler-icon"></span>

        </div>

        {{-- Menu --}}
        <div class="collapse navbar-collapse"
             id="sidebar-menu">

            <ul class="navbar-nav pt-lg-3">

                <li class="nav-item">

                    <a class="nav-link
                        {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">

                        <span class="nav-link-icon">
                            <i class="ti ti-layout-dashboard"></i>
                        </span>

                        <span class="nav-link-title">
                            Dashboard
                        </span>

                    </a>

                </li>

                <li class="nav-item mt-3">
                    <span class="nav-link text-uppercase small opacity-75">
                        Master Data
                    </span>
                </li>

                <li class="nav-item">

                    <a class="nav-link
                        {{ request()->routeIs('admin.surveys.*') ? 'active' : '' }}"
                    href="{{ route('admin.surveys.index') }}">

                        <span class="nav-link-icon">
                            <i class="ti ti-clipboard-list"></i>
                        </span>

                        <span class="nav-link-title">
                            Master Survei
                        </span>

                    </a>

                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.work-areas.index') }}"
                    class="nav-link {{ request()->routeIs('admin.work-areas.*') ? 'active' : '' }}">

                        <span class="nav-link-icon">
                            <i class="ti ti-map-pin"></i>
                        </span>

                        <span class="nav-link-title">
                            Wilayah Kerja
                        </span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <span class="nav-link text-uppercase small opacity-75">
                        Operasional
                    </span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.respondents.index') }}"
                    class="nav-link
                    {{ request()->routeIs('admin.respondents.*') ? 'active' : '' }}">

                        <span class="nav-link-icon">
                            <i class="ti ti-users"></i>
                        </span>

                        <span class="nav-link-title">
                            Kelola Responden
                        </span>

                    </a>

                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.survey-results.index') }}"
                    class="nav-link
                    {{ request()->routeIs('admin.survey-results.*') ? 'active' : '' }}">

                        <span class="nav-link-icon">
                            <i class="ti ti-chart-bar"></i>
                        </span>

                        <span class="nav-link-title">
                            Hasil Survei
                        </span>
                    </a>
                </li>

                @foreach ([
                    ['icon' => 'ti-file-spreadsheet', 'label' => 'Laporan'],
                ] as $menu)

                    <li class="nav-item">
                        <span class="nav-link opacity-75">
                            <span class="nav-link-icon">
                                <i class="ti {{ $menu['icon'] }}"></i>
                            </span>
                            <span class="nav-link-title">
                                {{ $menu['label'] }}
                            </span>
                        </span>
                    </li>

                @endforeach

                @if(auth()->user()->role === 'admin')

                    <li class="nav-item mt-3">
                        <span class="nav-link text-uppercase small opacity-75">
                            Pengaturan
                        </span>
                    </li>

                    <li class="nav-item">
                        <span class="nav-link opacity-75">
                            <span class="nav-link-icon">
                                <i class="ti ti-users-group"></i>
                            </span>
                            <span class="nav-link-title">
                                Manajemen User
                            </span>
                        </span>
                    </li>

                @endif

            </ul>

        </div>

    </div>

</aside>