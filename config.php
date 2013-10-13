<?php 
/*
Template Name: Events
*/

//show header
get_header();

//page content
if (have_posts()) : while (have_posts()) : the_post();
	the_content();
endwhile; endif;

//date format
$date_format='d/m/Y';
if(get_option('themex_events_date_format')) {
	$date_format=get_option('themex_events_date_format');
}

//page layout
$layout=get_option('themex_events_layout');

if($layout=='left') {
?>
	<div class="one-third column">
		<?php get_sidebar(); ?>
	</div>
	<div class="two-third column last">
<?php } else { ?>
	<div class="two-third column">
<?php } ?>
	<div class="content-block">
		<div class="block-title">
			<span><?php _e('Renginiai', 'replay'); ?></span>
			<img style="visibility:hidden" src="<?php echo get_template_directory_uri(); ?>/images/select-up.png"/>
			<span class='filter-container'>
				<span>Data: </span>
				<select class='date-filter selectmenu'>
					<option value=''>Visos</option>
					<?php
					$get_time_str = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
					$get_time = '';
					if ($get_time_str){
						$expl = explode('/', $get_time_str);
						$d = !empty($expl[0]) ? (int)$expl[0] : 0;
						$m = !empty($expl[1]) ? (int)$expl[1] : 0;
						$y = !empty($expl[2]) ? (int)$expl[2] : 0;
						if ($d && $m && $y){
							$get_time = mktime(0,0,0,$m, $d, $y);
						}
					}
					$events=themex_get_posts('event',array('ID','title','artists','date','place','video','gallery','link','status','details'),-1, array('event_category'=>get_post_meta($post->ID, 'page_events_category', true)));
					$current_time=time()-86400;
					$times = array();
					
					foreach($events as $event) {
						if($event['timestamp']>=$current_time) {
							$times[$event['timestamp']] = date($date_format, $event['timestamp']);
						}
					}
					$times = array_unique($times);
					foreach ($times as $key=>$time) {?>
						<option value='<?php echo $time; ?>' <?php if ($get_time==$key)echo 'selected="selected"';?> ><?php echo $time; ?></option><?php
					}
				?></select>
			</span>
		</div>
		<div class="block-content">
			<div class="events-list">
				<table>
					<?php
					//get events
					$events=themex_get_posts('event',array('ID','title','artists','date','place','video','gallery','link','status','details'),-1, array('event_category'=>get_post_meta($post->ID, 'page_events_category', true)));
					$current_time=time()-86400;
					
					//loop events
					foreach($events as $event) {
						if (!empty($get_time)){
							if (date($date_format, $event['timestamp']) != date($date_format, $get_time)){
								continue;
							}
						}
						if($event['timestamp']>=$current_time) {
					?>
					<tr>
						<td class="event-date"><?php echo date($date_format, $event['timestamp']); ?></td>
						<td class="event-title"><?php echo themex_event_title($event,'&#64;'); ?></td>
						<td class="event-place"><?php echo $event['place']; ?></td>
						<td class="event-option">							
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
						</td>
					</tr>
					<?php 
						}
					} 
					?>
				</table>
			</div>
		</div>
	</div>	
	<?php if(get_option('themex_events_past')!='true') { ?>
	<div class="content-block">
		<div class="block-title"><span><?php _e('Past Events', 'replay'); ?></span></div>
		<div class="block-content">
			<div class="events-list events-past">
				<table>
					<?php
					//get events
					$events=array_reverse($events);
					
					//loop events				
					foreach($events as $event) {
						if($event['timestamp']<$current_time) {
					?>
					<tr>
						<td class="event-date"><?php echo date($date_format, $event['timestamp']); ?></td>
						<td class="event-title"><?php echo themex_event_title($event,'&#64;'); ?></td>
						<td class="event-place"><?php echo $event['place']; ?></td>
						<td class="event-option">
							<?php
							if($event['gallery']!='default') { ?>
								<a class="attachment-icon gallery-icon" href="<?php echo get_permalink(intval($event['gallery'])); ?>" title="<?php _e('View Gallery','replay'); ?>"></a>
							<?php } ?>
							<?php 
							if($event['video']!='default') { ?>
								<a class="attachment-icon video-icon" href="<?php echo get_permalink(intval($event['video'])); ?>" title="<?php _e('Watch Video','replay'); ?>"></a>
							<?php } ?>
						</td>
					</tr>
					<?php
						}
					}
					?>					
				</table>
			</div>
		</div>
	</div>
	<?php } ?>
	</div>
<?php if($layout!='left') { ?>
<div class="one-third column last">
	<?php get_sidebar(); ?>
</div>
<?php } ?>
<?php get_footer(); ?>
<script>
(function($){
	$(function(){
		$('.date-filter').on('change', function(){
			window.location.href='?date='+$(this).val();
		});
		$('.selectmenu').selectmenu();
	});
})(jQuery);

</script>