<style>
#bslider-main{
	position:relative;
	margin:0 auto;
	width:<?php echo get_option('bslider_width')?>px;
}

</style>
<?php
$bottom = get_option('bslider_caption_bottom');
$right = get_option('bslider_caption_right');
$left = get_option('bslider_caption_left');
$top = get_option('bslider_caption_top');
?>

<script>
	
	jQuery(document).ready(function($) {
	var slider = new BeaverSlider({
		structure: {
			container: {
				id: "w2pe-beaverslider",
            width: <?php echo get_option('bslider_width')?>,
            height: <?php echo get_option('bslider_height')?>
			},
			
			<?php if(get_option('bslider_caption_visible') == 'TRUE'){?>
			messages: {
				<?php if( !empty($bottom)){?>
				bottom: <?php echo $bottom?>,
				<?php }?>
				<?php if( !empty($right)){?>
				right: <?php echo $right?>,
				<?php }?>
				<?php if( !empty($top)){?>
				top: <?php echo $top?>,
				<?php }?>
				<?php if( !empty($left)){?>
				left: <?php echo $left?>,
				<?php }?>
				containerClass: "message-container"
			},
			<?php }?>
			
			<?php if(get_option('bslider_paginate') == 'TRUE'){?>
			controls: {
				previewMode: false,
				align: "center",
				containerClass: "control-container",
				elementClass: "control-element",
				elementActiveClass: "control-element-active"
			}
			<?php }?>
		},
		content: {
			images: [
             <?php
			 global $wpdb;
			 $image_table = $wpdb->prefix . 'bslider_images';
			 
			 $result=$wpdb->get_results("SELECT * FROM `$image_table` ORDER BY `position` ASC");
			 foreach ($result as $r){
				 echo '"'.get_option('siteurl').'/wp-content/uploads/w2pe-beaverslider/'.$r->id.'.'.$r->ftype.'",';
			 }
             ?>      
			],
			<?php if(get_option('bslider_caption_visible') == 'TRUE'){?>
			messages: [
             <?php
			 global $wpdb;
			 $image_table = $wpdb->prefix . 'bslider_images';
			 
			 $result=$wpdb->get_results("SELECT * FROM `$image_table` ORDER BY `position` ASC");
			 foreach ($result as $r){
				 echo '"'.$r->caption.'",';
			 }
             ?>      
			]
			<?php }?>
		},
		animation: {
			waitAllImages: true,
			<?php echo get_option('bslider_effects')?>,
			initialInterval: <?php echo get_option('bslider_pause_time')?>,
			interval: <?php echo get_option('bslider_interval')?>,
			changeMessagesAfter: 1
		}
	});   	  
});
	
</script>

<div id="bslider-main">
	<div id="w2pe-beaverslider"></div>
</div>