<?php

use ROCKET_HYPERLINKS_STATS\Links;

defined( 'ABSPATH' ) || exit( 'shhh.' );

$rocket_hs_links = Links::get_links()

?>
<h1>
	Hyperlinks Stats
</h1>
<p>
	Welcome to the Hyperlinks Stats plugin!
	The next table shows the hyperlinks shown on your homepage. The count is the number of times each link was shown.
<div>
	<table class="widefat fixed">
		<thead>
		<tr>
			<th id="cb" class="manage-column column-cb check-column" scope="col"></th>
			<th id="column_link" class="manage-column column-column_link" scope="col">
				<?php esc_html_e( 'Link', 'rocket' ); ?>
			</th>
			<th id="column_size" class="manage-column column-column_size num" scope="col">
				<?php esc_html_e( 'Count', 'rocket' ); ?>
			</th>
		</tr>
		</thead>

		<tbody>
			<?php	foreach ( $rocket_hs_links as $rocket_index => $rocket_hs_link ) : ?>
				<tr class="<?php echo esc_attr( 0 === $rocket_index % 2 ? 'alternate' : '' ); ?>">
					<th class="check-column" scope="row"></th>
					<td class="column-column_url">
						<?php echo esc_html( $rocket_hs_link->url ); ?>
					</td>
					<td class="column-column_count">
						<?php echo esc_html( $rocket_hs_link->count ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>

	</table>
</div>
