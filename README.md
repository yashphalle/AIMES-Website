# AIMES Lab Website

Official website for the **AI-Media Strategies (AIMS) Lab** — [aimeslab.org](https://aimeslab.org)

This repository contains the custom WordPress child theme and documentation for the AIMES Lab website.

## Repository Contents

```
├── CONTENT-MANAGEMENT-GUIDE.md          # Admin guide for managing site content
└── app/public/wp-content/themes/
    └── marity-child/
        ├── functions.php                 # Custom functionality (announcements, portfolio, team bios)
        ├── style.css                     # Custom styles
        └── screenshot.png                # Theme preview
```

## Tech Stack

- **CMS:** WordPress
- **Page Builder:** Elementor
- **Parent Theme:** Marity by Qode Interactive *(required, not included — see below)*
- **Child Theme:** `marity-child` *(this repo)*

## Requirements

Before activating the child theme, the following must be installed:

### Parent Theme (Required)

| Theme | Source |
|-------|--------|
| **Marity** by Qode Interactive | Purchase from [ThemeForest](https://themeforest.net/) |

### Required Plugins

| Plugin | Source |
|--------|--------|
| Marity Core | Bundled with Marity theme |
| Qode Framework | Bundled with Marity theme |
| Elementor | [wordpress.org/plugins/elementor](https://wordpress.org/plugins/elementor/) |
| Contact Form 7 | [wordpress.org/plugins/contact-form-7](https://wordpress.org/plugins/contact-form-7/) |

### Optional Plugins

| Plugin | Purpose |
|--------|---------|
| Qi Addons for Elementor | Additional Elementor widgets |
| Revolution Slider | Slider functionality |
| Classic Editor | Traditional WordPress editor |
| Jetpack | Analytics and security |
| Custom Twitter Feeds | Twitter feed display |
| Instagram Feed | Instagram feed display |
| Olympus Google Fonts | Font management |
| Qode Optimizer | CSS/JS caching |

## Setup Instructions

1. Install WordPress on your server.
2. Purchase and install the **Marity** parent theme from ThemeForest.
3. Install and activate the required plugins listed above.
4. Clone this repository:
   ```bash
   git clone https://github.com/YOUR_USERNAME/localyash.git
   ```
5. Copy the child theme to your WordPress installation:
   ```bash
   cp -r app/public/wp-content/themes/marity-child /path/to/wordpress/wp-content/themes/
   ```
6. Activate **Marity Child** from `WordPress Admin > Appearance > Themes`.

## Custom Features (Child Theme)

The child theme adds the following functionality without modifying any parent theme or plugin files:

### 1. Announcements News Ticker
- Custom post type for announcements (`Dashboard > Announcements`)
- Scrolling ticker bar on the homepage below the hero section

### 2. Portfolio/Research Enhancement
- HTML support in portfolio descriptions (bold, italic, links, paragraphs)
- Featured image repositioned after description text
- Uniform grid cards with category badges, excerpts, and hover effects

### 3. Team Bio Sidebar Navigation
- Transforms Elementor accordion into interactive sidebar layout
- Dark left panel with member photos, white right panel with bio text
- Auto-matches team photos from Team CPT by first name

### 4. Homepage Hero Enhancement
- Particle animation overlay on hero section
- Gradient button hover effects
- Pebble-shaped image mask with pastel backdrop

## Documentation

See [CONTENT-MANAGEMENT-GUIDE.md](CONTENT-MANAGEMENT-GUIDE.md) for step-by-step instructions on:

- Adding research posts
- Adding team members and bios
- Editing contact information
- Managing announcements
- Writing blog posts
- Managing portfolio categories
- Uploading and managing media

## Local Development

This site is developed using [Local by Flywheel](https://localwp.com/). To set up locally:

1. Install Local by Flywheel.
2. Create a new site (or import existing).
3. Install the parent theme and plugins.
4. Clone this repo and symlink or copy the child theme into the local site's `wp-content/themes/`.

## License

The child theme code in this repository is open source. The parent theme (Marity) is a commercial product and is **not included** — it must be purchased separately from ThemeForest.
