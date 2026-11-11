<?php
/**
 * Rodapé do tema.
 *
 * @package Granistone_Intranet
 */
?>
<footer id="contatos">
	<div class="footer-top">
		<div class="footer-brand"><img class="footer-logo" src="<?php echo esc_url( granistone_logo_url() ); ?>" alt="<?php echo esc_attr( granistone_theme_text( 'brand_name', 'Granistone' ) ); ?>"><p><?php echo esc_html( granistone_theme_text( 'footer_tagline', 'Excelência em Rochas Ornamentais desde 1989.' ) ); ?></p></div>
		<blockquote><?php echo esc_html( granistone_theme_text( 'footer_quote_primary', '“Grandes histórias são' ) ); ?> <span><?php echo esc_html( granistone_theme_text( 'footer_quote_highlight', 'construídas por pessoas.”' ) ); ?></span></blockquote>
	</div>
	<div class="footer-bottom">
		<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> Granistone. Todos os direitos reservados.</p>
		<nav aria-label="<?php esc_attr_e( 'Redes e contatos', 'granistone-intranet' ); ?>"><a href="#contatos">Instagram</a><a href="#contatos">LinkedIn</a><a href="mailto:<?php echo esc_attr( granistone_theme_text( 'footer_email', 'comunicacao@granistone.com.br' ) ); ?>">Contato interno</a></nav>
	</div>
</footer>
<?php
$reflection = array(
	'quote'              => 'Porque dele, por ele e para ele são todas as coisas.',
	'reference'          => 'Romanos 11:36',
	'slot_key'           => 'fallback-' . wp_date( 'Y-m-d' ),
	'stone_index'        => 0,
	'stone_name'         => 'Amazonita',
	'seconds_until_next' => 0,
);
if ( function_exists( 'granistone_reflections_get_current' ) ) {
	$reflection = wp_parse_args( granistone_reflections_get_current(), $reflection );
}
$reflection_endpoint = function_exists( 'granistone_reflections_get_current' ) ? rest_url( 'granistone-reflections/v1/current' ) : '';
?>
<div class="popup-backdrop" role="dialog" aria-modal="true" aria-labelledby="reflection-title" data-reflection-slot="<?php echo esc_attr( $reflection['slot_key'] ); ?>" data-reflection-stone="<?php echo esc_attr( absint( $reflection['stone_index'] ) ); ?>" data-reflection-endpoint="<?php echo esc_url( $reflection_endpoint ); ?>" data-reflection-next="<?php echo esc_attr( absint( $reflection['seconds_until_next'] ) ); ?>" hidden>
	<div class="reflection-popup">
		<button class="popup-close" type="button" aria-label="<?php esc_attr_e( 'Fechar mensagem', 'granistone-intranet' ); ?>">×</button>
		<div class="popup-art reflection-stone-<?php echo esc_attr( absint( $reflection['stone_index'] ) ); ?>"><span>MOMENTO DE REFLEXÃO</span><small class="reflection-stone-name"><?php echo esc_html( $reflection['stone_name'] ); ?></small></div>
		<div class="popup-copy"><span class="quote-mark">“</span><h2 id="reflection-title"><?php echo esc_html( $reflection['quote'] ); ?></h2><p class="reflection-reference"><?php echo esc_html( $reflection['reference'] ); ?></p><button class="button button-primary popup-continue" type="button">Continuar no portal</button></div>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
