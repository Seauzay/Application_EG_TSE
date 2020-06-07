<nav class="navbar navbar-expand-md navbar-light bg-white">
    <div class="container">
        @yield('nav-items')
        <div class="row justify-content-end">
            @if(isset(Auth::user()->name))
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #4c5356 !important;">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                        <a class="dropdown-item" type="button" href="{{ $logout_url }}">Déconnexion</a>

                    </div>
                </div>
            @endif
            {{--      <div class="" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    @if(isset(Auth::user()->name))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdownMenuLink" class="nav-link dropleft dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown" style="position:relative !important;">
                                <a class="dropdown-item" href="{{ $logout_url }}">
                                    Déconnexion
                                </a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>--}}
        </div>
    </div>
</nav>