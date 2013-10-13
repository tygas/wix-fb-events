<!--<link rel='stylesheet' id='main-css' href='stylesheets/style.css?ver=3.5.1'                                          type='text/css' media='all'/>-->
<script type="text/javascript" src="http://code.jquery.com/jquery-git2.js"></script>
<script type="text/javascript" src="//sslstatic.wix.com/services/js-sdk/1.21.0/javascripts/Wix.js"></script>
<script type="text/javascript" src="javascripts/jquery.leanModal.min.js"></script>
<script type="text/javascript" src="javascripts/modal.js"></script>
<script type="text/javascript" src="javascripts/wix.js"></script>
<script>

<?
include 'autoload.php';

$aString = explode('.',$_GET['instance']);
?>
var decoded=atob("<?=$aString[1]?>");
var aJson = JSON.parse(decoded);
l(aJson);
console.log(aJson);
//alert(aJson);


</script>
<?

$aSettings = Settings::get();
//lg($aSettings);

//phpinfo();
$aMyIds = array(
    'appId' => '222819754544498',
    'secret' => '4a7357a0b329259802844c9615342805',
    'fan_site' => '162215620500346',
    'event_id' => '426638544107920'
);

//lg($aMyIds);
$oFb = new FacebookEvents($aMyIds['appId'], $aMyIds['secret'], $aMyIds['fan_site']);
$aEvent = $oFb->getEvent($aMyIds['event_id'], $aMyIds['fan_site']);
//$aGuests = $oFb->getGuestList(426638544107920);

//lg($aEvent);
//lg($aGuests);

include 'templates/israel/index.php';
?>
