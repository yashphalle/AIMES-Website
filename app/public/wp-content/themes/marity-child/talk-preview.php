<?php
/**
 * Talks Page Template
 * Integrated with theme - uses header/footer
 * Handles both /talks/ listing and /talks/{slug}/ detail pages
 */

// --- Single talk detail page ---
// Use registered WP query var first (most reliable), fall back to raw $_GET
$talk_id = absint( get_query_var( 'talk_id' ) );
if ( ! $talk_id ) {
	$talk_id = isset( $_GET['talk_id'] ) ? absint( $_GET['talk_id'] ) : 0;
}

if ( $talk_id ) {
	$t = get_post( $talk_id );

	if ( ! $t || $t->post_type !== 'aimes_talk' || $t->post_status !== 'publish' ) {
		wp_redirect( home_url( '/talks/' ), 302 );
		exit;
	}
	$date_raw = get_post_meta( $t->ID, '_aimes_talk_date', true );
	$ts       = $date_raw ? strtotime( $date_raw ) : false;
	$thumb    = get_post_meta( $t->ID, '_aimes_talk_thumb', true );
	if ( ! $thumb ) {
		$thumb = get_the_post_thumbnail_url( $t->ID, 'full' ) ?: '';
	}
	$now         = current_time( 'Y-m-d\TH:i' );
	$is_upcoming = $date_raw && $date_raw >= $now;
	$talk = array(
		'title'       => get_the_title( $t->ID ),
		'speaker'     => get_post_meta( $t->ID, '_aimes_talk_speaker', true ),
		'speaker_bio' => get_post_meta( $t->ID, '_aimes_talk_speaker_bio', true ),
		'location'    => get_post_meta( $t->ID, '_aimes_talk_location', true ),
		'date_fmt'    => $ts ? date_i18n( 'F j, Y', $ts ) : '',
		'time_fmt'    => $ts ? date_i18n( 'g:i A', $ts ) : '',
		'month'       => $ts ? date_i18n( 'M', $ts ) : '',
		'day'         => $ts ? date_i18n( 'j', $ts ) : '',
		'year'        => $ts ? date_i18n( 'Y', $ts ) : '',
		'thumb'       => $thumb,
		'register'    => get_post_meta( $t->ID, '_aimes_talk_register', true ),
		'virtual_url' => get_post_meta( $t->ID, '_aimes_talk_virtual_url', true ),
		'recording'   => get_post_meta( $t->ID, '_aimes_talk_recording', true ),
		'content'     => $t->post_content,
	);

	get_header();
	?>
	<div class="aimes-talk-detail">
		<div class="aimes-talk-detail-inner">

			<a href="<?php echo esc_url( home_url( '/talks/' ) ); ?>" class="aimes-talk-detail-back">← Back to Talks</a>

			<div class="aimes-talk-detail-hero">
				<?php if ( $talk['thumb'] ) : ?>
				<div class="aimes-talk-detail-thumb">
					<img src="<?php echo esc_url( $talk['thumb'] ); ?>" alt="<?php echo esc_attr( $talk['title'] ); ?>">
				</div>
				<?php endif; ?>

				<div class="aimes-talk-detail-main">
					<?php if ( $is_upcoming ) : ?>
						<span class="aimes-talk-detail-badge aimes-talk-detail-badge--upcoming">Upcoming</span>
					<?php else : ?>
						<span class="aimes-talk-detail-badge aimes-talk-detail-badge--past">Past Talk</span>
					<?php endif; ?>

					<h1 class="aimes-talk-detail-title"><?php echo esc_html( $talk['title'] ); ?></h1>

					<?php if ( $talk['speaker'] ) : ?>
						<div class="aimes-talk-detail-speaker"><?php echo esc_html( $talk['speaker'] ); ?></div>
					<?php endif; ?>
					<?php if ( $talk['speaker_bio'] ) : ?>
						<div class="aimes-talk-detail-bio"><?php echo esc_html( $talk['speaker_bio'] ); ?></div>
					<?php endif; ?>

					<div class="aimes-talk-detail-meta">
						<?php if ( $talk['date_fmt'] ) : ?>
							<div class="aimes-talk-detail-meta-item">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
								<?php echo esc_html( $talk['date_fmt'] ); ?>
								<?php if ( $talk['time_fmt'] ) : ?>&nbsp;·&nbsp;<?php echo esc_html( $talk['time_fmt'] ); ?><?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( $talk['location'] ) : ?>
							<div class="aimes-talk-detail-meta-item">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
								<?php echo esc_html( $talk['location'] ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $is_upcoming && ( $talk['register'] || $talk['virtual_url'] ) ) : ?>
					<div class="aimes-talk-detail-actions">
						<?php if ( $talk['register'] ) : ?>
							<a href="<?php echo esc_url( $talk['register'] ); ?>" class="aimes-talks-btn-primary">Register Now</a>
						<?php endif; ?>
						<?php if ( $talk['virtual_url'] ) : ?>
							<a href="<?php echo esc_url( $talk['virtual_url'] ); ?>" class="aimes-talks-btn-outline">Join Virtual</a>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( trim( $talk['content'] ) ) : ?>
			<div class="aimes-talk-detail-content">
				<?php echo wp_kses_post( wpautop( $talk['content'] ) ); ?>
			</div>
			<?php endif; ?>

			<!-- Recording -->
			<div class="aimes-talk-detail-recording">
				<h3 class="aimes-talk-detail-recording-label">Recording</h3>
				<?php if ( ! empty( $talk['recording'] ) ) : ?>
					<a href="<?php echo esc_url( $talk['recording'] ); ?>" class="aimes-talk-detail-recording-btn" target="_blank" rel="noopener">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
						Watch Recording
					</a>
				<?php else : ?>
					<span class="aimes-talk-detail-recording-unavailable">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
						Recording Unavailable
					</span>
				<?php endif; ?>
			</div>

		</div>
	</div>
	<?php
	get_footer();
	exit;
}

