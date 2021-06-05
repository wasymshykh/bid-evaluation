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
                <h1>Perform a GAP Analysis</h1>
            </div>

            <div class="landing-header-right">
                <div>
                    <a href="<?=URL?>/account.php" class="btn btn-secondary btn-sm">Go to account</a>
                </div>
                <p><small><?=current_date('d M Y h:i A')?></small></p>
            </div>

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

    <div class="container">
        <div class="row my-4">
                
            <div class="card col-lg-12">
                <div class="card-body">
                    
                    <div class="row">
                        <form method="POST" action="" class="col-lg-6">
                            <h6 class="border-bottom pb-2 mb-4">Admin Profile Settings</h6>
                            
                            <?php if (!empty($profile_errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error<?=count($profile_errors)>1?'s':''?>!</strong> <ul><?php foreach ($profile_errors as $error): ?><li><?=$error?></li><?php endforeach;?></ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php endif; ?>

                            <div class="form-group row">
                                <label for="fullname" class="col-lg-3 col-form-label">Admin Name</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Name" value="<?=$_POST['fullname']??$logged['user_fullname']?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-lg-3 col-form-label">Admin Email</label>
                                <div class="col-lg-9">
                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?=$_POST['email']??$logged['user_email']?>">
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" name="update_profile" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        <form method="POST" action="" class="col-lg-5 offset-1 border-left">
                            <h6 class="border-bottom pb-2 mb-4">Admin Password Settings</h6>

                            <?php if (!empty($password_errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error<?=count($password_errors)>1?'s':''?>!</strong> <ul><?php foreach ($password_errors as $error): ?><li><?=$error?></li><?php endforeach;?></ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php endif; ?>

                            <div class="form-group row">
                                <label for="password" class="col-lg-5 col-form-label">New Password</label>
                                <div class="col-lg-7">
                                    <input type="password" name="password" id="password" placeholder="Enter Password" value="<?=$_POST['password']??''?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="repassword" class="col-lg-5 col-form-label">New Password Repeat</label>
                                <div class="col-lg-7">
                                    <input type="password" name="repassword" id="repassword" placeholder="Repeat Password" value="<?=$_POST['repassword']??''?>" class="form-control">
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" name="update_password" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            
            <div class="card col-lg-12 mt-4">
                <form action="" method="post" class="card-body">
                
                    <h6 class="border-bottom pb-2 mb-4">App Interface Details</h6>
                    
                    <?php if (!empty($app_errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error<?=count($app_errors)>1?'s':''?>!</strong> <ul><?php foreach ($app_errors as $error): ?><li><?=$error?></li><?php endforeach;?></ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="txt_welcome">Text to display on the Welcome / Landing Page</label>
                        <textarea name="txt_welcome" class="form-control" id="txt_welcome" rows="3"><?=$_POST['txt_welcome']??$settings->website->welcome?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="txt_footer">Text to display in the footer</label>
                        <textarea name="txt_footer" class="form-control" id="txt_footer" rows="3"><?=$_POST['txt_footer']??$settings->website->footer?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="txt_consider">Text to display in the “What to Consider” Popup</label>
                        <textarea name="txt_consider" class="form-control" id="txt_consider" rows="3"><?=$_POST['txt_consider']??$settings->website->consider?></textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" name="update_settings" class="btn btn-primary">Update Settings</button>
                    </div>

                </form>
            </div>

            <div id="smtp" class="card col-lg-12 mt-4">
                <form action="#smtp" method="post" class="card-body">
                    
                    <div class="row border-bottom mb-4">
                        <h6 class="col col-form-label">Email Connection Settings</h6>
                        <div class="col-sm-3 form-group form-check col-form-label">
                            <input type="checkbox" class="form-check-input" name="email_enable" id="email_enable" <?=isset($_POST['smtp_settings']) ? (isset($_POST['email_enable']) ? 'checked' :'') : ($settings->email->enable ? 'checked' : '')?>>
                            <label class="form-check-label" for="email_enable">Enable Send on Email</label>
                        </div>
                    </div>

                    <?php if (!empty($smtp_errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error<?=count($smtp_errors)>1?'s':''?>!</strong> <ul><?php foreach ($smtp_errors as $error): ?><li><?=$error?></li><?php endforeach;?></ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-8 form-group">
                                    <label for="smtp_server">SMTP server</label>
                                    <input type="text" name="smtp_server" class="form-control" id="smtp_server" value="<?=$_POST['smtp_server']??$settings->email->smtp->server?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="smtp_port">Port</label>
                                    <input type="number" name="smtp_port" class="form-control" id="smtp_port" value="<?=$_POST['smtp_port']??$settings->email->smtp->port?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="smtp_connection_type">Connection Type</label>
                                <select name="smtp_connection_type" class="form-control" id="smtp_connection_type">
                                    <option value="tls" <?=isset($_POST['smtp_connection_type']) ? ($_POST['smtp_connection_type'] == 'tls'?'selected':'') : ($settings->email->smtp->connection_type == 'tls'?'selected':'')?>>TLS</option>
                                    <option value="smtps"<?=isset($_POST['smtp_connection_type']) ? ($_POST['smtp_connection_type'] == 'smtps'?'selected':'') : ($settings->email->smtp->connection_type == 'smtps'?'selected':'')?>>SMTPS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="smtp_auth" name="smtp_auth" <?=isset($_POST['smtp_settings']) ? (isset($_POST['smtp_auth']) ? 'checked' :'') : ($settings->email->smtp->auth ? 'checked' : '')?>>
                                <label class="form-check-label" for="smtp_auth">SMTP server requires authentication</label>
                            </div>

                            <div class="form-group">
                                <label for="smtp_username">Username</label>
                                <input type="text" name="smtp_username" id="smtp_username" class="form-control" value="<?=$_POST['smtp_username']??$settings->email->smtp->username?>">
                            </div>
                            <div class="form-group">
                                <label for="smtp_password">Password</label>
                                <input type="text" name="smtp_password" id="smtp_password" class="form-control" value="<?=$_POST['smtp_password']??$settings->email->smtp->password?>">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="smtp_sender">Email Sender Address</label>
                                <input type="email" name="smtp_sender" class="form-control" id="smtp_sender" value="<?=$_POST['smtp_sender']??$settings->email->sender_address?>">
                            </div>

                            <div class="form-group">
                                <label for="smtp_subject">Email Subject</label>
                                <input type="text" name="smtp_subject" class="form-control" id="smtp_subject" value="<?=$_POST['smtp_subject']??$settings->email->subject?>">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="smtp_sender_name">Email Sender Name</label>
                                <input type="text" name="smtp_sender_name" class="form-control" id="smtp_sender_name" value="<?=$_POST['smtp_sender_name']??$settings->email->sender_name?>">
                            </div>

                            <div class="form-group">
                                <label for="smtp_email_body">Text to display in the email (with the PDF attached)</label>
                                <textarea name="smtp_email_body" class="form-control" id="smtp_email_body" rows="3"><?=$_POST['smtp_email_body']??$settings->email->body?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-lg-12 text-center">
                            <button type="button" class="btn btn-sm btn-secondary">Test Email Connection</button>
                            <button type="submit" class="btn btn-primary ml-2" name="smtp_settings">Update Setting</button>
                        </div>
                    </div>
                    
                </form>
            </div>

        </div>
    </div>

    
</body>
</html>
