<?php
/**
 * The template for displaying the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mysterious_Face
 */
?>

				<?php if ( function_exists( 'do_blocks' ) ) : ?>
					<?php echo do_blocks( '<!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->' ); ?>
				<?php elseif ( function_exists( 'block_template_part' ) ) : ?>
					<?php block_template_part( 'footer' ); ?>
				<?php else : ?>
					<footer class="site-footer"></footer>
				<?php endif; ?>
			</div><!-- .container-site -->
		</div><!-- .wrapper-inner -->
	</div><!-- .wrapper -->
</div><!-- .wp-site-blocks -->

<?php wp_footer(); ?>

</body>
</html>
