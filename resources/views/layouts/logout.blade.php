<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        @yield('nav-items')
        <div class="row justify-content-end">
            @if(isset(Auth::user()->name))
                <div class="dropdown">
                    <button class="btn btn-secondary " type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #4c5356 !important;">
                        <svg class="bi bi-power" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5.578 4.437a5 5 0 1 0 4.922.044l.5-.866a6 6 0 1 1-5.908-.053l.486.875z"/>
                            <path fill-rule="evenodd" d="M7.5 8V1h1v7h-1z"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                        <a class="dropdown-item" type="button" href="{{ $logout_url }}">Déconnexion de {{ Auth::user()->name }}</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</nav>
