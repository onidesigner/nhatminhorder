
<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
    <!-- meta character set -->
    <meta charset="utf-8">
    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>NhatMinh247 HomePage</title>
    <!-- Meta Description -->
    <meta name="description" content="Blue One Page Creative HTML5 Template">
    <meta name="keywords" content="one page, single page, onepage, responsive, parallax, creative, business, html5, css3, css3 animation">
    <meta name="author" content="Muhammad Morshed">

    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

    <!-- Fontawesome Icon font -->
    <link rel="stylesheet" href="{{ asset('css/home/font-awesome.min.css') }}">

    <!-- bootstrap.min -->
    <link rel="stylesheet" href="{{ asset('css/home/bootstrap.min.css') }}">
    <!-- bootstrap.min -->
    <link rel="stylesheet" href="{{ asset('css/home/owl.carousel.css') }}">
    <!-- bootstrap.min -->
    <link rel="stylesheet" href="{{ asset('css/home/slit-slider.css') }}">
    <!-- bootstrap.min -->
    <link rel="stylesheet" href="{{ asset('css/home/animate.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/home/main.css') }}">

    <!-- Modernizer Script for old Browsers -->
    <script src="{{ asset('js/home/modernizr-2.6.2.min.js')  }}"></script>

</head>

<body id="body">

<!-- preloader -->
<div id="preloader">
    <div class="loder-box">
        <div class="battery"></div>
    </div>
</div>
<!-- end preloader -->

<!--
Fixed Navigation
==================================== -->
<header id="navigation" class="navbar-inverse navbar-fixed-top animated-header">
    <div class="container">
        <div class="navbar-header">
            <!-- responsive nav button -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- /responsive nav button -->

            <!-- logo -->
            <h1 class="navbar-brand">
                <a href="javascript:void(0)">NhatMinh247</a>
            </h1>
            <!-- /logo -->
        </div>

        <!-- main nav -->
        <nav class="collapse navbar-collapse navbar-right" role="navigation">
            <ul id="nav" class="nav navbar-nav _menu-header">
                <li><a href="">Đặt hàng</a></li>
                <li><a href="">Biểu phí</a></li>
                <li><a href="">Quy định & Chính sách</a></li>
                <li><a href="">Huớng dẫn</a></li>


                @if (Route::has('login'))
                    @if (Auth::check())

                        <li><a href="{{ url('/home') }}">Vào hệ thống</a></li>
                    @else

                        <li><a href="{{ url('/login') }}">Đăng nhập</a></li>
                        <li><a href="{{ url('/register') }}">Đăng ký</a></li>
                    @endif
                @endif
            </ul>
        </nav>
        <!-- /main nav -->

    </div>
</header>
<!--
End Fixed Navigation
==================================== -->