// --- Talks listing page ---

// Query talks from the CPT
$now   = current_time( 'Y-m-d\TH:i' );
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
	$date_raw    = get_post_meta( $t->ID, '_aimes_talk_date', true );
	$ts          = $date_raw ? strtotime( $date_raw ) : false;
	$thumb       = get_post_meta( $t->ID, '_aimes_talk_thumb', true );
	if ( ! $thumb ) {
		$thumb = get_the_post_thumbnail_url( $t->ID, 'medium_large' ) ?: get_the_post_thumbnail_url( $t->ID, 'medium' ) ?: '';
	}
	$item = array(
		'id'          => $t->ID,
		'permalink'   => add_query_arg( 'talk_id', $t->ID, home_url( '/talks/' ) ),
		'title'       => get_the_title( $t->ID ),
		'speaker'     => get_post_meta( $t->ID, '_aimes_talk_speaker', true ),
		'speaker_bio' => get_post_meta( $t->ID, '_aimes_talk_speaker_bio', true ),
		'location'    => get_post_meta( $t->ID, '_aimes_talk_location', true ),
		'date_raw'    => $date_raw,
		'date_fmt'    => $ts ? date_i18n( 'F j, Y', $ts ) : '',
		'time_fmt'    => $ts ? date_i18n( 'g:i A', $ts ) : '',
		'month'       => $ts ? date_i18n( 'M', $ts ) : '',
		'day'         => $ts ? date_i18n( 'j', $ts ) : '',
		'year'        => $ts ? date_i18n( 'Y', $ts ) : '',
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
// Past: newest first
$past = array_reverse( $past );

// Demo data if no talks exist yet
if ( empty( $upcoming ) && empty( $past ) ) {
	$demo = array(
		array(
			'id' => 0, 'permalink' => '#',
			'title' => 'AI Ethics in Modern Research Practices', 'speaker' => 'Dr. ABC Smith',
			'speaker_bio' => 'Professor of AI Ethics, University', 'location' => 'Room 302, Main Hall',
			'date_fmt' => 'March 15, 2026', 'time_fmt' => '2:00 PM', 'month' => 'Mar', 'day' => '15', 'year' => '2026',
			'thumb' => '', 'register' => '#', 'virtual_url' => '#',
			'content' => 'Exploring the ethical implications of AI systems in academic research, including bias detection, fairness metrics, and responsible deployment strategies.',
		),
		array(
			'id' => 0, 'permalink' => '#',
			'title' => 'Machine Learning for Climate Modeling', 'speaker' => 'Prof. XYZ Johnson',
			'speaker_bio' => 'Climate Science Researcher', 'location' => 'Virtual (Zoom)',
			'date_fmt' => 'March 28, 2026', 'time_fmt' => '3:30 PM', 'month' => 'Mar', 'day' => '28', 'year' => '2026',
			'thumb' => '', 'register' => '#', 'virtual_url' => '#',
			'content' => 'How neural networks and ensemble methods are revolutionizing climate prediction models with unprecedented accuracy.',
		),
		array(
			'id' => 0, 'permalink' => '#',
			'title' => 'Natural Language Processing in Healthcare', 'speaker' => 'Dr. PQR Williams',
			'location' => 'Auditorium B',
			'date_fmt' => 'January 5, 2026', 'time_fmt' => '1:00 PM', 'month' => 'Jan', 'day' => '5', 'year' => '2026',
			'thumb' => '', 'register' => '', 'virtual_url' => '',
			'content' => 'Applications of transformer models for clinical note analysis, diagnosis support, and patient outcome prediction.',
		),
		array(
			'id' => 0, 'permalink' => '#',
			'title' => 'Responsible AI: From Theory to Practice', 'speaker' => 'Dr. LMN Davis',
			'location' => 'Room 101',
			'date_fmt' => 'December 12, 2025', 'time_fmt' => '11:00 AM', 'month' => 'Dec', 'day' => '12', 'year' => '2025',
			'thumb' => '', 'register' => '', 'virtual_url' => '',
			'content' => 'Bridging the gap between ethical AI frameworks and real-world implementation in industry and academia.',
		),
	);
	$upcoming = array_slice( $demo, 0, 2 );
	$past     = array_slice( $demo, 2 );
}

get_header();
?>

<div class="aimes-talks-page">
	<!-- Page Title Section -->
	<div class="aimes-talks-page-header">
		<div class="aimes-talks-page-header-inner">
			<h1>Talks & Seminars</h1>
			<p>Explore our upcoming and past research talks, guest lectures, and seminars.</p>
		</div>
	</div>

	<div class="aimes-talks-page-content">
		<?php if ( ! empty( $upcoming ) ) : ?>
		<!-- Featured Upcoming Talk -->
		<div class="aimes-talks-section">
			<div class="aimes-talks-section-label">Featured Upcoming Talk</div>
			
			<?php $featured = $upcoming[0]; ?>
			<div class="aimes-talks-featured">
				<div class="aimes-talks-featured-visual">
					<div class="aimes-talks-featured-photo">
						<?php if ( $featured['thumb'] ) : ?>
							<img src="<?php echo esc_url( $featured['thumb'] ); ?>" alt="">
						<?php else : ?>
							<span><?php echo mb_substr( $featured['speaker'] ?: '?', 0, 1 ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="aimes-talks-featured-body">
					<span class="aimes-talks-featured-badge">Next Talk</span>
					<h2 class="aimes-talks-featured-title"><?php echo esc_html( $featured['title'] ); ?></h2>
					<?php if ( $featured['speaker'] ) : ?>
						<div class="aimes-talks-featured-speaker"><?php echo esc_html( $featured['speaker'] ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $featured['speaker_bio'] ) ) : ?>
						<div class="aimes-talks-featured-bio"><?php echo esc_html( $featured['speaker_bio'] ); ?></div>
					<?php endif; ?>
					<div class="aimes-talks-featured-meta">
						<?php if ( $featured['date_fmt'] ) : ?><span class="aimes-talks-featured-date-inline"><?php echo esc_html( $featured['month'] ); ?> <?php echo esc_html( $featured['day'] ); ?>, <?php echo esc_html( $featured['year'] ); ?></span><?php endif; ?>
						<?php if ( $featured['time_fmt'] ) : ?><span><?php echo esc_html( $featured['time_fmt'] ); ?></span><?php endif; ?>
						<?php if ( $featured['location'] ) : ?><span><?php echo esc_html( $featured['location'] ); ?></span><?php endif; ?>
					</div>
					<?php if ( $featured['content'] ) : ?>
						<div class="aimes-talks-featured-desc"><?php echo esc_html( $featured['content'] ); ?></div>
					<?php endif; ?>
					<div class="aimes-talks-featured-actions">
						<?php if ( $featured['register'] ) : ?>
							<a href="<?php echo esc_url( $featured['register'] ); ?>" class="aimes-talks-btn-primary">Register Now</a>
						<?php endif; ?>
						<?php if ( $featured['virtual_url'] ) : ?>
							<a href="<?php echo esc_url( $featured['virtual_url'] ); ?>" class="aimes-talks-btn-outline">Join Virtual</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if ( count( $upcoming ) > 1 ) : ?>
			<div class="aimes-talks-section-label" style="margin-top:50px;">More Upcoming</div>
			<div class="aimes-talks-grid">
				<?php foreach ( array_slice( $upcoming, 1 ) as $t ) : ?>
				<article class="aimes-talks-card aimes-talks-card-upcoming">
					<div class="aimes-talks-card-header">
						<div class="aimes-talks-card-date">
							<span class="atc-month"><?php echo esc_html( $t['month'] ); ?></span>
							<span class="atc-day"><?php echo esc_html( $t['day'] ); ?></span>
						</div>
						<h3 class="aimes-talks-card-title"><?php echo esc_html( $t['title'] ); ?></h3>
					</div>
					<div class="aimes-talks-card-body">
						<?php if ( $t['speaker'] ) : ?>
							<div class="aimes-talks-card-speaker"><?php echo esc_html( $t['speaker'] ); ?></div>
						<?php endif; ?>
						<div class="aimes-talks-card-meta">
							<?php if ( $t['time_fmt'] ) : ?><span><?php echo esc_html( $t['time_fmt'] ); ?></span><?php endif; ?>
							<?php if ( $t['location'] ) : ?><span><?php echo esc_html( $t['location'] ); ?></span><?php endif; ?>
						</div>
						<?php if ( $t['content'] ) : ?>
							<p class="aimes-talks-card-desc"><?php echo esc_html( $t['content'] ); ?></p>
						<?php endif; ?>
						<?php if ( $t['register'] ) : ?>
						<div class="aimes-talks-card-actions">
							<a href="<?php echo esc_url( $t['register'] ); ?>" class="aimes-talks-card-link">Register</a>
						</div>
						<?php endif; ?>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $past ) ) : ?>
		<!-- Featured Talks – same card grid as homepage -->
		<div class="aimes-talks-section aimes-talks-page-featured">
			<h3 class="aimes-talks-section-title">Featured Talks</h3>
			<div class="aimes-talks-grid aimes-talks-cards">
				<?php foreach ( $past as $talk ) : ?>
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
						<a href="<?php echo esc_url( $talk['permalink'] ); ?>" class="aimes-talk-btn-view">View more</a>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( empty( $upcoming ) && empty( $past ) ) : ?>
			<div class="aimes-talks-empty">No talks scheduled yet. Check back soon!</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
