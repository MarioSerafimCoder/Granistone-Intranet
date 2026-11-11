<?php
/**
 * Página inicial do Portal do Colaborador.
 *
 * @package Granistone_Intranet
 */

$values = array(
	array( '01', 'Excelência', 'Fazemos bem-feito, sempre.' ),
	array( '02', 'Respeito', 'Cuidamos das pessoas e relações.' ),
	array( '03', 'Segurança', 'A vida está em primeiro lugar.' ),
	array( '04', 'União', 'Crescemos quando caminhamos juntos.' ),
	array( '05', 'Fé', 'Nosso propósito orienta o caminho.' ),
	array( '06', 'Compromisso', 'Honramos cada entrega e parceria.' ),
);

get_header();
?>
<main>
	<section class="hero" id="inicio">
		<div class="hero-copy">
			<p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'hero_eyebrow', 'PORTAL DO COLABORADOR' ) ); ?></p>
			<h1><?php echo esc_html( granistone_theme_text( 'hero_title_primary', 'Bem-vindo ao' ) ); ?> <span><?php echo esc_html( granistone_theme_text( 'hero_title_secondary', 'Portal Granistone' ) ); ?></span></h1>
			<p class="hero-lead"><?php echo esc_html( granistone_theme_text( 'hero_lead', 'Informação, conexão e cultura para todos que fazem parte dessa história.' ) ); ?></p>
			<div class="hero-actions">
				<a class="button button-primary" href="#comunicados"><?php echo esc_html( granistone_theme_text( 'hero_primary_button', 'Últimas notícias' ) ); ?> <span aria-hidden="true">↗</span></a>
				<a class="button button-ghost" href="#historia"><?php echo esc_html( granistone_theme_text( 'hero_secondary_button', 'Nossa história' ) ); ?></a>
			</div>
			<div class="hero-signature"><span>DESDE</span><strong><?php echo esc_html( granistone_theme_text( 'hero_year', '1989' ) ); ?></strong><i></i><p><?php echo esc_html( granistone_theme_text( 'hero_signature', 'Excelência em rochas ornamentais' ) ); ?></p></div>
		</div>
		<?php
		$bundled_stones = granistone_bundled_stones();
		$bundled_map    = array();
		$stones         = array();
		$used_stones    = array();

		foreach ( $bundled_stones as $bundled_stone ) {
			$bundled_map[ sanitize_title( $bundled_stone['title'] ) ] = $bundled_stone;
		}

		$stones_query = new WP_Query(
			array(
				'post_type'      => 'granistone_stone',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			)
		);

		while ( $stones_query->have_posts() ) {
			$stones_query->the_post();
			$stone_title   = get_the_title();
			$stone_slug    = sanitize_title( $stone_title );
			$stone_default = isset( $bundled_map[ $stone_slug ] ) ? $bundled_map[ $stone_slug ] : array();
			$stone_image   = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			$stone_eyebrow = get_post_meta( get_the_ID(), '_granistone_stone_eyebrow', true );
			$stone_tagline = get_post_meta( get_the_ID(), '_granistone_stone_tagline', true );

			$stones[] = array(
				'title'   => $stone_title,
				'image'   => $stone_image ? $stone_image : ( isset( $stone_default['image'] ) ? $stone_default['image'] : '' ),
				'eyebrow' => $stone_eyebrow ? $stone_eyebrow : 'ORIGEM & EXCELÊNCIA',
				'tagline' => $stone_tagline ? $stone_tagline : ( get_the_excerpt() ? get_the_excerpt() : 'Natureza transformada em legado' ),
			);
			$used_stones[ $stone_slug ] = true;
		}
		wp_reset_postdata();

		foreach ( $bundled_stones as $bundled_stone ) {
			$stone_slug = sanitize_title( $bundled_stone['title'] );
			if ( ! isset( $used_stones[ $stone_slug ] ) ) {
				$stones[] = $bundled_stone;
			}
		}
		?>
		<div class="hero-stone stone-carousel" data-stone-carousel tabindex="0" aria-label="<?php esc_attr_e( 'Rochas Granistone', 'granistone-intranet' ); ?>">
			<div class="stone-slides" aria-live="polite">
				<?php foreach ( $stones as $stone_index => $stone ) : ?>
					<article class="stone-slide <?php echo 0 === $stone_index ? 'is-active' : ''; ?>" data-stone-slide aria-hidden="<?php echo 0 === $stone_index ? 'false' : 'true'; ?>" <?php if ( $stone['image'] ) : ?>style="background-image: url('<?php echo esc_url( $stone['image'] ); ?>');"<?php endif; ?>>
						<div class="stone-label"><span><?php echo esc_html( $stone['eyebrow'] ); ?></span><strong><?php echo esc_html( $stone['title'] ); ?></strong><small><?php echo esc_html( $stone['tagline'] ); ?></small></div>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ( count( $stones ) > 1 ) : ?>
				<div class="stone-controls">
					<button type="button" data-stone-prev aria-label="<?php esc_attr_e( 'Rocha anterior', 'granistone-intranet' ); ?>">←</button>
					<div class="stone-dots" aria-label="<?php esc_attr_e( 'Selecionar rocha', 'granistone-intranet' ); ?>">
						<?php for ( $dot_index = 0; $dot_index < count( $stones ); $dot_index++ ) : ?><button type="button" data-stone-dot="<?php echo esc_attr( $dot_index ); ?>" class="<?php echo 0 === $dot_index ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ver rocha %d', 'granistone-intranet' ), $dot_index + 1 ) ); ?>"></button><?php endfor; ?>
					</div>
					<button type="button" data-stone-next aria-label="<?php esc_attr_e( 'Próxima rocha', 'granistone-intranet' ); ?>">→</button>
				</div>
			<?php endif; ?>
			<div class="scroll-cue"><span></span>EXPLORE O PORTAL</div>
		</div>
	</section>

	<section class="quick-access" aria-label="Acessos rápidos">
		<p>Acessos rápidos</p>
		<a href="#agenda"><span aria-hidden="true">▦</span>Agenda <b aria-hidden="true">↗</b></a>
		<a href="#biblioteca"><span aria-hidden="true">□</span>Documentos <b aria-hidden="true">↗</b></a>
		<a href="#videos"><span aria-hidden="true">▷</span>Vídeos <b aria-hidden="true">↗</b></a>
		<a href="#ideias"><span aria-hidden="true">◇</span>Canal de ideias <b aria-hidden="true">↗</b></a>
	</section>

	<section class="section announcements" id="comunicados">
		<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'announcements_eyebrow', 'FIQUE POR DENTRO' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'announcements_title', 'Comunicados Granistone' ) ); ?></h2></div><a href="#noticias" class="text-link">Ver todos os comunicados <span aria-hidden="true">↗</span></a></div>
		<?php
		$notices_query = new WP_Query(
			array(
				'post_type'      => 'granistone_notice',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			)
		);
		?>
		<div class="announcement-grid">
			<?php if ( $notices_query->have_posts() ) : ?>
				<?php $notice_index = 0; ?>
				<?php while ( $notices_query->have_posts() ) : ?>
					<?php
					$notices_query->the_post();
					$notice_category = get_post_meta( get_the_ID(), '_granistone_notice_category', true );
					$notice_accent   = get_post_meta( get_the_ID(), '_granistone_notice_accent', true );
					$notice_accent   = in_array( $notice_accent, array( 'green', 'sand', 'gold' ), true ) ? $notice_accent : 'sand';
					$notice_link     = get_post_meta( get_the_ID(), '_granistone_notice_link', true );
					$notice_summary  = get_the_excerpt();
					$notice_summary  = $notice_summary ? $notice_summary : wp_trim_words( wp_strip_all_tags( get_the_content() ), 24, '…' );
					?>
					<article class="announcement-card <?php echo esc_attr( $notice_accent ); ?>">
						<div class="card-topline"><span><?php echo esc_html( $notice_category ? $notice_category : 'Comunicado' ); ?></span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( granistone_upper( get_the_date( 'd M Y' ) ) ); ?></time></div>
						<span class="card-number"><?php echo esc_html( str_pad( (string) ( $notice_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( $notice_summary ); ?></p>
						<a href="<?php echo esc_url( $notice_link ? $notice_link : '#noticias' ); ?>" <?php if ( $notice_link ) : ?>target="_blank" rel="noreferrer"<?php endif; ?>>Ler comunicado <span aria-hidden="true">↗</span></a>
					</article>
					<?php $notice_index++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<p class="content-empty"><?php esc_html_e( 'Nenhum comunicado publicado.', 'granistone-intranet' ); ?></p>
			<?php endif; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	</section>

	<?php $weekly_image = get_theme_mod( 'granistone_weekly_image', '' ); ?>
	<section class="weekly-word" id="cultura">
		<div class="word-stone <?php echo $weekly_image ? 'has-weekly-image' : ''; ?>" <?php if ( $weekly_image ) : ?>style="<?php echo esc_attr( 'background-image: url("' . esc_url_raw( $weekly_image ) . '");' ); ?>"<?php endif; ?>><span><?php echo esc_html( granistone_theme_text( 'weekly_label', 'PALAVRA DA SEMANA' ) ); ?></span><?php if ( ! $weekly_image ) : ?><div class="word-seal">G</div><?php endif; ?></div>
		<div class="word-copy"><span class="quote-mark">“</span><blockquote><?php echo esc_html( granistone_theme_text( 'weekly_quote', 'Cada pessoa tem um papel importante na construção dessa história.' ) ); ?></blockquote><p><?php echo esc_html( granistone_theme_text( 'weekly_verse', '“Tudo o que fizerem, façam de todo o coração, como para o Senhor.”' ) ); ?></p><strong><?php echo esc_html( granistone_theme_text( 'weekly_reference', 'COLOSSENSES 3:23' ) ); ?></strong></div>
	</section>

	<section class="section health-videos-section" id="saude">
		<?php
		$health_videos_query = new WP_Query(
			array(
				'post_type'      => 'granistone_video',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
			)
		);
		$health_categories = taxonomy_exists( 'granistone_video_category' )
			? get_terms(
				array(
					'taxonomy'   => 'granistone_video_category',
					'hide_empty' => true,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			)
			: array();
		$health_categories = is_wp_error( $health_categories ) ? array() : $health_categories;
		?>
		<div class="health-heading">
			<div><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'health_eyebrow', 'SAÚDE EM FOCO' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'health_title', 'Aprender também é uma forma de se cuidar.' ) ); ?></h2></div>
			<div class="health-intro"><p><?php echo esc_html( granistone_theme_text( 'health_intro', 'Conteúdos oficiais sobre prevenção, bem-estar e segurança para cuidar da saúde dentro e fora do trabalho.' ) ); ?></p><a href="<?php echo esc_url( granistone_theme_text( 'health_channel_url', 'https://www.youtube.com/@MinSaudeBR' ) ); ?>" target="_blank" rel="noreferrer" class="text-link"><?php echo esc_html( granistone_theme_text( 'health_channel_label', 'Canal do Ministério da Saúde' ) ); ?> <span aria-hidden="true">↗</span></a></div>
		</div>
		<div class="health-toolbar">
			<p><strong class="health-count"><?php echo esc_html( $health_videos_query->post_count ); ?></strong> vídeos indexados</p>
			<div class="health-filters" aria-label="Filtrar vídeos de saúde">
				<button class="active" type="button" data-health-filter="Todos">Todos</button>
				<?php foreach ( $health_categories as $health_category ) : ?>
					<button type="button" data-health-filter="<?php echo esc_attr( $health_category->slug ); ?>"><?php echo esc_html( $health_category->name ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="health-video-grid" aria-live="polite">
			<?php if ( $health_videos_query->have_posts() ) : ?>
				<?php $video_index = 0; ?>
				<?php while ( $health_videos_query->have_posts() ) : ?>
					<?php
					$health_videos_query->the_post();
					$video_post_id         = get_the_ID();
					$video_terms           = get_the_terms( $video_post_id, 'granistone_video_category' );
					$video_terms           = ( ! is_wp_error( $video_terms ) && ! empty( $video_terms ) ) ? $video_terms : array();
					$video_category        = ! empty( $video_terms ) ? $video_terms[0]->name : __( 'Outros', 'granistone-intranet' );
					$video_category_filter = ! empty( $video_terms ) ? implode( '|', wp_list_pluck( $video_terms, 'slug' ) ) : 'outros';
					$video_url             = function_exists( 'granistone_video_get_url' ) ? granistone_video_get_url( $video_post_id ) : get_post_meta( $video_post_id, '_granistone_video_url', true );
					$thumbnail_url         = function_exists( 'granistone_video_get_thumbnail_url' ) ? granistone_video_get_thumbnail_url( $video_post_id ) : get_the_post_thumbnail_url( $video_post_id, 'large' );
					$institution           = get_post_meta( $video_post_id, '_granistone_video_institution', true );
					$badge                 = get_post_meta( $video_post_id, '_granistone_video_badge', true );
					$duration              = get_post_meta( $video_post_id, '_granistone_video_duration', true );
					$video_label           = $duration ? $duration : ( $badge ? $badge : __( 'VÍDEO', 'granistone-intranet' ) );
					$description           = get_the_excerpt();
					$description           = $description ? $description : wp_trim_words( wp_strip_all_tags( get_the_content() ), 26, '…' );
					$video_number          = str_pad( (string) ( $video_index + 1 ), 2, '0', STR_PAD_LEFT );
					$video_href            = $video_url ? $video_url : '#saude';
					?>
					<article class="health-video-card" data-health-category="<?php echo esc_attr( $video_category_filter ); ?>">
						<a class="health-thumbnail" href="<?php echo esc_url( $video_href ); ?>" <?php if ( $video_url ) : ?>target="_blank" rel="noreferrer"<?php else : ?>aria-disabled="true"<?php endif; ?> aria-label="<?php echo esc_attr( sprintf( __( 'Assistir %s', 'granistone-intranet' ), get_the_title() ) ); ?>">
							<span class="health-thumb-fallback" aria-hidden="true"><b><?php echo esc_html( $video_category ); ?></b><em><?php esc_html_e( 'Conteúdo oficial', 'granistone-intranet' ); ?></em></span>
							<?php if ( $thumbnail_url ) : ?><img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="" loading="lazy"><?php endif; ?>
							<span class="health-play" aria-hidden="true">▶</span><span class="health-index"><?php echo esc_html( $video_number ); ?></span><small><?php echo esc_html( $video_label ); ?></small>
						</a>
						<div class="health-video-copy">
							<div><span><?php echo esc_html( $video_category ); ?></span><small><?php echo esc_html( $institution ); ?></small></div>
							<h3><?php the_title(); ?></h3><p><?php echo esc_html( $description ); ?></p>
							<a href="<?php echo esc_url( $video_href ); ?>" <?php if ( $video_url ) : ?>target="_blank" rel="noreferrer"<?php else : ?>aria-disabled="true"<?php endif; ?>>Assistir vídeo <span aria-hidden="true">↗</span></a>
						</div>
					</article>
					<?php $video_index++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<div class="health-empty">
					<strong><?php esc_html_e( 'Nenhum vídeo publicado.', 'granistone-intranet' ); ?></strong>
					<p><?php esc_html_e( 'Ative o plugin Granistone Vídeos e publique conteúdos para preencher esta seção automaticamente.', 'granistone-intranet' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php wp_reset_postdata(); ?>
		<p class="health-disclaimer"><?php echo esc_html( granistone_theme_text( 'health_disclaimer', 'Conteúdo educativo. Em caso de sintomas ou dúvidas individuais, procure orientação de um profissional de saúde.' ) ); ?></p>
	</section>

	<section class="section news-section" id="noticias">
		<div class="section-heading news-section-heading">
			<div><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'news_eyebrow', 'ACONTECE NA GRANISTONE' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'news_title', 'Últimas notícias' ) ); ?></h2></div>
			<div class="news-carousel-controls" aria-label="<?php esc_attr_e( 'Navegar pelas notícias', 'granistone-intranet' ); ?>">
				<button type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Ver notícias anteriores', 'granistone-intranet' ); ?>">←</button>
				<button type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Ver próximas notícias', 'granistone-intranet' ); ?>">→</button>
			</div>
		</div>
		<?php
		$latest_posts = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 9,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		?>
		<div class="news-carousel-shell" data-post-carousel>
			<div class="news-carousel" id="granistone-news-carousel" tabindex="0" aria-label="<?php esc_attr_e( 'Postagens recentes', 'granistone-intranet' ); ?>">
				<?php if ( $latest_posts->have_posts() ) : ?>
					<?php $post_index = 0; ?>
					<?php while ( $latest_posts->have_posts() ) : ?>
						<?php
						$latest_posts->the_post();
						$categories    = get_the_category();
						$category_name = ! empty( $categories ) ? $categories[0]->name : __( 'Notícias', 'granistone-intranet' );
						$post_number   = str_pad( (string) ( $post_index + 1 ), 2, '0', STR_PAD_LEFT );
						?>
						<article class="news-card">
							<a class="news-image news-post-image <?php echo has_post_thumbnail() ? 'has-thumbnail' : 'news-placeholder'; ?>" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ler: %s', 'granistone-intranet' ), get_the_title() ) ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
								<?php endif; ?>
								<span><?php echo esc_html( $post_number ); ?></span>
								<div class="news-image-brand">GRANISTONE</div>
							</a>
							<div class="news-meta">
								<span><?php echo esc_html( $category_name ); ?></span>
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></time>
							</div>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
							<a href="<?php the_permalink(); ?>">Continuar lendo <span aria-hidden="true">↗</span></a>
						</article>
						<?php $post_index++; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<p class="news-empty"><?php esc_html_e( 'Nenhuma postagem publicada ainda.', 'granistone-intranet' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php wp_reset_postdata(); ?>
	</section>

	<section class="agenda-section" id="agenda">
		<?php
		$events_query = new WP_Query(
			array(
				'post_type'      => 'granistone_event',
				'post_status'    => 'publish',
				'posts_per_page' => 8,
				'meta_key'       => '_granistone_event_date',
				'orderby'        => 'meta_value',
				'order'          => 'ASC',
				'meta_query'     => array(
					array(
						'key'     => '_granistone_event_date',
						'value'   => current_time( 'Y-m-d' ),
						'compare' => '>=',
						'type'    => 'DATE',
					),
				),
			)
		);
		$event_categories = taxonomy_exists( 'granistone_event_category' ) ? get_terms( array( 'taxonomy' => 'granistone_event_category', 'hide_empty' => true, 'orderby' => 'name' ) ) : array();
		$event_categories = is_wp_error( $event_categories ) ? array() : $event_categories;
		?>
		<div class="agenda-intro">
			<p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'agenda_eyebrow', 'PROGRAME-SE' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'agenda_title', 'Agenda Granistone' ) ); ?></h2><p><?php echo esc_html( granistone_theme_text( 'agenda_intro', 'Encontros, treinamentos e datas que fortalecem nossa conexão.' ) ); ?></p>
			<div class="agenda-filters"><button class="active" type="button" data-event-filter="Todos">Todos</button><?php foreach ( $event_categories as $event_category ) : ?><button type="button" data-event-filter="<?php echo esc_attr( $event_category->slug ); ?>"><?php echo esc_html( $event_category->name ); ?></button><?php endforeach; ?></div>
			<a class="button button-dark" href="#agenda">Ver calendário completo <span aria-hidden="true">↗</span></a>
		</div>
		<div class="event-list" aria-live="polite">
			<?php if ( $events_query->have_posts() ) : ?>
				<?php while ( $events_query->have_posts() ) : ?>
					<?php
					$events_query->the_post();
					$event_date       = get_post_meta( get_the_ID(), '_granistone_event_date', true );
					$event_time       = get_post_meta( get_the_ID(), '_granistone_event_time', true );
					$event_location   = get_post_meta( get_the_ID(), '_granistone_event_location', true );
					$event_link       = get_post_meta( get_the_ID(), '_granistone_event_link', true );
					$event_terms      = get_the_terms( get_the_ID(), 'granistone_event_category' );
					$event_terms      = ( ! is_wp_error( $event_terms ) && ! empty( $event_terms ) ) ? $event_terms : array();
					$event_label      = ! empty( $event_terms ) ? $event_terms[0]->name : 'Evento';
					$event_filters    = ! empty( $event_terms ) ? implode( '|', wp_list_pluck( $event_terms, 'slug' ) ) : 'outros';
					$event_timestamp  = $event_date ? strtotime( $event_date ) : current_time( 'timestamp' );
					$event_time_label = $event_time ? str_replace( ':', 'h', $event_time ) : '';
					$event_detail     = trim( $event_time_label . ( $event_time_label && $event_location ? ' · ' : '' ) . $event_location );
					?>
					<article class="event-row" data-event-category="<?php echo esc_attr( $event_filters ); ?>">
						<div class="event-date"><strong><?php echo esc_html( wp_date( 'd', $event_timestamp ) ); ?></strong><span><?php echo esc_html( granistone_upper( wp_date( 'M', $event_timestamp ) ) ); ?></span></div>
						<div class="event-detail"><span><?php echo esc_html( $event_label ); ?></span><h3><?php the_title(); ?></h3><p><?php echo esc_html( $event_detail ); ?></p></div>
						<?php if ( $event_link ) : ?><a class="event-action" href="<?php echo esc_url( $event_link ); ?>" target="_blank" rel="noreferrer" aria-label="<?php echo esc_attr( sprintf( __( 'Ver detalhes de %s', 'granistone-intranet' ), get_the_title() ) ); ?>">↗</a><?php else : ?><span class="event-action" aria-hidden="true">↗</span><?php endif; ?>
					</article>
				<?php endwhile; ?>
			<?php else : ?>
				<p class="content-empty"><?php esc_html_e( 'Nenhum evento futuro publicado.', 'granistone-intranet' ); ?></p>
			<?php endif; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	</section>

	<section class="section people-section" id="pessoas">
		<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'people_eyebrow', 'NOSSA GENTE' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'people_title', 'Celebramos quem faz parte' ) ); ?></h2></div><p class="heading-note"><?php echo esc_html( granistone_theme_text( 'people_note', 'Parabéns! Que o novo ciclo seja cheio de conquistas.' ) ); ?></p></div>
		<?php
		$birthday_posts = get_posts(
			array(
				'post_type'      => 'granistone_birthday',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			)
		);
		$featured_person = null;
		$birthday_months = array();
		foreach ( $birthday_posts as $birthday_post ) {
			if ( get_post_meta( $birthday_post->ID, '_granistone_birthday_featured', true ) && ! $featured_person ) {
				$featured_person = $birthday_post;
				continue;
			}
			$person_month = absint( get_post_meta( $birthday_post->ID, '_granistone_birthday_month', true ) );
			if ( $person_month >= 1 && $person_month <= 12 ) {
				$birthday_months[ $person_month ][] = $birthday_post;
			}
		}
		$display_month = (int) wp_date( 'n' );
		for ( $month_offset = 0; $month_offset < 12; $month_offset++ ) {
			$candidate_month = ( ( $display_month - 1 + $month_offset ) % 12 ) + 1;
			if ( ! empty( $birthday_months[ $candidate_month ] ) ) {
				$display_month = $candidate_month;
				break;
			}
		}
		$display_birthdays = isset( $birthday_months[ $display_month ] ) ? $birthday_months[ $display_month ] : array();
		usort(
			$display_birthdays,
			function ( $first, $second ) {
				return absint( get_post_meta( $first->ID, '_granistone_birthday_day', true ) ) <=> absint( get_post_meta( $second->ID, '_granistone_birthday_day', true ) );
			}
		);
		?>
		<div class="birthday-layout">
			<?php if ( $featured_person ) : ?>
				<?php
				$featured_initials = get_post_meta( $featured_person->ID, '_granistone_birthday_initials', true );
				$featured_role     = get_post_meta( $featured_person->ID, '_granistone_birthday_role', true );
				$featured_quote    = get_post_meta( $featured_person->ID, '_granistone_birthday_quote', true );
				$featured_link     = get_post_meta( $featured_person->ID, '_granistone_birthday_link', true );
				$featured_image    = get_the_post_thumbnail_url( $featured_person->ID, 'large' );
				$featured_first    = strtok( $featured_person->post_title, ' ' );
				?>
				<article class="featured-person"><div class="portrait portrait-featured" <?php if ( $featured_image ) : ?>style="background-image:url('<?php echo esc_url( $featured_image ); ?>')"<?php endif; ?>><span><?php echo esc_html( $featured_initials ); ?></span></div><div><p>CONHEÇA SEU COLEGA</p><h3><?php echo esc_html( $featured_person->post_title ); ?></h3><span><?php echo esc_html( $featured_role ); ?></span><blockquote>“<?php echo esc_html( $featured_quote ); ?>”</blockquote><a href="<?php echo esc_url( $featured_link ? $featured_link : '#pessoas' ); ?>" <?php if ( $featured_link ) : ?>target="_blank" rel="noreferrer"<?php endif; ?>>Conheça a história de <?php echo esc_html( $featured_first ); ?> <span aria-hidden="true">↗</span></a></div></article>
			<?php else : ?>
				<div class="featured-person content-empty"><?php esc_html_e( 'Defina um aniversariante como destaque no painel.', 'granistone-intranet' ); ?></div>
			<?php endif; ?>
			<div class="birthday-list"><p class="list-label"><?php echo esc_html( sprintf( 'ANIVERSARIANTES DE %s', granistone_upper( wp_date( 'F', mktime( 12, 0, 0, $display_month, 1 ) ) ) ) ); ?></p>
				<?php foreach ( $display_birthdays as $person ) : ?>
					<?php
					$person_initials = get_post_meta( $person->ID, '_granistone_birthday_initials', true );
					$person_area     = get_post_meta( $person->ID, '_granistone_birthday_area', true );
					$person_day      = absint( get_post_meta( $person->ID, '_granistone_birthday_day', true ) );
					$person_image    = get_the_post_thumbnail_url( $person->ID, 'thumbnail' );
					?>
					<article><div class="portrait" <?php if ( $person_image ) : ?>style="background-image:url('<?php echo esc_url( $person_image ); ?>')"<?php endif; ?>><span><?php echo esc_html( $person_initials ); ?></span></div><div><h3><?php echo esc_html( $person->post_title ); ?></h3><p><?php echo esc_html( $person_area ); ?></p></div><time><?php echo esc_html( str_pad( (string) $person_day, 2, '0', STR_PAD_LEFT ) . ' ' . granistone_upper( wp_date( 'M', mktime( 12, 0, 0, $display_month, 1 ) ) ) ); ?></time></article>
				<?php endforeach; ?>
				<?php if ( empty( $display_birthdays ) ) : ?><p class="content-empty"><?php esc_html_e( 'Nenhum aniversariante publicado.', 'granistone-intranet' ); ?></p><?php endif; ?>
			</div>
		</div>
	</section>

	<section class="history-section" id="historia">
		<div class="history-copy"><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'history_eyebrow', 'NOSSA HISTÓRIA' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'history_title', 'Da origem à excelência,' ) ); ?> <span><?php echo esc_html( granistone_theme_text( 'history_highlight', 'uma história escrita em pedra.' ) ); ?></span></h2><p><?php echo esc_html( granistone_theme_text( 'history_body', 'Desde 1989, a Granistone une conhecimento, tecnologia e respeito à natureza para levar a beleza das rochas brasileiras a diferentes mercados do mundo.' ) ); ?></p><a href="#historia" class="button button-outline-light"><?php echo esc_html( granistone_theme_text( 'history_button', 'Conheça a Granistone' ) ); ?> <span aria-hidden="true">↗</span></a></div>
		<ol class="timeline"><li><strong>1989</strong><div><h3>Fundação</h3><p>O início de uma história movida por visão e coragem.</p></div></li><li><strong>2001</strong><div><h3>Expansão das operações</h3><p>Novas frentes e capacidade produtiva ampliada.</p></div></li><li><strong>2012</strong><div><h3>Reconhecimento internacional</h3><p>A pedra brasileira conquistando novos destinos.</p></div></li><li><strong>HOJE</strong><div><h3>Granistone hoje</h3><p>Excelência, pessoas e propósito em evolução constante.</p></div></li></ol>
	</section>

	<section class="section values-section">
		<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'values_eyebrow', 'NOSSO JEITO DE SER' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'values_title', 'Valores que nos movem' ) ); ?></h2></div><p class="heading-note"><?php echo esc_html( granistone_theme_text( 'values_note', 'Princípios que orientam escolhas, relações e resultados.' ) ); ?></p></div>
		<div class="values-grid"><?php foreach ( $values as $value ) : ?><article><span><?php echo esc_html( $value[0] ); ?></span><div class="value-icon" aria-hidden="true">◇</div><h3><?php echo esc_html( $value[1] ); ?></h3><p><?php echo esc_html( $value[2] ); ?></p></article><?php endforeach; ?></div>
	</section>

	<section class="recognition-section">
		<div class="recognition-title"><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'recognition_eyebrow', 'GRANISTONE RECONHECE' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'recognition_title', 'Atitudes que inspiram' ) ); ?></h2><p><?php echo esc_html( granistone_theme_text( 'recognition_intro', 'Reconhecer é valorizar quem faz a diferença todos os dias.' ) ); ?></p></div>
		<div class="recognition-cards"><article><span><?php echo esc_html( granistone_theme_text( 'recognition_one_label', 'DESTAQUE DO MÊS' ) ); ?></span><h3><?php echo esc_html( granistone_theme_text( 'recognition_one_title', 'Marcos Almeida' ) ); ?></h3><p><?php echo esc_html( granistone_theme_text( 'recognition_one_text', 'Pela liderança, atenção à segurança e cuidado com toda a equipe.' ) ); ?></p><strong><?php echo esc_html( granistone_theme_text( 'recognition_one_detail', 'Produção · Unidade ES' ) ); ?></strong></article><article><span><?php echo esc_html( granistone_theme_text( 'recognition_two_label', 'ATITUDE QUE INSPIRA' ) ); ?></span><h3><?php echo esc_html( granistone_theme_text( 'recognition_two_title', 'Equipe de Logística' ) ); ?></h3><p><?php echo esc_html( granistone_theme_text( 'recognition_two_text', 'Colaboração que transformou um desafio em uma entrega exemplar.' ) ); ?></p><strong><?php echo esc_html( granistone_theme_text( 'recognition_two_detail', 'Juntos, vamos mais longe.' ) ); ?></strong></article></div>
	</section>

	<section class="journey-section">
		<div class="journey-heading"><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'journey_eyebrow', 'DO BLOCO À HISTÓRIA' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'journey_title', 'Entenda o nosso negócio' ) ); ?></h2></div>
		<div class="journey-steps"><article><span>01</span><i></i><h3>Origem</h3><p>A natureza revela materiais únicos.</p></article><article><span>02</span><i></i><h3>Extração</h3><p>Cuidado e precisão desde a pedreira.</p></article><article><span>03</span><i></i><h3>Beneficiamento</h3><p>Tecnologia valoriza cada detalhe.</p></article><article><span>04</span><i></i><h3>Produto final</h3><p>Beleza brasileira para o mundo.</p></article></div>
	</section>

	<section class="section resources-section">
		<div class="section-heading"><div><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'resources_eyebrow', 'CONTEÚDO E SERVIÇOS' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'resources_title', 'Explore o portal' ) ); ?></h2></div></div>
		<div class="resource-grid">
			<article id="videos" class="resource-card resource-video"><span>▷</span><div><p>VÍDEOS GRANISTONE</p><h3>Por dentro da nossa essência</h3><a href="#videos">Assistir agora ↗</a></div></article>
			<article id="biblioteca" class="resource-card resource-library"><span>▤</span><div><p>BIBLIOTECA INTERNA</p><h3>Documentos para o seu dia</h3><a href="#biblioteca">Acessar arquivos ↗</a></div></article>
			<article class="resource-card resource-spiritual"><span>✦</span><div><p>MOMENTO DE REFLEXÃO</p><h3>Uma pausa para renovar a fé</h3><a href="#cultura">Ler mensagem ↗</a></div></article>
		</div>
	</section>

	<section class="idea-section" id="ideias">
		<div><p class="eyebrow"><?php echo esc_html( granistone_theme_text( 'ideas_eyebrow', 'SUA VOZ IMPORTA' ) ); ?></p><h2><?php echo esc_html( granistone_theme_text( 'ideas_title', 'Tem uma ideia para melhorar nosso dia a dia?' ) ); ?></h2><p><?php echo esc_html( granistone_theme_text( 'ideas_body', 'Compartilhe sua sugestão. Boas transformações começam com uma boa conversa.' ) ); ?></p></div>
		<form class="idea-form"><label><span>Nome</span><input name="nome" placeholder="Como podemos chamar você?" required></label><label><span>Setor</span><input name="setor" placeholder="Sua área" required></label><label class="form-full"><span>Sugestão</span><textarea name="sugestao" placeholder="Conte sua ideia..." rows="3" required></textarea></label><button class="button button-primary" type="submit">Enviar sugestão ↗</button><p class="form-notice" aria-live="polite"></p></form>
	</section>
</main>
<?php get_footer(); ?>
