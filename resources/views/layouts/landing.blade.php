<!DOCTYPE html>
<html>
<head>
    <title>Page Register</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/flat-admin.css') }}">


</head>
<body>
<div class="app app-default">

    <div class="app-container app-login">
        <div class="flex-center">
            <div class="app-header"></div>
            <div class="app-body">
                <div class="loader-container text-center">
                    <div class="icon">
                        <div class="sk-folding-cube">
                            <div class="sk-cube1 sk-cube"></div>
                            <div class="sk-cube2 sk-cube"></div>
                            <div class="sk-cube4 sk-cube"></div>
                            <div class="sk-cube3 sk-cube"></div>
                        </div>
                    </div>
                    <div class="title">Logging in...</div>
                </div>
                <div class="app-block">
                    <div class="app-right-section">
                        <div class="app-brand"><span class="highlight">NhatMinh</span> 247</div>
                        <div class="app-info">

                            <ul class="list">
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                    </div>
                                    <div class="title">Increase <b>Productivity</b></div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-cubes" aria-hidden="true"></i>
                                    </div>
                                    <div class="title">Lot of <b>Components</b></div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-usd" aria-hidden="true"></i>
                                    </div>
                                    <div class="title">Forever <b>Free</b></div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="app-form">
                        @yield('content')
                    </div>

                </div>
            </div>
            <div class="app-footer">
            </div>
        </div>
    </div>

</div>

</body>
</html>