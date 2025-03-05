<!doctype html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset</title>

</head>
<body>
<main>
    <section class="RegisterBox">
        <h2>FComms</h2>
        <section>
            <form action="/set-password.php" method="post">
                <p>
                    Password Reset
                </p>
                <input type="Password" placeholder="New Password" name="password"> <br>
                <input type="Password" placeholder="New Repeat Password" id="PassRepeat"> <br>
                <input type="submit" value="Reset Password">
            </form>
        </section>
    </section>
</main>
</body>
</html>