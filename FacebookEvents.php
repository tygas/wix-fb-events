<?php
require 'Curl.php';
require 'fb/facebook.php';


class FacebookEvents
{
    private $APP_ID;
    private $SECRET;
    private $FAN_ID; //fan page id
    public $oFacebook; //fan page id

    function __construct($app, $secret, $FAN_ID)
    {

        $this->FAN_ID =$FAN_ID;
        $this->oFacebook = new Facebook(array(
            'appId' => $app,
            'secret' => $secret,
            'cookie' => true
        ));
    }

    /**
     * @param $iEventId
     * @param $bArFanPage -  jei ar = 0 tai duotas id fanu, jei ne tai event id
     * @return mixed
     */
    function getEvent($iEventId, $bArFanPage = true)
    {


        $fql = "SELECT
    eid, name, pic, start_time, end_time, location, description, attending_count, ticket_uri
   FROM
    event
   WHERE ";
        if ($bArFanPage == '0') {
            $fql .= "eid IN ( SELECT eid FROM event_member WHERE uid = " . $this->FAN_ID . " )";
        } else {
            $fql .= "eid IN ( SELECT eid FROM event WHERE eid = " . $iEventId . " )";
        }
        $fql .= "AND    start_time >= now()   ORDER BY    start_time desc";


        $param = array(
            'method' => 'fql.query',
            'query' => $fql,
            'callback' => ''
        );
//       lg($fql);
        $fqlResult = $this->oFacebook->api($param);

        return $fqlResult;
    }

    /**
     *
     * @param $eid - evento id ir kiek gražinti, gražina einančių žmonių vardus ir fb_id
     * @param $kiekis
     * @return mixed
     */
    function getGuestList($eid, $kiekis = 5) //$id site id
    {


        $dino = $this->oFacebook->api("/" . $this->FAN_ID . "?fields=access_token");
        $access_token = $dino['id'];
        $graph_url = "https://graph.facebook.com/" . $eid . "/attending/?limit=" . $kiekis . "&access_token=" . $access_token;
        $page_posts = json_decode(get_web_page($graph_url), true);

        return $page_posts;

        /*paveiksliukai gaunami
        echo '<img src="http://graph.facebook.com/'.user_id.'/picture?type=square" alt='.$f_name.'>';
        */
    }

}
