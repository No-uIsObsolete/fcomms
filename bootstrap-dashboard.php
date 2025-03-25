<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link
            href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css"
            rel="stylesheet"
    />
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
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle show" data-bs-toggle="dropdown" aria-expanded="true">
                    <img src="assets/css/default.png" alt="pfp" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small" data-popper-placement="top-start" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(0px, -33.6px, 0px);">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
</div>
<div class="row flex-shrink-0 min-vh-100">
<aside class="col-2 float-start pe-0 border-end border-success-subtle bg-success-subtle shadow-lg">
    <ul class="list-group list-group-flush border-success-subtle">
        <li class="list-group-item border-success-subtle bg-success-subtle text-success-emphasis">

            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#friend-collapse" aria-expanded="false">
                Friends
            </button>
            <div class="collapse" id="friend-collapse" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Search</a></li>
                </ul>
            </div>

        </li>
        <li class="list-group-item border-bottom border-success-subtle bg-success-subtle text-success-emphasis">

            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#group-collapse" aria-expanded="false">
                Groups
            </button>
            <div class="collapse" id="group-collapse" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Search</a></li>
                </ul>
            </div>

        </li>
    </ul>
</aside>
<main class="col-10 float-start bg-body">
    <div class="overflow-scroll">
    <hr>
    <h3>Feed</h3>
    <section>
        <hr>
        <!--            <form method="post" action="/dashboard.php">-->
        <section class="textarea-section"><!-- Create the toolbar container -->
            <div id="toolbar">
                <!--                        <button class="ql-bold">Bold</button>-->
                <!--                        <button class="ql-italic">Italic</button>-->
            </div>

            <!-- Create the editor container -->
            <div id="editor" name="postTextArea" >
                <p>Hello World!</p>
                <p>Some initial <strong>bold</strong> text</p>
                <p><br /></p>
            </div>
            <!--                    <textarea name="postTextArea" placeholder="Your post here" cols="144" rows="3"></textarea>-->
            <button type="submit" value="submitPost" class="feed-post-button"> --> </button></section>
        <hr>
    </div>
</main>
</div>
<div class="row">
<footer class="bg-success text-white col fixed-bottom border-top border-success-subtle">
    <div class="container-fluid">
        <hr>
    </div>

</footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
<script src="assets/js/jquery.js"></script>
<script type="text/javascript">
    const quill = new Quill("#editor", {
        theme: "snow",
    });

    $(document).ready(function () {


        $(document).on('click', '.reactions', function () {
            let obj = $(this)
            $.post("ajax.php",
                {
                    post_id: $(this).attr('post-id'),
                    like_type_id: $(this).attr('like-type-id'),
                    action: "change_reaction"
                },
                function (data) {
                    //console.log(data);
                    if (typeof data.status !== 'undefined' && data.status === 'success') {
                        let content = ""
                        // let reactions = JSON.parse(data)
                        // console.log(reactions)
                        $.each(data.data, function (index, reaction) {
                            content += '<b class="reaction_label" id="reaction' + reaction.id + '"> ' + reaction.count + ' </b><img class="reactions" src="' + reaction.icon + '" like-type-id="' + reaction.id + '" post-id="' + obj.attr('post-id') + '\">';

                        })
                        obj.parent('section').html(content)
                    }
                });
        });

        $(document).on('click', 'button[value="submitPost"]', function () {

            $.post("ajax.php",
                {
                    post_content: quill.getSemanticHTML(0),
                    action: "add_post"
                },
                function (data) {
                    if (typeof data.status !== 'undefined' && data.status === 'success') {
                        location.reload()
                    }
                });
        });




        $(document).on('click', 'section[action="loadComment"]', function () {
            let post_id = $(this).attr('comments-for')
            let action = $(this).attr('action')
            $('section[post-id="'+post_id+'"]').toggle()
            $.post("ajax.php",
                {
                    post_id: post_id,
                    action: action
                },
                function (data) {
                    if (typeof data.status !== 'undefined' && data.status === 'success') {
                        location.reload()

                        $('div[comment="' + post_id + '"]').html(data.content)
                    }
                });
        });

        $(document).on('click', 'section[action="addComment"]', function () {
            let post_id = $(this).attr('comments-for')
            let action = $(this).attr('action')
            $('section[post-id="'+post_id+'"]').toggle()
            $.post("ajax.php",
                {
                    action: action
                },
                function (data) {
                    if (typeof data.status !== 'undefined' && data.status === 'success') {
                        location.reload()
                    }


                });
        });






    });


</script>
</body>
</html>
