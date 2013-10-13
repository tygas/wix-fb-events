<?php
if (isset($_POST)) {
    $aSettings = json_encode($_POST);
    $file = 'settings/instance.json';
    file_put_contents($file, $aSettings);
//    print_r($aSettings);
    die('<div class="alert alert-success">Settings saved</div>');
}


