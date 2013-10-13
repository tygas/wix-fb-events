<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Display Facebook Events to You Website</title>

    <!-- Just adding some style -->
    <style type='text/css'>
        body{
            font-family: "Proxima Nova Regular","Helvetica Neue",Arial,Helvetica,sans-serif;
        }

        .clearBoth{
            clear: both;
        }

        .event{
            background-color: #E3E3E3;
            margin: 0 0 5px 0;
            padding: 5px;
        }

        .eventImage{
            margin: 0 8px 0 0;
        }

        .eventInfo{
            padding:5px 0;
        }

        .eventName{
            font-size: 26px;
        }

        .floatLeft{
            float:left;
        }

        .pageHeading{
            font-weight: bold;
            margin: 0 0 20px 0;
        }
    </style>
</head>

<body>
<?php
//we have to set timezone to California
date_default_timezone_set('America/Los_Angeles');

//requiring FB PHP SDK
require 'fb-sdk/src/facebook.php';

//initializing keys
$facebook = new Facebook(array(
    'appId'  => '345502198904230',
    'secret' => '4323db13768ceb8ff46afe61e44ed375',
    'cookie' => true,
    'pageid' => 162215620500346

));

$iPageID = '';

//just a heading
echo "<div class='pageHeading'>";
echo "This event list is synchronized with this ";
echo "<a href='https://www.facebook.com/pages/COAN-Dummy-Page/221167777906963?sk=events'>";
echo "COAN Dummy Page Events";
echo "</a>";
echo " | ";
echo "<a href='http://www.codeofaninja.com/2011/07/display-facebook-events-to-your-website.html'>";
echo "Tutorial Link: Display Facebook Events To Your Website with PHP, FQL and jQuery";
echo "</a>";
echo "</div>";

/*
 *-Query the events
 *
 *-We will select: 
 *  -name, start_time, end_time, location, description
 *  -but there are other data that you can get on the event table 
 *      -https://developers.facebook.com/docs/reference/fql/event/
 *  
 *-As you will notice, we have TWO select statements here because
 *-We can't just do "WHERE creator = your_fan_page_id".
 *-Only eid is indexable in the event table
 *  -So we have to retrieve list of events by eids
 *      -And this was achieved by selecting all eid from
 *          event_member table where the uid is the id of your fanpage.
 *
 *-Yes, you fanpage automatically becomes an event_member once it creates an event
 *-start_time >= now() is used to show upcoming events only
 */
$fql = "SELECT 
            name, pic, start_time, end_time, location, description 
        FROM 
            event 
        WHERE 
            eid IN ( SELECT eid FROM event_member WHERE uid = $iPageID )
        AND 
            start_time >= now()
        ORDER BY 
            start_time desc";

$param  =   array(
    'method'    => 'fql.query',
    'query'     => $fql,
    'callback'  => ''
);

$fqlResult   =   $facebook->api($param);

//looping through retrieved data
foreach( $fqlResult as $keys => $values ){
    /*
     * see here http://php.net/manual/en/function.date.php 
     * for the date format I used.
     * The pattern string I used 'l, F d, Y g:i a'
     * will output something like this: July 30, 2015 6:30 pm
     */

    /*   
     * getting start date,
     * 'l, F d, Y' pattern string will give us
     * something like: Thursday, July 30, 2015
     */
    $start_date = date( 'l, F d, Y', $values['start_time'] );

    /*
     * getting 'start' time
     * 'g:i a' will give us something
     * like 6:30 pm
     */
    $start_time = date( 'g:i a', $values['start_time'] );

    //printing the data
    echo "<div class='event'>";

    echo "<div class='floatLeft eventImage'>";
    echo "<img src={$values['pic']} width='150px' />";
    echo "</div>";

    echo "<div class='floatLeft'>";
    echo "<div class='eventName'>{$values['name']}</div>";

    /*
     * -the date is displaying correctly, but the time? uh, sometimes it is late by an hour.
     * -it might also depend on what country you are in
     * -the best solution i can give is to include the date only and not the time
     * -you should put the time of your event in the description.
     */
    echo "<div class='eventInfo'>{$start_date} at {$start_time}</div>";
    echo "<div class='eventInfo'>{$values['location']}</div>";
    echo "<div class='eventInfo'>{$values['description']}</div>";
    echo "</div>";

    echo "<div class='clearBoth'></div>";
    echo "</div>";

}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type='text/javascript'>
    //just to add some hover effects
    $(document).ready(function(){
        $('.event').hover(
            function () {
                $(this).css('background-color', '#CFF');
            },
            function () {
                $(this).css('background-color', '#E3E3E3');
            }
        );
    });
</script>

</body>
</html>