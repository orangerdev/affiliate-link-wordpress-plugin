<?php
$campaign_id = 0;
if ( isset( $form_data['settings']['form_tags'] ) ) :
    $form_tags = $form_data['settings']['form_tags'];
    foreach ( $form_tags as $form_tag ) :
        if ( strpos( $form_tag, '#' ) !== false ) :
            $campaign_id = intval(str_replace('#','',$form_tag));
        endif;
    endforeach;
endif;
$affiliate_id = (int) isset($_COOKIE[SDBAL_COOKIE_AFFILIATE_KEY]) ? $_COOKIE[SDBAL_COOKIE_AFFILIATE_KEY] : 0;
?>
<input type="hidden" name="wpforms[affiliate_id]" value="<?php echo $affiliate_id; ?>" />
<input type="hidden" name="wpforms[campaign_id]" value="<?php echo $campaign_id; ?>" />