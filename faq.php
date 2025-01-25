<?php
$page = 'FAQ';
include 'header.php';
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">FAQ</h4>
                        <div class="ml-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item text-primary active"
                                        aria-current="page"><?php echo htmlspecialchars($info["nom"]); ?></li>
                                    <li class="breadcrumb-item text-muted" aria-current="page">
                                        / <?php echo htmlspecialchars($page); ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="checkout-tabs">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                             aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-gen-ques-tab" data-bs-toggle="pill"
                               href="#v-pills-gen-ques" role="tab" aria-controls="v-pills-gen-ques"
                               aria-selected="true">
                                <i class="bx bx-question-mark d-block check-nav-icon mt-4 mb-2"></i>
                                <p class="fw-bold mb-4">General Questions</p>
                            </a>
                            <a class="nav-link" id="v-pills-privacy-tab" data-bs-toggle="pill" href="#v-pills-privacy"
                               role="tab" aria-controls="v-pills-privacy" aria-selected="false">
                                <i class="bx bx-check-shield d-block check-nav-icon mt-4 mb-2"></i>
                                <p class="fw-bold mb-4">Privacy Policy</p>
                            </a>
                            <a class="nav-link" id="v-pills-functions-tab" data-bs-toggle="pill"
                               href="#v-pills-functions" role="tab" aria-controls="v-pills-functions"
                               aria-selected="false">
                                <i class="bx bx-wrench d-block check-nav-icon mt-4 mb-2"></i>
                                <p class="fw-bold mb-4">Functions</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-gen-ques" role="tabpanel"
                                         aria-labelledby="v-pills-gen-ques-tab">
                                        <h4 class="card-title mb-5">General Questions</h4>
                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">Power guarantee</h5>
                                                <p class="text-muted">Giving specific numbers its very hard as they
                                                    depend on many factors, such as where it was tested and the network
                                                    status at that moment, among others. We also dont like to do false
                                                    advertising and giving exaggerated or miss leading numbers (as many
                                                    do). This being said we guarantee a minimum of 500,000 packets per
                                                    second for layer 4 (the gbps will depend on the method) and 20,000
                                                    rqps for layer 7 (usually much higher at around 50,000-100,000). Of
                                                    course this depends on the method and the network used (please refer
                                                    to the documentation for more information on the methods available).
                                                    These estimations are per concurrent and are doubled with each
                                                    attack sent.</p>
                                            </div>
                                        </div>
                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">Bypassing protections</h5>
                                                <p class="text-muted">We focus on providing methods that are capable of
                                                    bypass the most popular DDoS protections, please refer to the
                                                    documentation for more information. Some of the protections
                                                    supported are: Cloudflare (free-enterprise), Cloudflare UAM (js
                                                    challenge), Cloudflare Captcha (hcaptcha & recaptcha), DDoS-Guard
                                                    (JS challenge), Sucuri, Stormwall, Amazon CDN Cloudfront, Imperva
                                                    Incapsula, Akamai, Fastly, Blazingfast, Nooder, React.su, Qrator,
                                                    Arvan Cloud and ANY other automatic browser verification protection.
                                                    In addition we also bypass geo-block based protections for most
                                                    countries, ratelimit based protections, cookie based protections and
                                                    referer based protections. For Layer 4 we down most popular server
                                                    hostings (digital ocean, hetzner, amazon aws (some), google cloud,
                                                    linode, maxihost, some ovh).</p>
                                            </div>
                                        </div>
                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">How to contact us</h5>
                                                <p class="text-muted">You can reach us at our Telegram channel or
                                                    contact me (the owner) directly at @better_owner (telegram)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel"
                                         aria-labelledby="v-pills-privacy-tab">
                                        <h4 class="card-title mb-5">Privacy Policy</h4>

                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">Is your data secured?</h5>
                                                <p class="text-muted">We pride on being one of the most privacy
                                                    respectful stress testing services out there, as well as one of the
                                                    most secure.</p>
                                            </div>
                                        </div>
                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">Do we keep your informations?</h5>
                                                <p class="text-muted">We dont keep IP logs, we dont ask for email and we
                                                    dont invade your privacy with cookies or other tracking systems.</p>
                                            </div>
                                        </div>
                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">What about passwords?</h5>
                                                <p class="text-muted">Passwords are hashed and salted using blowfish,
                                                    everything is coded with privacy and security in mind.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-functions" role="tabpanel"
                                         aria-labelledby="v-pills-functions-tab">
                                        <h4 class="card-title mb-5">Functions</h4>

                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">Account Banned or locked</h5>
                                                <p class="text-muted">what to do ?.</p>
                                                <p class="text-muted">If you have been banned desolate to announce it to
                                                    you but it is impossible that your account is not reactivated more
                                                    our developer work on a brand new autonomous security system that
                                                    scans spam and hack attempts so I advise you to pay attention to
                                                    what you enter the fields before being surprised by a ban.</p>

                                                <p class="text-muted">if you have tried to log in more than 5 times your
                                                    login will block what to do? do not panic your IP has simply been
                                                    temporarily blocked by our system and you will have to wait 24 hours
                                                    before you can reconnect the time to remember your password * and if
                                                    I change IP address * yes you will be able to try again to connect
                                                    except that the concern is that in case of abuse it is your account
                                                    and plans if you have one that will be totally banned.</p>
                                            </div>
                                        </div>
                                        <div class="faq-box media mb-4">
                                            <div class="faq-icon me-3">
                                                <i class="bx bx-help-circle font-size-20 text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="font-size-15">Our payment system</h5>
                                                <p class="text-muted">Our payment system is fully automatic ensuring
                                                    every user gets the desired package at the first confirmation on the
                                                    blockchain, usually within 30 minutes. For privacy and security we
                                                    only accept crypto currency (no we dont accept paypal!), besides
                                                    bitcoin we also accept a variety of other currencies including XMR
                                                    (thought to be the most untraceable currency), if you dont have
                                                    cryptocurrency you can buy it from buybitcoinworldwide.com.</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'footer.php'; ?>
</div>
</div>

</body>
</html>