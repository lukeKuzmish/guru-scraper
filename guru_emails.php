<?php
define('GURU_SCRAPER_DIRECTORY', '/home/luke/guru-scraper/');

function getCurrentStatus() {
    $statusText = file_get_contents(GURU_SCRAPER_DIRECTORY . 'guru_emails_enabled.txt');
    // statusText is either 0 or 1
    // but casting a string to a bool seems to always return true,
    // therefore, cast it to an int, and then to a bool
    // TODO -- better way to handle this?
    $currentStatus = (bool) intval($statusText);
    return $currentStatus;
} // getCurrentStatus

function setCurrentStatus($newStatus) {
    return file_put_contents(GURU_SCRAPER_DIRECTORY . 'guru_emails_enabled.txt', $newStatus);
} // setCurrentStatus


if (isset($_REQUEST['guru-email-status'])) {
    
    $newStatus = $_REQUEST['guru-email-status'];
    $acceptableValues = array('0', '1');
    if (in_array($newStatus, $acceptableValues)) {
        if (setCurrentStatus($newStatus)) {
            // successfully updated status
            $alertHTML = <<< HTML
        <div class="alert alert-success" role="alert">
            The Guru scraper status was successfully updated!
        </div>
HTML;
        }
        else {
            // !!! failed to update status
            $alertHTML = <<< HTML
        <div class="alert alert-danger" role="alert">
            <strong>Oh no!</strong>  Could not update the Guru scraper status!
        </div>
HTML;
        }
    }
    else {
        $alertHTML = <<< HTML
        <div class="alert alert-danger" role="alert">
            <strong>Oh no!</strong>  Could not update the Guru scraper status (value not acceptable)
        </div>
HTML;
    }
}


$currentStatus = getCurrentStatus();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>

    </style>
</head>
<body>
    <div class='container'>
        <div class='row'>
            <h1 class='text-center'>Guru Scraper Email Settings</h1>
        </div>
<?php
if (isset($alertHTML)) { echo $alertHTML; }
?>
        <div class='row'>
            <p class='col-xs-6'>Use this script to start/stop the sending of Guru scraper emails</p>
        </div>
<?php

$spanClass = ($currentStatus) ? 'success' : 'danger';
$spanText  = ($currentStatus) ? 'Activated' : 'Deactivated';

// noSelected   If the $currentStatus is off, then default the select box value
//              to 'No'.  We don't have to do this for 'Yes' because it's the
//              first <option> and is preselected anyhow.
$noSelected = (!$currentStatus) ? ' selected="selected"' : '';
?>
        <div class='row'>
            <p class='col-xs-3'>Current scraper status is: <div class='bg-<?php echo $spanClass; ?> col-xs-2 text-center'><strong class='text-<?php echo $spanClass; ?>'><?php echo $spanText; ?></strong></div>
        </div>
        <div class='row'>
            <form method='POST' action='' id='guru-frm' class='col-xs-6'>
                <div class='form-group'>
                    <label for='guru-email-status'>Send Emails?</label>
                    <select name='guru-email-status' class='form-control'>
                        <option value='1'>Yes</option>
                        <option value='0'<?php echo $noSelected; ?>>No</option>
                    </select>
                </div>
                <div class='form-group'>
                    <button type='submit' class='btn btn-primary' id='guru-submit'>Submit</button>
                </div>
                
            </form>
        </div>
    </div>
</body>
</html>
