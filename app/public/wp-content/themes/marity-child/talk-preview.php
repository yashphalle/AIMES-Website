<?php
/**
 * Talks Page Template
 * Integrated with theme - uses header/footer
 */

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