<main class="site-content" role="main">

    <!--
    Home Slider
    ==================================== -->

    <section id="home-slider">
        <div id="slider" class="sl-slider-wrapper">

            <div class="sl-slider">

                <div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">

                    <div class="bg-img bg-img-1"></div>

                    <div class="slide-caption">
                        <div class="caption-content">
                            <h2 class="animated fadeInDown">CHUYÊN ĐẶT HÀNG TỪ TRUNG QUỐC!</h2>
                            <span class="animated fadeInDown" style="font-weight: 100; text-transform: none;">
									Tư vấn, tìm kiếm nguồn hàng và nhập hàng trực tuyến từ các website hàng đầu Trung Quốc. NhatMinh247 cam kết một dịch vụ vượt trội và cải tiến liên tục. NhatMinh247 là giải pháp nhập hàng tối ưu cho quý khách

                                </span>
                            <a href="#" class="btn btn-blue btn-effect">Tìm hiểu thêm</a>
                        </div>
                    </div>

                </div>

            </div><!-- /sl-slider -->

            <!--
            <nav id="nav-arrows" class="nav-arrows">
                <span class="nav-arrow-prev">Previous</span>
                <span class="nav-arrow-next">Next</span>
            </nav>
            -->

            <nav id="nav-arrows" class="nav-arrows hidden-xs hidden-sm visible-md visible-lg">
                <a href="javascript:;" class="sl-prev">
                    <i class="fa fa-angle-left fa-3x"></i>
                </a>
                <a href="javascript:;" class="sl-next">
                    <i class="fa fa-angle-right fa-3x"></i>
                </a>
            </nav>


            <nav id="nav-dots" class="nav-dots visible-xs visible-sm hidden-md hidden-lg">
                <span class="nav-dot-current"></span>
                <span></span>
                <span></span>
            </nav>

        </div><!-- /slider-wrapper -->
    </section>

    <!--
    End Home SliderEnd
    ==================================== -->




    <!-- Service section -->
    <section id="service">
        <div class="container">
            <div class="row">

                <div class="sec-title text-center">
                    <h2 class="wow animated bounceInLeft">WEBSITE ĐẶT HÀNG</h2>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12 text-center wow animated zoomIn" data-wow-delay="0.3s">
                    <div class="service-item">
                        <img src="{{ asset('images/home/taobao.png')  }}">


                        <p>Hàng về HN chỉ 5-7 ngày, về HCM 8-10 ngày sau khi đặt hàng...</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12 text-center wow animated zoomIn" data-wow-delay="0.6s">
                    <div class="service-item">
                        <img src="{{ asset('images/home/tmall.png')  }}">

                        <p>Chất lượng và dịch vụ tốt nhất, đảm bảo uy tín cao...</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12 text-center wow animated zoomIn">
                    <div class="service-item">

                        <img src="{{ asset('images/home/1688.png')  }}">


                        <p>Giá cả cạnh tranh đảm bảo rẻ nhất trên thị trường order...</p>
                    </div>
                </div>







            </div>
        </div>
    </section>
    <!-- end Service section -->

    <!-- Social section -->
    <section id="social" class="parallax">
        <div class="overlay">
            <div class="container">
                <div class="row">

                    <div class="text-center white wow animated fadeInDown">
                        <p style="font-size: 20px;line-height: 30px;color: #fff;">

                            NhatMinh247 là đơn vị chuyên nghiệp trong lĩnh vực đặt hàng, nhập hàng Trung Quốc giá rẻ, order hàng trên tất cả các website thương mại điện tử tại Trung Quốc: Taobao.com, 1688.com, Tmall.com…

                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- end Social section -->

    <!-- Service2 section -->
    <section id="service2" style="background: rgba(38, 38, 38, 0.04);">
        <div class="overlay">
            <div class="container">
                <div class="row">

                    <div class="sec-title text-center">
                        <h2 class="wow animated bounceInLeft">Dịch vụ</h2>

                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12 text-center wow animated zoomIn">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fa fa-shopping-cart fa-3x"></i>
                            </div>
                            <h3>Order Hàng</h3>
                            <p>Dịch vụ đặt hàng trực tiếp trên các trang taobao.com, 1688.com với phí dịch vụ rẻ nhất dánh cho khách hàng ...</p>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12 text-center wow animated zoomIn" data-wow-delay="0.3s">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fa fa-truck fa-3x"></i>
                            </div>
                            <h3>Ship hộ</h3>
                            <p>Dịch vụ chuyển hàng từ TQ về VN với giá thành thấp nhất, đảm bảo uy tín, thời gian, vận chuyển trên toàn quốc ...</p>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12 text-center wow animated zoomIn" data-wow-delay="0.6s">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fa fa-clock-o fa-3x"></i>
                            </div>
                            <h3>Tìm nguồn hàng</h3>
                            <p>Dịch vụ tìm nguồn hàng, đàm phán, ký kết hợp đồng với Nhà Cung Cấp uy tín tại Trung Quốc ...</p>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12 text-center wow animated zoomIn" data-wow-delay="0.9s">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fa fa-dollar fa-3x"></i>
                            </div>

                            <h3>Đổi tiền</h3>
                            <p>Dịch vụ chuyển tiền hai chiều Việt Nam - Trung Quốc đảm bảo nhanh chóng, an toàn, chuyên nghiệp.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- end Service section -->

    <!-- portfolio section -->
    <section id="portfolio" class="">
        <div class="container">
            <div class="row">

                <div class="sec-title text-center wow animated fadeInDown">
                    <h2>Lý do khách hàng chọn chúng tôi</h2>
                    <p>
                        - Đặt đơn hàng thuận tiện nhanh chóng với nhiều tiện ích hỗ trợ.
                    </p>
                    <p>
                        - Quản lý đơn hàng, Vận chuyển, Tài chính chuyên nghiệp, dễ dàng theo dõi.
                    </p>
                    <p>
                        - Có chính sách bảo hiểm rủi ro hàng hóa.
                    </p>
                    <p>
                        - Đội ngũ nhân viên hỗ trợ chăm sóc chu đáo nhiệt tình 24/7.
                    </p>
                </div>


                <ul class="project-wrapper wow animated fadeInUp">
                    <li class="portfolio-item">
                        <img src="{{ asset('images/home/ld1.png')  }}" class="img-responsive" alt="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat">
                        <figcaption class="mask">

                            <p>Đặt đơn hàng thuận tiện nhanh chóng với nhiều tiện ích hỗ trợ</p>
                        </figcaption>

                    </li>

                    <li class="portfolio-item">
                        <img src="{{ asset('images/home/ld2.png')  }}" class="img-responsive" alt="Lorem Ipsum is simply dummy text of the printing and typesetting ndustry. ">
                        <figcaption class="mask">
                            <p>Quản lý đơn hàng, Vận chuyển, Tài chính chuyên nghiệp, dễ dàng theo dõi.</p>
                        </figcaption>

                    </li>

                    <li class="portfolio-item">
                        <img src="{{ asset('images/home/ld3.jpg')  }}" class="img-responsive" alt="Lorem Ipsum is simply dummy text of the printing and typesetting ndustry. ">
                        <figcaption class="mask">
                            <p>Có chính sách bảo hiểm rủi ro hàng hóa.</p>
                        </figcaption>

                    </li>

                    <li class="portfolio-item">
                        <img src="{{ asset('images/home/ld4.png')  }}" class="img-responsive" alt="Lorem Ipsum is simply dummy text of the printing and typesetting ndustry.">
                        <figcaption class="mask">
                            <p>Đội ngũ nhân viên hỗ trợ chăm sóc chu đáo nhiệt tình 24/7.</p>
                        </figcaption>

                    </li>


                </ul>

            </div>
        </div>
    </section>
    <!-- end portfolio section -->

    <!-- Testimonial section -->
    <section id="testimonials" class="parallax">
        <div class="overlay">
            <div class="container">
                <div class="row">

                    <div class="sec-title text-center white wow animated fadeInDown">
                        <h2>Ý kiến khách hàng</h2>
                    </div>

                    <div id="testimonial" class=" wow animated fadeInUp">

                        <!-- <div class="testimonial-item text-center">
                            <img src="img/member-1.jpg" alt="Our Clients">
                            <div class="clearfix">
                                <span>Nguyễn Đức Thành</span>
                                <p>NhatMinh247 là đơn vị chuyên nghiệp trong lĩnh vực đặt hàng, nhập hàng Trung Quốc giá rẻ, order hàng trên tất cả các website thương mại điện tử tại Trung Quốc: Taobao.com, 1688.com, Tmall.com…</p>
                            </div>
                        </div>

                        <div class="testimonial-item text-center">
                            <img src="img/member-1.jpg" alt="Our Clients">
                            <div class="clearfix">
                                <span>Đinh Trọng Hoàng</span>
                                <p>Đội ngũ nhân viên đặt hàng, nhập hàng Trung Quốc chuyên nghiệp, năng động, trẻ trung, thông thạo và giàu kinh nghiệm đàm phán giao dịch bằng Tiếng Trung.</p>
                            </div>
                        </div> -->

                        <div class="testimonial-item text-center">
                            <img src="{{ asset('images/home/kh2.jpg')  }}" alt="Our Clients">
                            <div class="clearfix">
                                <span>Đinh Trọng Hoàng</span>
                                <p>Chi phí rẻ hơn nhiều khi mua tại shop nên mình đã nhen nhóm tí tưởng order hàng online. Công việc ngày càng thuận lợi hiện giờ mình đã có hẳn 1 hệ thống nhân viên riêng cho shop online. Cảm ơn NhatMinh247 đã mang cho mình cơ hội thành công trên con đường mình đã chọn</p>
                            </div>
                        </div>

                        <div class="testimonial-item text-center">
                            <img src="{{ asset('images/home/kh1.jpg')  }}" alt="Our Clients">
                            <div class="clearfix">
                                <span>Nguyễn Đức Thành</span>
                                <p>Mình đã đặt đèn nội thất qua dịch vụ của NhatMinh247. Đèn treo lên rất đẹp. Chi phí thì chỉ bằng phân nửa so với ngoài cửa hàng bán. Sắp tới mình có còn xây tầng tiếp nữa sẽ đặt toàn bộ nội thất bên bên về luôn. Sản phẩm đẹp , đa dạng còn hạt dẻ nữa.</p>
                            </div>
                        </div>



                        <div class="testimonial-item text-center">
                            <img src="{{ asset('images/home/kh3.jpg')  }}" alt="Our Clients">
                            <div class="clearfix">
                                <span>Ngô Kim Dung</span>
                                <p>Các bạn Chăm Sóc Khách Hàng của NhatMinh247 duyệt đơn cho mình rất nhanh. Chỉ 1 lần hướng dẫn mình đã làm quen với cách tạo đơn hàng và chưa đầy 1 tuần hàng đã về tay mình rồi</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- end Testimonial section -->

    <!-- Contact section -->
    <section id="contact" >
        <div class="container">
            <div class="row">

                <div class="sec-title text-center wow animated fadeInDown">
                    <h2>Liên hệ với chúng tôi</h2>
                </div>


                <div class="col-md-7 contact-form wow animated fadeInLeft">
                    <form action="#" method="post">
                        <div class="input-field">
                            <input type="text" name="name" class="form-control" placeholder="Họ & Tên...">
                        </div>
                        <div class="input-field">
                            <input type="email" name="email" class="form-control" placeholder="Email...">
                        </div>
                        <div class="input-field">
                            <input type="text" name="subject" class="form-control" placeholder="Chủ đề...">
                        </div>
                        <div class="input-field">
                            <textarea name="message" class="form-control" placeholder="Nội dung..."></textarea>
                        </div>
                        <button type="submit" id="submit" class="btn btn-blue btn-effect">GỬI</button>
                    </form>
                </div>

                <div class="col-md-5 wow animated fadeInRight">
                    <address class="contact-details">
                        <h3>Thông tin liên hệ</h3>
                        <p>
                            <i class="fa fa-pencil"></i>
                            8/3 Ngõ 198 Lê Trọng Tấn – Định Công – Hà Nội
                        </p>
                        <br>
                        <p><i class="fa fa-phone"></i>Điện thoại: 0966.986.304 (Zalo, Wechat) </p>
                        <p><i class="fa fa-phone"></i>Hotline: 04.2262.6699 </p>
                        <p><i class="fa fa-envelope"></i>admin@nhatminh247.vn</p>
                    </address>
                </div>

            </div>
        </div>
    </section>
    <!-- end Contact section -->

    <!-- <section id="google-map">
        <div id="map-canvas" class="wow animated fadeInUp"></div>
    </section> -->

