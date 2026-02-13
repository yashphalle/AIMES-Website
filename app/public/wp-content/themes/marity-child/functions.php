<?php

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
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( isset( $_POST['aimes_announcement_link'] ) ) {
			update_post_meta( $post_id, '_aimes_announcement_link', esc_url_raw( $_POST['aimes_announcement_link'] ) );
		}
	}
	add_action( 'save_post_aimes_announcement', 'marity_child_announcement_save' );
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
		$detail_url = ! empty( $items[0]['url'] ) ? $items[0]['url'] : '#';
		$spans = array();
		foreach ( array_merge( $items, $items ) as $i ) {
			$spans[] = '<span>' . esc_html( $i['text'] ) . ' · </span>';
		}
		$ticker_html = implode( '', $spans );
		ob_start();
		?>
		<div class="aimes-news-band">
			<div class="aimes-news-band-inner">
				<span class="aimes-news-band-badge">NEW</span>
				<div class="aimes-news-band-icon">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
					</svg>
				</div>
				<div class="aimes-news-band-ticker-wrap">
					<div class="aimes-news-band-ticker">
						<?php echo $ticker_html; ?>
					</div>
				</div>
				<a href="<?php echo esc_url( $detail_url ); ?>" class="aimes-news-band-cta">Details</a>
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
