<h1>w2pe Beaver Slider - Effects</h1> 

<div class="bSlider">
<div class="updated" id="msg"></div>
	<?php
   	$data = get_option('bslider_effects');
	$edata = explode(':',$data);
	$name = str_replace('"','',$edata[1]);
    ?>
    <fieldset>
        <legend>Slider Effects</legend>
        <form action="" method="post">
        <table width="100%" border="0" id="effect-table">
          <tr style="background:#000;color:#fff">
            <td width="32%"><div align="center"><strong>Effect</strong></div></td>
            <td width="18%"><div align="center"><strong>Duration, ms</strong></div></td>
            <td width="16%"><div align="center"><strong>Size</strong></div></td>
            <td width="17%"><div align="center"><strong>Steps</strong></div></td>
            <td width="17%"><div align="center"><strong>Action</strong></div></td>
          </tr>
          <?php if(empty($data)){?>
          <tr>
            <td>
            	<div align="center">
                	<select name="effect[]" class="effect">
                    	<option value="chessBoardRightDown">chessBoard</option>
                        <option value="fadeOut">Fade</option>
                        <option value="jalousieDown">Jalousie</option>
                        <option value="nailsDown">Nails</option>
                        <option value="pancakeIn">Pancake</option>
                        <option value="prisonHorizontal">Prison</option>
                        <option value="slideLeft">Slide</option>
                        <option value="slideOverLeft">SlideOver</option>
                        <option value="spiralOut">Spiral</option>
                    </select>
                </div>
            </td>
            <td><div align="center"><input type="text" name="duration[]" class="duration" placeholder="800"></div></td>
            <td><div align="center"><input type="text" name="size[]" class="size" placeholder="10"></div></td>
            <td><div align="center"><input type="text" name="steps[]" class="steps" placeholder="10"></div></td>
            <td><div align="center"><input type="submit" class="save" value="Save"></div></td>
          </tr>
          <?php }else{?>
          <tr>
            <td>
            	<div align="center">
                	<select name="effect[]" class="effect">
                    	<option value="chessBoardRightDown" <?php echo ($name=='chessBoardRightDown') ? 'selected=selected' : ''?>>chessBoard</option>
                        <option value="fadeOut" <?php echo ($name=='fadeOut') ? 'selected=selected' : ''?>>Fade</option>
                        <option value="jalousieDown" <?php echo ($name=='jalousieDown') ? 'selected=selected' : ''?>>Jalousie</option>
                        <option value="nailsDown" <?php echo ($name=='nailsDown') ? 'selected=selected' : ''?>>Nails</option>
                        <option value="pancakeIn" <?php echo ($name =='pancakeIn') ? 'selected=selected' : ''?>>Pancake</option>
                        <option value="prisonHorizontal" <?php echo ($name=='prisonHorizontal') ? 'selected=selected' : ''?>>Prison</option>
                        <option value="slideLeft" <?php echo ($name=='slideLeft') ? 'selected=selected' : ''?>>Slide</option>
                        <option value="slideOverLeft" <?php echo ($name=='slideOverLeft') ? 'selected=selected' : ''?>>SlideOver</option>
                        <option value="spiralOut" <?php echo ($name=='spiralOut') ? 'selected=selected' : ''?>>Spiral</option>
                    </select>
                </div>
            </td>
            <td><div align="center"><input type="text" name="duration[]" class="duration" value="<?php echo $edata[2]?>" placeholder="800"></div></td>
            <td><div align="center"><input type="text" name="size[]" class="size" value="<?php echo $edata[3]?>" placeholder="10"></div></td>
            <td><div align="center"><input type="text" name="steps[]" class="steps" value="<?php echo $edata[4]?>" placeholder="10"></div></td>
            <td><div align="center"><input type="submit" class="save button button-primary button-large" value="Save"></div></td>
          </tr>
          <?php }?>
        </table>
        </form>
    </fieldset>

<?php
// ajax request
add_action( 'admin_footer', 'bslider_effect_javascript' );

function bslider_effect_javascript() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {
	
	$('.updated').hide();
	
	$( "form" ).submit(function( event ) {
		//console.log( );
		event.preventDefault();
		var fdata =  $( this ).serialize();
			var data = {
			action: 'bslider_effect',
			sdata: fdata
		};
	
		$.post(ajaxurl, data, function(response) {
			if(response == 'added'){
				$('.updated').html('<p><strong>Effect Added</strong></p>');
				$('.updated').show();
			}else{
				$('.updated').html('<p><strong>Effect Updated</strong></p>');
				$('.updated').show();
			}
			//alert('Got this from the server: ' + response);
		});
	});
	
});
</script>
<?php
}
?>
</div>