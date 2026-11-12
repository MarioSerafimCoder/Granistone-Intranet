<?php
/**
 * Modelo padrão.
 *
 * @package Granistone_Intranet
 */

get_header();
?>
<main class="section wp-content" id="conteudo-principal" tabindex="-1">
	<p class="eyebrow">PORTAL GRANISTONE</p>
	<h1><?php echo esc_html( wp_get_document_title() ); ?></h1>
	<?php if ( have_posts() ) : ?>
		<div class="wp-post-list">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nenhum conteúdo encontrado.', 'granistone-intranet' ); ?></p>
	<?php endif; ?>
</main>
<?php get_footer(); ?>

