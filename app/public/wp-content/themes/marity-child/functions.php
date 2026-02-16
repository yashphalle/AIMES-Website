<?php

/**
 * Flush rewrite rules when child theme is activated so /talks/ and /demos-exploratory-applications/ work on new installs/staging.
 */
function marity_child_flush_rewrite_rules_on_activation() {
	if ( get_stylesheet() !== get_template() ) {
		flush_rewrite_rules();
	}
}
add_action( 'after_switch_theme', 'marity_child_flush_rewrite_rules_on_activation' );

/**
 * Enqueue child theme styles - ensures child CSS loads AFTER parent CSS
 */
function marity_child_enqueue_styles() {
	wp_enqueue_style( 'marity-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'marity-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'marity-parent-style' ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'marity_child_enqueue_styles', 20 );

/**
 * Homepage Hero – Particles animation + gradient button hover fix
 * Loads particles.js from marity-core and initializes on hero section.
 * Also overrides Marity JS hover on the CTA button with gradient.
 */
function marity_child_hero_enhancements() {
	if ( ! is_front_page() ) {
		return;
	}

	// Enqueue particles.js from marity-core plugin
	$particles_url = plugins_url( 'marity-core/assets/plugins/particles/particles.js' );
	wp_enqueue_script( 'particles-js', $particles_url, array(), null, true );
	?>
	<script>
	document.addEventListener('DOMContentLoaded', function(){
		/* --- Force-remove blob mask from container + img (Elementor applies it) --- */
		var maskContainer = document.querySelector('.elementor-element-cb6731e .elementor-widget-container');
		if (maskContainer) {
			maskContainer.style.setProperty('-webkit-mask-image', 'none', 'important');
			maskContainer.style.setProperty('mask-image', 'none', 'important');
			maskContainer.style.setProperty('-webkit-mask-size', 'unset', 'important');
			maskContainer.style.setProperty('mask-size', 'unset', 'important');
			maskContainer.style.setProperty('overflow', 'visible', 'important');
			var maskImg = maskContainer.querySelector('img');
			if (maskImg) {
				maskImg.style.setProperty('-webkit-mask-image', 'none', 'important');
				maskImg.style.setProperty('mask-image', 'none', 'important');
			}
		}

		/* --- Particles on hero section --- */
		var heroSection = document.querySelector('.elementor-element-e0101e2');
		if (heroSection) {
			var pc = document.createElement('div');
			pc.id = 'aimes-hero-particles';
			heroSection.insertBefore(pc, heroSection.firstChild);

			if (typeof particlesJS !== 'undefined') {
				particlesJS('aimes-hero-particles', {
					particles: {
						number: { value: 50, density: { enable: true, value_area: 900 } },
						color: { value: '#1e3a5f' },
						shape: { type: 'circle' },
						opacity: { value: 0.25, random: true, anim: { enable: true, speed: 0.6, opacity_min: 0.08, sync: false } },
						size: { value: 3, random: true },
						line_linked: { enable: true, distance: 130, color: '#1e3a5f', opacity: 0.1, width: 1 },
						move: { enable: true, speed: 1.2, direction: 'none', random: true, straight: false, out_mode: 'out' }
					},
					interactivity: {
						detect_on: 'canvas',
						events: { onhover: { enable: false }, onclick: { enable: false }, resize: true }
					},
					retina_detect: true
				});
			}
		}

		/* --- Button gradient hover (override Marity JS data-hover) --- */
		var heroBtn = document.querySelector('.elementor-element-c19954e a[data-hover-background-color]');
		if (heroBtn) {
			heroBtn.removeAttribute('data-hover-color');
			heroBtn.removeAttribute('data-hover-background-color');
			heroBtn.removeAttribute('data-hover-border-color');
			heroBtn.addEventListener('mouseenter', function(){
				this.style.background = 'linear-gradient(135deg, #1e3a5f 0%, #2d5a8e 100%)';
				this.style.color = '#fff';
				this.style.transform = 'translateY(-2px)';
				this.style.boxShadow = '0 6px 24px rgba(30,58,95,0.35)';
			});
			heroBtn.addEventListener('mouseleave', function(){
				this.style.background = 'linear-gradient(135deg, #0e202a 0%, #1e3a5f 100%)';
				this.style.color = '#fff';
				this.style.transform = '';
				this.style.boxShadow = '0 4px 16px rgba(14,32,42,0.25)';
			});
		}
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'marity_child_hero_enhancements', 8 );

/**
 * Announcements CPT – For news band (talks, seminars)
 * Admin: wp-admin > Announcements. Fields: title, link (meta), order by date.
 */
if ( ! function_exists( 'marity_child_register_announcements_cpt' ) ) {
	function marity_child_register_announcements_cpt() {
		register_post_type( 'aimes_announcement', array(
			'labels'            => array(
				'name'               => __( 'Announcements', 'marity' ),
				'singular_name'      => __( 'Announcement', 'marity' ),
				'add_new'            => __( 'Add New', 'marity' ),
				'add_new_item'       => __( 'Add New Announcement', 'marity' ),
				'edit_item'          => __( 'Edit Announcement', 'marity' ),
				'new_item'           => __( 'New Announcement', 'marity' ),
				'view_item'          => __( 'View Announcement', 'marity' ),
				'search_items'       => __( 'Search Announcements', 'marity' ),
				'not_found'          => __( 'No announcements found.', 'marity' ),
				'not_found_in_trash' => __( 'No announcements found in Trash.', 'marity' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'menu_icon'         => 'dashicons-megaphone',
			'capability_type'   => 'post',
			'supports'          => array( 'title' ),
			'has_archive'       => false,
			'rewrite'           => false,
		) );
	}
	add_action( 'init', 'marity_child_register_announcements_cpt' );
}

/**
 * Talks CPT – Separate admin section for managing talks/seminars
 * Admin: wp-admin > Talks
 */
if ( ! function_exists( 'marity_child_register_talks_cpt' ) ) {
	function marity_child_register_talks_cpt() {
		register_post_type( 'aimes_talk', array(
			'labels' => array(
				'name'               => __( 'Talks', 'marity' ),
				'singular_name'      => __( 'Talk', 'marity' ),
				'add_new'            => __( 'Add New Talk', 'marity' ),
				'add_new_item'       => __( 'Add New Talk', 'marity' ),
				'edit_item'          => __( 'Edit Talk', 'marity' ),
				'new_item'           => __( 'New Talk', 'marity' ),
				'view_item'          => __( 'View Talk', 'marity' ),
				'search_items'       => __( 'Search Talks', 'marity' ),
				'not_found'          => __( 'No talks found.', 'marity' ),
				'not_found_in_trash' => __( 'No talks found in Trash.', 'marity' ),
				'menu_name'          => __( 'Talks', 'marity' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'menu_icon'         => 'dashicons-microphone',
			'menu_position'     => 25,
			'capability_type'   => 'post',
			'supports'          => array( 'title', 'editor', 'thumbnail' ),
			'has_archive'       => false,
			'rewrite'           => false,
		) );

		// Talk categories (e.g., Seminar, Workshop, Guest Lecture)
		register_taxonomy( 'talk_category', 'aimes_talk', array(
			'labels' => array(
				'name'          => 'Talk Categories',
				'singular_name' => 'Talk Category',
				'add_new_item'  => 'Add New Category',
			),
			'hierarchical' => true,
			'show_ui'      => true,
			'show_in_menu' => true,
			'rewrite'      => false,
		) );
	}
	add_action( 'init', 'marity_child_register_talks_cpt' );
}

/**
 * Talks CPT – Meta box for talk details
 */
if ( ! function_exists( 'marity_child_talk_cpt_meta_box' ) ) {
	function marity_child_talk_cpt_meta_box() {
		add_meta_box(
			'aimes_talk_cpt_details',
			__( 'Talk Details', 'marity' ),
			'marity_child_talk_cpt_details_cb',
			'aimes_talk',
			'normal',
			'high'
		);
	}
	add_action( 'add_meta_boxes', 'marity_child_talk_cpt_meta_box' );

	function marity_child_talk_cpt_details_cb( $post ) {
		wp_nonce_field( 'aimes_talk_cpt_details', 'aimes_talk_cpt_nonce' );
		$fields = array(
			'_aimes_talk_date'        => array( 'label' => 'Event Date & Time',       'type' => 'datetime-local' ),
			'_aimes_talk_end_date'    => array( 'label' => 'End Date & Time (optional)', 'type' => 'datetime-local' ),
			'_aimes_talk_speaker'     => array( 'label' => 'Speaker Name',             'type' => 'text' ),
			'_aimes_talk_speaker_bio' => array( 'label' => 'Speaker Short Bio',        'type' => 'text' ),
			'_aimes_talk_location'    => array( 'label' => 'Location / Room',          'type' => 'text' ),
			'_aimes_talk_virtual_url' => array( 'label' => 'Virtual/Zoom Link',        'type' => 'url' ),
			'_aimes_talk_register'    => array( 'label' => 'Registration URL',         'type' => 'url' ),
			'_aimes_talk_thumb'       => array( 'label' => 'Speaker Photo URL (or use Featured Image)', 'type' => 'url' ),
		);
		echo '<table class="form-table">';
		foreach ( $fields as $key => $f ) {
			$val = get_post_meta( $post->ID, $key, true );
			echo '<tr><th><label for="' . esc_attr( $key ) . '">' . esc_html( $f['label'] ) . '</label></th>';
			echo '<td><input type="' . esc_attr( $f['type'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="widefat"></td></tr>';
		}
		echo '</table>';
		echo '<p class="description">Use the main editor for a full talk description/abstract. Featured Image = talk banner.</p>';
	}

	function marity_child_talk_cpt_save( $post_id ) {
		if ( ! isset( $_POST['aimes_talk_cpt_nonce'] ) || ! wp_verify_nonce( $_POST['aimes_talk_cpt_nonce'], 'aimes_talk_cpt_details' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;

		$text_keys = array( '_aimes_talk_date', '_aimes_talk_end_date', '_aimes_talk_speaker', '_aimes_talk_speaker_bio', '_aimes_talk_location' );
		foreach ( $text_keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
			}
		}
		$url_keys = array( '_aimes_talk_virtual_url', '_aimes_talk_register', '_aimes_talk_thumb' );
		foreach ( $url_keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, esc_url_raw( $_POST[ $key ] ) );
			}
		}
	}
	add_action( 'save_post_aimes_talk', 'marity_child_talk_cpt_save' );
}

/**
 * Announcement Link meta box (link URL only — talks have their own CPT now)
 */
if ( ! function_exists( 'marity_child_announcement_meta_box' ) ) {
	function marity_child_announcement_meta_box() {
		add_meta_box(
			'aimes_announcement_link',
			__( 'Link URL', 'marity' ),
			'marity_child_announcement_link_cb',
			'aimes_announcement',
			'normal'
		);
	}
	add_action( 'add_meta_boxes', 'marity_child_announcement_meta_box' );

	function marity_child_announcement_link_cb( $post ) {
		wp_nonce_field( 'aimes_announcement_link', 'aimes_announcement_link_nonce' );
		$link = get_post_meta( $post->ID, '_aimes_announcement_link', true );
		echo '<p><input type="url" name="aimes_announcement_link" value="' . esc_attr( $link ) . '" class="widefat" placeholder="https://..." /></p>';
	}

	function marity_child_announcement_save( $post_id ) {
		if ( ! isset( $_POST['aimes_announcement_link_nonce'] ) || ! wp_verify_nonce( $_POST['aimes_announcement_link_nonce'], 'aimes_announcement_link' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( isset( $_POST['aimes_announcement_link'] ) ) {
			update_post_meta( $post_id, '_aimes_announcement_link', esc_url_raw( $_POST['aimes_announcement_link'] ) );
		}
	}
	add_action( 'save_post_aimes_announcement', 'marity_child_announcement_save' );
}

/**
 * Talks admin columns – show date, speaker, location in list table
 */
if ( ! function_exists( 'marity_child_talk_admin_columns' ) ) {
	function marity_child_talk_admin_columns( $columns ) {
		$new = array();
		foreach ( $columns as $k => $v ) {
			$new[ $k ] = $v;
			if ( $k === 'title' ) {
				$new['talk_date']    = 'Event Date';
				$new['talk_speaker'] = 'Speaker';
				$new['talk_status']  = 'Status';
			}
		}
		return $new;
	}
	add_filter( 'manage_aimes_talk_posts_columns', 'marity_child_talk_admin_columns' );

	function marity_child_talk_admin_column_data( $column, $post_id ) {
		if ( $column === 'talk_date' ) {
			$d = get_post_meta( $post_id, '_aimes_talk_date', true );
			echo $d ? date_i18n( 'M j, Y g:i A', strtotime( $d ) ) : '—';
		} elseif ( $column === 'talk_speaker' ) {
			echo esc_html( get_post_meta( $post_id, '_aimes_talk_speaker', true ) ?: '—' );
		} elseif ( $column === 'talk_status' ) {
			$d = get_post_meta( $post_id, '_aimes_talk_date', true );
			if ( $d && $d >= current_time( 'Y-m-d\TH:i' ) ) {
				echo '<span style="color:#0a7;font-weight:600;">Upcoming</span>';
			} else {
				echo '<span style="color:#999;">Past</span>';
			}
		}
	}
	add_action( 'manage_aimes_talk_posts_custom_column', 'marity_child_talk_admin_column_data', 10, 2 );

	function marity_child_talk_sortable_columns( $columns ) {
		$columns['talk_date'] = '_aimes_talk_date';
		return $columns;
	}
	add_filter( 'manage_edit-aimes_talk_sortable_columns', 'marity_child_talk_sortable_columns' );
}

/**
 * News Band – Homepage announcement bar (Option 1+3, Color 3)
 * Injected after hero section via the_content filter.
 */
if ( ! function_exists( 'marity_child_news_band_html' ) ) {
	function marity_child_news_band_html() {
		$posts = get_posts( array(
			'post_type'      => 'aimes_announcement',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		if ( empty( $posts ) ) {
			return '';
		}
		$items = array();
		foreach ( $posts as $p ) {
			$link = get_post_meta( $p->ID, '_aimes_announcement_link', true );
			$text = get_the_title( $p->ID );
			$items[] = array(
				'text' => $text,
				'url'  => $link ?: '#',
			);
		}
		$spans = array();
		foreach ( array_merge( $items, $items ) as $i ) {
			$url = ! empty( $i['url'] ) && $i['url'] !== '#' ? $i['url'] : '';
			if ( $url ) {
				$spans[] = '<a href="' . esc_url( $url ) . '" class="aimes-news-band-ticker-item" target="_blank" rel="noopener">' . esc_html( $i['text'] ) . '</a><span class="aimes-news-band-sep"> · </span>';
			} else {
				$spans[] = '<span>' . esc_html( $i['text'] ) . '</span><span class="aimes-news-band-sep"> · </span>';
			}
		}
		$ticker_html = implode( '', $spans );
		ob_start();
		?>
		<div class="aimes-news-band">
			<div class="aimes-news-band-inner">
				<span class="aimes-news-band-badge">NEW</span>
				<div class="aimes-news-band-icon">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
					</svg>
				</div>
				<div class="aimes-news-band-ticker-wrap">
					<div class="aimes-news-band-ticker">
						<?php echo $ticker_html; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'marity_child_inject_news_band_after_hero' ) ) {
	function marity_child_inject_news_band_after_hero() {
		if ( ! is_front_page() ) {
			return;
		}
		$band = marity_child_news_band_html();
		if ( empty( $band ) ) {
			return;
		}
		echo '<template id="aimes-news-band-tpl">' . $band . '</template>';
		?>
		<script>
		(function(){
			var tpl = document.getElementById("aimes-news-band-tpl");
			if (!tpl) return;
			var frag = tpl.content ? tpl.content.cloneNode(true) : document.createRange().createContextualFragment(tpl.innerHTML);
			var first = document.querySelector(".elementor-top-section");
			if (first && first.parentNode) {
				first.parentNode.insertBefore(frag, first.nextSibling);
			}
			tpl.remove();
		})();
		</script>
		<?php
	}
	add_action( 'wp_footer', 'marity_child_inject_news_band_after_hero', 5 );
}

if ( ! function_exists( 'marity_child_theme_enqueue_scripts' ) ) {
	/**
	 * Function that enqueue theme's child style
	 */
	function marity_child_theme_enqueue_scripts() {
		$main_style = 'marity-main';

		wp_enqueue_style( 'marity-child-style', get_stylesheet_directory_uri() . '/style.css', array( $main_style ) );
	}

	add_action( 'wp_enqueue_scripts', 'marity_child_theme_enqueue_scripts' );
}

/**
 * Enhanced portfolio single page rendering via output buffering.
 *
 * Why output buffering? The plugin's description template uses esc_html()
 * which strips all HTML tags. We capture the rendered article HTML, then:
 *   1. Replace the escaped description with an HTML-safe version (wp_kses_post + wpautop)
 *      so that <strong>, <em>, and paragraph breaks all work.
 *   2. Inject the featured image AFTER the description text (inside the left column)
 *      instead of above the article, for a cleaner, more compact layout.
 *
 * No plugin files are modified. Only child theme files are touched.
 */
if ( ! function_exists( 'marity_child_portfolio_buffer_start' ) ) {
	function marity_child_portfolio_buffer_start() {
		if ( is_singular( 'portfolio-item' ) ) {
			ob_start();
		}
	}

	add_action( 'marity_core_action_before_portfolio_single_item', 'marity_child_portfolio_buffer_start', 1 );
}

if ( ! function_exists( 'marity_child_portfolio_buffer_end' ) ) {
	function marity_child_portfolio_buffer_end() {
		if ( ! is_singular( 'portfolio-item' ) ) {
			return;
		}

		if ( ob_get_level() < 1 ) {
			return;
		}

		$html = ob_get_clean();

		// --- 1. Replace esc_html description with HTML-safe rendering ---
		$raw_desc = get_post_meta( get_the_ID(), 'qodef_portfolio_description', true );

		if ( ! empty( $raw_desc ) ) {
			// wpautop converts double newlines to <p> tags; wp_kses_post allows safe HTML
			$formatted_desc = wp_kses_post( wpautop( $raw_desc ) );

			// Build replacement: formatted description + featured image (if set)
			$replacement = '<div class="qodef-portfolio-description">' . $formatted_desc . '</div>';

			if ( has_post_thumbnail() ) {
				$replacement .= '<div class="qodef-research-featured-image">'
					. get_the_post_thumbnail( null, 'large' )
					. '</div>';
			}

			// Replace the original <span> (with esc_html'd content) with our enhanced version
			$html = preg_replace(
				'/<span class="qodef-portfolio-description">.*?<\/span>/s',
				$replacement,
				$html,
				1
			);
		} elseif ( has_post_thumbnail() ) {
			// No description but has thumbnail — insert image before the info sidebar
			$image_html = '<div class="qodef-research-featured-image">'
				. get_the_post_thumbnail( null, 'large' )
				. '</div>';

			$html = preg_replace(
				'/<div class="qodef-additional-info">/',
				$image_html . '<div class="qodef-additional-info">',
				$html,
				1
			);
		}

		echo $html;
	}

	add_action( 'marity_core_action_after_portfolio_single_item', 'marity_child_portfolio_buffer_end', 1 );
}

/**
 * Team Bios – Interactive Sidebar Navigation (Option D)
 *
 * Transforms the Elementor accordion on the Our Team page into a modern
 * sidebar-nav layout: dark left panel with member photos/names, and a
 * right content area showing the selected member's bio.
 *
 * How it works:
 *   1. PHP queries the 'team' CPT for thumbnail URLs keyed by post title.
 *   2. A small JS block (output in wp_footer) reads the existing accordion
 *      DOM, extracts each item's title + content, builds the sidebar HTML,
 *      and replaces the accordion in-place.
 *   3. CSS for the sidebar lives in the child theme's style.css.
 *
 * Only runs on page ID 6575 (Our Team). No plugin files are touched.
 */
if ( ! function_exists( 'marity_child_team_sidebar_bio' ) ) {
	function marity_child_team_sidebar_bio() {
		if ( ! is_page( 6575 ) ) {
			return;
		}

		// Gather team member thumbnail URLs keyed by post title
		$team_posts   = get_posts( array(
			'post_type'      => 'team',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		$member_images = array();
		foreach ( $team_posts as $tp ) {
			$thumb_url = get_the_post_thumbnail_url( $tp->ID, 'thumbnail' );
			if ( $thumb_url ) {
				$member_images[ $tp->post_title ] = $thumb_url;
			}
		}
		?>
		<script>
		(function(){
			var memberImages = <?php echo wp_json_encode( $member_images ); ?>;

			document.addEventListener('DOMContentLoaded', function(){
				// Team list: director alone in first row (bigger card), RAs in 2-col grid below
				var list = document.querySelector('.page-id-6575 .qodef-team-list');
				if (list) {
					var cards = list.querySelectorAll('article.portfolio-item, .qodef-e.qodef-grid-item');
					if (cards.length) {
						var grid = cards[0].parentElement;
						if (grid) {
							grid.classList.add('aimes-team-list-grid');
							cards[0].classList.add('aimes-team-director-card');
						}
					}
				}

				var accordion = document.querySelector('.page-id-6575 .qodef-accordion');
				if (!accordion) return;

				var titles   = accordion.querySelectorAll('.qodef-accordion-title');
				var contents = accordion.querySelectorAll('.qodef-accordion-content');
				if (!titles.length) return;

				var items = [];
				for (var i = 0; i < titles.length; i++) {
					var titleSpan = titles[i].querySelector('.qodef-tab-title');
					var titleText = titleSpan ? titleSpan.textContent.trim() : '';
					var bodyInner = contents[i] ? contents[i].querySelector('.qodef-accordion-content-inner') : null;
					var bodyHTML  = bodyInner ? bodyInner.innerHTML.trim() : '';

					// Parse "Name (Role)" format
					var m    = titleText.match(/^(.+?)\s*\((.+?)\)\s*$/);
					var name = m ? m[1].trim() : titleText;
					var role = m ? m[2].trim() : '';

					// Match image by first name (handles "John Wihbey" vs "John Wihbey, EdD")
					var firstName = name.split(' ')[0].toLowerCase();
					var imgUrl    = '';
					for (var key in memberImages) {
						if (key.toLowerCase().split(' ')[0] === firstName ||
							key.toLowerCase().split(',')[0].indexOf(firstName) === 0) {
							imgUrl = memberImages[key];
							break;
						}
					}

					items.push({ name:name, role:role, content:bodyHTML, image:imgUrl });
				}

				// Build sidebar HTML
				var h = '<div class="aimes-bio-sidebar">';

				// Left nav
				h += '<div class="aimes-bio-nav">';
				items.forEach(function(it, idx){
					h += '<button class="aimes-bio-btn' + (idx===0?' active':'') + '" data-idx="'+idx+'">';
					if (it.image) h += '<img src="'+it.image+'" alt="">';
					h += '<div><div class="aimes-bio-btn-name">'+it.name+'</div>';
					if (it.role) h += '<div class="aimes-bio-btn-role">'+it.role+'</div>';
					h += '</div></button>';
				});
				h += '</div>';

				// Right content
				h += '<div class="aimes-bio-content">';
				items.forEach(function(it, idx){
					h += '<div class="aimes-bio-panel" data-idx="'+idx+'" style="display:'+(idx===0?'block':'none')+'">';
					h += '<div class="aimes-bio-panel-name">'+it.name+'</div>';
					if (it.role) h += '<span class="aimes-bio-panel-role">'+it.role+'</span>';
					if (it.content) {
						h += '<div class="aimes-bio-panel-text">'+it.content+'</div>';
					} else {
						h += '<div class="aimes-bio-panel-empty">Bio coming soon.</div>';
					}
					h += '</div>';
				});
				h += '</div></div>';

				// Replace accordion in-place
				accordion.outerHTML = h;

				// Wire up click handlers
				document.querySelectorAll('.aimes-bio-btn').forEach(function(btn){
					btn.addEventListener('click', function(){
						var idx = this.getAttribute('data-idx');
						document.querySelectorAll('.aimes-bio-btn').forEach(function(b){ b.classList.remove('active'); });
						this.classList.add('active');
						document.querySelectorAll('.aimes-bio-panel').forEach(function(p){ p.style.display='none'; });
						document.querySelector('.aimes-bio-panel[data-idx="'+idx+'"]').style.display='block';
					});
				});
			});
		})();
		</script>
		<?php
	}

	add_action( 'wp_footer', 'marity_child_team_sidebar_bio' );
}

/**
 * Portfolio Listing Enhancement – Option A: Uniform Grid Cards
 *
 * Injects additional content into portfolio list items: date, excerpt,
 * category badge data attribute, and read more link. Works with the
 * CSS in style.css to create the Option A card design.
 *
 * Only runs on pages/archives that contain the portfolio list.
 * Uses JavaScript DOM manipulation to avoid modifying plugin files.
 */
if ( ! function_exists( 'marity_child_portfolio_list_enhance' ) ) {
	function marity_child_portfolio_list_enhance() {
		// Skip single portfolio item pages - only run on listing/grid pages
		if ( is_singular( 'portfolio-item' ) ) {
			return;
		}

		// Gather data for all portfolio items
		$portfolio_items = get_posts( array(
			'post_type'      => 'portfolio-item',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );

		$items_data = array();
		foreach ( $portfolio_items as $p ) {
			$categories = wp_get_post_terms( $p->ID, 'portfolio-category', array( 'fields' => 'names' ) );
			$cat_label  = ! empty( $categories ) ? $categories[0] : 'Research';

			$desc_raw = get_post_meta( $p->ID, 'qodef_portfolio_description', true );
			$excerpt  = $desc_raw ? wp_trim_words( strip_tags( $desc_raw ), 22, '…' ) : '';

			// Get date from info items meta
			$info_items = get_post_meta( $p->ID, 'qodef_portfolio_info_items', true );
			$date_str   = '';
			if ( is_array( $info_items ) ) {
				foreach ( $info_items as $info ) {
					if ( ! empty( $info['item_title'] ) && stripos( $info['item_title'], 'date' ) !== false ) {
						$date_str = $info['item_text'] ?? '';
						break;
					}
				}
			}
			if ( ! $date_str ) {
				$date_str = get_the_date( 'M Y', $p->ID );
			}

			$thumb_url = get_the_post_thumbnail_url( $p->ID, 'medium_large' );

			$items_data[ $p->ID ] = array(
				'category' => $cat_label,
				'excerpt'  => $excerpt,
				'date'     => $date_str,
				'thumb'    => $thumb_url ? $thumb_url : '',
				'title'    => get_the_title( $p->ID ),
				'url'      => get_permalink( $p->ID ),
			);
		}
		?>
		<script>
		(function(){
			var itemsData = <?php echo wp_json_encode( $items_data ); ?>;

			document.addEventListener('DOMContentLoaded', function(){
				var articles = document.querySelectorAll('article.portfolio-item');
				if (!articles.length) return;

				articles.forEach(function(article){
					// Extract post ID
					var postId = null;
					article.className.split(' ').forEach(function(cls){
						var m = cls.match(/^post-(\d+)$/);
						if (m) postId = m[1];
					});
					if (!postId || !itemsData[postId]) return;

					var d = itemsData[postId];

					// Get existing image src as fallback
					var existingImg = article.querySelector('.qodef-e-media-image img');
					var imgSrc = d.thumb || (existingImg ? existingImg.src : '');

					// Build image HTML
					var imgHTML = '';
					if (imgSrc) {
						imgHTML = '<img src="' + imgSrc + '" alt="" style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.4s ease;">';
					} else {
						imgHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:14px;color:#999;font-family:Sora,sans-serif;">No Image</div>';
					}

					// Category badge
					var catHTML = d.category ? '<span style="position:absolute;top:14px;left:14px;font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;background:#0e202a;color:#fff;padding:5px 12px;border-radius:4px;z-index:2;">' + d.category.replace(/</g,'&lt;') + '</span>' : '';

					// Completely rebuild the article inner HTML
					article.innerHTML =
						'<a href="' + d.url + '" class="optionA-card" style="display:flex;flex-direction:column;height:100%;text-decoration:none;color:inherit;">' +
							'<div class="optionA-img" style="width:100%;height:220px;overflow:hidden;position:relative;background:#e8ecf0;flex-shrink:0;">' +
								imgHTML +
								catHTML +
							'</div>' +
							'<div class="optionA-body" style="padding:22px 22px 26px;flex:1;display:flex;flex-direction:column;">' +
								'<div style="font-size:11px;font-weight:500;color:#9b9b9b;letter-spacing:0.5px;margin-bottom:10px;font-family:Sora,sans-serif;">' + (d.date || '').replace(/</g,'&lt;') + '</div>' +
								'<div style="font-size:16px;font-weight:600;line-height:1.4;color:#0e202a;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">' + (d.title || '').replace(/</g,'&lt;') + '</div>' +
								(d.excerpt ? '<p style="font-size:13px;line-height:1.6;color:#646464;flex:1;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0 0 16px 0;font-family:Sora,sans-serif;">' + d.excerpt.replace(/</g,'&lt;') + '</p>' : '') +
								'<span style="font-size:12px;font-weight:600;color:#1e3a5f;display:inline-flex;align-items:center;gap:6px;text-transform:uppercase;letter-spacing:1px;margin-top:auto;font-family:Sora,sans-serif;">Read More <span style="font-size:14px;">→</span></span>' +
							'</div>' +
						'</a>';

					// Apply card styles directly on the article
					article.style.cssText = 'background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.06);transition:transform 0.3s ease,box-shadow 0.3s ease;';

					// Hover effects
					article.addEventListener('mouseenter', function(){
						this.style.transform = 'translateY(-6px)';
						this.style.boxShadow = '0 12px 32px rgba(0,0,0,0.1)';
						var img = this.querySelector('.optionA-img img');
						if (img) img.style.transform = 'scale(1.05)';
					});
					article.addEventListener('mouseleave', function(){
						this.style.transform = '';
						this.style.boxShadow = '0 1px 4px rgba(0,0,0,0.06)';
						var img = this.querySelector('.optionA-img img');
						if (img) img.style.transform = '';
					});
				});
			});
		})();
		</script>
		<?php
	}

	add_action( 'wp_footer', 'marity_child_portfolio_list_enhance', 5 );
}


/**
 * Create virtual page for /talk-preview/ (legacy support)
 * Redirects to /talks/
 */
function marity_child_talk_preview_virtual_page() {
	if ( strpos( $_SERVER['REQUEST_URI'], '/talk-preview' ) !== false ) {
		wp_redirect( home_url( '/talks/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'marity_child_talk_preview_virtual_page' );

/**
 * Add rewrite rule for talk-preview
 */
function marity_child_add_talk_preview_rewrite() {
	add_rewrite_rule( '^talk-preview/?', 'index.php?talk_preview=1', 'top' );
}
add_action( 'init', 'marity_child_add_talk_preview_rewrite' );

/**
 * Add query var for talk-preview
 */
function marity_child_talk_preview_query_var( $vars ) {
	$vars[] = 'talk_preview';
	return $vars;
}
add_filter( 'query_vars', 'marity_child_talk_preview_query_var' );

/**
 * Customize navigation menu:
 * - Add "Talks" menu item
 * - Rename "Insights" / "Blogs" to "Articles & Insights"
 * - Reorder: Contact Us should be last
 */
function marity_child_customize_menu_items( $items, $args ) {
	if ( ! in_array( $args->theme_location, array( 'main-navigation', 'primary', 'primary-menu', '' ), true ) ) {
		return $items;
	}
	
	// Rename "Insights" / "Blogs" to "Articles & Insights"
	$items = preg_replace( '/>Insights</i', '>Articles & Insights<', $items );
	$items = preg_replace( '/>Blogs</i', '>Articles & Insights<', $items );
	
	// Use DOMDocument to properly parse and reorder menu items
	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML( '<ul>' . $items . '</ul>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	
	$ul = $dom->getElementsByTagName( 'ul' )->item( 0 );
	if ( ! $ul ) {
		return $items;
	}
	
	$menu_items = array();
	$contact_item = null;
	$has_talks = false;
	
	// Collect all li elements
	$lis = $ul->getElementsByTagName( 'li' );
	$items_to_process = array();
	foreach ( $lis as $li ) {
		// Only process direct children of ul
		if ( $li->parentNode === $ul ) {
			$items_to_process[] = $li;
		}
	}
	
	foreach ( $items_to_process as $li ) {
		$html = $dom->saveHTML( $li );
		$text = strtolower( $li->textContent );
		
		if ( strpos( $text, 'contact' ) !== false ) {
			$contact_item = $html;
		} else {
			$menu_items[] = $html;
			if ( strpos( $html, '/talks' ) !== false ) {
				$has_talks = true;
			}
		}
	}
	
	// Add Talks if not present
	if ( ! $has_talks ) {
		$active = '';
		if ( strpos( $_SERVER['REQUEST_URI'], '/talks' ) !== false ) {
			$active = ' current-menu-item';
		}
		$menu_items[] = '<li class="menu-item menu-item-talks' . $active . '"><a href="/talks/">Talks</a></li>';
	}
	
	// Add Contact Us at the end
	if ( $contact_item ) {
		$menu_items[] = $contact_item;
	}
	
	return implode( '', $menu_items );
}
add_filter( 'wp_nav_menu_items', 'marity_child_customize_menu_items', 10, 2 );

/**
 * Add rewrite rule for /talks/ page
 */
function marity_child_talks_rewrite() {
	add_rewrite_rule( '^talks/?$', 'index.php?aimes_talks_page=1', 'top' );
}
add_action( 'init', 'marity_child_talks_rewrite' );

/**
 * Add query var for talks page
 */
function marity_child_talks_query_var( $vars ) {
	$vars[] = 'aimes_talks_page';
	return $vars;
}
add_filter( 'query_vars', 'marity_child_talks_query_var' );

/**
 * Set dummy post for virtual pages so header/plugins (e.g. RevSlider) and nav menu don't break
 */
function marity_child_set_virtual_page_post( $title = 'Page' ) {
	global $wp_query, $post;
	$dummy = new stdClass();
	$dummy->ID             = 0;
	$dummy->post_content   = '';
	$dummy->post_title     = $title;
	$dummy->post_type      = 'page';
	$dummy->post_parent    = 0;
	$dummy->post_status    = 'publish';
	$dummy->post_author    = 1;
	$dummy->post_date      = current_time( 'mysql' );
	$dummy->post_name      = '';
	$dummy->comment_count  = 0;
	$dummy->comment_status = 'closed';
	$dummy->ping_status    = 'closed';
	$dummy->post_password  = '';
	$dummy->to_ping        = '';
	$dummy->pinged         = '';
	$dummy->post_modified  = current_time( 'mysql' );
	$dummy->post_excerpt   = '';
	$dummy->menu_order     = 0;
	$dummy->guid           = '';
	$dummy->filter         = 'raw';
	// Required by nav-menu-template.php: in_array() expects $haystack to be array
	$dummy->ancestors      = array();
	$post = $dummy;
	$wp_query->queried_object    = $dummy;
	$wp_query->queried_object_id = 0;
	$wp_query->post              = $dummy;
	$wp_query->posts             = array( $dummy );
	$wp_query->post_count        = 1;
}

/**
 * Handle /talks/ virtual page
 */
function marity_child_talks_template_redirect() {
	if ( get_query_var( 'aimes_talks_page' ) || strpos( $_SERVER['REQUEST_URI'], '/talks' ) !== false && strpos( $_SERVER['REQUEST_URI'], '/talk-preview' ) === false ) {
		global $wp_query;
		$wp_query->is_page     = true;
		$wp_query->is_singular = true;
		$wp_query->is_home     = false;
		$wp_query->is_archive  = false;
		marity_child_set_virtual_page_post( 'Talks & Seminars' );
		$template = get_stylesheet_directory() . '/talk-preview.php';
		if ( ! file_exists( $template ) ) {
			wp_die( 'Talks template not found. Ensure talk-preview.php is in the child theme folder on this server.', 'Template missing', array( 'response' => 500 ) );
		}
		include $template;
		exit;
	}
}
add_action( 'template_redirect', 'marity_child_talks_template_redirect', 5 );

/**
 * Homepage Featured Talks Section
 * Displays upcoming and past talks in a grid on the homepage
 */
function marity_child_homepage_talks_section() {
	if ( ! is_front_page() ) {
		return;
	}

	// Query talks
	$now = current_time( 'Y-m-d\TH:i' );
	$talks = get_posts( array(
		'post_type'      => 'aimes_talk',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'meta_value',
		'meta_key'       => '_aimes_talk_date',
		'order'          => 'ASC',
	) );

	$upcoming = array();
	$past     = array();

	foreach ( $talks as $t ) {
		$date_raw = get_post_meta( $t->ID, '_aimes_talk_date', true );
		$ts       = $date_raw ? strtotime( $date_raw ) : false;
		$thumb    = get_post_meta( $t->ID, '_aimes_talk_thumb', true );
		if ( ! $thumb ) {
			$thumb = get_the_post_thumbnail_url( $t->ID, 'medium_large' ) ?: get_the_post_thumbnail_url( $t->ID, 'medium' ) ?: '';
		}
		$item = array(
			'id'          => $t->ID,
			'permalink'   => get_permalink( $t->ID ),
			'title'       => get_the_title( $t->ID ),
			'speaker'     => get_post_meta( $t->ID, '_aimes_talk_speaker', true ),
			'speaker_bio' => get_post_meta( $t->ID, '_aimes_talk_speaker_bio', true ),
			'location'    => get_post_meta( $t->ID, '_aimes_talk_location', true ),
			'date_raw'    => $date_raw,
			'date_fmt'    => $ts ? date_i18n( 'F j, Y', $ts ) : '',
			'time_fmt'    => $ts ? date_i18n( 'g:i A', $ts ) : '',
			'month'       => $ts ? date_i18n( 'M', $ts ) : '',
			'day'         => $ts ? date_i18n( 'j', $ts ) : '',
			'thumb'       => $thumb,
			'register'    => get_post_meta( $t->ID, '_aimes_talk_register', true ),
			'virtual_url' => get_post_meta( $t->ID, '_aimes_talk_virtual_url', true ),
			'content'     => wp_trim_words( strip_tags( $t->post_content ), 22, '...' ),
		);
		if ( $date_raw && $date_raw >= $now ) {
			$upcoming[] = $item;
		} else {
			$past[] = $item;
		}
	}
	$past = array_reverse( $past );

	// Demo data if no talks exist
	if ( empty( $upcoming ) && empty( $past ) ) {
		$demo = array(
			array(
				'id' => 0, 'permalink' => '/talks/',
				'title' => 'AI Ethics in Modern Research', 'speaker' => 'Dr. ABC Smith',
				'location' => 'Room 302, Main Hall',
				'date_fmt' => 'March 15, 2026', 'time_fmt' => '2:00 PM', 'month' => 'Mar', 'day' => '15',
				'thumb' => '', 'register' => '#', 'virtual_url' => '#',
				'content' => 'Exploring the ethical implications of AI systems in academic research.',
			),
			array(
				'id' => 0, 'permalink' => '/talks/',
				'title' => 'Machine Learning for Climate', 'speaker' => 'Prof. XYZ Johnson',
				'location' => 'Virtual (Zoom)',
				'date_fmt' => 'March 28, 2026', 'time_fmt' => '3:30 PM', 'month' => 'Mar', 'day' => '28',
				'thumb' => '', 'register' => '#', 'virtual_url' => '#',
				'content' => 'How neural networks are revolutionizing climate prediction models.',
			),
			array(
				'id' => 0, 'permalink' => '/talks/',
				'title' => 'NLP in Healthcare', 'speaker' => 'Dr. PQR Williams',
				'location' => 'Auditorium B',
				'date_fmt' => 'January 5, 2026', 'time_fmt' => '1:00 PM', 'month' => 'Jan', 'day' => '5',
				'thumb' => '', 'register' => '', 'virtual_url' => '',
				'content' => 'Applications of transformer models for clinical note analysis.',
			),
		);
		$upcoming = array_slice( $demo, 0, 2 );
		$past = array_slice( $demo, 2 );
	}

	$featured = array_slice( $past, 0, 3 );
	$upcoming = array_slice( $upcoming, 0, 3 );

	ob_start();
	?>
	<div class="aimes-homepage-talks">
		<div class="aimes-talks-header">
			<h2>Featured Talks</h2>
			<p>Join our upcoming research talks and seminars</p>
		</div>

		<?php if ( ! empty( $featured ) ) : ?>
		<div class="aimes-talks-section">
			<h3 class="aimes-talks-section-title">Featured Talks</h3>
			<div class="aimes-talks-grid aimes-talks-cards">
				<?php foreach ( $featured as $talk ) : ?>
				<article class="aimes-talk-card aimes-talk-featured">
					<a href="<?php echo esc_url( $talk['permalink'] ); ?>" class="aimes-talk-card-photo">
						<?php if ( ! empty( $talk['thumb'] ) ) : ?>
							<img src="<?php echo esc_url( $talk['thumb'] ); ?>" alt="" loading="lazy" />
						<?php else : ?>
							<span class="aimes-talk-card-placeholder"><?php echo esc_html( $talk['month'] . ' ' . $talk['day'] ); ?></span>
						<?php endif; ?>
					</a>
					<div class="aimes-talk-card-body">
						<h4 class="aimes-talk-card-title"><?php echo esc_html( $talk['title'] ); ?></h4>
						<?php if ( ! empty( $talk['location'] ) ) : ?>
							<div class="aimes-talk-card-venue"><?php echo esc_html( $talk['location'] ); ?></div>
						<?php endif; ?>
						<div class="aimes-talk-card-day"><?php echo esc_html( $talk['month'] . ' ' . $talk['day'] ); ?></div>
						<?php if ( ! empty( $talk['content'] ) ) : ?>
							<p class="aimes-talk-card-excerpt"><?php echo esc_html( $talk['content'] ); ?></p>
						<?php endif; ?>
						<a href="<?php echo esc_url( $talk['permalink'] ); ?>" class="aimes-talk-card-btn aimes-talk-btn-view">View more</a>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $upcoming ) ) : ?>
		<div class="aimes-talks-section">
			<h3 class="aimes-talks-section-title">Upcoming Talks</h3>
			<div class="aimes-talks-grid aimes-talks-cards">
				<?php foreach ( $upcoming as $talk ) : ?>
				<article class="aimes-talk-card aimes-talk-upcoming">
					<div class="aimes-talk-card-upcoming-dot" aria-hidden="true"></div>
					<span class="aimes-talk-badge aimes-talk-badge-upcoming">Upcoming</span>
					<a href="<?php echo esc_url( $talk['permalink'] ); ?>" class="aimes-talk-card-photo">
						<?php if ( ! empty( $talk['thumb'] ) ) : ?>
							<img src="<?php echo esc_url( $talk['thumb'] ); ?>" alt="" loading="lazy" />
						<?php else : ?>
							<span class="aimes-talk-card-placeholder"><?php echo esc_html( $talk['month'] . ' ' . $talk['day'] ); ?></span>
						<?php endif; ?>
					</a>
					<div class="aimes-talk-card-body">
						<h4 class="aimes-talk-card-title"><?php echo esc_html( $talk['title'] ); ?></h4>
						<?php if ( ! empty( $talk['location'] ) ) : ?>
							<div class="aimes-talk-card-venue"><?php echo esc_html( $talk['location'] ); ?></div>
						<?php endif; ?>
						<div class="aimes-talk-card-day"><?php echo esc_html( $talk['month'] . ' ' . $talk['day'] ); ?><?php if ( ! empty( $talk['time_fmt'] ) ) : ?> · <?php echo esc_html( $talk['time_fmt'] ); ?><?php endif; ?></div>
						<?php if ( ! empty( $talk['content'] ) ) : ?>
							<p class="aimes-talk-card-excerpt"><?php echo esc_html( $talk['content'] ); ?></p>
						<?php endif; ?>
						<div class="aimes-talk-card-actions">
							<?php if ( ! empty( $talk['register'] ) ) : ?>
								<a href="<?php echo esc_url( $talk['register'] ); ?>" class="aimes-talk-card-btn aimes-talk-btn-primary">Register</a>
							<?php endif; ?>
							<?php if ( ! empty( $talk['virtual_url'] ) ) : ?>
								<a href="<?php echo esc_url( $talk['virtual_url'] ); ?>" class="aimes-talk-card-btn aimes-talk-btn-outline">Join Virtual</a>
							<?php endif; ?>
							<a href="<?php echo esc_url( $talk['permalink'] ); ?>" class="aimes-talk-btn-view">View more</a>
						</div>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="aimes-talks-footer">
			<a href="<?php echo esc_url( home_url( '/talks/' ) ); ?>" class="aimes-talks-view-all">View All Talks</a>
		</div>
	</div>
	<?php
	$html = ob_get_clean();

	// Output as template to inject via JS - position after "Our Work" / "AI & Media Innovation" section
	echo '<template id="aimes-homepage-talks-tpl">' . $html . '</template>';
	?>
	<script>
	(function(){
		document.addEventListener('DOMContentLoaded', function(){
			setTimeout(function(){
				var tpl = document.getElementById('aimes-homepage-talks-tpl');
				if (!tpl) return;
				var frag = tpl.content ? tpl.content.cloneNode(true) : document.createRange().createContextualFragment(tpl.innerHTML);
				
				// Find "Our Work" or "AI & Media Innovation" section by looking for text content
				var targetSection = null;
				var sections = document.querySelectorAll('.elementor-top-section, .elementor-section');
				
				for (var i = 0; i < sections.length; i++) {
					var text = sections[i].textContent.toLowerCase();
					// Look for "Our Work" or "AI & Media" or "Innovation" headings
					if (text.indexOf('our work') !== -1 || 
						text.indexOf('ai & media') !== -1 || 
						text.indexOf('ai and media') !== -1 ||
						text.indexOf('media innovation') !== -1) {
						targetSection = sections[i];
						break;
					}
				}
				
				// Fallback: look for portfolio/carousel section (Featured Research)
				if (!targetSection) {
					var carousel = document.querySelector('.swiper-container, .swiper, .qodef-portfolio-list');
					if (carousel) {
						targetSection = carousel.closest('.elementor-section') || carousel.closest('.elementor-top-section');
					}
				}
				
				// Final fallback: insert after 3rd section
				if (!targetSection) {
					var allSections = document.querySelectorAll('.elementor-top-section');
					if (allSections.length > 3) {
						targetSection = allSections[2];
					}
				}
				
				if (targetSection && targetSection.parentNode) {
					// Insert AFTER the target section
					targetSection.parentNode.insertBefore(frag, targetSection.nextSibling);
				}
				
				tpl.remove();
			}, 600);
		});
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'marity_child_homepage_talks_section', 6 );

/**
 * Remove default Marity footer (blue ribbon / top & bottom areas) so only our custom footer shows.
 */
add_filter( 'marity_filter_enable_page_footer', '__return_false' );

/**
 * Custom Footer for AIMES
 * Modern footer with contact info, quick links, and branding
 */
function marity_child_custom_footer() {
	?>
	<style>
	/* Custom Footer Styles */
	.aimes-custom-footer {
		background: linear-gradient(135deg, #0e202a 0%, #1a3245 100%);
		color: #fff;
		font-family: 'Sora', sans-serif;
		padding: 0;
		margin-top: 60px;
	}
	.aimes-footer-main {
		max-width: 1200px;
		margin: 0 auto;
		padding: 60px 24px 40px;
		display: grid;
		grid-template-columns: 1.5fr 1fr 1fr 1fr;
		gap: 48px;
	}
	.aimes-footer-brand h3 {
		font-size: 22px;
		font-weight: 700;
		margin: 0 0 16px;
		color: #fff;
	}
	.aimes-footer-brand p {
		font-size: 14px;
		line-height: 1.7;
		color: rgba(255,255,255,0.7);
		margin: 0 0 20px;
	}
	.aimes-footer-social {
		display: flex;
		gap: 12px;
	}
	.aimes-footer-social a {
		width: 38px;
		height: 38px;
		border-radius: 8px;
		background: rgba(255,255,255,0.1);
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		text-decoration: none;
		transition: all 0.2s;
	}
	.aimes-footer-social a:hover {
		background: #1e3a5f;
		transform: translateY(-2px);
	}
	.aimes-footer-col h4 {
		font-size: 13px;
		font-weight: 700;
		letter-spacing: 1.5px;
		text-transform: uppercase;
		color: #7ec8e3;
		margin: 0 0 20px;
	}
	.aimes-footer-col ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.aimes-footer-col ul li {
		margin-bottom: 12px;
	}
	.aimes-footer-col ul li a {
		color: rgba(255,255,255,0.75);
		text-decoration: none;
		font-size: 14px;
		transition: color 0.2s;
	}
	.aimes-footer-col ul li a:hover {
		color: #fff;
	}
	.aimes-footer-contact-item {
		display: flex;
		align-items: flex-start;
		gap: 12px;
		margin-bottom: 16px;
		font-size: 14px;
		color: rgba(255,255,255,0.75);
	}
	.aimes-footer-contact-item svg {
		width: 18px;
		height: 18px;
		flex-shrink: 0;
		margin-top: 2px;
		color: #7ec8e3;
	}
	.aimes-footer-contact-item a {
		color: rgba(255,255,255,0.75);
		text-decoration: none;
	}
	.aimes-footer-contact-item a:hover {
		color: #fff;
	}
	.aimes-footer-bottom {
		border-top: 1px solid rgba(255,255,255,0.1);
		padding: 24px;
		text-align: center;
	}
	.aimes-footer-bottom-inner {
		max-width: 1200px;
		margin: 0 auto;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 16px;
	}
	.aimes-footer-copyright {
		font-size: 13px;
		color: rgba(255,255,255,0.5);
	}
	.aimes-footer-links {
		display: flex;
		gap: 24px;
	}
	.aimes-footer-links a {
		font-size: 13px;
		color: rgba(255,255,255,0.5);
		text-decoration: none;
	}
	.aimes-footer-links a:hover {
		color: #fff;
	}
	@media (max-width: 900px) {
		.aimes-footer-main {
			grid-template-columns: 1fr 1fr;
			gap: 36px;
		}
	}
	@media (max-width: 600px) {
		.aimes-footer-main {
			grid-template-columns: 1fr;
			gap: 32px;
			padding: 40px 20px 30px;
		}
		.aimes-footer-bottom-inner {
			flex-direction: column;
			text-align: center;
		}
	}
	</style>
	
	<footer class="aimes-custom-footer">
		<div class="aimes-footer-main">
			<!-- Brand Column -->
			<div class="aimes-footer-brand">
				<h3>AIMES Lab</h3>
				<p>AI & Media Innovation Lab at Northeastern University. Advancing research at the intersection of artificial intelligence, media, and society.</p>
				<div class="aimes-footer-social">
					<a href="https://www.linkedin.com/in/john-wihbey-55b44213/" aria-label="LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
					</a>
				</div>
			</div>
			
			<!-- Quick Links -->
			<div class="aimes-footer-col">
				<h4>Quick Links</h4>
				<ul>
					<li><a href="/">Home</a></li>
					<li><a href="/about/">About</a></li>
					<li><a href="/research/">Research</a></li>
					<li><a href="/talks/">Talks</a></li>
					<li><a href="/blogs/">Articles & Insights</a></li>
				</ul>
			</div>
			
			<!-- Resources -->
			<div class="aimes-footer-col">
				<h4>Resources</h4>
				<ul>
					<li><a href="/demos-exploratory-applications/">Courses & Projects</a></li>
					<li><a href="/our-team/">Our Team</a></li>
					<li><a href="/contact-us/">Contact Us</a></li>
				</ul>
			</div>
			
			<!-- Contact Info -->
			<div class="aimes-footer-col">
				<h4>Contact</h4>
				<div class="aimes-footer-contact-item">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
					<span>Northeastern University<br>Boston, MA</span>
				</div>
				<div class="aimes-footer-contact-item">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
					<a href="mailto:j.wihbey@northeastern.edu">j.wihbey@northeastern.edu</a>
				</div>
			</div>
		</div>
		
		<!-- Footer Bottom -->
		<div class="aimes-footer-bottom">
			<div class="aimes-footer-bottom-inner">
				<div class="aimes-footer-copyright">
					&copy; <?php echo date('Y'); ?> AIMES Lab, Northeastern University. All rights reserved.
				</div>
				<div class="aimes-footer-links">
				</div>
			</div>
		</div>
	</footer>
	<?php
}
add_action( 'wp_footer', 'marity_child_custom_footer', 100 );

/**
 * Register Courses CPT
 */
function marity_child_register_courses_cpt() {
	register_post_type( 'aimes_course', array(
		'labels' => array(
			'name'               => 'Courses',
			'singular_name'      => 'Course',
			'add_new'            => 'Add New Course',
			'add_new_item'       => 'Add New Course',
			'edit_item'          => 'Edit Course',
			'new_item'           => 'New Course',
			'view_item'          => 'View Course',
			'search_items'       => 'Search Courses',
			'not_found'          => 'No courses found.',
			'not_found_in_trash' => 'No courses found in Trash.',
			'menu_name'          => 'Courses',
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'menu_icon'         => 'dashicons-welcome-learn-more',
		'menu_position'     => 26,
		'capability_type'   => 'post',
		'supports'          => array( 'title', 'editor', 'thumbnail' ),
		'has_archive'       => false,
		'rewrite'           => false,
	) );
}
add_action( 'init', 'marity_child_register_courses_cpt' );

/**
 * Course Meta Box
 */
function marity_child_course_meta_box() {
	add_meta_box(
		'aimes_course_details',
		'Course Details',
		'marity_child_course_meta_box_cb',
		'aimes_course',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'marity_child_course_meta_box' );

function marity_child_course_meta_box_cb( $post ) {
	wp_nonce_field( 'aimes_course_details', 'aimes_course_nonce' );
	$fields = array(
		'_aimes_course_code'     => array( 'label' => 'Course Code (e.g., CS 5100)', 'type' => 'text' ),
		'_aimes_course_level'    => array( 'label' => 'Level', 'type' => 'select', 'options' => array( 'Graduate', 'Undergraduate' ) ),
		'_aimes_course_semester' => array( 'label' => 'Semester (e.g., Fall 2025)', 'type' => 'text' ),
	);
	echo '<table class="form-table">';
	foreach ( $fields as $key => $f ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<tr><th><label for="' . esc_attr( $key ) . '">' . esc_html( $f['label'] ) . '</label></th><td>';
		if ( $f['type'] === 'select' ) {
			echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="widefat">';
			foreach ( $f['options'] as $opt ) {
				echo '<option value="' . esc_attr( $opt ) . '"' . selected( $val, $opt, false ) . '>' . esc_html( $opt ) . '</option>';
			}
			echo '</select>';
		} else {
			echo '<input type="' . esc_attr( $f['type'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="widefat">';
		}
		echo '</td></tr>';
	}
	echo '</table>';
	echo '<p class="description">Use the main editor for the course description. Featured Image = course banner.</p>';
}

function marity_child_course_save( $post_id ) {
	if ( ! isset( $_POST['aimes_course_nonce'] ) || ! wp_verify_nonce( $_POST['aimes_course_nonce'], 'aimes_course_details' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	
	$keys = array( '_aimes_course_code', '_aimes_course_level', '_aimes_course_semester' );
	foreach ( $keys as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
		}
	}
}
add_action( 'save_post_aimes_course', 'marity_child_course_save' );

/**
 * Register Projects CPT
 */
function marity_child_register_projects_cpt() {
	register_post_type( 'aimes_project', array(
		'labels' => array(
			'name'               => 'Projects',
			'singular_name'      => 'Project',
			'add_new'            => 'Add New Project',
			'add_new_item'       => 'Add New Project',
			'edit_item'          => 'Edit Project',
			'new_item'           => 'New Project',
			'view_item'          => 'View Project',
			'search_items'       => 'Search Projects',
			'not_found'          => 'No projects found.',
			'not_found_in_trash' => 'No projects found in Trash.',
			'menu_name'          => 'Projects',
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'menu_icon'         => 'dashicons-portfolio',
		'menu_position'     => 27,
		'capability_type'   => 'post',
		'supports'          => array( 'title', 'editor', 'thumbnail' ),
		'has_archive'       => false,
		'rewrite'           => false,
	) );
}
add_action( 'init', 'marity_child_register_projects_cpt' );

/**
 * Project Meta Box
 */
function marity_child_project_meta_box() {
	add_meta_box(
		'aimes_project_details',
		'Project Details',
		'marity_child_project_meta_box_cb',
		'aimes_project',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'marity_child_project_meta_box' );

function marity_child_project_meta_box_cb( $post ) {
	wp_nonce_field( 'aimes_project_details', 'aimes_project_nonce' );
	$course_val = get_post_meta( $post->ID, '_aimes_project_course', true );
	$course_posts = get_posts( array( 'post_type' => 'aimes_course', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	echo '<table class="form-table">';
	echo '<tr><th><label for="_aimes_project_course">Course</label></th><td>';
	if ( ! empty( $course_posts ) ) {
		echo '<select name="_aimes_project_course" id="_aimes_project_course" class="widefat">';
		echo '<option value="">— Select course —</option>';
		foreach ( $course_posts as $c ) {
			$t = get_the_title( $c->ID );
			echo '<option value="' . esc_attr( $t ) . '"' . selected( $course_val, $t, false ) . '>' . esc_html( $t ) . '</option>';
		}
		echo '</select>';
		echo '<p class="description">Projects under the same course appear under that course on the Demos page. Add courses first under <strong>Courses</strong>.</p>';
	} else {
		echo '<input type="text" name="_aimes_project_course" id="_aimes_project_course" value="' . esc_attr( $course_val ) . '" class="widefat">';
		echo '<p class="description">Add courses under <strong>Courses</strong> to get a dropdown here.</p>';
	}
	echo '</td></tr>';
	$fields = array(
		'_aimes_project_students' => array( 'label' => 'Student Names (comma separated)', 'type' => 'text' ),
		'_aimes_project_year'     => array( 'label' => 'Year', 'type' => 'text' ),
		'_aimes_project_link'     => array( 'label' => 'Project URL', 'type' => 'url' ),
		'_aimes_project_featured' => array( 'label' => 'Featured (show under course; uncheck for "View more projects")', 'type' => 'checkbox' ),
	);
	foreach ( $fields as $key => $f ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<tr><th><label for="' . esc_attr( $key ) . '">' . esc_html( $f['label'] ) . '</label></th><td>';
		if ( $f['type'] === 'checkbox' ) {
			echo '<input type="checkbox" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="1"' . checked( $val, '1', false ) . '>';
		} else {
			echo '<input type="' . esc_attr( $f['type'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="widefat">';
		}
		echo '</td></tr>';
	}
	echo '</table>';
	echo '<p class="description">Use the main editor for the project description. Featured Image = project thumbnail.</p>';
}

function marity_child_project_save( $post_id ) {
	if ( ! isset( $_POST['aimes_project_nonce'] ) || ! wp_verify_nonce( $_POST['aimes_project_nonce'], 'aimes_project_details' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	
	$text_keys = array( '_aimes_project_course', '_aimes_project_students', '_aimes_project_year' );
	foreach ( $text_keys as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
		}
	}
	if ( isset( $_POST['_aimes_project_link'] ) ) {
		update_post_meta( $post_id, '_aimes_project_link', esc_url_raw( $_POST['_aimes_project_link'] ) );
	}
	update_post_meta( $post_id, '_aimes_project_featured', isset( $_POST['_aimes_project_featured'] ) ? '1' : '' );
}
add_action( 'save_post_aimes_project', 'marity_child_project_save' );

/**
 * Handle /demos-exploratory-applications/ virtual page (Courses & Project Showcase – Design 1)
 */
function marity_child_courses_showcase_rewrite() {
	add_rewrite_rule( '^demos-exploratory-applications/?$', 'index.php?aimes_courses_page=1', 'top' );
}
add_action( 'init', 'marity_child_courses_showcase_rewrite' );

function marity_child_courses_showcase_query_var( $vars ) {
	$vars[] = 'aimes_courses_page';
	return $vars;
}
add_filter( 'query_vars', 'marity_child_courses_showcase_query_var' );

function marity_child_courses_showcase_template_redirect() {
	// Redirect old URL to new
	if ( strpos( $_SERVER['REQUEST_URI'], '/courses-project-showcase' ) !== false ) {
		wp_redirect( home_url( '/demos-exploratory-applications/' ), 301 );
		exit;
	}
	if ( get_query_var( 'aimes_courses_page' ) || ( strpos( $_SERVER['REQUEST_URI'], '/demos-exploratory-applications' ) !== false ) ) {
		global $wp_query;
		$wp_query->is_page     = true;
		$wp_query->is_singular = true;
		$wp_query->is_home     = false;
		$wp_query->is_archive  = false;
		marity_child_set_virtual_page_post( 'Courses & Project Showcase' );
		$template = get_stylesheet_directory() . '/courses-showcase.php';
		if ( ! file_exists( $template ) ) {
			wp_die( 'Courses showcase template not found. Ensure courses-showcase.php is in the child theme folder on this server.', 'Template missing', array( 'response' => 500 ) );
		}
		include $template;
		exit;
	}
}
add_action( 'template_redirect', 'marity_child_courses_showcase_template_redirect', 5 );

/**
 * Courses & Project Showcase – 3 design options preview (standalone page, no theme header)
 * Visit: /courses-showcase-designs/?design=1 or ?design=2 or ?design=3
 */
function marity_child_courses_designs_rewrite() {
	add_rewrite_rule( '^courses-showcase-designs/?$', 'index.php?aimes_courses_designs=1', 'top' );
}
add_action( 'init', 'marity_child_courses_designs_rewrite' );

function marity_child_courses_designs_query_var( $vars ) {
	$vars[] = 'aimes_courses_designs';
	return $vars;
}
add_filter( 'query_vars', 'marity_child_courses_designs_query_var' );

function marity_child_courses_designs_template_redirect() {
	if ( get_query_var( 'aimes_courses_designs' ) || strpos( $_SERVER['REQUEST_URI'], '/courses-showcase-designs' ) !== false ) {
		include( get_stylesheet_directory() . '/courses-showcase-designs.php' );
		exit;
	}
}
add_action( 'template_redirect', 'marity_child_courses_designs_template_redirect', 5 );

/**
 * Remove unnecessary admin menu items
 * Uncomment the lines for items you want to hide. Run only for non-admin users if desired.
 */
function marity_child_remove_admin_menu_items() {
	// Uncomment to hide:
	// remove_menu_page( 'edit.php' );                    // Posts
	// remove_menu_page( 'upload.php' );                  // Media
	// remove_menu_page( 'edit.php?post_type=page' );     // Pages
	// remove_menu_page( 'edit-comments.php' );           // Comments
	// remove_menu_page( 'index.php' );                   // Dashboard (not recommended)
	// Marity theme CPTs:
	remove_menu_page( 'edit.php?post_type=marity-client' );
	remove_menu_page( 'edit.php?post_type=marity-masonry-gallery' );
	// remove_menu_page( 'edit.php?post_type=portfolio-item' );  // Marity Portfolio (research)
	// remove_menu_page( 'edit.php?post_type=team' );            // Marity Team
	remove_menu_page( 'edit.php?post_type=marity-testimonial' );
	// Child theme / plugins:
	// remove_menu_page( 'edit.php?post_type=aimes_announcement' );  // Announcements
	// remove_menu_page( 'edit.php?post_type=aimes_talk' );          // Talks
	// remove_menu_page( 'wpcf7' );                                  // Contact Form 7
	// remove_menu_page( 'edit.php?post_type=aimes_course' );        // Courses
	// remove_menu_page( 'edit.php?post_type=aimes_project' );       // Projects
	remove_menu_page( 'edit.php?post_type=ditty' );               // Ditty
	remove_menu_page( 'edit.php?post_type=feedback' );            // Feedback
	// WooCommerce:
	// remove_menu_page( 'woocommerce' );
	// remove_menu_page( 'edit.php?post_type=product' );
}
add_action( 'admin_menu', 'marity_child_remove_admin_menu_items', 999 );
