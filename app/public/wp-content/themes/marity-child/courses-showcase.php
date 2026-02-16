<?php
/**
 * Courses & Project Showcase – Design 1 (Clean cards)
 * URL: /demos-exploratory-applications/
 * Data from WP Admin: Courses + Projects CPTs.
 */

$courses = get_posts( array(
	'post_type'      => 'aimes_course',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
) );

$projects = get_posts( array(
	'post_type'      => 'aimes_project',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$projects_by_course = array();
$other_projects = array();
$featured_per_course = 5;

foreach ( $projects as $p ) {
	$course_name = get_post_meta( $p->ID, '_aimes_project_course', true );
	$is_featured = get_post_meta( $p->ID, '_aimes_project_featured', true );
	$thumb = get_the_post_thumbnail_url( $p->ID, 'medium_large' ) ?: '';
	$content = $p->post_content ? $p->post_content : '';
	$project_data = array(
		'id'          => $p->ID,
		'title'       => get_the_title( $p->ID ),
		'course'      => $course_name,
		'students'    => get_post_meta( $p->ID, '_aimes_project_students', true ),
		'year'        => get_post_meta( $p->ID, '_aimes_project_year', true ),
		'description' => wp_trim_words( strip_tags( $content ), 30, '...' ),
		'thumb'       => $thumb,
		'link'        => get_post_meta( $p->ID, '_aimes_project_link', true ),
	);
	if ( $is_featured && $course_name ) {
		$key = trim( $course_name );
		if ( ! isset( $projects_by_course[ $key ] ) ) {
			$projects_by_course[ $key ] = array();
		}
		$projects_by_course[ $key ][] = $project_data;
	} else {
		$other_projects[] = $project_data;
	}
}

// Demo data when no content
$demo_courses = array();
$demo_projects_by_course = array();
if ( empty( $courses ) ) {
	$demo_courses = array(
		array( 'title' => 'AI & Machine Learning Fundamentals', 'info' => 'An introduction to AI and ML concepts, covering supervised and unsupervised learning, neural networks, and practical applications in media.', 'level' => 'Graduate', 'code' => 'CS 5100', 'semester' => 'Fall 2025', 'thumb' => '' ),
		array( 'title' => 'Data Journalism & Visualization', 'info' => 'Learn to analyze data, create compelling visualizations, and tell stories with data. Covers Python, D3.js, and Tableau.', 'level' => 'Undergraduate', 'code' => 'JRNL 3200', 'semester' => 'Spring 2026', 'thumb' => '' ),
		array( 'title' => 'Ethics in AI & Media', 'info' => 'Explores ethical considerations in AI development: bias, fairness, transparency, and societal impact in media.', 'level' => 'Graduate', 'code' => 'CS 6140', 'semester' => 'Fall 2025', 'thumb' => '' ),
	);
}
if ( empty( $projects ) ) {
	$demo_projects_by_course = array(
		'AI & Machine Learning Fundamentals' => array(
			array( 'title' => 'Misinformation Detection Tool', 'students' => 'Student A, Student B', 'year' => '2025', 'description' => 'ML model that identifies potential misinformation in news using NLP.', 'thumb' => '', 'link' => '#' ),
			array( 'title' => 'Automated Caption Generator', 'students' => 'Student I, Student J', 'year' => '2024', 'description' => 'Deep learning model for accurate image captions.', 'thumb' => '', 'link' => '#' ),
		),
		'Data Journalism & Visualization' => array(
			array( 'title' => 'Climate Data Dashboard', 'students' => 'Student D, Student E', 'year' => '2025', 'description' => 'Interactive climate visualizations with D3.js.', 'thumb' => '', 'link' => '#' ),
			array( 'title' => 'Social Media Sentiment Tracker', 'students' => 'Student K, Student L', 'year' => '2024', 'description' => 'Real-time sentiment dashboard for major events.', 'thumb' => '', 'link' => '#' ),
		),
		'Ethics in AI & Media' => array(
			array( 'title' => 'Bias Detection in Headlines', 'students' => 'Student F, Student G', 'year' => '2025', 'description' => 'Detects political bias using sentiment analysis.', 'thumb' => '', 'link' => '#' ),
		),
	);
	$other_projects = array(
		array( 'title' => 'Podcast Transcription Tool', 'course' => 'AI & ML', 'students' => 'Student P, Q', 'year' => '2024', 'link' => '#' ),
		array( 'title' => 'Accessibility Checker for Media', 'course' => 'Data Journalism', 'students' => 'Student R, S', 'year' => '2024', 'link' => '#' ),
	);
}

get_header();
?>

<div class="aimes-courses-page aimes-design-1">
	<div class="aimes-courses-header">
		<div class="aimes-courses-header-inner">
			<h1>Courses & Project Showcase</h1>
			<p>Explore our academic courses and discover innovative student projects at the intersection of AI and media.</p>
		</div>
	</div>

	<div class="aimes-courses-content">
		<?php
		$course_list = ! empty( $courses ) ? $courses : $demo_courses;
		$is_demo = empty( $courses );
		foreach ( $course_list as $course ) :
			if ( is_object( $course ) ) {
				$title   = get_the_title( $course->ID );
				$info    = $course->post_content ? wp_kses_post( wpautop( $course->post_content ) ) : '';
				$level   = get_post_meta( $course->ID, '_aimes_course_level', true );
				$code    = get_post_meta( $course->ID, '_aimes_course_code', true );
				$semester = get_post_meta( $course->ID, '_aimes_course_semester', true );
				$thumb   = get_the_post_thumbnail_url( $course->ID, 'medium_large' ) ?: '';
			} else {
				$title   = $course['title'];
				$info    = $course['info'];
				$level   = $course['level'];
				$code    = $course['code'];
				$semester = $course['semester'];
				$thumb   = isset( $course['thumb'] ) ? $course['thumb'] : '';
			}
			$course_projects = array();
			if ( $is_demo && isset( $demo_projects_by_course[ $title ] ) ) {
				$course_projects = $demo_projects_by_course[ $title ];
			} elseif ( ! $is_demo && isset( $projects_by_course[ trim( $title ) ] ) ) {
				$course_projects = array_slice( $projects_by_course[ trim( $title ) ], 0, $featured_per_course );
			}
		?>
		<section class="aimes-d1-course-block">
			<div class="aimes-d1-course-card">
				<div class="aimes-d1-course-visual">
					<?php if ( $thumb ) : ?>
						<img src="<?php echo esc_url( $thumb ); ?>" alt="">
					<?php else : ?>
						<span><?php echo esc_html( substr( $title, 0, 2 ) ); ?></span>
					<?php endif; ?>
				</div>
				<div class="aimes-d1-course-body">
					<div class="aimes-d1-course-meta">
						<?php if ( $code ) : ?><span class="aimes-d1-course-code"><?php echo esc_html( $code ); ?></span><?php endif; ?>
						<?php if ( $level ) : ?><span class="aimes-d1-course-level"><?php echo esc_html( $level ); ?></span><?php endif; ?>
					</div>
					<h2 class="aimes-d1-course-title"><?php echo esc_html( $title ); ?></h2>
					<?php if ( $semester ) : ?><div class="aimes-d1-course-semester"><?php echo esc_html( $semester ); ?></div><?php endif; ?>
					<div class="aimes-d1-course-info"><?php echo $info; ?></div>
				</div>
			</div>

			<?php if ( ! empty( $course_projects ) ) : ?>
			<div class="aimes-d1-projects-label">Featured student projects from course</div>
			<div class="aimes-d1-projects-grid">
				<?php foreach ( $course_projects as $project ) : ?>
				<article class="aimes-d1-project-card">
					<div class="aimes-d1-project-thumb">
						<?php if ( ! empty( $project['thumb'] ) ) : ?>
							<img src="<?php echo esc_url( $project['thumb'] ); ?>" alt="">
						<?php else : ?>
							<span><?php echo esc_html( substr( $project['title'], 0, 1 ) ); ?></span>
						<?php endif; ?>
					</div>
					<div class="aimes-d1-project-body">
						<h3 class="aimes-d1-project-title"><?php echo esc_html( $project['title'] ); ?></h3>
						<div class="aimes-d1-project-meta"><?php echo esc_html( ( $project['students'] ? $project['students'] . ' · ' : '' ) . $project['year'] ); ?></div>
						<p class="aimes-d1-project-desc"><?php echo esc_html( $project['description'] ); ?></p>
						<?php if ( ! empty( $project['link'] ) && $project['link'] !== '#' ) : ?>
							<a href="<?php echo esc_url( $project['link'] ); ?>" class="aimes-d1-project-link">View project</a>
						<?php endif; ?>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</section>
		<?php endforeach; ?>

		<?php if ( ! empty( $other_projects ) ) : ?>
		<div class="aimes-d1-more-wrap">
			<button type="button" class="aimes-d1-more-btn" id="aimes-view-more-projects">
				View more projects
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
			</button>
			<div class="aimes-d1-more-list" id="aimes-more-projects-list">
				<?php foreach ( $other_projects as $p ) : ?>
				<div class="aimes-d1-more-item">
					<strong><?php echo esc_html( $p['title'] ); ?></strong>
					<span><?php echo esc_html( ( isset( $p['course'] ) ? $p['course'] . ' · ' : '' ) . $p['year'] ); ?></span>
					<?php if ( ! empty( $p['link'] ) && $p['link'] !== '#' ) : ?>
						<a href="<?php echo esc_url( $p['link'] ); ?>" class="aimes-d1-more-link">View</a>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<script>
		document.getElementById('aimes-view-more-projects').addEventListener('click', function() {
			var list = document.getElementById('aimes-more-projects-list');
			list.classList.toggle('visible');
			this.textContent = list.classList.contains('visible') ? 'Show less' : 'View more projects';
		});
		</script>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
