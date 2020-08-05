<?php
/**
 * This template can be overridden by copying it to yourtheme/video-conferencing-zoom/fragments/filters.php.
 *
 * @author      Deepen Bajracharya (CodeManas)
 * @created     3.6.0
 */
?>
<form class="vczapi-filters" method="GET">
    <div class="vczapi-wrap  vczapi-ptb">
        <div class="vczapi-col-4">
			<?php
			$terms = get_terms( array(
				'taxonomy'   => 'zoom-meeting',
				'hide_empty' => false
			) );
			if ( ! empty( $terms ) ) {
				?>
                <select name="taxonomy" class="vczapi-taxonomy-ordering">
                    <option value="category_order">All Category</option>
					<?php foreach ( $terms as $term ) { ?>
                        <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
					<?php } ?>
                </select>
				<?php
			}
			?>
        </div>
        <div class="vczapi-col-4">
            <select name="orderby" class="vczapi-ordering">
                <option value="show_all">Show All</option>
                <option value="latest">Sort by Latest</option>
                <option value="past">Sorty by Past</option>
            </select>
        </div>
        <div class="vczapi-col-4">
            <input type="text" placeholder="Search.." class="vczapi-searching" value="" name="search">
        </div>
    </div>
</form>
