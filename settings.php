<?php
if (isset($_POST)) {
    $tmp = json_encode($_POST);
    $file = 'test.txt';
    file_put_contents($file, $tmp);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Settings &bull; Wix</title>
    <link type="text/css" href="stylesheets/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="stylesheets/css/common.css" rel="stylesheet">
    <link type="text/css" href="stylesheets/css/buttons.css" rel="stylesheet">
    <link type="text/css" href="stylesheets/css/settings.css" rel="stylesheet">

    <link type="text/css" href="javascripts/components/color-picker/css/color-picker.css" rel="stylesheet"/>
    <link type="text/css" href="javascripts/components/glued-position-min/glued.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="javascripts/bootstrap/bootstrap-tooltip.js"></script>
    <script type="text/javascript" src="javascripts/bootstrap/bootstrap-popover.js"></script>
    <link type="text/css" href="stylesheets/settings.css" rel="stylesheet">
</head>
<body>


<form class="box active" method='post' action=''>
    <h3 class="ng-binding">Settings</h3>

    <div class="feature main-settings" style="display: block;">
        <ul class="list outer">
            <li>

                <label class="option ng-binding">Event list title </label>
                <span class='example'>426638544107920</span>
                <input type="text" name="title" placeholder="List title" class='form-control' value='426638544107920'>
                <br>

                <label class="option ng-binding"> Enter your facebook page </label>
                <span class='example'>162215620500346</span>
                <input type="text" name="fan" placeholder="Fan page id" value='162215620500346'   class='form-control '/>

             <!--   <br>
                <label class="option ng-binding"> Facebook event id </label>
                <span class='example'>162215620500346</span>
                <input type="text" name="event" placeholder="Event id" value='162215620500346'/><br>-->

            </li>


            </li>
            <li class='feature'>
                <label class="option ng-binding">Background color </label>


                <a rel="popover" id='background-picker' class="background-picker color-selector default"></a>
                <input name='background-picker' type='hidden' id='background-picker-color' >

            </li>
            <li  class='feature'>
                <label class="option ng-binding">List color </label>

                <a rel="popover" id='list-picker' class="list-picker color-selector default"></a>
                <input name='list-picker-color' type='hidden' id='list-picker-color'>

            </li>


            <span class="control-group">
									<button class="btn default btn-sm" ng-click="finishAutocomplete()">Save</button>
								</span>
        </ul>


    </div>


    <!-- Wix SDK -->
    <script type="text/javascript" src="//sslstatic.wix.com/services/js-sdk/1.19.0/js/Wix.js"></script>

    <!-- jQuery; needed for Wix Plugins -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <!-- Twitter Bootstrap components;
         include this to utilize the Color Pickers, based on Tooltip and Popover -->
    <script type="text/javascript" src="javascripts/bootstrap/bootstrap-tooltip.js"></script>
    <script type="text/javascript" src="javascripts/bootstrap/bootstrap-popover.js"></script>

    <!-- Wix UI Components -->
    <script type="text/javascript" src="javascripts/components/accordion/accordion.js"></script>
    <script type="text/javascript" src="javascripts/components/checkbox/checkbox.js"></script>
    <script type="text/javascript" src="javascripts/components/radio-button/radio-button.js"></script>
    <script type="text/javascript" src="javascripts/components/slider/slider.js"></script>
    <script type="text/javascript" src="javascripts/components/color-picker/color-pickers/simple.js"></script>
    <script type="text/javascript" src="javascripts/components/color-picker/color-pickers/advanced.js"></script>
    <script type="text/javascript" src="javascripts/components/color-picker/color-picker.js"></script>

    <!-- Settings Glued Logic -->
    <script type="text/javascript" src="javascripts/components/glued-position-min/glued.min.js"></script>

    <!-- Settings View Logic -->
    <script type="text/javascript" src="javascripts/views/settings.js"></script>
    <script type="text/javascript" src='js/features.js'></script>
    <? include 'controller/settings-submit.php'?>

</body>
</html>