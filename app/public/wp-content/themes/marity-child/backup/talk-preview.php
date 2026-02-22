<?php
/**
 * Talk Preview – Design Options 3 & 5
 * Visit /talk-preview/?design=3 or /talk-preview/?design=5
 */

$design = isset( $_GET['design'] ) ? intval( $_GET['design'] ) : 3;

// Query talks from the new CPT
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
		$thumb = get_the_post_thumbnail_url( $t->ID, 'medium' ) ?: '';
	}
	$item = array(
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
		'content'     => wp_trim_words( strip_tags( $t->post_content ), 40, '…' ),
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
			'title' => 'AI Ethics in Modern Research Practices', 'speaker' => 'Dr. Sarah Chen',
			'speaker_bio' => 'Professor of AI Ethics, MIT', 'location' => 'Room 302, Ryder Hall',
			'date_fmt' => 'January 15, 2025', 'time_fmt' => '2:00 PM', 'month' => 'Jan', 'day' => '15', 'year' => '2025',
			'thumb' => '', 'register' => '#', 'virtual_url' => '#',
			'content' => 'Exploring the ethical implications of AI systems in academic research, including bias detection, fairness metrics, and responsible deployment strategies.',
		),
		array(
			'title' => 'Machine Learning for Climate Modeling', 'speaker' => 'Prof. James Rivera',
			'speaker_bio' => 'Climate Science, Stanford', 'location' => 'Virtual (Zoom)',
			'date_fmt' => 'January 28, 2025', 'time_fmt' => '3:30 PM', 'month' => 'Jan', 'day' => '28', 'year' => '2025',
			'thumb' => '', 'register' => '#', 'virtual_url' => '#',
			'content' => 'How neural networks and ensemble methods are revolutionizing climate prediction models with unprecedented accuracy.',
		),
		array(
			'title' => 'Natural Language Processing in Healthcare', 'speaker' => 'Dr. Maria Lopez',
			'speaker_bio' => 'NLP Researcher, Johns Hopkins', 'location' => 'Auditorium B',
			'date_fmt' => 'December 5, 2024', 'time_fmt' => '1:00 PM', 'month' => 'Dec', 'day' => '5', 'year' => '2024',
			'thumb' => '', 'register' => '', 'virtual_url' => '',
			'content' => 'Applications of transformer models for clinical note analysis, diagnosis support, and patient outcome prediction.',
		),
		array(
			'title' => 'Responsible AI: From Theory to Practice', 'speaker' => 'Dr. Alan Turing III',
			'speaker_bio' => 'AI Policy, Oxford', 'location' => 'Room 101',
			'date_fmt' => 'November 12, 2024', 'time_fmt' => '11:00 AM', 'month' => 'Nov', 'day' => '12', 'year' => '2024',
			'thumb' => '', 'register' => '', 'virtual_url' => '',
			'content' => 'Bridging the gap between ethical AI frameworks and real-world implementation in industry and academia.',
		),
	);
	$upcoming = array_slice( $demo, 0, 2 );
	$past     = array_slice( $demo, 2 );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Talk Preview – Design <?php echo $design; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* === SHARED RESET === */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Sora', sans-serif; background: #f5f6f8; color: #333; line-height: 1.6; }
a { text-decoration: none; color: inherit; }
img { max-width: 100%; display: block; }

