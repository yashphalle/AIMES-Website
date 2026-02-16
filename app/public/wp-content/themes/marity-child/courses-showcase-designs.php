<?php
/**
 * Courses & Project Showcase – 3 Design Options (Preview)
 * Visit: /courses-showcase-designs/?design=1 or ?design=2 or ?design=3
 * Same data, different layouts. Approve one to merge into main.
 */

$design = isset( $_GET['design'] ) ? max( 1, min( 3, intval( $_GET['design'] ) ) ) : 1;

// Hardcoded demo data
$courses = array(
	array(
		'title'    => 'AI & Machine Learning Fundamentals',
		'info'     => 'An introduction to artificial intelligence and machine learning concepts, covering supervised and unsupervised learning, neural networks, and practical applications in media and communication.',
		'level'    => 'Graduate',
		'code'     => 'CS 5100',
		'semester' => 'Fall 2025',
		'image'    => '', // optional image URL
	),
	array(
		'title'    => 'Data Journalism & Visualization',
		'info'     => 'Learn to analyze data, create compelling visualizations, and tell stories with data. Covers tools like Python, D3.js, and Tableau for journalistic applications.',
		'level'    => 'Undergraduate',
		'code'     => 'JRNL 3200',
		'semester' => 'Spring 2026',
		'image'    => '',
	),
	array(
		'title'    => 'Ethics in AI & Media',
		'info'     => 'Explores ethical considerations in AI development and deployment, focusing on bias, fairness, transparency, and the societal impact of AI in media industries.',
		'level'    => 'Graduate',
		'code'     => 'CS 6140',
		'semester' => 'Fall 2025',
		'image'    => '',
	),
);

// Projects grouped by course (4–5 featured per course, then "more")
$projects_by_course = array(
	'AI & Machine Learning Fundamentals' => array(
		array( 'title' => 'Misinformation Detection Tool', 'students' => 'Student A, Student B', 'year' => '2025', 'desc' => 'ML model that identifies potential misinformation in news using NLP.', 'link' => '#', 'thumb' => '' ),
		array( 'title' => 'Automated Caption Generator', 'students' => 'Student I, Student J', 'year' => '2024', 'desc' => 'Deep learning model for accurate image captions.', 'link' => '#', 'thumb' => '' ),
		array( 'title' => 'News Recommendation Engine', 'students' => 'Student X, Student Y', 'year' => '2025', 'desc' => 'Personalized news feed using collaborative filtering.', 'link' => '#', 'thumb' => '' ),
		array( 'title' => 'Fake News Classifier', 'students' => 'Student M, Student N', 'year' => '2024', 'desc' => 'Classifier trained on fact-checked datasets.', 'link' => '#', 'thumb' => '' ),
	),
	'Data Journalism & Visualization' => array(
		array( 'title' => 'Climate Data Dashboard', 'students' => 'Student D, Student E', 'year' => '2025', 'desc' => 'Interactive climate change visualizations with D3.js.', 'link' => '#', 'thumb' => '' ),
		array( 'title' => 'Social Media Sentiment Tracker', 'students' => 'Student K, Student L', 'year' => '2024', 'desc' => 'Real-time sentiment dashboard for major events.', 'link' => '#', 'thumb' => '' ),
		array( 'title' => 'Election Data Explorer', 'students' => 'Student P, Student Q', 'year' => '2025', 'desc' => 'Explore voting patterns and demographics.', 'link' => '#', 'thumb' => '' ),
	),
	'Ethics in AI & Media' => array(
		array( 'title' => 'Bias Detection in Headlines', 'students' => 'Student F, Student G', 'year' => '2025', 'desc' => 'Detects political bias using sentiment analysis.', 'link' => '#', 'thumb' => '' ),
		array( 'title' => 'AI Ethics Case Study Database', 'students' => 'Student N, Student O', 'year' => '2024', 'desc' => 'Database of AI ethics case studies.', 'link' => '#', 'thumb' => '' ),
	),
);

$more_projects = array(
	array( 'title' => 'Podcast Transcription Tool', 'course' => 'AI & ML', 'students' => 'Student P, Q', 'year' => '2024', 'link' => '#' ),
	array( 'title' => 'Accessibility Checker for Media', 'course' => 'Data Journalism', 'students' => 'Student R, S', 'year' => '2024', 'link' => '#' ),
	array( 'title' => 'Deepfake Detection Prototype', 'course' => 'Ethics in AI', 'students' => 'Student T, U', 'year' => '2024', 'link' => '#' ),
	array( 'title' => 'Local News Aggregator', 'course' => 'AI & ML', 'students' => 'Student V, W', 'year' => '2023', 'link' => '#' ),
	array( 'title' => 'Reader Revenue Dashboard', 'course' => 'Data Journalism', 'students' => 'Student Z', 'year' => '2023', 'link' => '#' ),
);

