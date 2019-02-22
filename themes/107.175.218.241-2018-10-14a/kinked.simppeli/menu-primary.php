<?php
/**
 * Primary menu.
 *
 * @package Simppeli
 */
?>

<?php if ( has_nav_menu( 'primary' ) ) : // Check if there's a menu assigned to the 'primary' location. ?>
		
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_attr_e( 'Primary Menu', 'simppeli' ); ?></h2>
			
			<?php wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'depth'          => 1
				) );
			?>
			
		</nav><!-- #site-navigation -->

<?php endif; // End check for menu. ?>