<?php
// resize image
function make_thumb($src,$dest,$type,$thumb_height,$thumb_width){
		if($type=='jpg'){
			  $source_image = imagecreatefromjpeg($src);
		}else if($type=='png'){
			  $source_image = imagecreatefrompng($src);
		}else{
			  $source_image = imagecreatefromgif($src);
		}
		  $width = imagesx($source_image);
		  $height = imagesy($source_image);
		 
		 if($thumb_height==''){
			 $thumb_height = floor($height*($thumb_width/$width));
			 $thumb_image = imagecreatetruecolor($thumb_width,$thumb_height);
		 }else if($thumb_width==''){
			 $thumb_width = floor($width*($thumb_height/$height));
			 $thumb_image = imagecreatetruecolor($thumb_width,$thumb_height);
		 }else{
			 $thumb_image = imagecreatetruecolor($thumb_width,$thumb_height);
		 }
		  
		  imagecopyresampled($thumb_image,$source_image,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
		  
		  if($type=='jpg'){		  
			  imagejpeg($thumb_image,$dest);
			  imagedestroy($thumb_image);
		  }
		  else if($type=='png'){
			  imagepng($thumb_image,$dest);
			  imagedestroy($thumb_image);
		  }
		  else{
			  imagegif($thumb_image,$dest);
			  imagedestroy($thumb_image);
		  }
}


global $wpdb;

$image_table = $wpdb->prefix . 'bslider_images';

$upload_dir = ABSPATH."wp-content/uploads/w2pe-beaverslider";

if ( function_exists('plugins_url') ){
	$url = plugins_url(plugin_basename(dirname(__FILE__)));
}
else{
	$url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
}

$height = get_option( 'bslider_height' );
$width = get_option( 'bslider_width' );

// insert data
if(isset($_POST['upload'])){
	if(empty($_FILES['image']['tmp_name'])){
		$msg1='Image missing !!!';
	}else{
		$filetype = wp_check_filetype($_FILES['image']['name']);
		if($filetype['type']!='image/jpeg' && $filetype['type']!='image/png' && $filetype['type']!='image/gif'){
			$msg1='Invalid Image. Choose another';
		}else{
			$pos = !empty($_POST['position']) ? $_POST['position'] : 1 ;
			//insert data
			$wpdb->insert( 
				$image_table, 
				array( 
					'caption' => $_POST['caption'],
					'ftype' => $filetype['ext'],
					'position' => $pos
				), 
				array( 
					'%s', 
					'%s',
					'%d'
				) 
			);
			if($wpdb->insert_id){
				//success
				$id=$wpdb->insert_id;
				$imgname=$id.'.'.$filetype['ext'];
				move_uploaded_file($_FILES['image']['tmp_name'],$upload_dir.'/'.$imgname);
				make_thumb($upload_dir.'/'.$imgname,$upload_dir.'/'.$imgname,$filetype['ext'],$height,$width);
				$msg2='New Image Added';
			}
		}
	}
}

//update data
if(isset($_POST['update'])){
	$pos = !empty($_POST['position']) ? $_POST['position'] : 1 ;
	if(empty($_FILES['image']['tmp_name'])){

			$res = $wpdb->update( 
				$image_table, 
				array( 
					'caption' => $_POST['caption'],
					'position' => $pos
				), 
				array( 'id' => $_REQUEST['cid'] ), 
				array( 
					'%s',
					'%d'
				), 
					'%d'
			);				
		
	}else{
		$filetype = wp_check_filetype($_FILES['image']['name']);
		if($filetype['type']!='image/jpeg' && $filetype['type']!='image/png' && $filetype['type']!='image/gif'){
			$msg1='Invalid Image. Choose another';
		}else{
			//update data
			$res = $wpdb->update( 
				$image_table, 
				array( 
					'caption' => $_POST['caption'],
					'ftype' => $filetype['ext'],
					'position' => $pos
				), 
				array( 'id' => $_REQUEST['cid'] ), 
				array( 
					'%s',
					'%s',
					'%d'
				), 
					'%d'
			);				
			//success
			$imgname=$_REQUEST['cid'].'.'.$filetype['ext'];
			move_uploaded_file($_FILES['image']['tmp_name'],$upload_dir.'/'.$imgname);
			make_thumb($upload_dir.'/'.$imgname,$upload_dir.'/'.$imgname,$filetype['ext'],$height,$width);
		}
	}
	if($res===0 || $res>0){
		$msg2='Information Updated';
	}
	
}

//delete
if ( isset($_REQUEST['did']) ){
	$type=$wpdb->get_var( "SELECT `ftype` FROM $image_table where `id`='".$_REQUEST['did']."'" );
	$imgname=$_REQUEST['did'].'.'.$type;
	if (file_exists($upload_dir.'/' . $imgname)) {
		unlink($upload_dir.'/'.$imgname);
	}
	
	$wpdb->query( 
		$wpdb->prepare( 
			"DELETE FROM $image_table
			 WHERE id = %d
			",
				$_REQUEST['did'] 
			)
	);	
}
?>

    <h1>w2pe Beaver Slider - All Images</h1>
    
    <div class="bSlider">
    <?php if ( isset($msg1) ){?>
    <div class="updated error" id="msg"><p><strong><?php echo $msg1;?></strong></p></div>
    <?php }?>
    <?php if ( isset($msg2) ){?>
    <div class="updated" id="msg"><p><strong><?php echo $msg2;?></strong></p></div>
    <?php }?>

        <fieldset>
            <legend>How To Use</legend>
            <p align="center">Place <code>&lt;?php if (function_exists('w2pe_bslider')) { w2pe_bslider(); } ?&gt;</code> in your templates</p>
            <p align="center"><strong>OR</strong></p>
            <p align="center">Use this short code<code>[w2pe_bSlider]</code></p>
        </fieldset>
        
    <!--html for update-->
        <?php
        if ( isset($_REQUEST['cid']) ){
            $row=$wpdb->get_row("select * from $image_table WHERE id = '".$_REQUEST['cid']."' ");
        ?>
        <fieldset class="cSlider">
            <legend>Update Content</legend>
        <form action="" method="post" enctype="multipart/form-data">
        <table width="100%" border="0">
          <tr>
            <td width="26%"><div align="right">Caption</div></td>
            <td width="1%"><div align="center"><strong>:</strong></div></td>
            <td width="73%"><input type="text" name="caption" value="<?php echo stripslashes($row->caption) ?>" /></td>
          </tr>
          <tr>
            <td><div align="right">Order</div></td>
            <td><div align="center"><strong>:</strong></div></td>
            <td><input type="text" name="position" value="<?php echo $row->position; ?>" /></td>
          </tr>
          <tr valign="top">
            <td width="26%"><div align="right">Current Image</div></td>
            <td width="1%"><div align="center"><strong>:</strong></div></td>
            <td width="73%"><img src="<?php echo get_option('siteurl').'/wp-content/uploads/w2pe-beaverslider/'.$row->id.'.'.$row->ftype?>" width="100px" /></td>
          </tr>
          <tr>
            <td><div align="right">Image<span class="error">*</span></div></td>
            <td><div align="center"><strong>:</strong></div></td>
            <td><input type="file" name="image" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" class="button button-primary button-large" name="update" value="Update Content" /></td>
          </tr>
        </table>
        </form>
        </fieldset>
        <?php }else{?>
        <!--html for add-->
        <fieldset>
            <legend>Add Slider Image</legend>
            <form action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0">
              <tr>
                <td width="26%"><div align="right">Caption</div></td>
                <td width="1%"><div align="center"><strong>:</strong></div></td>
                <td width="73%"><input type="text" name="caption" value="" /></td>
              </tr>
              <tr>
                <td><div align="right">Order</div></td>
                <td><div align="center"><strong>:</strong></div></td>
                <td><input type="text" name="position" placeholder="1" /></td>
              </tr>
              <tr>
                <td><div align="right">Image<span class="error">*</span></div></td>
                <td><div align="center"><strong>:</strong></div></td>
                <td><input type="file" name="image" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><input type="submit" class="button button-primary button-large" name="upload" value="Add" /></td>
              </tr>
            </table>
            </form>
        </fieldset>
        <?php }?>
        
        
        <fieldset>
            <legend>Slider Contents</legend>
            <table width="100%" border="0">
              <tr style="background:#000;color:#fff">
                <td width="32%"><div align="center"><strong>Caption</strong></div></td>
                <td width="32%"><div align="center"><strong>Image</strong></div></td>
                <td width="12%"><div align="center"><strong>Order</strong></div></td>
                <td width="24%"><div align="center"><strong>Action</strong></div></td>
              </tr>
              <?php
                 $result=$wpdb->get_results("SELECT * FROM `$image_table` ORDER BY `id` DESC");
				 if(!empty($result)){
                 foreach ($result as $r){
              ?>      
              <tr>
                <td><div align="center"><?php echo stripslashes($r->caption)?></div></td>
                <td><div align="center"><img src="<?php echo get_option('siteurl').'/wp-content/uploads/w2pe-beaverslider/'.$r->id.'.'.$r->ftype?>" width="300px" height="100" /></div></td>
                <td><div align="center"><?php echo $r->position?></div></td>
                <td>
                    <div align="center">
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?page=w2pe_bslider_images&cid=<?php echo $r->id?>" title="Update"><img src="<?php echo $url?>/images/edit.png" /></a> 
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?page=w2pe_bslider_images&did=<?php echo $r->id?>" title="Delete"><img src="<?php echo $url?>/images/delete.png" /></a>
                    </div>
                </td>
              </tr>
              <?php }}else{?>
              <tr><td colspan="3" align="center">No images added yet</td></tr>
              <?php }?>
            </table>
        </fieldset>
</div>