<?php
$affiliate_id = (int) isset($_COOKIE[SDBAL_COOKIE_AFFILIATE_KEY]) ? $_COOKIE[SDBAL_COOKIE_AFFILIATE_KEY] : 0;
?>
<input type="hidden" name="wpforms[affiliate_id]" value="<?php echo $affiliate_id; ?>" />