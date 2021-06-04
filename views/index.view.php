<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Solicitation Bid/No Bid Decision Support Tool</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="<?=URL?>/assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</head>

<body>

    <header class="landing-header">
        <div class="landing-header-inner">

            <div class="landing-header-text">
                <h3>Welcome to the</h3>
                <h1>Solicitation Bid/No Bid Decision Support Tool</h1>
            </div>

            <a href="<?=URL?>" class="leading-header-logo">
                <img src="<?=URL?>/assets/images/logo.png" alt="Logo">
            </a>

        </div>
    </header>

    <section class="admin-message">
        <div class="admin-message-inner">
            <div class="admin-message-text"><?= $admin_message ?></div>
            <div class="admin-message-button">
                <a href="https://yourwebsite.com/" target="_blank">Visit our website</a>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-md-6 offset-2">

                <div class="card bg-light">
                    <div class="card-body">
                        <form method="POST" action="">
                            <?php if (isset($success) && !empty($success)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> <?=$success?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php if ((isset($_POST['login']) || !isset($_POST['signup'])) && !empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error<?=count($errors)>1?'s':''?>!</strong> <ul><?php foreach ($errors as $error): ?><li><?=$error?></li><?php endforeach;?></ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?= $_POST['email'] ?? '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?= $_POST['password'] ?? '' ?>">
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </form>
                    </div>

                    <div class="card-footer text-muted">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#signupModal">Click Here to Register to perform GAP analysis of a Solicitation</button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal -->

    <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="POST" action="" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalTitle">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <?php if (isset($_POST['signup']) && !empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error<?=count($errors)>1?'s':''?>!</strong> <ul><?php foreach ($errors as $error): ?><li><?=$error?></li><?php endforeach;?></ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" value="<?= $_POST['fname'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" value="<?= $_POST['lname'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="remail">Email address</label>
                        <input type="email" class="form-control" id="remail" name="remail" placeholder="Enter email" value="<?= $_POST['remail'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="rpassword">Password</label>
                        <input type="password" class="form-control" id="rpassword" name="rpassword" placeholder="Password" value="<?= $_POST['rpassword'] ?? '' ?>">
                        <small id="passwordHelp" class="form-text text-muted">Must be at least 8 characters long.</small>
                    </div>
                    <div class="form-group">
                        <label for="rrepassword">Confirmation</label>
                        <input type="password" class="form-control" id="rrepassword" name="rrepassword" placeholder="Password" value="<?= $_POST['rrepassword'] ?? '' ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="signup" class="btn btn-primary">Signup</button>
                </div>
            </form>
        </div>
    </div>

    <!-- End Modal -->

    <?php if (isset($_POST['signup']) && !empty($errors)): ?>
        <script>
            $('#signupModal').modal('show')
        </script>
    <?php endif; ?>


    <footer class="footer">
        <div class="footer-inner">

            <div class="footer-text"><?= $footer_message ?></div>

        </div>
    </footer>

</body>
</html>
