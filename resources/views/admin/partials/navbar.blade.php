<header class="navbar navbar-expand-md d-print-none">

    <div class="container-xl">

        <div class="navbar-nav flex-row align-items-center w-100">

            {{-- Page title --}}
            <div class="nav-item">

                <div class="fw-semibold">
                    @yield('page-title', 'Dashboard')
                </div>

                <div class="text-secondary small">
                    Sistem Survei Perwira Pandu
                </div>

            </div>

            <div class="ms-auto d-flex align-items-center gap-3">

                {{-- Theme Toggle --}}
                <button type="button"
                        class="btn btn-icon btn-ghost-secondary"
                        id="btnToggleTheme"
                        title="Ganti Tema"
                        aria-label="Ganti tema">

                    <i class="ti ti-moon fs-2"></i>

                </button>

                {{-- Profile --}}
                <div class="dropdown">

                    <a href="#"
                       class="nav-link d-flex align-items-center"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">

                        <span class="avatar avatar-sm bg-primary-lt">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </span>

                        <div class="d-none d-xl-block ps-2">

                            <div class="fw-medium">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="text-secondary small">
                                {{ strtoupper(auth()->user()->role) }}
                            </div>

                        </div>

                        <i class="ti ti-chevron-down ms-2"></i>

                    </a>

                    <div class="dropdown-menu dropdown-menu-end">

                        <div class="dropdown-header">
                            {{ auth()->user()->email }}
                        </div>

                        <div class="dropdown-divider"></div>

                        <form method="POST"
                              id="formLogout"
                              action="{{ route('admin.logout') }}">

                            @csrf

                            <button type="submit"
                                    class="dropdown-item text-danger">

                                <i class="ti ti-logout me-2"></i>
                                Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>