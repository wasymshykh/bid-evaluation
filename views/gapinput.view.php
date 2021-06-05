<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Solicitation Bid/No Bid Decision Support Tool</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= URL ?>/assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css" integrity="sha512-3q8fi8M0VS+X/3n64Ndpp6Bit7oXSiyCnzmlx6IDBLGlY5euFySyJ46RUlqIVs0DPCGOypqP8IRk/EyPvU28mQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <header class="landing-header">
        <div class="landing-header-inner">

            <div class="landing-header-text">
                <h1>Perform a GAP Analysis</h1>
            </div>

            <div class="landing-header-right">
                <div>
                    <a href="<?= URL ?>/account.php" class="btn btn-secondary btn-sm">Go to my account</a>
                </div>
                <p><small><?= current_date('d M Y h:i A') ?></small></p>
            </div>

        </div>
    </header>

    <form method="POST" action="" class="container">
        <div class="row my-4">

            <div class="card col-lg-12">
                <div class="card-body">
                    <?php if ((isset($_POST['login']) || !isset($_POST['signup'])) && !empty($errors)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error<?= count($errors) > 1 ? 's' : '' ?>!</strong>
                            <ul><?php foreach ($errors as $error) : ?><li><?= $error ?></li><?php endforeach; ?></ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <h4>Solicitation Details <small>(<span class="text-danger">*</span>fields required)</small></h4>

                            <div class="form-group row mt-4">
                                <label for="sol_number" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Solicitation Number</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="sol_number" name="sol_number" placeholder="Solicitation Number" value="<?= $_POST['sol_number'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sol_title" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Solicitation Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="sol_title" name="sol_title" placeholder="Solicitation Title" value="<?= $_POST['sol_title'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sol_agency" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Agency</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="sol_agency" name="sol_agency" placeholder="Agency" value="<?= $_POST['sol_agency'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sol_due" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Solicitation Due Date</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="sol_due" name="sol_due" placeholder="Agency" value="<?= $_POST['sol_due'] ?? '' ?>" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sol_url" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Solicitation URL</label>
                                <div class="col-sm-6">
                                    <input type="url" class="form-control" id="sol_url" name="sol_url" placeholder="Solicitation URL" value="<?= $_POST['sol_url'] ?? '' ?>" required>
                                </div>
                                <div class="col-sm-3 form-group form-check col-form-label">
                                    <input type="checkbox" class="form-check-input" name="sol_url_check" id="sol_url_check" <?= isset($_POST['sol_url_check']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sol_url_check">sam.gov</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sol_description">Solicitation Description</label>
                                <textarea name="sol_description" id="sol_description" class="form-control" rows="3"><?= $_POST['sol_description'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>GAP Analysis Tool</h6>
                                    <p>A gap analysis delineates each of the requirements and the team contribution to a solution. An important aspect of gap analysis is identifying what needs to be done in a project and estimating success probability.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12" id="requirements">
                            <h4>GAP Analysis</h4>

                            <div class="row border-dashed-bottom requirement">
                                <div class="col-lg-9 px-4">

                                    <div class="row">
                                        <div class="col">

                                            <div class="form-group row">
                                                <label for="sol_req_1" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Requirement 1</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="sol_req_1" name="sol_req[]" value="<?= $_POST['sol_req'][0] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="sol_risks_1" class="col-sm-3 col-form-label">Risks</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="sol_risks_1" name="sol_risks[]" value="<?= $_POST['sol_risks'][0] ?? '' ?>">
                                                </div>
                                                <div class="col-sm-3">
                                                    <select name="sol_risks_type[]" class="form-control">
                                                        <option value="high" <?= (isset($_POST['sol_risks_type'][0]) && $_POST['sol_risks_type'][0] == 'high') ? 'selected' : '' ?>>High</option>
                                                        <option value="med" <?= (isset($_POST['sol_risks_type'][0]) && $_POST['sol_risks_type'][0] == 'med') ? 'selected' : '' ?>>Medium</option>
                                                        <option value="low" <?= (isset($_POST['sol_risks_type'][0]) && $_POST['sol_risks_type'][0] == 'low') ? 'selected' : '' ?>>Low</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="sol_impacts_1" class="col-sm-3 col-form-label">Impacts</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="sol_impacts_1" name="sol_impacts[]" value="<?= $_POST['sol_impacts'][0] ?? '' ?>">
                                                </div>
                                                <div class="col-sm-3">
                                                    <select name="sol_impacts_type[]" class="form-control">
                                                        <option value="high" <?= (isset($_POST['sol_impacts_type'][0]) && $_POST['sol_impacts_type'][0] == 'high') ? 'selected' : '' ?>>High</option>
                                                        <option value="med" <?= (isset($_POST['sol_impacts_type'][0]) && $_POST['sol_impacts_type'][0] == 'med') ? 'selected' : '' ?>>Medium</option>
                                                        <option value="low" <?= (isset($_POST['sol_impacts_type'][0]) && $_POST['sol_impacts_type'][0] == 'low') ? 'selected' : '' ?>>Low</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="sol_action_items_1" class="col-sm-3 col-form-label">Action Items</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="sol_action_items_1" name="sol_action_items[]" value="<?= $_POST['sol_action_items'][0] ?? '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-group">
                                                <label for="gap">GAP?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sol_gap_radio[]" id="yes-lbl-1" value="yes" <?= (isset($_POST['sol_gap_radio'][0]) && $_POST['sol_gap_radio'][0] == 'yes') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="yes-lbl-1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="sol_gap_radio[]" id="no-lbl-1" value="no" <?= (isset($_POST['sol_gap_radio'][0]) && $_POST['sol_gap_radio'][0] == 'no') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="no-lbl-1">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5>What to consider?</h5>
                                            <p>Consider questions such as... <button type="button" class="btn btn-link" data-toggle="modal" data-target="#consider">[click to expand]</button></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (isset($_POST['sol_req']) && !empty($_POST['sol_req']) && is_array($_POST['sol_req']) && count($_POST['sol_req']) > 1): ?>
                                <?php foreach ($_POST['sol_req'] as $i => $req): if ($i === 0) { continue; } ?>
                                <div class="row mt-4 border-dashed-bottom requirement">
                                    <div class="col-lg-9 px-4">

                                        <div class="row">
                                            <div class="col">

                                                <div class="form-group row">
                                                    <label for="sol_req_<?=$i+1?>" class="col-sm-3 col-form-label title-req"><span class="text-danger">*</span> Requirement <?=$i+1?></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="sol_req_<?=$i+1?>" name="sol_req[]" value="<?= $_POST['sol_req'][$i] ?? '' ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="sol_risks_<?=$i+1?>" class="col-sm-3 col-form-label">Risks</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="sol_risks_<?=$i+1?>" name="sol_risks[]" value="<?= $_POST['sol_risks'][$i] ?? '' ?>">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <select name="sol_risks_type[]" class="form-control">
                                                            <option value="high" <?= (isset($_POST['sol_risks_type'][$i]) && $_POST['sol_risks_type'][$i] == 'high') ? 'selected' : '' ?>>High</option>
                                                            <option value="med" <?= (isset($_POST['sol_risks_type'][$i]) && $_POST['sol_risks_type'][$i] == 'med') ? 'selected' : '' ?>>Medium</option>
                                                            <option value="low" <?= (isset($_POST['sol_risks_type'][$i]) && $_POST['sol_risks_type'][$i] == 'low') ? 'selected' : '' ?>>Low</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="sol_impacts_<?=$i+1?>" class="col-sm-3 col-form-label">Impacts</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="sol_impacts_<?=$i+1?>" name="sol_impacts[]" value="<?= $_POST['sol_impacts'][$i] ?? '' ?>">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <select name="sol_impacts_type[]" class="form-control">
                                                            <option value="high" <?= (isset($_POST['sol_impacts_type'][$i]) && $_POST['sol_impacts_type'][$i] == 'high') ? 'selected' : '' ?>>High</option>
                                                            <option value="med" <?= (isset($_POST['sol_impacts_type'][$i]) && $_POST['sol_impacts_type'][$i] == 'med') ? 'selected' : '' ?>>Medium</option>
                                                            <option value="low" <?= (isset($_POST['sol_impacts_type'][$i]) && $_POST['sol_impacts_type'][$i] == 'low') ? 'selected' : '' ?>>Low</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="sol_action_items_<?=$i+1?>" class="col-sm-3 col-form-label">Action Items</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="sol_action_items_<?=$i+1?>" name="sol_action_items[]" value="<?= $_POST['sol_action_items'][$i] ?? '' ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-group">
                                                    <label for="gap">GAP?</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input lbl-yes" type="radio" name="sol_gap_radio[<?=$i?>]" id="yes-lbl-<?=$i+1?>" value="yes" <?= (isset($_POST['sol_gap_radio'][$i]) && $_POST['sol_gap_radio'][$i] == 'yes') ? 'checked' : '' ?>>
                                                            <label class="form-check-label lbl-yes-lbl" for="yes-lbl-<?=$i+1?>">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input lbl-no" type="radio" name="sol_gap_radio[<?=$i?>]" id="no-lbl-<?=$i+1?>" value="no" <?= (isset($_POST['sol_gap_radio'][$i]) && $_POST['sol_gap_radio'][$i] == 'no') ? 'checked' : '' ?>>
                                                            <label class="form-check-label lbl-no-lbl" for="no-lbl-<?=$i+1?>">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <button type="button" class="btn btn-danger btn-sm remove-button">Remove Requirement</button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>


                        </div>
                        <div class="col-lg-12 mt-2">
                            <button type="button" class="btn btn-success btn-sm" id="add-requirement">Add new requirement</button>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12">

                            <div class="form-group">
                                <label for="">Recommendation</label>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="sol_recommendation_text" name="sol_recommendation_text" value="<?= $_POST['sol_recommendation_text'] ?? '' ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="sol_recommendation" class="form-control">
                                            <option value="yes" <?= (isset($_POST['sol_recommendation']) && $_POST['sol_recommendation'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                            <option value="maybe" <?= (isset($_POST['sol_recommendation']) && $_POST['sol_recommendation'] == 'maybe') ? 'selected' : '' ?>>Maybe</option>
                                            <option value="no" <?= (isset($_POST['sol_recommendation']) && $_POST['sol_recommendation'] == 'no') ? 'selected' : '' ?>>No Bid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sol_notes">Additional Notes</label>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <textarea name="sol_notes" class="form-control" id="sol_notes" rows="3"><?= $_POST['sol_notes'] ?? '' ?></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="sol_ability">Ability to Respond to Solicitation</label>

                                            <input type="text" name="sol_ability" id="sol_ability" data-slider-min="0" data-slider-max="100" data-slider-value="<?= $_POST['sol_ability'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-lg-12 text-center">

                            <button type="submit" class="btn btn-primary px-4">Go!</button>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>

    <style>
        .slider-selection {
            background: #6acaf9;
        }
    </style>

    <!--  Modal -->
    <div class="modal fade" id="consider" tabindex="-1" role="dialog" aria-labelledby="considerTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="considerTitle">What to consider?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?=$settings->website->consider?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--  Modal -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js" integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('#sol_ability').slider({
            formatter: function(value) {
                return value + '%';
            },
        });

        function generate_requirement_template (count) {

            let requirement_template = `
                <div class="row mt-4 border-dashed-bottom requirement">
                    <div class="col-lg-9 px-4">

                        <div class="row">
                            <div class="col">

                                <div class="form-group row">
                                    <label for="sol_req_${count}" class="col-sm-3 col-form-label title-req"><span class="text-danger">*</span> Requirement ${count}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="sol_req_${count}" name="sol_req[]" value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="sol_risks_${count}" class="col-sm-3 col-form-label">Risks</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="sol_risks_${count}" name="sol_risks[]" value="">
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="sol_risks_type[]" class="form-control">
                                            <option value="high">High</option>
                                            <option value="med">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="sol_impacts_${count}" class="col-sm-3 col-form-label">Impacts</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="sol_impacts_${count}" name="sol_impacts[]" value="">
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="sol_impacts_type[]" class="form-control">
                                            <option value="high">High</option>
                                            <option value="med">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="sol_action_items_${count}" class="col-sm-3 col-form-label">Action Items</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="sol_action_items_${count}" name="sol_action_items[]" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <label for="gap">GAP?</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input lbl-yes" type="radio" name="sol_gap_radio[${count-1}]" id="yes-lbl-${count}" value="yes">
                                            <label class="form-check-label lbl-yes-lbl" for="yes-lbl-${count}">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input lbl-no" type="radio" name="sol_gap_radio[${count-1}]" id="no-lbl-${count}" value="no">
                                            <label class="form-check-label lbl-no-lbl" for="no-lbl-${count}">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <button type="button" class="btn btn-danger btn-sm remove-button">Remove Requirement</button>
                    </div>
                </div>
            `;
            return requirement_template;
        }

        function remove_requirement (e) {

            $(e.target).parent().parent().remove();
            
            // changing the numbers
            $('.requirement').each ( (i, el) => {
                if (i != 0) {
                    $(el).find('.title-req').html(`<span class="text-danger">*</span> Requirement ${i+1}`);
                    $(el).find('.lbl-yes').attr('name', `sol_gap_radio[${i}]`);
                    $(el).find('.lbl-no').attr('name', `sol_gap_radio[${i}]`);

                    $(el).find('.lbl-yes').attr('id', `yes-lbl-[${i+1}]`);
                    $(el).find('.lbl-no').attr('id', `no-lbl-[${i+1}]`);
                    $(el).find('.lbl-yes-lbl').attr('id', `yes-lbl-[${i+1}]`);
                    $(el).find('.lbl-no-lbl').attr('id', `no-lbl-[${i+1}]`);

                }
            })

        }

        $('#add-requirement').on('click', (e) => {

            let requirement_template_html = $(generate_requirement_template($('.requirement').length+1));

            requirement_template_html.find('.remove-button').on('click', remove_requirement);

            $('#requirements').append(requirement_template_html);

        })

        $('.remove-button').on('click', remove_requirement)
    </script>

</body>

</html>