$featured_per_course = 4;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Courses & Project Showcase – Design <?php echo (int) $design; ?> Preview</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<style>
		* { box-sizing: border-box; margin: 0; padding: 0; }
		body { font-family: 'Sora', sans-serif; background: #f5f6f8; color: #333; line-height: 1.6; }
		a { color: inherit; text-decoration: none; }
		img { max-width: 100%; display: block; }

		/* Design switcher */
		.design-switcher {
			position: fixed; top: 20px; right: 20px; z-index: 9999;
			background: #0e202a; padding: 14px 20px; border-radius: 12px;
			box-shadow: 0 8px 32px rgba(0,0,0,0.3); display: flex; gap: 8px; align-items: center; flex-wrap: wrap;
		}
		.design-switcher span { color: #7ec8e3; font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-right: 4px; }
		.design-switcher a {
			display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 600;
			transition: all 0.2s; color: #fff;
		}
		.design-switcher a.active { background: #1e3a5f; }
		.design-switcher a:not(.active) { background: rgba(255,255,255,0.1); }
		.design-switcher a:hover:not(.active) { background: rgba(255,255,255,0.2); }

		/* Page container */
		.preview-page { padding: 80px 24px 60px; max-width: 1200px; margin: 0 auto; }
		.preview-page h1 { font-size: 28px; font-weight: 800; color: #0e202a; margin-bottom: 8px; }
		.preview-page .subtitle { font-size: 14px; color: #666; margin-bottom: 40px; }

		/* ========== DESIGN 1: Clean cards ========== */
		.design-1-content { display: none; }
		.design-1-content.active { display: block; }
		.d1-course-block { margin-bottom: 48px; }
		.d1-course-card {
			display: grid; grid-template-columns: 280px 1fr; gap: 0;
			background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
			margin-bottom: 28px;
		}
		.d1-course-visual {
			height: 220px; background: linear-gradient(135deg, #0e202a, #1e3a5f);
			display: flex; align-items: center; justify-content: center;
		}
		.d1-course-visual span { font-size: 48px; font-weight: 800; color: rgba(255,255,255,0.15); }
		.d1-course-body { padding: 28px 32px; }
		.d1-course-meta { display: flex; gap: 12px; margin-bottom: 12px; flex-wrap: wrap; }
		.d1-course-code { font-size: 11px; font-weight: 700; color: #1e3a5f; background: #e8f4f8; padding: 4px 10px; border-radius: 4px; }
		.d1-course-level { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #7ec8e3; }
		.d1-course-title { font-size: 20px; font-weight: 700; color: #0e202a; margin-bottom: 10px; }
		.d1-course-info { font-size: 14px; color: #666; line-height: 1.65; }
		.d1-projects-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #1e3a5f; margin-bottom: 16px; }
		.d1-projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
		.d1-project-card {
			background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.05);
			transition: transform 0.2s, box-shadow 0.2s;
		}
		.d1-project-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
		.d1-project-thumb { height: 120px; background: #e8ecf0; display: flex; align-items: center; justify-content: center; color: #aaa; }
		.d1-project-body { padding: 18px; }
		.d1-project-title { font-size: 15px; font-weight: 700; color: #0e202a; margin-bottom: 6px; }
		.d1-project-meta { font-size: 12px; color: #888; margin-bottom: 8px; }
		.d1-project-desc { font-size: 13px; color: #666; line-height: 1.5; }
		.d1-more-wrap { margin-top: 32px; }
		.d1-more-btn {
			display: inline-flex; align-items: center; gap: 8px;
			border: 2px solid #1e3a5f; color: #1e3a5f; padding: 12px 24px; border-radius: 8px;
			font-size: 13px; font-weight: 600; cursor: pointer; background: transparent;
			font-family: 'Sora', sans-serif;
		}
		.d1-more-btn:hover { background: #1e3a5f; color: #fff; }
		.d1-more-list { display: none; margin-top: 20px; }
		.d1-more-list.visible { display: block; }
		.d1-more-item {
			display: flex; align-items: center; justify-content: space-between; padding: 14px 18px;
			background: #fff; border-radius: 8px; margin-bottom: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
		}
		.d1-more-item strong { font-size: 14px; color: #0e202a; }
		.d1-more-item span { font-size: 12px; color: #888; }

		@media (max-width: 768px) {
			.d1-course-card { grid-template-columns: 1fr; }
			.d1-course-visual { height: 160px; }
		}

		/* ========== DESIGN 2: Magazine / Bold ========== */
		.design-2-content { display: none; }
		.design-2-content.active { display: block; }
		.d2-course-block { margin-bottom: 56px; }
		.d2-course-hero {
			background: linear-gradient(135deg, #0e202a 0%, #1e3a5f 50%, #2d5a8e 100%);
			border-radius: 20px; padding: 48px 40px; position: relative; overflow: hidden;
			margin-bottom: 32px;
		}
		.d2-course-hero::before {
			content: ''; position: absolute; inset: 0;
			background: radial-gradient(circle at 80% 20%, rgba(126,200,227,0.15) 0%, transparent 50%);
		}
		.d2-course-hero > * { position: relative; z-index: 1; }
		.d2-course-badge { display: inline-block; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #7ec8e3; margin-bottom: 12px; }
		.d2-course-hero h2 { font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 8px; }
		.d2-course-hero .d2-meta { font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 16px; }
		.d2-course-hero .d2-info { font-size: 15px; color: rgba(255,255,255,0.85); line-height: 1.7; max-width: 720px; }
		.d2-projects { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
		.d2-project-card {
			background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
			display: flex; flex-direction: column; transition: transform 0.2s;
		}
		.d2-project-card:hover { transform: translateY(-4px); }
		.d2-project-thumb { height: 140px; background: linear-gradient(135deg, #e8ecf0, #dde1e6); display: flex; align-items: center; justify-content: center; color: #999; }
		.d2-project-body { padding: 22px; flex: 1; }
		.d2-project-title { font-size: 16px; font-weight: 700; color: #0e202a; margin-bottom: 8px; }
		.d2-project-desc { font-size: 13px; color: #666; line-height: 1.55; margin-bottom: 12px; }
		.d2-project-footer { font-size: 12px; color: #888; display: flex; justify-content: space-between; }
		.d2-view-more { margin-top: 36px; text-align: center; }
		.d2-view-more .btn {
			display: inline-flex; align-items: center; gap: 8px;
			background: #0e202a; color: #fff; padding: 14px 28px; border-radius: 10px;
			font-size: 13px; font-weight: 600; font-family: 'Sora', sans-serif; border: none; cursor: pointer;
		}
		.d2-view-more .btn:hover { background: #1e3a5f; }
		.d2-more-list { display: none; margin-top: 24px; }
		.d2-more-list.visible { display: block; }
		.d2-more-item {
			background: #fff; border-radius: 10px; padding: 18px 22px; margin-bottom: 10px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center;
		}
		.d2-more-item strong { font-size: 14px; color: #0e202a; }
		.d2-more-item span { font-size: 12px; color: #888; }

		@media (max-width: 768px) {
			.d2-projects { grid-template-columns: 1fr; }
		}

		/* ========== DESIGN 3: Minimal / List ========== */
		.design-3-content { display: none; }
		.design-3-content.active { display: block; }
		.d3-course-block { margin-bottom: 48px; }
		.d3-course-row {
			display: flex; gap: 24px; align-items: flex-start;
			background: #fff; border-radius: 12px; padding: 24px; margin-bottom: 24px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #eef0f2;
		}
		.d3-course-thumb {
			width: 120px; height: 120px; flex-shrink: 0;
			background: linear-gradient(135deg, #0e202a, #1e3a5f); border-radius: 10px;
			display: flex; align-items: center; justify-content: center;
		}
		.d3-course-thumb span { font-size: 28px; font-weight: 800; color: rgba(255,255,255,0.2); }
		.d3-course-content { flex: 1; min-width: 0; }
		.d3-course-tags { display: flex; gap: 10px; margin-bottom: 8px; }
		.d3-course-tags span { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #1e3a5f; background: #f0f4f8; padding: 4px 10px; border-radius: 4px; }
		.d3-course-title { font-size: 18px; font-weight: 700; color: #0e202a; margin-bottom: 8px; }
		.d3-course-info { font-size: 14px; color: #666; line-height: 1.6; }
		.d3-projects-label { font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #1e3a5f; margin: 20px 0 12px; }
		.d3-project-list { display: flex; flex-direction: column; gap: 8px; }
		.d3-project-item {
			display: flex; align-items: center; gap: 16px; padding: 14px 18px;
			background: #f8f9fb; border-radius: 8px; border-left: 3px solid #1e3a5f;
		}
		.d3-project-item:hover { background: #f0f4f8; }
		.d3-project-item .thumb { width: 56px; height: 56px; flex-shrink: 0; background: #e2e5e8; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #aaa; font-size: 20px; }
		.d3-project-item .text { flex: 1; min-width: 0; }
		.d3-project-item .title { font-size: 14px; font-weight: 600; color: #0e202a; }
		.d3-project-item .meta { font-size: 12px; color: #888; margin-top: 2px; }
		.d3-more-section { margin-top: 32px; }
		.d3-more-btn {
			width: 100%; padding: 14px; border: 2px dashed #dde1e6; border-radius: 10px;
			background: transparent; color: #1e3a5f; font-size: 13px; font-weight: 600; cursor: pointer;
			font-family: 'Sora', sans-serif;
		}
		.d3-more-btn:hover { border-color: #1e3a5f; background: #f8f9fb; }
		.d3-more-list { display: none; margin-top: 16px; }
		.d3-more-list.visible { display: block; }
		.d3-more-item {
			display: flex; justify-content: space-between; align-items: center; padding: 12px 16px;
			background: #fff; border-radius: 6px; margin-bottom: 6px; border: 1px solid #eef0f2;
		}
		.d3-more-item strong { font-size: 13px; }
		.d3-more-item span { font-size: 11px; color: #888; }

		@media (max-width: 600px) {
			.d3-course-row { flex-direction: column; }
			.d3-course-thumb { width: 100%; height: 140px; }
		}
	</style>
</head>
<body>

<div class="design-switcher">
	<span>Design</span>
	<a href="?design=1" class="<?php echo $design === 1 ? 'active' : ''; ?>">1 – Clean cards</a>
	<a href="?design=2" class="<?php echo $design === 2 ? 'active' : ''; ?>">2 – Magazine</a>
	<a href="?design=3" class="<?php echo $design === 3 ? 'active' : ''; ?>">3 – Minimal list</a>
</div>

<div class="preview-page">
	<h1>Courses & Project Showcase</h1>
	<p class="subtitle">Design <?php echo (int) $design; ?> preview – same content, different layout. Choose one to merge into main.</p>

	<?php if ( $design === 1 ) : ?>
	<!-- ========== DESIGN 1: Clean cards ========== -->
	<div class="design-1-content active">
		<?php foreach ( $courses as $course ) : ?>
		<div class="d1-course-block">
			<div class="d1-course-card">
				<div class="d1-course-visual"><span><?php echo esc_html( substr( $course['title'], 0, 2 ) ); ?></span></div>
				<div class="d1-course-body">
					<div class="d1-course-meta">
						<span class="d1-course-code"><?php echo esc_html( $course['code'] ); ?></span>
						<span class="d1-course-level"><?php echo esc_html( $course['level'] ); ?></span>
					</div>
					<h2 class="d1-course-title"><?php echo esc_html( $course['title'] ); ?></h2>
					<p class="d1-course-info"><?php echo esc_html( $course['info'] ); ?></p>
				</div>
			</div>
			<div class="d1-projects-label">Featured projects</div>
			<div class="d1-projects-grid">
				<?php
				$projs = isset( $projects_by_course[ $course['title'] ] ) ? array_slice( $projects_by_course[ $course['title'] ], 0, $featured_per_course ) : array();
				foreach ( $projs as $p ) :
				?>
				<div class="d1-project-card">
					<div class="d1-project-thumb"><?php echo esc_html( substr( $p['title'], 0, 1 ) ); ?></div>
					<div class="d1-project-body">
						<div class="d1-project-title"><?php echo esc_html( $p['title'] ); ?></div>
						<div class="d1-project-meta"><?php echo esc_html( $p['students'] . ' · ' . $p['year'] ); ?></div>
						<p class="d1-project-desc"><?php echo esc_html( $p['desc'] ); ?></p>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
		<div class="d1-more-wrap">
			<button type="button" class="d1-more-btn" onclick="this.nextElementSibling.classList.toggle('visible'); this.textContent = this.nextElementSibling.classList.contains('visible') ? 'Show less' : 'View more projects';">
				View more projects
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
			</button>
			<div class="d1-more-list">
				<?php foreach ( $more_projects as $p ) : ?>
				<div class="d1-more-item">
					<strong><?php echo esc_html( $p['title'] ); ?></strong>
					<span><?php echo esc_html( $p['course'] . ' · ' . $p['year'] ); ?></span>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $design === 2 ) : ?>
	<!-- ========== DESIGN 2: Magazine ========== -->
	<div class="design-2-content active">
		<?php foreach ( $courses as $course ) : ?>
		<div class="d2-course-block">
			<div class="d2-course-hero">
				<span class="d2-course-badge"><?php echo esc_html( $course['code'] ); ?> · <?php echo esc_html( $course['level'] ); ?></span>
				<h2><?php echo esc_html( $course['title'] ); ?></h2>
				<div class="d2-meta"><?php echo esc_html( $course['semester'] ); ?></div>
				<p class="d2-info"><?php echo esc_html( $course['info'] ); ?></p>
			</div>
			<div class="d2-projects">
				<?php
				$projs = isset( $projects_by_course[ $course['title'] ] ) ? array_slice( $projects_by_course[ $course['title'] ], 0, 5 ) : array();
				foreach ( $projs as $p ) :
				?>
				<div class="d2-project-card">
					<div class="d2-project-thumb"><?php echo esc_html( substr( $p['title'], 0, 1 ) ); ?></div>
					<div class="d2-project-body">
						<div class="d2-project-title"><?php echo esc_html( $p['title'] ); ?></div>
						<p class="d2-project-desc"><?php echo esc_html( $p['desc'] ); ?></p>
						<div class="d2-project-footer">
							<span><?php echo esc_html( $p['students'] ); ?></span>
							<span><?php echo esc_html( $p['year'] ); ?></span>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
		<div class="d2-view-more">
			<button type="button" class="btn" onclick="var e=document.querySelector('.d2-more-list'); e.classList.toggle('visible'); this.textContent=e.classList.contains('visible')?'Show less':'View all projects';">
				View all projects
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
			</button>
			<div class="d2-more-list">
				<?php foreach ( $more_projects as $p ) : ?>
				<div class="d2-more-item">
					<strong><?php echo esc_html( $p['title'] ); ?></strong>
					<span><?php echo esc_html( $p['course'] . ' · ' . $p['year'] ); ?></span>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $design === 3 ) : ?>
	<!-- ========== DESIGN 3: Minimal list ========== -->
	<div class="design-3-content active">
		<?php foreach ( $courses as $course ) : ?>
		<div class="d3-course-block">
			<div class="d3-course-row">
				<div class="d3-course-thumb"><span><?php echo esc_html( substr( $course['title'], 0, 2 ) ); ?></span></div>
				<div class="d3-course-content">
					<div class="d3-course-tags">
						<span><?php echo esc_html( $course['code'] ); ?></span>
						<span><?php echo esc_html( $course['level'] ); ?></span>
						<span><?php echo esc_html( $course['semester'] ); ?></span>
					</div>
					<h2 class="d3-course-title"><?php echo esc_html( $course['title'] ); ?></h2>
					<p class="d3-course-info"><?php echo esc_html( $course['info'] ); ?></p>
				</div>
			</div>
			<div class="d3-projects-label">Featured projects</div>
			<div class="d3-project-list">
				<?php
				$projs = isset( $projects_by_course[ $course['title'] ] ) ? array_slice( $projects_by_course[ $course['title'] ], 0, 5 ) : array();
				foreach ( $projs as $p ) :
				?>
				<a href="<?php echo esc_url( $p['link'] ); ?>" class="d3-project-item">
					<div class="thumb"><?php echo esc_html( substr( $p['title'], 0, 1 ) ); ?></div>
					<div class="text">
						<div class="title"><?php echo esc_html( $p['title'] ); ?></div>
						<div class="meta"><?php echo esc_html( $p['students'] . ' · ' . $p['year'] ); ?></div>
					</div>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
		<div class="d3-more-section">
			<button type="button" class="d3-more-btn" onclick="var e=document.querySelector('.d3-more-list'); e.classList.toggle('visible');">
				View more projects (<?php echo count( $more_projects ); ?>)
			</button>
			<div class="d3-more-list">
				<?php foreach ( $more_projects as $p ) : ?>
				<div class="d3-more-item">
					<strong><?php echo esc_html( $p['title'] ); ?></strong>
					<span><?php echo esc_html( $p['course'] . ' · ' . $p['year'] ); ?></span>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

</body>
</html>
