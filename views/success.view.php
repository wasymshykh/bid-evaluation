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

    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-2 mt-4">
            
                <div class="card">
                    <div class="card-header text-white bg-success">
                        Success!
                    </div>
                    <div class="card-body">
                        <p>We have successfully sent you the email on selected address with pdf attached.</p>

                        <a href="<?=URL?>/account.php" class="btn btn-secondary">Go back to account</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>