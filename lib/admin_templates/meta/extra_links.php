<?php
/**
 * HTML For The Extra links Meta Box.
 * 
 * @version 0.1
 * @package totally-booked
 * @since 0.1
 */

global $post;

//Load The Extra Links From The Database
$links = get_post_meta( $post->ID, 'reader_links', true );

//Ensure They Are The Right Format.
if( ! $links || ! is_array( $links ) ) $links = array();

////Get A Count
$count = count( $links );

$next = $count ++;
?>

<p class="hide-if-js">
    <?php _e( 'You Must Have Javascript Enabled To Use This Section.', 'totally-booked' ); ?>
</p>

<script type="text/javascript">
    var reader_links = <?php echo json_encode( $links ); ?>
</script>

<p>
    <?php _e( 'Have a sample chapter or discussion guide for your readers? Enter the links below. Don\'t have either? Be sure to check your publisher’s website, to see if they have links available. If so, copy those links from their site, and paste them in the boxes.  Don’t have enough boxes? Click the Add Link line to create more.  Then input whatever extras you\'d like (links to Pinterest boards, author interviews, etc.).', 'totally-booked' ); ?>
</p>

<table class="hide-if-no-js" id="reader_links_table">

    <thead>
        <tr>
            <th><?php _e( 'URL'      , 'totally-booked' ); ?></th>
            <th><?php _e( 'Link Text', 'totally-booked' ); ?></th>
            <th><?php _e( 'Preview'  , 'totally-booked' ); ?></th>
            <th><?php _e( 'Actions'  , 'totally-booked' ); ?></th>
        </tr>
    </thead>

    <tr  id="add_new_link_row" data-count="<?php echo $next; ?>">
        <td class="url"><input type="text" id="new_link_url" name="new_link_url" value="" /></td>
        <td class="text"><input type="text" id="new_link_text" name="new_link_text" value="" /></td>
        <td class="_preview">&nbsp;</td>
        <td class="actions"><a id="add_link_button" href="javascript:void(0)">Add Link</a></td>
    </tr>

</table>