/* === DESIGN SWITCHER === */
.design-switcher {
	position: fixed; top: 20px; right: 20px; z-index: 9999;
	background: #0e202a; padding: 14px 20px; border-radius: 12px;
	box-shadow: 0 8px 32px rgba(0,0,0,0.3); display: flex; gap: 8px; align-items: center;
}
.design-switcher span { color: #7ec8e3; font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-right: 8px; }
.design-switcher a {
	display: inline-block; padding: 8px 18px; border-radius: 6px; font-size: 12px; font-weight: 600;
	transition: all 0.2s; color: #fff;
}
.design-switcher a.active { background: #1e3a5f; }
.design-switcher a:not(.active) { background: rgba(255,255,255,0.1); }
.design-switcher a:hover:not(.active) { background: rgba(255,255,255,0.2); }

/* === PAGE HEADER (shared) === */
.talk-page-header {
	background: linear-gradient(135deg, #0e202a 0%, #1e3a5f 60%, #2d5a8e 100%);
	padding: 80px 40px 60px; text-align: center; color: #fff; position: relative; overflow: hidden;
}
.talk-page-header::before {
	content: ''; position: absolute; inset: 0;
	background: radial-gradient(circle at 20% 80%, rgba(126,200,227,0.1) 0%, transparent 50%),
	            radial-gradient(circle at 80% 20%, rgba(45,90,142,0.15) 0%, transparent 50%);
}
.talk-page-header * { position: relative; z-index: 1; }
.talk-page-header h1 { font-size: 36px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px; }
.talk-page-header p { font-size: 16px; color: rgba(255,255,255,0.7); max-width: 500px; margin: 0 auto; }
.talk-section-label {
	font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
	color: #1e3a5f; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;
}
.talk-section-label::after { content: ''; flex: 1; height: 1px; background: #dde1e6; }

<?php if ( $design === 3 ) : ?>
/* ================================================
   OPTION 3 – TIMELINE LAYOUT
   ================================================ */
.timeline-wrap { max-width: 900px; margin: 0 auto; padding: 60px 24px; }
.timeline { position: relative; padding-left: 60px; }
.timeline::before {
	content: ''; position: absolute; left: 24px; top: 0; bottom: 0;
	width: 2px; background: linear-gradient(to bottom, #1e3a5f, #dde1e6);
}
.timeline-item { position: relative; margin-bottom: 48px; }
.timeline-item:last-child { margin-bottom: 0; }
.timeline-dot {
	position: absolute; left: -44px; top: 8px; width: 14px; height: 14px;
	border-radius: 50%; border: 3px solid #1e3a5f; background: #fff; z-index: 2;
}
.timeline-item.is-upcoming .timeline-dot {
	background: #1e3a5f; box-shadow: 0 0 0 4px rgba(30,58,95,0.2);
}
.timeline-date-marker {
	position: absolute; left: -120px; top: 4px; width: 64px; text-align: center;
}
.timeline-date-marker .tdm-month {
	font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #1e3a5f;
}
.timeline-date-marker .tdm-day {
	font-size: 22px; font-weight: 800; color: #0e202a; line-height: 1;
}
.timeline-card {
	background: #fff; border-radius: 14px; padding: 28px 30px;
	box-shadow: 0 2px 12px rgba(0,0,0,0.05); transition: transform 0.3s, box-shadow 0.3s;
	border-left: 4px solid transparent;
}
.timeline-item.is-upcoming .timeline-card { border-left-color: #1e3a5f; }
.timeline-card:hover { transform: translateX(6px); box-shadow: 0 8px 28px rgba(0,0,0,0.08); }
.timeline-card-top { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 14px; }
.timeline-speaker-img {
	width: 56px; height: 56px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
	background: linear-gradient(135deg, #1e3a5f, #2d5a8e); display: flex; align-items: center;
	justify-content: center; color: #fff; font-size: 20px; font-weight: 700;
}
.timeline-speaker-img img { width: 100%; height: 100%; object-fit: cover; }
.timeline-card-info { flex: 1; }
.timeline-card-badge {
	display: inline-block; font-size: 9px; font-weight: 700; letter-spacing: 1.5px;
	text-transform: uppercase; padding: 3px 10px; border-radius: 4px; margin-bottom: 8px;
}
.timeline-card-badge.upcoming { background: #0e202a; color: #7ec8e3; }
.timeline-card-badge.past { background: #eef0f2; color: #888; }
.timeline-card-title { font-size: 18px; font-weight: 700; color: #0e202a; line-height: 1.35; margin-bottom: 6px; }
.timeline-card-speaker { font-size: 13px; color: #1e3a5f; font-weight: 600; }
.timeline-card-speaker-bio { font-size: 12px; color: #999; }
.timeline-card-meta {
	display: flex; flex-wrap: wrap; gap: 16px; font-size: 12px; color: #777;
	margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #f0f0f0;
}
.timeline-card-meta span { display: flex; align-items: center; gap: 5px; }
.timeline-card-desc { font-size: 13px; color: #555; line-height: 1.65; margin-bottom: 16px; }
.timeline-card-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.timeline-btn-primary {
	display: inline-flex; align-items: center; gap: 6px;
	background: linear-gradient(135deg, #0e202a, #1e3a5f); color: #fff;
	padding: 9px 22px; border-radius: 8px; font-size: 12px; font-weight: 600;
	letter-spacing: 0.5px; transition: transform 0.2s, box-shadow 0.2s;
}
.timeline-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(14,32,42,0.25); color: #fff; }
.timeline-btn-secondary {
	display: inline-flex; align-items: center; gap: 6px;
	border: 1.5px solid #1e3a5f; color: #1e3a5f;
	padding: 8px 20px; border-radius: 8px; font-size: 12px; font-weight: 600;
	transition: all 0.2s;
}
.timeline-btn-secondary:hover { background: #1e3a5f; color: #fff; }
.timeline-empty {
	text-align: center; padding: 60px 20px; color: #999; font-size: 14px;
}
@media (max-width: 700px) {
	.timeline { padding-left: 30px; }
	.timeline::before { left: 10px; }
	.timeline-dot { left: -26px; }
	.timeline-date-marker { display: none; }
	.timeline-card { padding: 20px; }
}

<?php elseif ( $design === 5 ) : ?>
/* ================================================
   OPTION 5 – MAGAZINE / EDITORIAL LAYOUT
   ================================================ */
.magazine-wrap { max-width: 1100px; margin: 0 auto; padding: 60px 24px; }

/* Featured Hero Card */
.mag-hero {
	display: grid; grid-template-columns: 1fr 1fr; gap: 0; border-radius: 18px;
	overflow: hidden; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,0.08);
	margin-bottom: 60px; min-height: 380px;
}
.mag-hero-visual {
	background: linear-gradient(135deg, #0e202a 0%, #1e3a5f 100%);
	display: flex; flex-direction: column; justify-content: center; align-items: center;
	padding: 50px 40px; position: relative; overflow: hidden;
}
.mag-hero-visual::before {
	content: ''; position: absolute; inset: 0;
	background: radial-gradient(circle at 30% 70%, rgba(126,200,227,0.12) 0%, transparent 60%);
}
.mag-hero-visual * { position: relative; z-index: 1; }
.mag-hero-date-block { text-align: center; color: #fff; margin-bottom: 24px; }
.mag-hero-date-block .mhd-month {
	font-size: 14px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: #7ec8e3;
}
.mag-hero-date-block .mhd-day { font-size: 64px; font-weight: 800; line-height: 1; }
.mag-hero-date-block .mhd-year { font-size: 13px; color: rgba(255,255,255,0.5); font-weight: 500; }
.mag-hero-speaker-photo {
	width: 100px; height: 100px; border-radius: 50%; overflow: hidden;
	border: 4px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1);
	display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.4); font-size: 36px;
}
.mag-hero-speaker-photo img { width: 100%; height: 100%; object-fit: cover; }
.mag-hero-body { padding: 50px 44px; display: flex; flex-direction: column; justify-content: center; }
.mag-hero-label {
	display: inline-block; font-size: 10px; font-weight: 700; letter-spacing: 2px;
	text-transform: uppercase; background: #0e202a; color: #7ec8e3;
	padding: 5px 14px; border-radius: 5px; margin-bottom: 18px; width: fit-content;
}
.mag-hero-title { font-size: 26px; font-weight: 800; color: #0e202a; line-height: 1.3; margin-bottom: 12px; }
.mag-hero-speaker-name { font-size: 15px; font-weight: 600; color: #1e3a5f; margin-bottom: 4px; }
.mag-hero-speaker-bio { font-size: 12px; color: #999; margin-bottom: 16px; }
.mag-hero-meta { display: flex; gap: 20px; font-size: 12px; color: #777; margin-bottom: 20px; flex-wrap: wrap; }
.mag-hero-meta span { display: flex; align-items: center; gap: 5px; }
.mag-hero-desc { font-size: 14px; color: #555; line-height: 1.7; margin-bottom: 24px; }
.mag-hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.mag-btn-primary {
	display: inline-flex; align-items: center; gap: 8px;
	background: linear-gradient(135deg, #0e202a, #1e3a5f); color: #fff;
	padding: 12px 30px; border-radius: 10px; font-size: 13px; font-weight: 700;
	letter-spacing: 0.5px; transition: transform 0.2s, box-shadow 0.2s;
}
.mag-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(14,32,42,0.3); color: #fff; }
.mag-btn-outline {
	display: inline-flex; align-items: center; gap: 8px;
	border: 2px solid #1e3a5f; color: #1e3a5f;
	padding: 10px 26px; border-radius: 10px; font-size: 13px; font-weight: 700;
	transition: all 0.2s;
}
.mag-btn-outline:hover { background: #1e3a5f; color: #fff; }

/* Past Talks Grid */
.mag-past-grid {
	display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;
}
.mag-past-card {
	background: #fff; border-radius: 14px; overflow: hidden;
	box-shadow: 0 2px 10px rgba(0,0,0,0.04); transition: transform 0.3s, box-shadow 0.3s;
	display: flex; flex-direction: column;
}
.mag-past-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }
.mag-past-card-top {
	background: linear-gradient(135deg, #1a2f42, #1e3a5f); padding: 24px;
	display: flex; align-items: center; gap: 16px; min-height: 90px;
}
.mag-past-date-sm { text-align: center; color: #fff; line-height: 1.1; flex-shrink: 0; width: 50px; }
.mag-past-date-sm .mpd-month { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #7ec8e3; }
.mag-past-date-sm .mpd-day { font-size: 26px; font-weight: 800; }
.mag-past-card-top h3 { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.35; }
.mag-past-card-body { padding: 22px 24px 26px; flex: 1; display: flex; flex-direction: column; }
.mag-past-speaker { font-size: 13px; font-weight: 600; color: #1e3a5f; margin-bottom: 4px; }
.mag-past-meta { font-size: 11px; color: #999; margin-bottom: 12px; display: flex; gap: 14px; flex-wrap: wrap; }
.mag-past-desc { font-size: 13px; color: #666; line-height: 1.6; flex: 1; margin-bottom: 16px;
	display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.mag-past-link {
	font-size: 12px; font-weight: 600; color: #1e3a5f; letter-spacing: 0.5px;
	text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; margin-top: auto;
}
.mag-past-link:hover { text-decoration: underline; }
.mag-empty { text-align: center; padding: 60px 20px; color: #999; font-size: 14px; }

@media (max-width: 768px) {
	.mag-hero { grid-template-columns: 1fr; }
	.mag-hero-visual { min-height: 220px; padding: 30px; }
	.mag-hero-body { padding: 28px 24px; }
	.mag-hero-title { font-size: 22px; }
	.mag-past-grid { grid-template-columns: 1fr; }
}
<?php endif; ?>
</style>
</head>
<body>

<!-- Design Switcher -->
<div class="design-switcher">
	<span>Design</span>
	<a href="?design=3" class="<?php echo $design === 3 ? 'active' : ''; ?>">Option 3 – Timeline</a>
	<a href="?design=5" class="<?php echo $design === 5 ? 'active' : ''; ?>">Option 5 – Magazine</a>
</div>

<!-- Page Header -->
<div class="talk-page-header">
	<h1>Talks & Seminars</h1>
	<p>Explore our upcoming and past research talks, guest lectures, and seminars.</p>
</div>

<?php if ( $design === 3 ) : ?>
<!-- ============================
     OPTION 3 – TIMELINE
     ============================ -->
<div class="timeline-wrap">

	<?php if ( ! empty( $upcoming ) ) : ?>
	<div class="talk-section-label">Upcoming Talks</div>
	<div class="timeline">
		<?php foreach ( $upcoming as $t ) : ?>
		<div class="timeline-item is-upcoming">
			<div class="timeline-dot"></div>
			<div class="timeline-date-marker">
				<div class="tdm-month"><?php echo esc_html( $t['month'] ); ?></div>
				<div class="tdm-day"><?php echo esc_html( $t['day'] ); ?></div>
			</div>
			<div class="timeline-card">
				<div class="timeline-card-top">
					<div class="timeline-speaker-img">
						<?php if ( $t['thumb'] ) : ?>
							<img src="<?php echo esc_url( $t['thumb'] ); ?>" alt="">
						<?php else : ?>
							<?php echo mb_substr( $t['speaker'] ?: $t['title'], 0, 1 ); ?>
						<?php endif; ?>
					</div>
					<div class="timeline-card-info">
						<span class="timeline-card-badge upcoming">Upcoming</span>
						<div class="timeline-card-title"><?php echo esc_html( $t['title'] ); ?></div>
						<?php if ( $t['speaker'] ) : ?>
							<div class="timeline-card-speaker"><?php echo esc_html( $t['speaker'] ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $t['speaker_bio'] ) ) : ?>
							<div class="timeline-card-speaker-bio"><?php echo esc_html( $t['speaker_bio'] ); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="timeline-card-meta">
					<?php if ( $t['date_fmt'] ) : ?><span>📅 <?php echo esc_html( $t['date_fmt'] ); ?></span><?php endif; ?>
					<?php if ( $t['time_fmt'] ) : ?><span>🕐 <?php echo esc_html( $t['time_fmt'] ); ?></span><?php endif; ?>
					<?php if ( $t['location'] ) : ?><span>📍 <?php echo esc_html( $t['location'] ); ?></span><?php endif; ?>
				</div>
				<?php if ( $t['content'] ) : ?>
					<div class="timeline-card-desc"><?php echo esc_html( $t['content'] ); ?></div>
				<?php endif; ?>
				<div class="timeline-card-actions">
					<?php if ( $t['register'] ) : ?>
						<a href="<?php echo esc_url( $t['register'] ); ?>" class="timeline-btn-primary">Register →</a>
					<?php endif; ?>
					<?php if ( $t['virtual_url'] ) : ?>
						<a href="<?php echo esc_url( $t['virtual_url'] ); ?>" class="timeline-btn-secondary">Join Virtual</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<?php if ( ! empty( $past ) ) : ?>
	<div class="talk-section-label" style="margin-top:48px;">Past Talks</div>
	<div class="timeline">
		<?php foreach ( $past as $t ) : ?>
		<div class="timeline-item is-past">
			<div class="timeline-dot"></div>
			<div class="timeline-date-marker">
				<div class="tdm-month"><?php echo esc_html( $t['month'] ); ?></div>
				<div class="tdm-day"><?php echo esc_html( $t['day'] ); ?></div>
			</div>
			<div class="timeline-card">
				<div class="timeline-card-top">
					<div class="timeline-speaker-img">
						<?php if ( $t['thumb'] ) : ?>
							<img src="<?php echo esc_url( $t['thumb'] ); ?>" alt="">
						<?php else : ?>
							<?php echo mb_substr( $t['speaker'] ?: $t['title'], 0, 1 ); ?>
						<?php endif; ?>
					</div>
					<div class="timeline-card-info">
						<span class="timeline-card-badge past">Past</span>
						<div class="timeline-card-title"><?php echo esc_html( $t['title'] ); ?></div>
						<?php if ( $t['speaker'] ) : ?>
							<div class="timeline-card-speaker"><?php echo esc_html( $t['speaker'] ); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="timeline-card-meta">
					<?php if ( $t['date_fmt'] ) : ?><span>📅 <?php echo esc_html( $t['date_fmt'] ); ?></span><?php endif; ?>
					<?php if ( $t['time_fmt'] ) : ?><span>🕐 <?php echo esc_html( $t['time_fmt'] ); ?></span><?php endif; ?>
					<?php if ( $t['location'] ) : ?><span>📍 <?php echo esc_html( $t['location'] ); ?></span><?php endif; ?>
				</div>
				<?php if ( $t['content'] ) : ?>
					<div class="timeline-card-desc"><?php echo esc_html( $t['content'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<?php if ( empty( $upcoming ) && empty( $past ) ) : ?>
		<div class="timeline-empty">No talks scheduled yet. Check back soon!</div>
	<?php endif; ?>
</div>

<?php elseif ( $design === 5 ) : ?>
<!-- ============================
     OPTION 5 – MAGAZINE
     ============================ -->
<div class="magazine-wrap">

	<?php if ( ! empty( $upcoming ) ) : ?>
	<div class="talk-section-label">Featured Upcoming Talk</div>

	<?php $featured = $upcoming[0]; ?>
	<div class="mag-hero">
		<div class="mag-hero-visual">
			<div class="mag-hero-date-block">
				<div class="mhd-month"><?php echo esc_html( $featured['month'] ); ?></div>
				<div class="mhd-day"><?php echo esc_html( $featured['day'] ); ?></div>
				<div class="mhd-year"><?php echo esc_html( $featured['year'] ); ?></div>
			</div>
			<div class="mag-hero-speaker-photo">
				<?php if ( $featured['thumb'] ) : ?>
					<img src="<?php echo esc_url( $featured['thumb'] ); ?>" alt="">
				<?php else : ?>
					<?php echo mb_substr( $featured['speaker'] ?: '?', 0, 1 ); ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="mag-hero-body">
			<span class="mag-hero-label">Next Talk</span>
			<h2 class="mag-hero-title"><?php echo esc_html( $featured['title'] ); ?></h2>
			<?php if ( $featured['speaker'] ) : ?>
				<div class="mag-hero-speaker-name"><?php echo esc_html( $featured['speaker'] ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $featured['speaker_bio'] ) ) : ?>
				<div class="mag-hero-speaker-bio"><?php echo esc_html( $featured['speaker_bio'] ); ?></div>
			<?php endif; ?>
			<div class="mag-hero-meta">
				<?php if ( $featured['date_fmt'] ) : ?><span>📅 <?php echo esc_html( $featured['date_fmt'] ); ?></span><?php endif; ?>
				<?php if ( $featured['time_fmt'] ) : ?><span>🕐 <?php echo esc_html( $featured['time_fmt'] ); ?></span><?php endif; ?>
				<?php if ( $featured['location'] ) : ?><span>📍 <?php echo esc_html( $featured['location'] ); ?></span><?php endif; ?>
			</div>
			<?php if ( $featured['content'] ) : ?>
				<div class="mag-hero-desc"><?php echo esc_html( $featured['content'] ); ?></div>
			<?php endif; ?>
			<div class="mag-hero-actions">
				<?php if ( $featured['register'] ) : ?>
					<a href="<?php echo esc_url( $featured['register'] ); ?>" class="mag-btn-primary">Register Now →</a>
				<?php endif; ?>
				<?php if ( $featured['virtual_url'] ) : ?>
					<a href="<?php echo esc_url( $featured['virtual_url'] ); ?>" class="mag-btn-outline">Join Virtual</a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if ( count( $upcoming ) > 1 ) : ?>
	<div class="talk-section-label">More Upcoming</div>
	<div class="mag-past-grid" style="margin-bottom:50px;">
		<?php foreach ( array_slice( $upcoming, 1 ) as $t ) : ?>
		<div class="mag-past-card">
			<div class="mag-past-card-top">
				<div class="mag-past-date-sm">
					<div class="mpd-month"><?php echo esc_html( $t['month'] ); ?></div>
					<div class="mpd-day"><?php echo esc_html( $t['day'] ); ?></div>
				</div>
				<h3><?php echo esc_html( $t['title'] ); ?></h3>
			</div>
			<div class="mag-past-card-body">
				<?php if ( $t['speaker'] ) : ?>
					<div class="mag-past-speaker"><?php echo esc_html( $t['speaker'] ); ?></div>
				<?php endif; ?>
				<div class="mag-past-meta">
					<?php if ( $t['time_fmt'] ) : ?><span>🕐 <?php echo esc_html( $t['time_fmt'] ); ?></span><?php endif; ?>
					<?php if ( $t['location'] ) : ?><span>📍 <?php echo esc_html( $t['location'] ); ?></span><?php endif; ?>
				</div>
				<?php if ( $t['content'] ) : ?>
					<div class="mag-past-desc"><?php echo esc_html( $t['content'] ); ?></div>
				<?php endif; ?>
				<div style="display:flex;gap:10px;margin-top:auto;">
					<?php if ( $t['register'] ) : ?>
						<a href="<?php echo esc_url( $t['register'] ); ?>" class="mag-past-link">Register →</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<?php endif; ?>

	<?php if ( ! empty( $past ) ) : ?>
	<div class="talk-section-label">Past Talks</div>
	<div class="mag-past-grid">
		<?php foreach ( $past as $t ) : ?>
		<div class="mag-past-card">
			<div class="mag-past-card-top">
				<div class="mag-past-date-sm">
					<div class="mpd-month"><?php echo esc_html( $t['month'] ); ?></div>
					<div class="mpd-day"><?php echo esc_html( $t['day'] ); ?></div>
				</div>
				<h3><?php echo esc_html( $t['title'] ); ?></h3>
			</div>
			<div class="mag-past-card-body">
				<?php if ( $t['speaker'] ) : ?>
					<div class="mag-past-speaker"><?php echo esc_html( $t['speaker'] ); ?></div>
				<?php endif; ?>
				<div class="mag-past-meta">
					<?php if ( $t['date_fmt'] ) : ?><span>📅 <?php echo esc_html( $t['date_fmt'] ); ?></span><?php endif; ?>
					<?php if ( $t['location'] ) : ?><span>📍 <?php echo esc_html( $t['location'] ); ?></span><?php endif; ?>
				</div>
				<?php if ( $t['content'] ) : ?>
					<div class="mag-past-desc"><?php echo esc_html( $t['content'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<?php if ( empty( $upcoming ) && empty( $past ) ) : ?>
		<div class="mag-empty">No talks scheduled yet. Check back soon!</div>
	<?php endif; ?>
</div>
<?php endif; ?>

</body>
</html>
