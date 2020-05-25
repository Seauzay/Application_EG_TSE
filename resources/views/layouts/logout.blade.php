<nav class="navbar navbar-expand-md navbar-light bg-white">
    <div class="container">
        @yield('nav-items')
        <div class="row justify-content-end">
            <div class="" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    @if(isset(Auth::user()->name))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ $logout_url }}">
                                    Déconnexion
                                </a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>