<?php
/**
 * Cabeçalho do tema.
 *
 * @package Granistone_Intranet
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<a class="brand" href="<?php echo esc_url( home_url( '/#inicio' ) ); ?>" aria-label="<?php esc_attr_e( 'Granistone, início', 'granistone-intranet' ); ?>">
		<img class="brand-logo" src="<?php echo esc_url( granistone_logo_url() ); ?>" alt="<?php echo esc_attr( granistone_theme_text( 'brand_name', 'Granistone' ) ); ?>">
		<small class="brand-portal"><?php echo esc_html( granistone_theme_text( 'brand_subtitle', 'PORTAL DO COLABORADOR' ) ); ?></small>
	</a>
	<nav class="main-nav" id="main-nav" aria-label="<?php esc_attr_e( 'Navegação principal', 'granistone-intranet' ); ?>">
		<a href="<?php echo esc_url( home_url( '/#inicio' ) ); ?>">Início</a>
		<a href="<?php echo esc_url( home_url( '/#noticias' ) ); ?>">Notícias</a>
		<a href="<?php echo esc_url( home_url( '/#historia' ) ); ?>">A Granistone</a>
		<a href="<?php echo esc_url( home_url( '/#agenda' ) ); ?>">Agenda</a>
		<a href="<?php echo esc_url( home_url( '/#pessoas' ) ); ?>">Pessoas</a>
		<a href="<?php echo esc_url( home_url( '/#cultura' ) ); ?>">Cultura</a>
		<a href="<?php echo esc_url( home_url( '/#saude' ) ); ?>">Saúde</a>
		<a href="<?php echo esc_url( home_url( '/#contatos' ) ); ?>">Contatos</a>
	</nav>
	<div class="header-actions">
		<a class="search-button" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Buscar no portal', 'granistone-intranet' ); ?>"><span aria-hidden="true">⌕</span></a>
		<button class="menu-button" type="button" aria-label="<?php esc_attr_e( 'Abrir menu', 'granistone-intranet' ); ?>" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button>
	</div>
</header>
