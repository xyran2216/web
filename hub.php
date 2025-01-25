<?php
$page = "Attack Hub";
include 'header.php';
if (!$user->hasMembership($sql)) {
    header('Location: shop');
    die();
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">ATTACK HUB</h4>
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
            <div class="row">
                <div class="col-xl-5">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Stresser Form
                            </div>
                        </div>

                        <div class="card-body">

                            <ul class="nav nav-pills nav-justified" role="tablist">
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#l4" role="tab"
                                       aria-selected="true">
                                        <span class="d-block d-sm-none">Layer 4</span>
                                        <span class="d-none d-sm-block">Layer 4</span>
                                    </a>
                                </li>
                                <li class="nav-item waves-effect waves-light">
                                    <a class="nav-link" data-bs-toggle="tab" href="#l7" role="tab"
                                       aria-selected="false">
                                        <span class="d-block d-sm-none">Layer 7</span>
                                        <span class="d-none d-sm-block">Layer 7</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content text-muted">
                                <div class="tab-pane active" id="l4" role="tabpanel">
                                    <form method="POST" id="Details4">
                                        <input type="hidden" id="__csrf" name="__csrf"
                                               value="<?php echo $_SESSION['token']; ?>">
                                        <div class="form-group">
                                            <label>Attack Method</label>
                                            <select class="form-control" name="method">
                                                <optgroup label="Layer 4">
                                                    <?php
                                                    $SQLSelect = $sql->query("SELECT * FROM `methodes` WHERE `classement` = 4 ORDER BY `id` ASC");
                                                    while ($show = $SQLSelect->fetch()) {
                                                        $nom = $show['nom'];
                                                        $nommethod = $show['method'];
                                                        ?>
                                                        <option value="<?php echo $nommethod; ?>"><?php echo $nom; ?></option>
                                                    <?php } ?>
                                                </optgroup>

                                            </select>
                                        </div>
                                        <div class="form-group"><label for="address">
                                                Address
                                            </label>
                                            <input type="text" class="form-control" required="" name="host"
                                                   placeholder="127.0.0.1">
                                        </div>
                                        <div class="form-group"><label for="port">
                                                Port
                                            </label>
                                            <input type="text" class="form-control" required="" value="80" name="port"
                                                   placeholder="1-65535">
                                        </div>
                                        <div class="form-group"><label for="duration">
                                                Duration
                                            </label>
                                            <input class="form-range" step="1" min="30" max="<?php
                                            if ($userInfo['mbt'] == "") {
                                                echo "0";
                                            } else {
                                                echo $userInfo['mbt'] + $userInf['secs'];
                                            }
                                            ?>" value="30" oninput="this.form.duration.value=this.value"
                                                   type="range"><input
                                                    class="form-control" required="" name="duration" type="number"
                                                    placeholder="Min: 15, Max: sec">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" id="submit4" name="L4"
                                                        onclick="return startLayer4();"
                                                        class="btn btn-outline-primary waves-effect waves-light btn-block pointer">
                                                    Send Attack
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="l7" role="tabpanel">
                                    <form method="POST" id="Details7">
                                        <input type="hidden" id="__csrf" name="__csrf"
                                               value="<?php echo $_SESSION['token']; ?>">
                                        <div class="form-group">
                                            <label>Attack Method</label>
                                            <select class="form-control" name="method">
                                                <optgroup label="Layer 7">
                                                    <?php
                                                    $SQLSelect = $sql->query("SELECT * FROM `methodes` WHERE `classement` = 7 ORDER BY `id` ASC");
                                                    while ($show = $SQLSelect->fetch()) {
                                                        $nom = $show['nom'];
                                                        $nommethod = $show['method'];
                                                        ?>
                                                        <option value="<?php echo $nommethod; ?>"><?php echo $nom; ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="form-group"><label>
                                                Address
                                            </label>
                                            <input type="text" class="form-control" id="address" required=""
                                                   name="address" maxlength="" value=""
                                                   placeholder="Website: https://example.com/">
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">Duration</label>
                                            <input class="form-range" step="1" min="30" max="<?php
                                            if ($userInfo['mbt'] == "") {
                                                echo "0";
                                            } else {
                                                echo $userInfo['mbt'] + $userInf['secs'];
                                            }
                                            ?>" value="30" oninput="this.form.duration.value=this.value"
                                                   type="range"><input
                                                    class="form-control" required="" id="duration" name="duration"
                                                    type="text"
                                                    placeholder="Min: 15, Max: sec">
                                        </div>
                                        <div class="multi-collapse collapse" id="AdvancedLayer7" style="">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="col-form-label">Request Method</label><br>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mode"
                                                               value="GET" checked="">
                                                        <label class="form-check-label">
                                                            GET
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mode"
                                                               value="POST">
                                                        <label class="form-check-label">
                                                            POST
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mode"
                                                               value="HEAD">
                                                        <label class="form-check-label">
                                                            HEAD
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mode"
                                                               value="DELETE">
                                                        <label class="form-check-label">
                                                            DELETE
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mode"
                                                               value="PUT">
                                                        <label class="form-check-label">
                                                            PUT
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mode"
                                                               value="PATCH">
                                                        <label class="form-check-label">
                                                            PATCH
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 col-12"><label for="rate"
                                                                                            class="col-form-label">Requests
                                                        per IP</label> <input name="rate"
                                                                              placeholder="Min 1, Max: 64 (Optional)"
                                                                              type="text" class="form-control"></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="post">Post Data</label>
                                                <input class="form-control" id="post" name="post" type="text"
                                                       placeholder="username=%RANDOM%&password=password (Optional)">
                                            </div>
                                            <div class="form-group">
                                                <label for="custom_header">Custom Header</label>
                                                <input class="form-control" id="custom_header" name="custom_header"
                                                       type="text" placeholder="SUS=true&x-API=sus (Optional)">
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="submit" id="submit7" name="l7"
                                                        onclick="return startLayer7();"
                                                        class="btn btn-outline-primary waves-effect waves-light btn-block pointer">
                                                    Send Attack
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button"
                                                        class="btn btn-outline-light waves-effect btn-block"
                                                        data-bs-toggle="collapse" href="#AdvancedLayer7" role="button"
                                                        aria-expanded="false" aria-controls="AdvancedLayer7">Advanced
                                                    Options
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-7 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Running Attacks
                            </div>
                            <div class="card-options">
                                <div class="d-flex gap-2 flex-wrap">
                                    <div class="btn-group">
                                        <form method="POST">
                                            <button onclick="return stopAll()" type="submit" name="all"
                                                    class="btn btn-primary"
                                                    id="stop-ALL"
                                            >Stop All Attacks
                                            </button>
                                        </form>
                                        <div class="dropdown-menu">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" id="attacks">

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
<script>
    function stopAttack(id) {
        $(`#stop-${id}`).attr("disabled", true);

        $.ajax(`/reset-api/stop?host=${id}&__csrf=<?php echo $_SESSION['token']; ?>`, {
            "type": "GET",
            "error": data => {
                $(`#stop-${id}`).attr("disabled", false);
                toastr["error"]("An error acquired while stopping attack, please contact admin.")

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
                $(`#stop-${id}`).attr("disabled", false);

                if (data["success"]) {
                    toastr["success"](id !== "ALL" ? "Attack stopped!" : "All Attack stopped!")
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
                    return;
                }
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

    function stopAll() {
        return stopAttack("ALL");
    }

    setInterval(() => {
        $.ajax("/ajax/running_attacks", {
            "method": "GET", "success": data => {
                $("#attacks").html(data)
            },
        });
    }, 1000)

    function startLayer4() {
        $("#submit4").attr("disabled", true);
        $.ajax(`/reset-api/layer4?${$("#Details4").serialize()}`, {
            "type": "GET",
            "error": data => {
                $("#submit4").attr("disabled", false);

                toastr["error"]("An error acquired while starting attack, please contact admin.")

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
                $("#submit4").attr("disabled", false);

                if (data["success"]) {
                    toastr["success"]("Attack started!")

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
                    return;
                }
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

    function startLayer7() {
        $("#submit7").attr("disabled", true);

        $.ajax(`/reset-api/layer7?${$("#Details7").serialize()}`, {
            "type": "GET",
            "error": data => {
                $("#submit7").attr("disabled", false);

                toastr["error"]("An error acquired while starting attack, please contact admin.")

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
                $("#submit7").attr("disabled", false);

                if (data["success"]) {
                    toastr["success"]("Attack started!")

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
                    return;
                }
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
