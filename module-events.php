<?php	
//events count
$count=1;
if(get_option('themex_artist_events_count')) {
	$count=intval(get_option('themex_artist_events_count'));
}

//get events
$events=themex_filter_events(themex_get_posts('event',array('ID','title','artists','date','place','link','status','details'),-1,array('artists'=>$post->ID)));
$events=array_slice($events,0,$count);

if(!empty($events)) {
?>
<div class="content-block">
	<div class="block-title"><span><?php _e('Renginiai','replay'); ?></span></div>
	<div class="block-content">
	<?php foreach($events as $event) { ?>
	<div class="featured-event">
		<div class="event-date">
			<div class="event-date-holder">
				<div class="event-date-number"><?php echo substr($event['date'],0,2); ?></div>
				<div class="event-month"><?php 
				$m = array(
						'01' => 'Sau',
						'02' => 'Vas',
						'03' => 'Kov',
						'04' => 'Bal',
						'05' => 'Geg',
						'06' => 'Bir',
						'07' => 'Lie',
						'08' => 'Rgp',
						'09' => 'Rgs',
						'10' => 'Spl',
						'11' => 'Lap',
						'12' => 'Grd',
					);
					echo $m[date('m', $event['timestamp'])];
				//echo date('M', $event['timestamp']); 

				?></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="event-details">
			<h5 class="event-title"><?php echo themex_event_title($event,'&#64;'); ?></h5>
			<div class="event-place"><?php echo $event['place']; ?></div>
			<?php if($event['status']=='active' && $event['link']) { ?>
			<a href="<?php echo $event['link']; ?>" target="_blank" class="button small"><span><?php _e('Bilietai','replay'); ?></span></a>
			<?php } else if($event['status']!='active') { ?>
			<span class="event-status">
			<?php
			if($event['status']=='free') {
				_e('Nemokamas','replay'); 
			} else if($event['status']=='sold') {
				_e('Sold Out','replay');
			} else if($event['status']=='cancelled') { 
				_e('Cancelled','replay');
			} 
			?>	
			</span>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
	</div>
</div>
<?php } ?>