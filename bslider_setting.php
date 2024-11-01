<?php

// update settings
if( isset($_POST['save_opt'])){
	update_option('bslider_width', $_POST['width']);
	update_option('bslider_height', $_POST['height']);
	update_option('bslider_caption_visible', $_POST['op_caption']);
	update_option('bslider_caption_left', $_POST['cp_left']);
	update_option('bslider_caption_right', $_POST['cp_right']);
	update_option('bslider_caption_bottom', $_POST['cp_bot']);
	update_option('bslider_paginate', $_POST['pagenate']);
	update_option('bslider_paginate_pos', $_POST['page_pos']);
	update_option('bslider_pause_time', $_POST['ptime']);
	update_option('bslider_interval', $_POST['interval']);
	update_option('bslider_supdate', 1);
	
	$msg2='Slider Setting Updated';
}
?>

    <h1>w2pe Beaver Slider - Settings</h1>
    
    <div class="bSlider">
    <?php if ( isset($msg1) ){?>
    <div class="updated error" id="msg"><p><strong><?php echo $msg1;?></strong></p></div>
    <?php }?>
    <?php if ( isset($msg2) ){?>
    <div class="updated" id="msg"><p><strong><?php echo $msg2;?></strong></p></div>
    <?php }?>
    
   	  <fieldset>
        	<legend>Slider Settings</legend>
            <form action="" method="post" enctype="multipart/form-data">
            <table width="100%">
				<tr>
                	<td align="right">Width</td>
                    <td>:</td>
                    <td><input type="text" name="width" value="<?php echo get_option('bslider_width')?>"></td>
                </tr>            
            	<tr>
                	<td align="right">Height</td>
                    <td>:</td>
                    <td><input type="text" name="height" value="<?php echo get_option('bslider_height')?>"></td>
                </tr>
            	<tr>
                	<td align="right">Caption</td>
                    <td>:</td>
                    <td>
                    	<input type="radio" value="TRUE" name="op_caption" <?php echo (get_option('bslider_caption_visible') == 'TRUE') ? 'checked=checked' : '';?>>Visible
                        
                        <input type="radio" value="FALSE" name="op_caption" <?php echo (get_option('bslider_caption_visible') == 'FALSE') ? 'checked=checked' : '';?>>Hidden
                    </td>
                </tr>
            	<tr>
                	<td align="right">Caption Position (Left)</td>
                    <td>:</td>
                    <td><input type="text" name="cp_left" value="<?php echo get_option('bslider_caption_left')?>"></td>
                </tr>
            	<tr>
                	<td align="right">Caption Position (Right)</td>
                    <td>:</td>
                    <td><input type="text" name="cp_right" value="<?php echo get_option('bslider_caption_right')?>"></td>                    
                </tr>
            	<tr>
                	<td align="right">Caption Position (Top)</td>
                    <td>:</td>
                    <td><input type="text" name="cp_top" value="<?php echo get_option('bslider_caption_top')?>"></td>                    
                </tr>
            	<tr>
                	<td align="right">Caption Position (Bottom)</td>
                    <td>:</td>
                    <td><input type="text" name="cp_bot" value="<?php echo get_option('bslider_caption_bottom')?>"></td>                    
                </tr>
            	<tr>
                	<td align="right">Pagination</td>
                    <td>:</td>
                    <td>
						<input type="radio" value="TRUE" name="pagenate" <?php echo (get_option('bslider_paginate') == 'TRUE') ? 'checked=checked' : '';?>>Visible
                        
                        <input type="radio" value="FALSE" name="pagenate" <?php echo (get_option('bslider_paginate') == 'FALSE') ? 'checked=checked' : '';?>>Hidden
                    </td>
                </tr>
                <tr>
                	<td align="right">Pause Time</td>
                    <td>:</td>
                    <td><input type="text" name="ptime" value="<?php echo get_option('bslider_pause_time')?>"></td>                    
                </tr>
                <tr>
                	<td align="right">Interval</td>
                    <td>:</td>
                    <td><input type="text" name="interval" value="<?php echo get_option('bslider_interval')?>"></td>                    
                </tr>
                <tr>
                	<td></td>
                    <td></td>
                    <td><input type="submit" class="button button-primary button-large" name="save_opt" value="Save" /></td>                    
                </tr>
                
            </table>
            </form>
        </fieldset>
	</div>