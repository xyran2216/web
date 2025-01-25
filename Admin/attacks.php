<?php
$page = 'Running attacks';
include "admin.php";
if (!isset($info)){
    die("invalid info");
}
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Send</h4>
                        <div class="ml-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item text-primary active"
                                        aria-current="page"><?php echo $info["nom"]; ?></li>
                                    <li class="breadcrumb-item text-muted" aria-current="page">
                                        / <?php echo $page; ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card" style="height: 95.2%;">
                    <div class="card-header">
                        <div class="card-title">Daily history</div>
                    </div>
                    <div class="card-body" id="attacks">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>      <!-- partial:partials/_footer.html -->
<script>
    setInterval(() => {
        $.ajax("ajax/running", {
            "method": "GET", "success": data => {
                $("#attacks").html(data)
            },
        });
    }, 1000)

    function stopAttack(id) {
        $(`#stop-${id}`).attr("disabled", true);
        $.ajax("ajax/running", {
            "method": "POST",
            "data": `${id}=true`,
            "success": data => {
                $("#attacks").html(data)
            },
            "error": data=>{
                $(`#stop-${id}`).attr("disabled", false);
            }
        });
    }
</script>
<?php include 'footer.php'; ?>

</div>
</div>

<div class="rightbar-overlay"></div>
</body>
</html>


