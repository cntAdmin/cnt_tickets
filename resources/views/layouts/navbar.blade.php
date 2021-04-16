<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark px-5">
    <a class="navbar-brand" href="{{ route('dashboard') }}">
        <img src="{{ asset('cnt_logo.png') }}" width="150" height="48" alt="{{ env('APP_NAME') }}">
    </a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        {{-- LEFT PART --}}
        <ul class="navbar-nav mr-auto align-items-center">
            @hasrole(1)
            <li class="nav-item dropdown">
                <a class="nav-link" href="!#" id="adminDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <button
                        class="btn btn-dark border border-light rounded font-weight-bold text-uppercase dropdown-toggle">
                        <i class="fa fa-user-cog"></i>
                        <span class="ml-2">{{ __('Admin') }}</span>
                    </button>
                </a>
                <div class="dropdown-menu" aria-labelledby="adminDropdown">
                    <div class="col-12 align-items-center">
                        <a class="dropdown-item d-inline-flex" href="/customer">
                            <div class="col-2 text-right">
                                <i class="fa fa-building"></i>
                            </div>
                            <div class="col-10 text-left">
                                {{ __('Clientes') }}
                            </div>
                        </a>
                    </div>
                    <div class="col-12 align-items-center">
                        <a class="dropdown-item d-inline-flex" href="/user">
                            <div class="col-2 text-right">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="col-10 text-left">
                                {{ __('Usuarios') }}
                            </div>
                        </a>
                    </div>
                    <div class="col-12 align-items-center">
                        <a class="dropdown-item d-inline-flex" href="/ticket-status">
                            <div class="col-2 text-right">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="col-10 text-left">
                                {{ __('Estados') }}
                            </div>
                        </a>
                    </div>
                    <div class="col-12 align-items-center">
                        <a class="dropdown-item d-inline-flex" href="/department">
                            <div class="col-2 text-right">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                            <div class="col-10 text-left">
                                {{ __('Departamentos') }}
                            </div>
                        </a>
                    </div>
                    <div class="col-12 align-items-center">
                        <a class="dropdown-item d-inline-flex" href="/department-type">
                            <div class="col-2 text-right">
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="col-10 text-left">
                                {{ __('Sub Departamentos') }}
                            </div>
                        </a>
                    </div>
                    <div class="col-12 align-items-center">
                        <a class="dropdown-item d-inline-flex" href="/origin-type">
                            <div class="col-2 text-right">
                                <i class="fa fa-ticket-alt"></i>
                            </div>
                            <div class="col-10 text-left">
                                {{ __('Procedencias') }}
                            </div>
                        </a>
                    </div>
                </div>
            </li>
            @endhasrole
            <li class="nav-item">
                <a class="nav-link" href="{{ route('ticket.index') }}">
                    <button class="btn btn-dark border border-light rounded">
                        <i class="fa faw fa-clipboard-list"></i>
                        <span class="font-weight-bold ml-2 text-uppercase">{{ __('Tickets') }}</span>
                    </button>
                </a>
            </li>
        </ul>
        {{-- RIGHT PART --}}
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a id="usernameDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    <i class="fa faw fa-user-circle"></i><span
                        class="text-lowercase ml-2">{{ Auth::user()->username }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="usernameDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>