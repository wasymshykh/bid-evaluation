<?php

require_once 'includes/config.php';

// checking the login
$logged = check_login();
if (!$logged['status']) {
    $_SESSION['message'] = ['type' => 'error', 'data' => $logged['data']];
    go (URL.'/');
}
$logged = $logged['data'];


if (isset($_POST) && !empty($_POST)) {
    
    $data = [];

    if (isset($_POST['sol_number']) && !empty($_POST['sol_number']) && is_numeric($_POST['sol_number'])) {
        $data['solicitation_number'] = normal_text($_POST['sol_number']);
    } else { $errors[] = "Solicitation Number cannot be empty or non-numeric"; }

    if (isset($_POST['sol_title']) && !empty($_POST['sol_title']) && is_string($_POST['sol_title']) && !empty(normal_text($_POST['sol_title']))) {
        $data['solicitation_title'] = normal_text($_POST['sol_title']);
    } else { $errors[] = "Solicitation Title cannot be empty"; }

    if (isset($_POST['sol_agency']) && !empty($_POST['sol_agency']) && is_string($_POST['sol_agency']) && !empty(normal_text($_POST['sol_agency']))) {
        $data['solicitation_agency'] = normal_text($_POST['sol_agency']);
    } else { $errors[] = "Agency cannot be empty"; }

    if (isset($_POST['sol_due']) && !empty($_POST['sol_due']) && is_string($_POST['sol_due']) && !empty(normal_text($_POST['sol_due']))) {
        $data['solicitation_due_date'] = normal_text($_POST['sol_due']);
    } else { $errors[] = "Solicitation due date cannot be empty"; }

    if (isset($_POST['sol_url']) && !empty($_POST['sol_url']) && is_string($_POST['sol_url']) && !empty(normal_text($_POST['sol_url']))) {
        $data['solicitation_url'] = normal_text($_POST['sol_url']);
        if (isset($_POST['sol_url_check'])) { $data['solicitation_url'] .= "sam.gov"; }
    } else { $errors[] = "Solicitation url cannot be empty"; }

    if (isset($_POST['sol_description']) && !empty($_POST['sol_description']) && is_string($_POST['sol_description']) && !empty(normal_text($_POST['sol_description']))) {
        $data['solicitation_description'] = normal_text($_POST['sol_description']);
    } else { $data['solicitation_description'] = ""; }

    // --- requirements

    if (isset($_POST['sol_req']) && !empty($_POST['sol_req']) && is_array($_POST['sol_req'])) {

        $sol_req = $_POST['sol_req'];
        // checking the count of fields -> and type of field data
        $invalid = false;
        if (!(isset($_POST['sol_risks']) && is_array($_POST['sol_risks']) && count($sol_req) === count($_POST['sol_risks']))) {
            $invalid = true;
        } else if (!(isset($_POST['sol_risks_type']) && is_array($_POST['sol_risks_type']) && count($sol_req) === count($_POST['sol_risks_type']))) {
            $invalid = true;
        } else if (!(isset($_POST['sol_impacts']) && is_array($_POST['sol_impacts']) && count($sol_req) === count($_POST['sol_impacts']))) {
            $invalid = true;
        }  else if (!(isset($_POST['sol_impacts_type']) && is_array($_POST['sol_impacts_type']) && count($sol_req) === count($_POST['sol_impacts_type']))) {
            $invalid = true;
        } else if (!(isset($_POST['sol_action_items']) && is_array($_POST['sol_action_items']) && count($sol_req) === count($_POST['sol_action_items']))) {
            $invalid = true;
        } else if (!(isset($_POST['sol_gap_radio']) && is_array($_POST['sol_gap_radio']) && count($sol_req) === count($_POST['sol_gap_radio']))) {
            $invalid = true;
        }

        if ($invalid) {
            $errors[] = "Invalid requirements data submission";
        } else {
            // sorting out requirements
            $requirements = [];
            foreach ($sol_req as $i => $title) {
                if (!empty(normal_text($title))) {
                    $requirements[] = [
                        'requirement_title' => normal_text($title),
                        'requirement_gap' => ($_POST['sol_gap_radio'][$i] == 'yes' ? '1' : '0'),
                        'requirement_impacts' => normal_text($_POST['sol_impacts'][$i]),
                        'requirement_impacts_type' => normal_text($_POST['sol_impacts_type'][$i]),
                        'requirement_risks' => normal_text($_POST['sol_risks'][$i]),
                        'requirement_risks_type' => normal_text($_POST['sol_risks_type'][$i]),
                        'requirement_action_items' => normal_text($_POST['sol_action_items'][$i])
                    ];
                } else {
                    $errors[] = "Requirements title cannot be empty"; break;
                }
            }
        }

    } else {
        $errors[] = "Atleast one requirement is required";
    }
    // --- end requirments check

    if (isset($_POST['sol_recommendation_text']) && !empty($_POST['sol_recommendation_text']) && is_string($_POST['sol_recommendation_text']) && !empty(normal_text($_POST['sol_recommendation_text']))) {
        $data['solicitation_recommendation'] = normal_text($_POST['sol_recommendation_text']);
    } else { $data['solicitation_recommendation'] = ""; }

    if (isset($_POST['sol_recommendation']) && !empty($_POST['sol_recommendation']) && is_string($_POST['sol_recommendation']) && !empty(normal_text($_POST['sol_recommendation']))) {
        $data['solicitation_recommendation_type'] = normal_text($_POST['sol_recommendation']);
    } else { $errors[] = "Select the recommendation"; }
    
    if (isset($_POST['sol_notes']) && !empty($_POST['sol_notes']) && is_string($_POST['sol_notes']) && !empty(normal_text($_POST['sol_notes']))) {
        $data['solicitation_notes'] = normal_text($_POST['sol_notes']);
    } else { $data['solicitation_notes'] = ""; }
    
    if (isset($_POST['sol_ability']) && !empty($_POST['sol_ability']) && is_string($_POST['sol_ability']) && !empty(normal_text($_POST['sol_ability']))) {
        $data['solicitation_ability'] = normal_text($_POST['sol_ability']);
    } else { $data['solicitation_ability'] = ""; }

    if (empty($errors)) {

        try {

            $db->beginTransaction();

            // inserting data
            $data['solicitation_user_id'] = $logged['user_id'];

            $col = "";
            $val = "";
            $d = [];
            foreach ($data as $c => $v) {
                if (!empty($col)) { $col .= ", "; $val .= ", "; }
                $col .= "`$c`";
                $val .= ":$c";
                $d[":$c"] = $v;
            }

            $q = "INSERT INTO `solicitations` ($col) VALUES ($val)";
            
            $st = $db->prepare($q);
            if ($st->execute($d)) {
                $solicitation_id = $db->lastInsertId();

                $col = "";
                $vals = "";
                $d = [];
                foreach ($requirements as $i => $req) {
                    $val = "";
                    $req['requirement_solicitation_id'] = $solicitation_id;
                    foreach ($req as $c => $v) {
                        if ($i == 0) {
                            if (!empty($col)) { $col .= ", "; }
                            $col .= "`$c`";
                        }
                        if (!empty($val)) { $val .= ", "; }
                        $val .= ":$c$i";
                        $d[":$c$i"] = $v;
                    }
                    if (!empty($vals)) { $vals .= ", "; }
                    $vals .= "($val)";
                }

                $q = "INSERT INTO `requirements` ($col) VALUES $vals";

                $st = $db->prepare($q);

                if ($st->execute($d)) {
                    $db->commit();
                    go (URL.'/gapoutput.php?s='.$solicitation_id);
                } else {
                    $errors[] = "E.03: Unable to create the record";
                    $db->rollBack();
                }

            } else {
                $errors[] = "E.02: Unable to create the record";
                $db->rollBack();
            }

        } catch (Exception $e) {
            $errors[] = "E.01: Unable to create the record";
        }

    }

}

require_once DIR.'views/gapinput.view.php';
