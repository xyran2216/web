<?php
require 'includes/db.php';
require 'includes/init.php';
if ($user->LoggedIn()) {
    header('Location: home');
    die();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php echo $info["nom"]; ?> | Login Portal</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $info["nom"]; ?> is a premium IP Stresser/Booter that features API access, Browser emulation / CAPTCHA bypass for Layer 7 & Unique Layer 4 DDoS attack methods!"
          name="description"/>
    <meta content="top stresser, stressthem, stress them, stress denial of service, dos, stresser app,booter,ip stresser, stresser, booter, ddos tool, ddos attack, ddos attack online, layer7 ddos, layer4 ddos, api, api access, network stresser, network booter, msctf, best Booter, best stresser, strongest stresser, powerful booter, ddoser, ddos, gbooter, top booter, ipstress, booter, stresser, network stresser, network booter, #Booter, STORM, captcha bypass, drdos,ssyn, dns amplification"
          name="keywords">
    <meta content="<?php echo $info["nom"]; ?>" name="author"/>
    <meta content="<?php echo $info["url"]; ?>" name="application-url">
    <meta content="<?php echo $info["nom"]; ?>" name="application-name">
    <meta content="index, follow" name="robots">
    <meta property="og:title" content="The most advanced IP Booter on the market.">
    <meta property="og:description"
          content="<?php echo $info["nom"]; ?> is a premium IP Stresser/Booter that has browser emulation technology with CAPTCHA bypass for Layer 7 and unique Layer 4 DDoS methods. We provide API access and are always online to stress them all.">
    <meta property="og:image" content="<?php echo '../' . $info["icon"] . '' ?>">
    <meta property="og:url" content="<?php echo $info["nom"]; ?>">
    <meta property="og:type" content="website">
    <link rel="icon" href="<?php echo '../' . $info["icon"] . '' ?>"/>
    <link rel="stylesheet" type="text/css" href="/assets/libs/toastr/build/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/loader.css">
    <link href="/assets/css/bootstrap-dark.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/app-dark.min.css" id="app-style" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="loader">
    <span></span>
    <span></span>
    <span></span>
</div>

<section class="main">
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-circle-shape text-center p-3"><h1
                                            class="text-white text-sans-serif font-weight-extra-bold fs-2 z-index-1 position-relative">
                                        Login Area</h1></div>

                                <div class="card-body pt-0">
                                    <div class="auth-logo">
                                        <a href="/" class="auth-logo-light">
                                            <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="assets/images/logo-light.svg" alt="" class="rounded-circle"
                                                 height="34">
                                        </span>
                                            </div>
                                        </a>
                                        <div class="text-end"><a href="/register.php" class="btn btn-primary"><i
                                                        class="fas fa-user-plus"></i> Register</a>

                                        </div>
                                        <div class="p-2">
                                            <form method="POST" id="login">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" name="username"
                                                           placeholder="Enter username">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Password</label>
                                                    <div class="input-group auth-pass-inputgroup">
                                                        <input type="password" class="form-control" name="password"
                                                               placeholder="Enter password">
                                                        <button class="btn btn-light" type="button" id="password-addon">
                                                            <i class="mdi mdi-eye-outline"></i></button>
                                                        <button type="button" class="btn btn-light right-bar-toggle">
                                                            <i class="bx bx-cog bx-spin"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mt-3 d-grid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button id="submit" onclick="return submitLogin()"
                                                                    type="submit"
                                                                    class="btn btn-primary waves-effect waves-light w100">
                                                                Login
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div> <!-- end preview-->
            <div class="right-bar">
                <div data-simplebar class="h-100">
                    <div class="rightbar-title d-flex align-items-center px-3 py-4">

                        <h5 class="m-0 me-2">Settings</h5>

                        <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                            <i class="mdi mdi-close noti-icon"></i>
                        </a>
                    </div>

                    <!-- Settings -->
                    <hr class="mt-0"/>
                    <h6 class="text-center mb-0">Choose Layouts</h6>

                    <div class="p-4">
                        <div class="mb-2">
                            <img src="assets/images/layouts/layout-1.jpg" class="img-fluid img-thumbnail" alt="">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch">
                            <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                        </div>

                        <div class="mb-2">
                            <img src="assets/images/layouts/layout-2.jpg" class="img-fluid img-thumbnail" alt="">
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch"
                                   data-bsStyle="assets/css/bootstrap-dark.min.css"
                                   data-appStyle="assets/css/app-dark.min.css" checked>
                            <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                        </div>


                    </div>

                </div> <!-- end slimscroll-menu-->
            </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
<script src="/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/libs/toastr/build/toastr.min.js"></script>
<script src="/assets/js/toastr.js"></script>
<script src="/assets/js/query.min.js"></script>
<script src="/assets/js/app.js"></script>
<script src="assets/toastr/toastr.min.js"></script>
<script>
    function submitLogin() {
        const submit = $("#submit");
        submit.attr("disabled", true);
        $.ajax("/ajax/login.php", {
            "type": "POST",
            "data": $("#login").serialize(),
            "error": data => {
                submit.attr("disabled", false);
                toastr["error"]("An error acquired while authentication, please contact admin.")

                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            },
            "success": data => {
                if (data["success"]) {
                    toastr["success"]("Login success Redirecting...!")

                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    window.location = "/home"
                    return;
                }
                submit.attr("disabled", false);

                toastr["error"](data["error"])

                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            }
        })
        return false;
    }
</script>
</body>
</html>