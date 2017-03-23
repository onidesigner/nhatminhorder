
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">
    <meta name="author" content="">
    <title>NhatMinh247 - HoTro</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/support/bootstrap.min.css')  }}" rel="stylesheet">

    <!-- Temporary fix for navbar responsiveness -->
    <style>
        body {
            padding-top: 54px;
        }

        @media (min-width: 992px) {
            body {
                padding-top: 56px;
            }
        }

        .pagination {
            margin-bottom: 15px;
        }

        .navbar-toggler {
            z-index: 1;
        }

        @media (max-width: 576px) {
            nav > .container {
                width: 100%;
            }
        }
    </style>

</head>

<body>

<!-- Navigation -->
<nav class="navbar fixed-top navbar-toggleable-md navbar-inverse bg-inverse">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarExample" aria-controls="navbarExample" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="container">
        <a class="navbar-brand" href="http://nhatminh247.vn/">NhatMinh247</a>
        <div class="collapse navbar-collapse" id="navbarExample">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="http://nhatminh247.vn/">Trang chủ <span class="sr-only">(current)</span></a>
                </li>
                {{--<li class="nav-item">--}}
                    {{--<a class="nav-link" href="#">About</a>--}}
                {{--</li>--}}

            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container">
    @yield('content')
</div>
<!-- /.container -->

<!-- Footer -->
<footer class="py-5 bg-inverse">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; NhatMinh247 2017</p>
    </div>
    <!-- /.container -->
</footer>

<!-- jQuery Version 3.1.1 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.js')  }}"></script>

<!-- Tether -->
<script src="{{ asset('js/support/tether.min.js')  }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.js')  }}"></script>

</body>

</html>
