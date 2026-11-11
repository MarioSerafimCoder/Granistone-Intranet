<?php
/**
 * Funções do tema Granistone.
 *
 * @package Granistone_Intranet
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function granistone_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'granistone-intranet' ),
		)
	);
}
add_action( 'after_setup_theme', 'granistone_setup' );

function granistone_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'granistone-style', get_stylesheet_uri(), array(), $version );
	wp_enqueue_script(
		'granistone-theme',
		get_theme_file_uri( 'assets/js/theme.js' ),
		array(),
		$version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'granistone_assets' );

/**
 * Retorna um texto configurável do tema.
 *
 * @param string $key     Identificador sem prefixo.
 * @param string $default Texto padrão.
 * @return string
 */
function granistone_theme_text( $key, $default = '' ) {
	return get_theme_mod( 'granistone_' . $key, $default );
}

/**
 * Retorna a logo escolhida no WordPress ou a logo oficial incluída no tema.
 *
 * @return string
 */
function granistone_logo_url() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$custom_logo    = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : '';
	return $custom_logo ? $custom_logo : get_theme_file_uri( 'assets/images/granistone-logo.png' );
}

/**
 * Retorna as rochas oficiais incluídas no tema.
 *
 * A imagem destacada definida no painel pode substituir qualquer uma delas.
 *
 * @return array
 */
function granistone_bundled_stones() {
	$stones = array(
		array( 'title' => 'Amazonita', 'file' => 'amazonita.webp' ),
		array( 'title' => 'Ametista', 'file' => 'ametista.webp' ),
		array( 'title' => 'Bianco Milano', 'file' => 'bianco-milano.webp' ),
		array( 'title' => 'Capolavoro', 'file' => 'capolavoro.webp' ),
		array( 'title' => 'Crystalline Ice', 'file' => 'crystalline-ice.webp' ),
		array( 'title' => 'Mel Rose', 'file' => 'mel-rose.webp' ),
		array( 'title' => 'Melissa Sky', 'file' => 'melissa-sky.webp' ),
		array( 'title' => 'Palladio', 'file' => 'palladio.webp' ),
		array( 'title' => 'Panda Sunet', 'file' => 'panda-sunet.webp' ),
		array( 'title' => 'Presidence', 'file' => 'presidence.webp' ),
		array( 'title' => 'Red Carpet', 'file' => 'red-carpet.webp' ),
		array( 'title' => 'Speranza', 'file' => 'speranza.webp' ),
	);

	foreach ( $stones as &$stone ) {
		$stone['image']   = get_theme_file_uri( 'assets/images/rocks/' . $stone['file'] );
		$stone['eyebrow'] = 'ORIGEM & EXCELÊNCIA';
		$stone['tagline'] = 'Natureza transformada em legado';
	}
	unset( $stone );

	return $stones;
}

/**
 * Converte textos para caixa alta preservando acentos quando possível.
 *
 * @param string $text Texto original.
 * @return string
 */
function granistone_upper( $text ) {
	return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $text, 'UTF-8' ) : strtoupper( $text );
}

/**
 * Adiciona os textos institucionais ao Personalizador.
 *
 * @param WP_Customize_Manager $wp_customize Instância do Personalizador.
 */
