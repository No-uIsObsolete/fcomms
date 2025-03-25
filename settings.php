<?php
require 'src/functions.php';
//$errors = [];
if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $userid = $_SESSION['user']['id'];
    $friendResult = getFriends($userid);
    $groupResult = getGroups($userid);
    $username = $_SESSION['user']['username'];
    $first_name = $_SESSION['user']['firstname'];
    $last_name = $_SESSION['user']['lastname'];
    $email = $_SESSION['user']['email'];
    $telephone = $_SESSION['user']['telephone'];
} else {
    header('Location: index.php');
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body data-bs-theme="dark">

<div class="row">
    <header class="bg-success text-white sticky-top border-bottom border-success-subtle">
        <div class="row">
            <div class="col-2">
                <h1 class="ps-2 pt-1">FComms</h1>
            </div>
            <div class="col-8">
                <hr class="d-none">
            </div>
            <div class="col-2 pt-1 mt-2">
                <div class="dropdown float-end me-4">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle show"
                       data-bs-toggle="dropdown" aria-expanded="true">
                        <img src="assets/css/default.png" alt="pfp" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small" data-popper-placement="top-start"
                        style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(0px, -33.6px, 0px);">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</div>
<div class="row ">
    <div class="col-4"> </div>
    <main class="col-4 pt-2">
        <div class="center-block">
        <div>
            <p class="h2">User settings</p>
            <hr>
            <p class="h3">User Information</p>
        </div>
        <div>
            <input type="text" placeholder="Username" name="username" class="form-control mb-3" value="<?php echo $username ?>">
            <input type="email" placeholder="Email" name="email" class="form-control mb-3" value="<?php echo $email ?>">
            <input type="text" placeholder="Firstname" name="firstname" class="form-control mb-3" value="<?php echo $first_name ?>">
            <input type="text" placeholder="Lastname" name="lastname" class="form-control mb-3" value="<?php echo $last_name ?>">
            <label for="currentTelephone"> Current Telephone Number: </label> <br> <input type="text" class="form-control disabled mb-3" value="<?php echo $telephone ?>" disabled readonly>
            <div class="input-group mb-3">


                <input class="form-control w-25" type="text" placeholder="Code" name="telephone1" value=""><input
                        type="tel" placeholder="Telephone Number" class="form-control w-75" name="telephone2"></div>
            <hr>
        </div>

        <div>
            <p class="h5">User Privacy</p>
            <div class="form-check">
            <input type="checkbox" class="form-check-input"> Private account.
            </div>
            <hr>
        </div>

        <div>
            <p class="h5">User Safety</p>
            <button class="btn btn-secondary btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#password-collapse" aria-expanded="false">
                Change Password
            </button>
            <div class="collapse" id="password-collapse" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li>
                            <input type="password" placeholder="New Password" name="password" class="form-control mt-3 mb-3">
                            <input type="password" placeholder="Repeat Password" name="passwordRepeat" class="form-control mb-3">
                            <button submit="newPass" class="btn btn-secondary">Submit</button>
                    </li>
                </ul>

            </div>
            <hr>
        </div>
        </div>
    </main>
    <div class="col-4"> </div>
</div>
<div class="row">
    <footer class="bg-success text-white col fixed-bottom border-top border-success-subtle">
        <div class="container-fluid">
            <hr>
        </div>

    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="assets/js/jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change', '.form-check-input', function () {
            let obj = $(this)
            // console.log($('.privacy-checkbox').is(':checked'))
            // console.log($('.privacy-checkbox').is(':checked'))

            if ($('.privacy-checkbox').is(':checked')) {

                $.post("ajax.php",
                    {
                        checked: "1",
                        action: "private_account"
                    })
            } else {
                $.post("ajax.php",
                    {
                        checked: "0",
                        action: "private_account"
                    })
            }
        })


    });
</script>
</body>
</html>