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
    $firstname = $_SESSION['user']['firstname'];
    $lastname = $_SESSION['user']['lastname'];
    $email = $_SESSION['user']['email'];
    $telephone = $_SESSION['user']['telephone'];

    $privateAccount = checkPrivateAccount($userid);





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

<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="check-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>



<div class="row">
    <header class="bg-success text-white sticky-top border-bottom border-success-subtle">
        <div class="row">
            <div class="col-2">
                <h1 class="ps-2 pt-1">FComms</h1>
            </div>
            <div class="col-8">


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
            <input type="text" placeholder="Username" name="username" class="form-control mb-3 is-valid" value="<?php echo $username ?>">
            <input type="email" placeholder="Email" name="email" class="form-control mb-3 is-valid" value="<?php echo $email ?>">
            <input type="text" placeholder="Firstname" name="firstname" class="form-control mb-3 is-valid" value="<?php echo $firstname ?>">
            <input type="text" placeholder="Lastname" name="lastname" class="form-control mb-3 is-valid" value="<?php echo $lastname ?>">
            <input type="tel" placeholder="Telephone Number" class="form-control " name="telephone" value="<?php echo $telephone?>">
            <button type="submit" action="update-account-details" submit="update">Update</button>
            <hr>
        </div>

        <div>
            <p class="h5">User Privacy</p>
            <div class="form-check">
                <?php if ($privateAccount[0]['private_account'] != 0) {
                    echo '<input type="checkbox" class="form-check-input" private-account="1" value="true" checked>';
                }
                else {
                    echo '<input type="checkbox" class="form-check-input" private-account="0" value="false">';
                }?>
                Private account.

<!--                <input type="checkbox" class="form-check-input" private-account="0" > -->
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
                        <div class="mt-3 mb-3">
                            <input type="password" placeholder="Current Password" name="currentPassword" class="form-control">
                            <div class="invalid-feedback" feedback="currentPassword">

                            </div>
                        </div>

                        <div class="mt-3 mb-3">
                            <input type="password" placeholder="New Password" name="password" class="form-control">
                            <div class="invalid-feedback" feedback="password">

                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="password" placeholder="Repeat Password" name="passwordRepeat" class="form-control">
                            <div class="invalid-feedback" feedback="passwordRepeat">

                            </div>
                        </div>
                            <button submit="newPass" class="btn btn-secondary" action="settings_new_password">Submit</button>
                    </li>
                </ul>

            </div>
            <hr>
        </div>
        </div>
    </main>
    <div class="col-4">
        <div class="alert alert-success float-end m-2" id="passAlert" role="alert" style="display: none;">
            <h4 class="alert-heading">Success!</h4>
            <hr>
            <p class="mb-0">Password was changed successfully <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></p>
        </div> </div>
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
        $(document).on('change', 'input[private-account]', function () {
            let obj = $(this)
            // console.log($('.privacy-checkbox').is(':checked'))
            // console.log($('.privacy-checkbox').is(':checked'))

            if ($('input[private-account]').is(':checked')) {

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

        $(document).on('click', 'input[name="currentPassword"]', function () {
            $(this).attr("class", "form-control")
            $('div[password_current_feedback]').html('')
        });

        $(document).on('click', 'input[name="password"]', function () {
            $(this).attr("class", "form-control")
            $('div[password_feedback]').html('')
        });

        $(document).on('click', 'input[name="passwordRepeat"]', function () {
            $(this).attr("class", "form-control")
            $('div[password_repeat_feedback]').html('')
        });



        $(document).on('click', 'button[submit="newPass"]', function () {
            let action = $(this).attr('action')
            let pass_wordCurrent = $('input[name="currentPassword"]').val()
            let pass_word = $('input[name="password"]').val()
            let pass_wordRepeat = $('input[name="passwordRepeat"]').val()
            // console.log(pass_word)
            // console.log(pass_wordRepeat)
            $.post("ajax.php",
                {
                    current_password: pass_wordCurrent,
                    password: pass_word,
                    password_repeat: pass_wordRepeat,
                    action: action
                },
                function (data) {

                    if (typeof data.status !== 'undefined' && data.status === 'success') {

                        $('input[name="currentPassword"]').val("")
                        $('input[name="password"]').val("")
                        $('input[name="passwordRepeat"]').val("")
                        $('#passAlert').show()

                        const alert = bootstrap.Alert.getOrCreateInstance('#passAlert')


                        setTimeout(function(){


                            alert.close();
                        }, 5000);

                    }
                    if (typeof data.status !== 'undefined' && data.status === 'failure') {

                        $.each(data.errors, function (key, value) {
                            $('input[name="'+key+'"]').attr("class", "form-control is-invalid")
                            $('div[feedback="'+key+'"]').html(value)
                            $('div[feedback="'+key+'"]').show()

                        })
                        $.each(data.correct, function (key, value) {
                            $('input[name="'+key+'"]').attr("class", "form-control is-valid")
                            $('div[feedback="'+key+'"]').html('')
                            $('div[feedback="'+key+'"]').hide()

                        })
                    }


                })
        });
        $(document).on('click', 'button[submit="update"]', function () {
            let action = $(this).attr('action')
            let username = $('input[name="username"]').attr('value')
            let email = $('input[name="email"]').attr('value')
            let firstname = $('input[name="firstname"]').attr('value')
            let lastname = $('input[name="lastname"]').attr('value')
            let telephone = $('input[name="telephone"]').attr('value')
            $.post("ajax.php",
                {
                    username: username,
                    email: email,
                    firstname: firstname,
                    lastname: lastname,
                    telephone: telephone,
                    action: action
                },
                function (data) {



                })

        });




    });
</script>
</body>
</html>