<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Multiflex - Subscription</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<!--? Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="assets/img/logo/loder.png" alt="">
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
                <div class="menu-wrapper d-flex align-items-center justify-content-between mt-4">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="index.html"><img src="assets/img/logo/logo.png" alt=""></a>
                    </div>
                    <!-- Header-btn -->
                    <div class="header-btns d-none d-lg-block f-right">
                        <a href="/" class="btn">Return Home</a>
                    </div>
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
    <!--? Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70">
                            <h2>Subscription</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->
    <!--?  Contact Area start  -->
    <section class="contact-section ml-auto mr-auto">
        <div style="width: 90%" class="d-flex align-items-center justify-content-center ml-auto mr-auto">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">Subscription</h2>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                </div>
                <div class="col-lg-12 row m-4">
                    @php
                        $offer = App\Models\FitnessOffer::find(request('offer_id'));
                    @endphp
                    @if ($offer)
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4 p-4 border">
                            <div class="properties__card">
                                <center>
                                    <div class="about-icon"
                                        style="background: red; width: fit-content; padding: 1rem; border-radius: 50%;">
                                        <img src="assets/img/icon/price.svg" alt="">
                                    </div>
                                </center>
                                <br>
                                <center>
                                    <div class="properties__caption pl-4">
                                        <span id="monthSelected"
                                            class="month">{{ $offer->duration_days >= 30 ? intval($offer->duration_days / 30) . ' month' . (intval($offer->duration_days / 30) > 1 ? 's' : '') : intval($offer->duration_days / 7) . ' week' . (intval($offer->duration_days / 7) > 1 ? 's' : '') }}</span>
                                        <p id="priceTotal" class="mb-25" style="font-size: 2rem; font-weight: 700;">PHP
                                            {{ $offer->price }}</p>
                                        <div class="features-caption">
                                            <p>{{ $offer->description[0]['fitness_offered'] ?? 'No description' }}</p>
                                            @forelse ($offer->description[0]['includes'] as $include)
                                                <div class="single-features row">
                                                    <div class="features-icon">
                                                        <img src="assets/img/icon/check.svg" alt="">
                                                    </div>
                                                    <div class="features-caption">
                                                        <p class="ml-2"> {{ $include['sub_fitness_offered'] }} </p>
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                        <form class="form-contact contact_form col-lg-4 col-md-6 col-sm-6"
                            action="{{ route('subscription.store') }}" method="post" id="subscriptionForm"
                            enctype="multipart/form-data" novalidate="novalidate">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-sm-6 mb-3">
                                    <label for="name" class="form-label fw-bold">Name</label>
                                    <input class="form-control border border-2 border-primary" name="name"
                                        id="name" type="text" placeholder="Enter Name"
                                        value="{{ auth()->user()->name }}" readonly>
                                </div>

                                <!-- Address -->
                                <div class="col-sm-6 mb-3">
                                    <label for="address" class="form-label fw-bold">Address</label>
                                    <input class="form-control border border-2 border-primary" name="address"
                                        id="address" value="{{ auth()->user()->address }}" type="text"
                                        placeholder="Enter Address" required>
                                </div>
                                <!-- Email Address -->
                                <div class="col-sm-6 mb-3">
                                    <label for="address" class="form-label fw-bold">Address</label>
                                    <input class="form-control border border-2 border-primary" name="email"
                                        id="email" value="{{ auth()->user()->email }}" type="text"
                                        placeholder="Enter email" required>
                                </div>

                                <!-- Subscription Months -->
                                <div class="col-sm-6 mb-3">
                                    <label for="subscription_months" class="form-label fw-bold">Subscription
                                        Duration</label>
                                    <select class="form-select border border-2 border-primary"
                                        name="subscription_months" id="subscription_months" required>
                                        <option value="{{ $offer->duration_days }}" selected>
                                            {{ $offer->duration_days >= 30 ? intval($offer->duration_days / 30) . ' month' . (intval($offer->duration_days / 30) > 1 ? 's' : '') : intval($offer->duration_days / 7) . ' week' . (intval($offer->duration_days / 7) > 1 ? 's' : '') }}
                                        </option>
                                        <option value="{{ $offer->duration_days * 2 }}">
                                            {{ $offer->duration_days * 3 >= 30 ? intval(($offer->duration_days * 2) / 30) . ' month' . (intval(($offer->duration_days * 2) / 30) > 1 ? 's' : '') : intval(($offer->duration_days * 2) / 7) . ' week' . (intval(($offer->duration_days * 2) / 7) > 1 ? 's' : '') }}
                                        </option>
                                        <option value="{{ $offer->duration_days * 3 }}">
                                            {{ $offer->duration_days * 2 >= 30 ? intval(($offer->duration_days * 3) / 30) . ' month' . (intval(($offer->duration_days * 3) / 30) > 1 ? 's' : '') : intval(($offer->duration_days * 3) / 7) . ' week' . (intval(($offer->duration_days * 3) / 7) > 1 ? 's' : '') }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Start Date -->
                                <div class="col-sm-6 mb-3">
                                    <label for="start_date" class="form-label fw-bold">Start Date</label>
                                    <input class="form-control border border-2 border-primary" name="start_date"
                                        id="start_date" type="date" min="{{ now()->format('Y-m-d') }}"
                                        placeholder="Select Start Date" required>
                                </div>


                                <!-- Reference -->
                                <div class="col-sm-6 mb-3">
                                    <label for="reference" class="form-label fw-bold">Reference</label>
                                    <input class="form-control border border-2 border-primary" name="reference"
                                        id="reference" placeholder="{{ md5(now() . $offer->id) }}" type="text"
                                        value="" required>
                                </div>

                                <!-- Proof of Payment -->
                                <div class="col-sm-12 mb-3">
                                    <label for="proof_of_payment" class="form-label fw-bold">Proof of Payment</label>
                                    <input class="form-control border border-2 border-primary" name="proof_of_payment"
                                        id="proof_of_payment" type="file" accept="image/*,.pdf" required>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                            </div>
                        </form>
                    @else
                        <p>Offer not found.</p>
                    @endif
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <img style="width: 100%; height: 100%;" src="/index.png" alt="" class="section-bg">
                    </div>
                </div>

            </div>
        </div>
    </section>
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
                                <a href="index.html"><img src="assets/img/logo/logo2_footer.png" alt=""></a>
                            </div>
                            <!-- Menu -->
                            <!-- social -->
                            <div class="footer-social mt-30 wow fadeInUp" data-wow-duration="3s"
                                data-wow-delay=".8s">
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="https://bit.ly/sai4ull"><i class="fab fa-facebook-f"></i></a>
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
<style>
    .error {
        color: rgb(211, 4, 4) !important;
    }

    .input-group .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
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

<!-- Nice-select, sticky -->
<script src="./assets/js/jquery.nice-select.min.js"></script>
<script src="./assets/js/jquery.sticky.js"></script>
<script src="./assets/js/jquery.magnific-popup.js"></script>

<!-- contact js -->
<script src="./assets/js/contact.js"></script>
<script src="./assets/js/jquery.form.js"></script>
<script src="./assets/js/jquery.validate.min.js"></script>
<script src="./assets/js/mail-script.js"></script>
<script src="./assets/js/jquery.ajaxchimp.min.js"></script>

<!-- Jquery Plugins, main Jquery -->
<script src="./assets/js/plugins.js"></script>
<script src="./assets/js/main.js"></script>

<script>
    $(document).ready(function() {
        var baseDays = {{ $offer->duration_days ?? 0 }};
        var basePrice = {{ $offer->price ?? 0 }};

        function updateDisplay(selectedDays) {
            var multiplier = selectedDays / baseDays;
            var newPrice = basePrice * multiplier;
            $('#priceTotal').text('PHP ' + newPrice.toFixed(2));

            var months = selectedDays >= 30 ? Math.floor(selectedDays / 30) : 0;
            var weeks = selectedDays >= 7 ? Math.floor(selectedDays / 7) : 0;
            var displayText = '';
            if (months > 0) {
                displayText = months + ' month' + (months > 1 ? 's' : '');
            } else if (weeks > 0) {
                displayText = weeks + ' week' + (weeks > 1 ? 's' : '');
            } else {
                displayText = selectedDays + ' day' + (selectedDays > 1 ? 's' : '');
            }
            $('#monthSelected').text(displayText);
        }

        $('#subscription_months').on('change', function() {
            var selectedDays = parseInt($(this).val());
            updateDisplay(selectedDays);
        });

        // Initial display
        updateDisplay(baseDays);

        // Initialize datepicker for start_date
        $('#start_date').datepicker({
            format: 'yyyy-mm-dd',
            minDate: new Date()
        });
    });
</script>

</body>

</html>