function granistone_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'granistone_portal_content',
		array(
			'title'       => __( 'Textos do Portal', 'granistone-intranet' ),
			'description' => __( 'Edite os principais textos institucionais da página inicial. Posts e Vídeos continuam em seus menus próprios.', 'granistone-intranet' ),
			'priority'    => 30,
		)
	);

	$sections = array(
		'hero' => array(
			'title'       => __( 'Capa e boas-vindas', 'granistone-intranet' ),
			'description' => __( 'Textos da primeira área do portal.', 'granistone-intranet' ),
		),
		'headings' => array(
			'title'       => __( 'Títulos das seções', 'granistone-intranet' ),
			'description' => __( 'Chamadas e introduções das áreas principais.', 'granistone-intranet' ),
		),
		'culture' => array(
			'title'       => __( 'Cultura e reflexão', 'granistone-intranet' ),
			'description' => __( 'Palavra da semana e mensagem institucional.', 'granistone-intranet' ),
		),
		'history' => array(
			'title'       => __( 'Nossa história', 'granistone-intranet' ),
			'description' => __( 'Texto institucional sobre a Granistone.', 'granistone-intranet' ),
		),
		'recognition' => array(
			'title'       => __( 'Reconhecimento', 'granistone-intranet' ),
			'description' => __( 'Destaques e atitudes que inspiram.', 'granistone-intranet' ),
		),
		'ideas' => array(
			'title'       => __( 'Canal de ideias', 'granistone-intranet' ),
			'description' => __( 'Chamada do formulário de sugestões.', 'granistone-intranet' ),
		),
		'footer' => array(
			'title'       => __( 'Rodapé', 'granistone-intranet' ),
			'description' => __( 'Assinatura, frase e contato institucional.', 'granistone-intranet' ),
		),
	);

	foreach ( $sections as $section_key => $section ) {
		$wp_customize->add_section(
			'granistone_' . $section_key,
			array(
				'title'       => $section['title'],
				'description' => $section['description'],
				'panel'       => 'granistone_portal_content',
			)
		);
	}

	$fields = array(
		array( 'hero', 'brand_name', 'Nome da marca no cabeçalho', 'GRANISTONE' ),
		array( 'hero', 'brand_subtitle', 'Subtítulo no cabeçalho', 'PORTAL DO COLABORADOR' ),
		array( 'hero', 'hero_eyebrow', 'Identificação', 'PORTAL DO COLABORADOR' ),
		array( 'hero', 'hero_title_primary', 'Título principal', 'Bem-vindo ao' ),
		array( 'hero', 'hero_title_secondary', 'Título em destaque', 'Portal Granistone' ),
		array( 'hero', 'hero_lead', 'Texto de apresentação', 'Informação, conexão e cultura para todos que fazem parte dessa história.', 'textarea' ),
		array( 'hero', 'hero_primary_button', 'Botão principal', 'Últimas notícias' ),
		array( 'hero', 'hero_secondary_button', 'Botão secundário', 'Nossa história' ),
		array( 'hero', 'hero_year', 'Ano de fundação', '1989' ),
		array( 'hero', 'hero_signature', 'Assinatura do ano', 'Excelência em rochas ornamentais' ),
		array( 'hero', 'hero_stone_eyebrow', 'Identificação da pedra', 'ORIGEM & EXCELÊNCIA' ),
		array( 'hero', 'hero_stone_title', 'Nome da pedra', 'Amazonita' ),
		array( 'hero', 'hero_stone_subtitle', 'Legenda da pedra', 'Natureza transformada em legado' ),

		array( 'headings', 'announcements_eyebrow', 'Comunicados: identificação', 'FIQUE POR DENTRO' ),
		array( 'headings', 'announcements_title', 'Comunicados: título', 'Comunicados Granistone' ),
		array( 'headings', 'health_eyebrow', 'Saúde: identificação', 'SAÚDE EM FOCO' ),
		array( 'headings', 'health_title', 'Saúde: título', 'Aprender também é uma forma de se cuidar.' ),
		array( 'headings', 'health_intro', 'Saúde: introdução', 'Conteúdos oficiais sobre prevenção, bem-estar e segurança para cuidar da saúde dentro e fora do trabalho.', 'textarea' ),
		array( 'headings', 'health_channel_label', 'Saúde: texto do link', 'Canal do Ministério da Saúde' ),
		array( 'headings', 'health_channel_url', 'Saúde: endereço do link', 'https://www.youtube.com/@MinSaudeBR', 'url' ),
		array( 'headings', 'health_disclaimer', 'Saúde: aviso final', 'Conteúdo educativo. Em caso de sintomas ou dúvidas individuais, procure orientação de um profissional de saúde.', 'textarea' ),
		array( 'headings', 'news_eyebrow', 'Notícias: identificação', 'ACONTECE NA GRANISTONE' ),
		array( 'headings', 'news_title', 'Notícias: título', 'Últimas notícias' ),
		array( 'headings', 'agenda_eyebrow', 'Agenda: identificação', 'PROGRAME-SE' ),
		array( 'headings', 'agenda_title', 'Agenda: título', 'Agenda Granistone' ),
		array( 'headings', 'agenda_intro', 'Agenda: introdução', 'Encontros, treinamentos e datas que fortalecem nossa conexão.', 'textarea' ),
		array( 'headings', 'people_eyebrow', 'Pessoas: identificação', 'NOSSA GENTE' ),
		array( 'headings', 'people_title', 'Pessoas: título', 'Celebramos quem faz parte' ),
		array( 'headings', 'people_note', 'Pessoas: mensagem', 'Parabéns! Que o novo ciclo seja cheio de conquistas.', 'textarea' ),
		array( 'headings', 'values_eyebrow', 'Valores: identificação', 'NOSSO JEITO DE SER' ),
		array( 'headings', 'values_title', 'Valores: título', 'Valores que nos movem' ),
		array( 'headings', 'values_note', 'Valores: introdução', 'Princípios que orientam escolhas, relações e resultados.', 'textarea' ),
		array( 'headings', 'journey_eyebrow', 'Negócio: identificação', 'DO BLOCO À HISTÓRIA' ),
		array( 'headings', 'journey_title', 'Negócio: título', 'Entenda o nosso negócio' ),
		array( 'headings', 'resources_eyebrow', 'Serviços: identificação', 'CONTEÚDO E SERVIÇOS' ),
		array( 'headings', 'resources_title', 'Serviços: título', 'Explore o portal' ),

		array( 'culture', 'weekly_label', 'Identificação', 'PALAVRA DA SEMANA' ),
		array( 'culture', 'weekly_quote', 'Frase principal', 'Cada pessoa tem um papel importante na construção dessa história.', 'textarea' ),
		array( 'culture', 'weekly_verse', 'Mensagem', '“Tudo o que fizerem, façam de todo o coração, como para o Senhor.”', 'textarea' ),
		array( 'culture', 'weekly_reference', 'Referência', 'COLOSSENSES 3:23' ),

		array( 'history', 'history_eyebrow', 'Identificação', 'NOSSA HISTÓRIA' ),
		array( 'history', 'history_title', 'Título', 'Da origem à excelência,' ),
		array( 'history', 'history_highlight', 'Trecho em destaque', 'uma história escrita em pedra.' ),
		array( 'history', 'history_body', 'Texto institucional', 'Desde 1989, a Granistone une conhecimento, tecnologia e respeito à natureza para levar a beleza das rochas brasileiras a diferentes mercados do mundo.', 'textarea' ),
		array( 'history', 'history_button', 'Texto do botão', 'Conheça a Granistone' ),

		array( 'recognition', 'recognition_eyebrow', 'Identificação', 'GRANISTONE RECONHECE' ),
		array( 'recognition', 'recognition_title', 'Título', 'Atitudes que inspiram' ),
		array( 'recognition', 'recognition_intro', 'Introdução', 'Reconhecer é valorizar quem faz a diferença todos os dias.', 'textarea' ),
		array( 'recognition', 'recognition_one_label', 'Card 1: identificação', 'DESTAQUE DO MÊS' ),
		array( 'recognition', 'recognition_one_title', 'Card 1: título', 'Marcos Almeida' ),
		array( 'recognition', 'recognition_one_text', 'Card 1: texto', 'Pela liderança, atenção à segurança e cuidado com toda a equipe.', 'textarea' ),
		array( 'recognition', 'recognition_one_detail', 'Card 1: detalhe', 'Produção · Unidade ES' ),
		array( 'recognition', 'recognition_two_label', 'Card 2: identificação', 'ATITUDE QUE INSPIRA' ),
		array( 'recognition', 'recognition_two_title', 'Card 2: título', 'Equipe de Logística' ),
		array( 'recognition', 'recognition_two_text', 'Card 2: texto', 'Colaboração que transformou um desafio em uma entrega exemplar.', 'textarea' ),
		array( 'recognition', 'recognition_two_detail', 'Card 2: detalhe', 'Juntos, vamos mais longe.' ),

		array( 'ideas', 'ideas_eyebrow', 'Identificação', 'SUA VOZ IMPORTA' ),
		array( 'ideas', 'ideas_title', 'Título', 'Tem uma ideia para melhorar nosso dia a dia?' ),
		array( 'ideas', 'ideas_body', 'Texto de apresentação', 'Compartilhe sua sugestão. Boas transformações começam com uma boa conversa.', 'textarea' ),

		array( 'footer', 'footer_tagline', 'Descrição da empresa', 'Excelência em Rochas Ornamentais desde 1989.' ),
		array( 'footer', 'footer_quote_primary', 'Frase principal', '“Grandes histórias são' ),
		array( 'footer', 'footer_quote_highlight', 'Frase em destaque', 'construídas por pessoas.”' ),
		array( 'footer', 'footer_email', 'E-mail de contato', 'comunicacao@granistone.com.br', 'email' ),
	);

	foreach ( $fields as $index => $field ) {
		$type              = isset( $field[4] ) ? $field[4] : 'text';
		$sanitize_callback = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize_callback = 'sanitize_textarea_field';
		} elseif ( 'url' === $type ) {
			$sanitize_callback = 'esc_url_raw';
		} elseif ( 'email' === $type ) {
			$sanitize_callback = 'sanitize_email';
		}

		$setting_id = 'granistone_' . $field[1];
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $field[3],
				'sanitize_callback' => $sanitize_callback,
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'    => $field[2],
				'section'  => 'granistone_' . $field[0],
				'type'     => $type,
				'priority' => $index + 10,
			)
		);
	}

	$wp_customize->add_setting(
		'granistone_weekly_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'granistone_weekly_image',
			array(
				'label'       => __( 'Imagem da Palavra da Semana', 'granistone-intranet' ),
				'description' => __( 'Use uma imagem horizontal, preferencialmente em WebP ou JPG com cerca de 1600 × 1100 px.', 'granistone-intranet' ),
				'section'     => 'granistone_culture',
				'priority'    => 5,
			)
		)
	);
}
add_action( 'customize_register', 'granistone_customize_register' );