</main>

<footer id="footer">
    <div class="container">
        <div class="row text-center">
            <div class="footer-content">
                <!-- <div class="footer-social">
                    <ul>
                        <li class="wow animated zoomIn"><a href="#"><i class="fa fa-thumbs-up fa-3x"></i></a></li>
                        <li class="wow animated zoomIn" data-wow-delay="0.3s"><a href="#"><i class="fa fa-twitter fa-3x"></i></a></li>
                        <li class="wow animated zoomIn" data-wow-delay="0.6s"><a href="#"><i class="fa fa-skype fa-3x"></i></a></li>
                        <li class="wow animated zoomIn" data-wow-delay="0.9s"><a href="#"><i class="fa fa-dribbble fa-3x"></i></a></li>
                        <li class="wow animated zoomIn" data-wow-delay="1.2s"><a href="#"><i class="fa fa-youtube fa-3x"></i></a></li>
                    </ul>
                </div> -->

                <p>Copyright &copy; 2017 Design by <a href="http://nhatminh247.vn">NhatMinh247</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- Essential jQuery Plugins
================================================== -->
<!-- Main jQuery -->
<script src="{{ asset('js/home/jquery-1.11.1.min.js')  }}"></script>
<!-- Twitter Bootstrap -->
<script src="{{ asset('js/home/bootstrap.min.js')  }}"></script>
<!-- Single Page Nav -->
<script src="{{ asset('js/home/jquery.singlePageNav.min.js')  }}"></script>

<!-- Owl Carousel -->
<script src="{{ asset('js/home/owl.carousel.min.js')  }}"></script>
<!-- jquery easing -->
<script src="{{ asset('js/home/jquery.easing.min.js')  }}"></script>
<!-- Fullscreen slider -->
<script src="{{ asset('js/home/jquery.slitslider.js')  }}"></script>
<script src="{{ asset('js/home/jquery.ba-cond.min.js')  }}"></script>
<!-- onscroll animation -->
<script src="{{ asset('js/home/wow.min.js')  }}"></script>
<!-- Custom Functions -->
<script src="{{ asset('js/home/main.js')  }}"></script>
</body>
</html>
