<!-- ? LEFT WIDGET AREA STARTS -->

<?php if ( is_active_sidebar( 'left-sidebar' ) ) : ?>

<!---BEGIN LEFT SIDEBARS--->

<div id="widget-left" class="widget-column">				
	<div class="widget-container">
		<div class="blk-body">
			<div class="blk-row1">
				<div class="blk-row1-inner"></div>
			</div>
			<div class="blk-content">
				<div class="blk-content-inner">
					<aside id="left" class="sidebar" aria-label="<?php echo is_active_sidebar( 'right-sidebar' ) ? esc_attr_x( 'Left Sidebar', 'ARIA label', 'dw-cosmosv2' ) : esc_attr_x( 'Sidebar', 'ARIA label', 'dw-cosmosv2' ); ?>">
						<ul>
							<?php dynamic_sidebar( 'left-sidebar' ); ?>
						</ul>
					</aside>
				</div>
			</div>
			<div class="blk-row3">
				<div class="blk-row3-inner"></div>
			</div>
		</div>
	</div>
</div>

<!---END LEFT SIDEBARS--->

<?php endif; ?>

<!-- ! LEFT WIDGET AREA ENDS -->

<!-- ? RIGHT WIDGET AREA STARTS -->

<?php if ( is_active_sidebar( 'right-sidebar' ) ) : ?>

<!---BEGIN RIGHT SIDEBARS--->

<div id="widget-right" class="widget-column">
	<div class="widget-container">
		<div class="blk-body">
			<div class="blk-row1">
					<div class="blk-row1-inner"></div>
			</div>
			<div class="blk-content">
				<div class="blk-content-inner">
					<aside id="right" class="sidebar2" aria-label="<?php echo is_active_sidebar( 'left-sidebar' ) ? esc_attr_x( 'Right Sidebar', 'ARIA label', 'dw-cosmosv2' ) : esc_attr_x( 'Sidebar', 'ARIA label', 'dw-cosmosv2' ); ?>">
						<ul>
							<?php dynamic_sidebar( 'right-sidebar' ); ?>
						</ul>
					</aside>
				</div>
			</div>
			<div class="blk-row3">
				<div class="blk-row3-inner"></div>
			</div>
		</div>
	</div>
</div>

<!---END RIGHT SIDEBARS--->

<?php endif; ?>

<!-- ! RIGHT WIDGET AREA ENDS -->
