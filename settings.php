<?php
require 'src/functions.php';
//$errors = [];
if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $userid = $_SESSION['user']['id'];
    $friendResult = getFriends($userid);
    $groupResult = getGroups($userid);
}
else {
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
    <title>Dashboard</title>

</head>
<body>

<header>
    <h1>FComms</h1>
</header>
<aside>

</aside>
<main>
    <section>
        <h2>User settings</h2>
        <h3>User Information</h3>
    </section>
    <input type="text" placeholder="Username"> <br>
    <input type="email" placeholder="Email"> <br>
    <input type="text" placeholder="Firstname"> <br>
    <input type="text" placeholder="Lastname"> <br>
    <input class="telephone-code" type="text" placeholder="Code" name="telephone1" value=""><input
            type="tel" placeholder="Telephone Number" class="telephone-number" name="telephone2"> <br>

    <section>
    <h4>User Privacy</h4>
        <input type="checkbox" class="privacy-checkbox"> Private account.
    </section>

    <section>
        <h4>User Safety</h4>
        <p>Change Password?</p>
    </section>
</main>
<aside>


</aside>
<footer>
    <script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('change', '.privacy-checkbox', function(){
                let obj = $(this)
                // console.log($('.privacy-checkbox').is(':checked'))
                // console.log($('.privacy-checkbox').is(':checked'))

                if ($('.privacy-checkbox').is(':checked')){

                $.post("ajax.php",
                    {
                        checked: "1",
                        action: "private_account"
                    })
            }
            else
            {
                $.post("ajax.php",
                    {
                        checked: "0",
                        action: "private_account"
                    })
            }
            })




            });
    </script>
</footer>
</body>
</html>