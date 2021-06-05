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

            <a href="<?=URL?>/account.php" class="leading-header-logo">
                <img src="<?=URL?>/assets/images/logo.png" alt="Logo">
            </a>

        </div>
    </header>

    
    <?php if (isset($success) && !empty($success)): ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?=$success?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error<?=count($errors)>1?'s':''?>!</strong> <ul><?php foreach ($errors as $error): ?><li><?=$error?></li><?php endforeach;?></ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 col-lg-6">

                <div class="card">
                    <div class="card-body">
                        
                        <form action="" method="post">
                            
                            <div class="form-group row">
                                <label for="fname" class="col-sm-3 col-form-label">First Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" value="<?=$_POST['fname']??$logged['user_fname']?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lname" class="col-sm-3 col-form-label">Last Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" value="<?=$_POST['lname']??$logged['user_lname']?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" placeholder="Email" value="<?=$logged['user_email']?>" disabled>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" name="update_profile" class="btn btn-primary">Save</button>
                            </div>

                        </form>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-secondary">Change my password</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12">
                
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Sol Title</th>
                                    <th>Date Created</th>
                                    <th>Decision</th>
                                    <th>CC recipients</th>
                                    <th>Send to me</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sols)): foreach ($sols as $sol): ?>
                                <tr>
                                    <td><?=$sol['solicitation_title']?></td>
                                    <td><?=normal_date($sol['solicitation_created'])?></td>
                                    <td><?=$sol['solicitation_calculated_pwin']?></td>
                                    <td><?=$sol['solicitation_cc']?></td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="sol_id" value="<?=$sol['solicitation_id']?>">
                                            <button type="submit" name="send_mail" class="table-btn"><img src="<?=URL?>/assets/images/icons/mail-icon.svg" alt="Send"></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="sol_id" value="<?=$sol['solicitation_id']?>">
                                            <button type="submit" name="delete" class="table-btn"><img src="<?=URL?>/assets/images/icons/delete-icon.svg" alt="Delete"></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center"><i>no record found.</i></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="<?=URL?>/gapinput.php" class="btn btn-secondary">Perform a GAP Analysis</a>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <footer class="footer">
        <div class="footer-inner">

            <div class="footer-text"><?=$settings->website->footer?><div>

        </div>
    </footer>

</body>
</html>