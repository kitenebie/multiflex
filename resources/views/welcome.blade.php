<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Multiflex </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/gijgo.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/animated-headline.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @media (max-width: 991.98px) {
            .header-btns-mobile {
                display: flex;
                justify-content: flex-end;
                flex-wrap: wrap;
                column-gap: 8px;
                row-gap: 8px;
            }

            .header-btns-mobile .btn {
                height: 30px;
                font-size: 1.5rem;
                padding: 6px 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }
        }

        @media (max-width: 575.98px) {
            .header-btns-mobile {
                width: 100%;
                justify-content: space-between;
            }

            .header-btns-mobile .btn {
                flex: 1;
                text-align: center;
            }
        }
    </style>
</head>

<body class="black-bg">
    <!-- ? Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    {{-- <img src="assets/img/logo/loder.png" alt=""> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->
    <header>
        <!-- Header Start -->
        <div class="header-area header-transparent">
            <div class="main-header header-sticky">
                <div class="container-fluid">
                    <div class="menu-wrapper d-flex align-items-center mt-4 justify-content-between">
                        <!-- Logo -->
                        <div class="logo d-none d-md-block">
                            <a href="index.html"><img src="assets/img/logo/logo.png" alt=""></a>
                        </div>
                        <!-- Header-btn -->
                        @if (!auth()->check())
                            <div class="header-btns d-none d-lg-block f-right">
                                <a href="/login" class="btn">Login</a>
                                <a href="/register" class="btn">Be a member</a>
                            </div>
                            <div class="header-btns header-btns-mobile d-lg-none mt-3">
                                <a href="/login" class="btn btn-sm">Login</a>
                                <a href="/register" class="btn btn-sm">Be a member</a>
                            </div>
                        @else
                            @if (auth()->user()->role == 'coach')
                                <div class="header-btns d-none d-lg-block f-right">
                                    <a href="/app/QR%20Code%20Scanner" class="btn">Dashboard</a>
                                </div>
                                <div class="header-btns header-btns-mobile d-lg-none mt-3">
                                    <a href="/app/QR%20Code%20Scanner" class="btn btn-sm">Dashboard</a>
                                </div>
                            @elseif(auth()->user()->role == 'member')
                                <div class="header-btns d-none d-lg-block f-right">
                                    <a href="/app/My%20QR%20Code" class="btn">Dashboard</a>
                                </div>
                                <div class="header-btns header-btns-mobile d-lg-none mt-3">
                                    <a href="/app/My%20QR%20Code" class="btn btn-sm">Dashboard</a>
                                </div>
                            @else
                                <div class="header-btns d-none d-lg-block f-right">
                                    <a href="/app" class="btn">Dashboard</a>
                                </div>
                                <div class="header-btns header-btns-mobile d-lg-none mt-3">
                                    <a href="/app" class="btn btn-sm">Dashboard</a>
                                </div>
                            @endif
                        @endif
                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->
    </header>
    <main>
        <!--? slider Area Start-->
        <div class="slider-area position-relative">
            <div class="slider-active">
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-9 col-lg-9 col-md-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInLeft" data-delay="0.1s">Hi This is Multiflex</span>
                                    <h1 data-animation="fadeInLeft" data-delay="0.4s">Gym Trainer</h1>
                                    <a href="#offeredSection" class="border-btn hero-btn" data-animation="fadeInLeft"
                                        data-delay="0.8s">Courses Offered</a>
                                    <a href="#pricingSection" class="border-btn hero-btn ml-4"
                                        data-animation="fadeInLeft" data-delay="0.8s">Pricing</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <!-- Traning categories Start -->
        <section class="traning-categories black-bg">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="single-topic text-center mb-30">
                            <div class="topic-img">
                                <img src="assets/img/gallery/cat1.png" alt="">
                                <div class="topic-content-box">
                                    <div class="topic-content">
                                        <h3>Personal traning</h3>
                                        <p>You’ll look at graphs and charts in Task One, how to approach the task and
                                            <br> the language needed for a successful answer.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="single-topic text-center mb-30">
                            <div class="topic-img">
                                <img src="assets/img/gallery/cat2.png" alt="">
                                <div class="topic-content-box">
                                    <div class="topic-content">
                                        <h3>Group traning</h3>
                                        <p>You’ll look at graphs and charts in Task One, how to approach the task and
                                            <br> the language needed for a successful answer.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Traning categories End-->
        <!--? Team -->
        <section id="offeredSection" class="team-area fix">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="1s"
                            data-wow-delay=".1s">
                            <h2>What we Offer</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s"
                            data-wow-delay=".2s">
                            <div class="cat-icon">
                                <img src="assets/img/gallery/team1.png" alt="">
                            </div>
                            <div class="cat-cap">
                                <h5><a href="#">Body Building</a></h5>
                                <p>You’ll look at graphs and charts in Task One, how to approach the task </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s"
                            data-wow-delay=".4s">
                            <div class="cat-icon">
                                <img src="assets/img/gallery/team2.png" alt="">
                            </div>
                            <div class="cat-cap">
                                <h5><a href="#">Muscle Gain</a></h5>
                                <p>You’ll look at graphs and charts in Task One, how to approach the task </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s"
                            data-wow-delay=".6s">
                            <div class="cat-icon">
                                <img src="assets/img/gallery/team3.png" alt="">
                            </div>
                            <div class="cat-cap">
                                <h5><a href="#">Weight Loss</a></h5>
                                <p>You’ll look at graphs and charts in Task One, how to approach the task </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services End -->
        <!--? Gallery Area Start -->
        <div class="gallery-area section-padding30 ">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url(assets/img/gallery/gallery1.png);"></div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h3>Muscle gaining </h3>
                                    <a href="{{ auth()->check() ? '#' : '/register' }}"><i class="ti-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url(assets/img/gallery/gallery2.png);"></div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h3>Muscle gaining </h3>
                                    <a href="{{ auth()->check() ? '#' : '/register' }}"><i class="ti-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url(assets/img/gallery/gallery3.png);"></div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h3>Muscle gaining </h3>
                                    <a href="{{ auth()->check() ? '#' : '/register' }}"><i class="ti-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url(assets/img/gallery/gallery4.png);"></div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h3>Muscle gaining </h3>
                                    <a href="{{ auth()->check() ? '#' : '/register' }}"><i class="ti-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url(assets/img/gallery/gallery5.png);"></div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h3>Muscle gaining </h3>
                                    <a href="{{ auth()->check() ? '#' : '/register' }}"><i class="ti-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url(assets/img/gallery/gallery6.png);"></div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h3>Muscle gaining </h3>
                                    <a href="{{ auth()->check() ? '#' : '/register' }}"><i class="ti-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gallery Area End -->
        <!-- Courses area start -->
        <section id="pricingSection" class="pricing-area section-padding40 fix">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="2s"
                            data-wow-delay=".1s">
                            <h2>Pricing</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @forelse (App\Models\FitnessOffer::get() as $offer)
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="properties mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">
                                <div class="properties__card">
                                    <div class="about-icon">
                                        <img src="assets/img/icon/price.svg" alt="">
                                    </div>
                                    <div class="properties__caption">
                                        <span
                                            class="month">{{ $offer->duration_days >= 30 ? intval($offer->duration_days / 30) . ' month' . (intval($offer->duration_days / 30) > 1 ? 's' : '') : intval($offer->duration_days / 7) . ' week' . (intval($offer->duration_days / 7) > 1 ? 's' : '') }}</span>
                                        <p class="mb-25">PHP {{ $offer->price }}</p>
                                        <div class="single-features">
                                            <div class="features-caption">
                                                <p>{{ $offer->description[0]['fitness_offered'] ?? 'No description' }}
                                                </p>
                                            </div>
                                        </div>
                                        @forelse ($offer->description[0]['includes'] as $include)
                                            <div class="single-features">
                                                <div class="features-icon">
                                                    <img src="assets/img/icon/check.svg" alt="">
                                                </div>
                                                <div class="features-caption">
                                                    <p>{{ $include['sub_fitness_offered'] }} </p>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                        @if (auth()->check() && auth()->user()->role == 'member' && auth()->user()->status == 'active')
                                            @if (auth()->user()->subscriptions()->where('end_date', '>', now())->exists())
                                                <a href="/app/subscriptions" class="border-btn border-btn2">You
                                                    already Subscribed</a>
                                            @else
                                                <a href="/subscription?offer_id={{ $offer->id }}"
                                                    class="border-btn border-btn2">Subscribe Now</a>
                                            @endif
                                        @else
                                            @if (auth()->check() && auth()->user()->status == 'pending')
                                                <a href="#pricingSection" class="border-btn border-btn2">Your accout
                                                    is waiting for Approval</a>
                                            @else
                                                <a href="/register" class="border-btn border-btn2">Join Now</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </section>
        <!-- Courses area End -->
    </main>
    <footer>
        <!--? Footer Start-->
        <div class="footer-area black-bg">
            <div class="container">
                <div class="footer-top footer-padding">
                    <!-- Footer Menu -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="single-footer-caption mb-50 text-center">
                                <!-- logo -->
                                <div class="footer-logo wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">
                                    <a href="index.html"><img src="assets/img/logo/logo2_footer.png"
                                            alt=""></a>
                                </div>
                                <!-- Menu -->
                                <!-- social -->
                                <div class="footer-social mt-30 wow fadeInUp" data-wow-duration="3s"
                                    data-wow-delay=".8s">
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-pinterest-p"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End-->
    </footer>
    <!-- Scroll Up -->
    <div id="back-top">
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->

    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <!-- Jquery Mobile Menu -->
    <script src="./assets/js/jquery.slicknav.min.js"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>

    <!-- Date Picker -->
    <script src="./assets/js/gijgo.min.js"></script>
    <!-- Nice-select, sticky -->
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>

    <!-- counter , waypoint,Hover Direction -->
    <script src="./assets/js/jquery.counterup.min.js"></script>
    <script src="./assets/js/waypoints.min.js"></script>
    <script src="./assets/js/jquery.countdown.min.js"></script>
    <script src="./assets/js/hover-direction-snake.min.js"></script>

    <!-- contact js -->
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>

</body>

</html>
