# AIMES Lab Website - Content Management Guide

> **Site:** AI-Media Strategies (AIMS) Lab | **URL:** aimeslab.org
> **Platform:** WordPress + Elementor | **Theme:** Marity (Child Theme)
> **Last Updated:** February 2026

---

## Table of Contents

1. [Adding a Research Post (Portfolio Item)](#1-adding-a-research-post-portfolio-item)
2. [Adding a Team Member / Bio](#2-adding-a-team-member--bio)
3. [Editing Contact Info](#3-editing-contact-info)
4. [Adding/Managing Announcements (News Ticker)](#4-addingmanaging-announcements-news-ticker)
5. [Writing Blog Posts](#5-writing-blog-posts)
6. [Managing Portfolio Categories](#6-managing-portfolio-categories)
7. [Uploading & Managing Media (Images)](#7-uploading--managing-media-images)
8. [Quick Reference Card](#8-quick-reference-card)

---

## 1. Adding a Research Post (Portfolio Item)

Research publications are managed as **Portfolio Items** — a custom post type provided by the Marity Core plugin. Each research post has a detail page with a description, featured image, and an info sidebar (article link, date, authors, category).

### How to Add a New Research Post

**Navigate to:** `Dashboard > Portfolio > Add New`

### Step-by-Step

#### Step 1: Enter the Title

- In the **Title** field at the top, enter the full research paper/article title.
- Example: *"AI-Driven Analysis of Media Trust in Rural Communities"*

#### Step 2: Set the Featured Image

- In the right sidebar, find the **Featured Image** panel.
- Click **Set featured image** and upload or select an image from the Media Library.
- **Recommended size:** At least 800 x 500 px (landscape orientation works best).
- This image appears on the research listing cards and at the top of the detail page.

#### Step 3: Write the Description

- Scroll down to the **Portfolio Settings** section (below the main editor area).
- Find the **Portfolio Description** field (textarea).
- Enter the research summary/abstract here.
- **HTML is supported** — you can use:
  - `<strong>bold text</strong>` for bold
  - `<em>italic text</em>` for italic
  - `<a href="https://...">link text</a>` for links
  - Blank lines for paragraph breaks
- The child theme automatically converts double line breaks into proper `<p>` paragraphs.

#### Step 4: Add Info Items (Sidebar Metadata)

- In the **Portfolio Settings** section, find **Info Items**.
- Click **Add Item** to add each metadata row. Common items to add:

| Item Label | Item Text | Item Link | Notes |
|------------|-----------|-----------|-------|
| `Article Link` | *(leave empty)* | `https://doi.org/...` | The full URL to the published article. Displays as a styled button. |
| `Published Date` | `January 2026` | *(leave empty)* | The publication date in readable format. |
| `Authors` | `J. Smith, A. Doe, B. Lee` | *(leave empty)* | Author names, comma-separated. |
| `Published In` | `Journal of AI & Media` | *(leave empty)* | The journal or conference name. |

- **Item Label** — The label shown above the value (e.g., "Authors").
- **Item Text** — The text value displayed.
- **Item Link** — If the item should be clickable (mainly used for "Article Link").
- **Item Target** — Set to **"New Tab"** for external article links so visitors don't leave the site.

#### Step 5: Assign a Category

- In the right sidebar, find **Portfolio Categories**.
- Check the appropriate category (e.g., "Research", "Publication", "Conference Paper").
- To create a new category, click **+ Add New Portfolio Category**.
- The category appears as a badge on the research listing cards.

#### Step 6: Configure Media (Optional)

- In Portfolio Settings, the **Media** section allows you to add gallery images, videos, or audio.
- Click **Add Media** and select the media type:
  - **Gallery** — Upload multiple images for a slideshow.
  - **Image** — A single image.
  - **Video** — Paste a video URL (YouTube, Vimeo).
  - **Audio** — Paste an audio URL.
- *Note: The child theme currently hides the top media section on single portfolio pages and uses the featured image instead. Media items may not display unless the layout is changed.*

#### Step 7: Choose a Layout (Optional)

- **Single Layout** — Controls how the detail page renders. The current site design works best with the default layout.
- **Columns / Spacing** — Only relevant for masonry or gallery layouts. Leave at defaults unless needed.

#### Step 8: Publish

- In the right sidebar, click **Publish** (or **Save Draft** to save without publishing).
- The research post will immediately appear on:
  - The portfolio listing/grid page (as a card with image, title, excerpt, and category badge).
  - Its own detail page at `/portfolio-item/your-post-slug/`.

### Editing an Existing Research Post

1. Go to `Dashboard > Portfolio > All Portfolio Items`.
2. Hover over the item and click **Edit**.
3. Make your changes and click **Update**.

### Deleting a Research Post

1. Go to `Dashboard > Portfolio > All Portfolio Items`.
2. Hover over the item and click **Trash**.
3. To permanently delete: go to **Trash** tab and click **Delete Permanently**.

### Research Post Fields — Quick Reference

| Field | Location | Required | Description |
|-------|----------|----------|-------------|
| Title | Top of editor | Yes | Full research paper title |
| Featured Image | Right sidebar | Recommended | Card thumbnail + detail page image (800x500px+) |
| Portfolio Description | Portfolio Settings > General | Yes | Abstract/summary (HTML supported) |
| Info Items | Portfolio Settings > General | Recommended | Article Link, Date, Authors, Journal |
| Portfolio Category | Right sidebar | Recommended | Category badge on listing cards |
| Media | Portfolio Settings > Media | Optional | Gallery, video, or audio attachments |
| Single Layout | Portfolio Settings > General | Optional | Page layout style (keep default) |
| List Image | Portfolio Settings > List | Optional | Override image shown on listing grid |

---

## 2. Adding a Team Member / Bio

Team members are managed in two connected places:
- **Team CPT** (Marity Team) — stores the member's name, photo, role, and social links. Used for the team card grid display.
- **Elementor Accordion on the Our Team page** — stores the detailed bio text. The child theme transforms this accordion into an interactive sidebar navigation layout.

### How to Add a New Team Member

### Part A: Create the Team Member Post

**Navigate to:** `Dashboard > Marity Team > Add New`

#### Step 1: Enter the Member's Name

- In the **Title** field, enter the full name.
- Example: *"Jane Smith"* or *"Jane Smith, PhD"*

#### Step 2: Set the Photo

- In the right sidebar, find the **Featured Image** panel.
- Click **Set featured image** and upload a professional headshot.
- **Recommended size:** At least 400 x 400 px (square crop works best).
- This photo appears on:
  - The team card grid (magazine-style cards).
  - The bio sidebar navigation (circular thumbnail, 44x44px).

#### Step 3: Set the Role

- Scroll down to the **Team Settings** section.
- In the **Role** field, enter the member's title/position.
- Example: *"Research Assistant"*, *"Director"*, *"Postdoctoral Fellow"*

#### Step 4: Add Social Media Links (Optional)

- In Team Settings, find **Social Networks**.
- Click **Add New Network** for each social profile:
  - **Icon Source** — Choose "SVG Path" (default) or "Icon Pack".
  - **SVG Path** — Paste the SVG path markup for the social icon (remove version/id attributes).
  - **Icon Link** — The full URL to their social profile (e.g., `https://linkedin.com/in/janesmith`).
  - **Icon Target** — Set to **"New Tab"** so links open in a new window.
- Common networks to add: LinkedIn, Twitter/X, Google Scholar, personal website.

#### Step 5: Publish

- Click **Publish** in the right sidebar.
- The member will appear on the team card grid on the Our Team page.

### Part B: Add the Bio Text to the Our Team Page

The detailed bio is stored in an **Elementor accordion widget** on the Our Team page. The child theme transforms this accordion into the interactive sidebar bio viewer.

#### Step 1: Edit the Our Team Page

1. Go to `Dashboard > Pages > All Pages`.
2. Find the **Our Team** page (page ID 6575).
3. Click **Edit with Elementor**.

#### Step 2: Locate the Accordion Widget

- Scroll down to find the **Accordion** widget that contains existing team member bios.
- Each accordion item represents one team member.

#### Step 3: Add a New Accordion Item

1. Click on the Accordion widget to select it.
2. In the left panel, click **+ Add Item**.
3. **Title format:** Enter the name and role in this exact format:
   ```
   Full Name (Role Title)
   ```
   Example: `Jane Smith (Research Assistant)`

   > **Important:** The parentheses format `Name (Role)` is required — the child theme JavaScript parses this to extract the name and role separately.

4. **Content:** Enter the full bio text in the content area. You can use:
   - Regular paragraphs (just type and press Enter for new paragraphs).
   - Bold, italic, links using the Elementor text editor toolbar.
   - HTML if switching to the "Text" tab.

#### Step 4: Match the Name

- The bio sidebar matches team member photos by **first name**.
- Ensure the first name in the accordion title matches the first name in the Team CPT post title.
- Example: If the Team post is titled "Jane Smith", the accordion title should start with "Jane".

#### Step 5: Save

- Click **Update** in the Elementor panel to save the page.

### Editing an Existing Team Member

**To update photo/role/social links:**
1. Go to `Dashboard > Marity Team`.
2. Click the member's name to edit.
3. Make changes and click **Update**.

**To update the bio text:**
1. Go to `Pages > Our Team > Edit with Elementor`.
2. Click the accordion widget, find the member's item, and edit the content.
3. Click **Update**.

### Removing a Team Member

1. **Trash the Team post:** `Dashboard > Marity Team > Trash`.
2. **Remove the accordion item:** Edit the Our Team page with Elementor, delete the accordion item, and Update.

### Team Member Fields — Quick Reference

| Field | Location | Required | Description |
|-------|----------|----------|-------------|
| Name (Title) | Team post title | Yes | Full name of the member |
| Featured Image | Team post sidebar | Yes | Headshot photo (400x400px+ square) |
| Role | Team Settings > Role | Yes | Job title / position |
| Social Networks | Team Settings > Social | Optional | LinkedIn, Twitter, Scholar, etc. |
| Bio Text | Elementor accordion on Our Team page | Yes | Detailed biography paragraph(s) |
| Accordion Title Format | Elementor accordion item title | Yes | Must be: `Name (Role)` |

### Additional Team Fields (Advanced, Currently Unused)

These fields exist in the Team CPT but are not currently displayed by the theme:

| Field | Type | Description |
|-------|------|-------------|
| Birth Date | Date picker | Member's birth date |
| E-mail | Text | Email address |
| Address | Text | Physical address |
| Education | Text | Educational background |
| Resume | File upload | PDF/DOC resume file |

> These fields would only display if the Team CPT is configured with `has_single = true` (individual team member pages enabled).

---

## 3. Editing Contact Info

Contact information on the AIMES Lab site is managed across multiple locations depending on where it appears.

### Where Contact Info Lives

| Location on Site | Managed Via | Admin Path |
|------------------|-------------|------------|
| **Contact Page content** | Elementor page editor | `Pages > Contact Us > Edit with Elementor` |
| **Footer columns** | WordPress Widgets (Block editor) | `Appearance > Widgets` |
| **Contact Form** | Contact Form 7 plugin | `Dashboard > Contact > Contact Forms` |
| **Social media links** | Footer widgets (custom HTML/blocks) | `Appearance > Widgets > Footer columns` |

### A. Editing the Contact Page

1. Go to `Dashboard > Pages > All Pages`.
2. Find the **Contact Us** page.
3. Click **Edit with Elementor**.
4. Click on any text element (address, phone number, email) to edit it directly.
5. Click **Update** to save.

### B. Editing Footer Contact Info

The footer has **4 top columns** and **2 bottom columns** managed as widget areas.

**Navigate to:** `Appearance > Widgets`

#### Footer Widget Areas:

| Widget Area | Typical Content |
|-------------|----------------|
| **Footer Top Area Column 1** | Site logo, lab name, tagline |
| **Footer Top Area Column 2** | "Our Lab" links (Our Team, What We Do, Who We Are) |
| **Footer Top Area Column 3** | "Our Work" links (Research, Insights, Contact Us) |
| **Footer Top Area Column 4** | Newsletter signup form / social links |
| **Footer Bottom Area Column 1** | Copyright text |
| **Footer Bottom Area Column 2** | Footer navigation links |

#### To Edit Footer Content:

1. Go to `Appearance > Widgets`.
2. Expand the widget area you want to edit (e.g., "Footer Top Area Column 1").
3. Click on the block/widget inside to edit its content.
4. Modify the text, links, or HTML as needed.
5. Click **Update** to save.

#### Updating Social Media Links in the Footer:

Social media links are typically embedded in footer widgets as custom HTML or icon widgets.

1. Go to `Appearance > Widgets`.
2. Find the widget area containing social icons.
3. Edit the HTML/block to update URLs:
   - Instagram: `https://instagram.com/aimeslab`
   - LinkedIn: `https://linkedin.com/company/aimeslab`
   - Twitter/X: `https://twitter.com/aimeslab`
   - Facebook: `https://facebook.com/aimeslab`
   - YouTube: `https://youtube.com/@aimeslab`
4. Click **Update**.

### C. Editing Contact Forms

Contact forms are managed by the **Contact Form 7** plugin.

**Navigate to:** `Dashboard > Contact > Contact Forms`

#### To Edit a Form:

1. Click on the form name (e.g., "Newsletter" or "Contact Form").
2. **Form tab:** Edit the HTML form fields.
   - Each field uses CF7 shortcode syntax: `[text* your-name]`, `[email* your-email]`, `[textarea your-message]`
   - `*` means the field is required.
3. **Mail tab:** Configure where form submissions are sent.
   - **To:** The email address that receives submissions.
   - **From:** The sender email displayed.
   - **Subject:** Email subject line.
   - **Message Body:** Template using field tags like `[your-name]`, `[your-email]`.
4. **Messages tab:** Customize success/error messages shown to users.
5. Click **Save** to update.

#### To Embed a Form on a Page:

- Copy the shortcode shown at the top of the form editor:
  ```
  [contact-form-7 id="3534" title="Newsletter"]
  ```
- Paste this shortcode into any Elementor text widget, WordPress page, or widget area.

### D. Using Theme Contact Widgets

The Marity Core plugin provides specialized contact widgets:

#### Contact Info Widget (`marity_core_contact_info`)
- **Purpose:** Display phone, fax, email, or custom link with proper schema markup.
- **Options:**
  - Widget Title
  - Link Type: Custom Link / Telephone / Fax / Email
  - Link URL (auto-formats `tel:`, `fax:`, or `mailto:` prefix)
  - Link Target: Same window / New tab

#### Info Location Widget (`marity_core_info_location`)
- **Purpose:** Display physical address/location information.
- **Options:**
  - Title, subtitle, address text
  - Layout variations
  - Custom padding and styling

**To add these widgets:** Go to `Appearance > Widgets`, click **+** in the desired area, and search for "Contact Info" or "Info Location".

---

## 4. Adding/Managing Announcements (News Ticker)

Announcements power the **scrolling news ticker** that appears on the homepage below the hero section. The ticker displays a "NEW" badge, a bell icon, scrolling announcement titles, and a "Details" link.

### How Announcements Work

- Announcements are a **custom post type** registered by the child theme.
- Up to **10 most recent** published announcements are shown in the ticker.
- The ticker scrolls continuously with all announcement titles.
- The **"Details" button** links to the URL of the most recent announcement.
- Announcements appear only on the **homepage**.

### How to Add a New Announcement

**Navigate to:** `Dashboard > Announcements > Add New`

> Look for the **megaphone icon** in the left sidebar menu.

#### Step 1: Enter the Title

- In the **Title** field, enter a short, descriptive announcement.
- Keep it concise — this text scrolls in the ticker bar.
- Examples:
  - *"AIMES Lab Seminar: AI Ethics in Journalism - March 15, 2026"*
  - *"New Publication: Trust in AI-Generated News"*
  - *"Open Position: Research Assistant - Apply by April 1"*

#### Step 2: Add a Link URL

- Below the title, find the **Link URL** field.
- Enter the full URL where the "Details" button should link to.
- Example: `https://aimeslab.org/events/ai-ethics-seminar-march-2026`
- If no link is needed, leave blank (the button will link to `#`).

#### Step 3: Publish

- Click **Publish** in the right sidebar.
- The announcement will immediately appear in the homepage news ticker.
- Newest announcements appear first in the scrolling text.

### Managing Existing Announcements

**Navigate to:** `Dashboard > Announcements`

| Action | How |
|--------|-----|
| **Edit** | Click the announcement title, make changes, click **Update** |
| **Delete** | Hover over the title, click **Trash** |
| **Unpublish** | Edit the announcement, change Status to **Draft**, click **Update** |
| **Reorder** | Announcements are ordered by publish date (newest first). Change the publish date to reorder. |

### Tips

- **To temporarily hide all announcements:** Set all announcements to Draft. The ticker will disappear from the homepage.
- **Character limit:** No hard limit, but shorter titles (under 80 characters) read better in the scrolling ticker.
- **No description/content needed:** Announcements only use the Title and Link URL fields. The main content editor area is not used.

### Announcement Fields — Quick Reference

| Field | Required | Description |
|-------|----------|-------------|
| Title | Yes | The text that scrolls in the news ticker |
| Link URL | Optional | URL for the "Details" button (full URL with `https://`) |

---

## 5. Writing Blog Posts

Blog posts use WordPress's built-in **Posts** system. They appear on the blog/insights page of the site.

### How to Write a New Blog Post

**Navigate to:** `Dashboard > Posts > Add New`

#### Step 1: Enter the Title

- Type the blog post title in the **Title** field at the top.
- Example: *"How AI is Transforming Local Journalism"*

#### Step 2: Write the Content

- Use the **WordPress editor** (Classic Editor or Block Editor depending on your setup) to write the post content.
- **Classic Editor:** Use the toolbar for formatting (bold, italic, headings, links, lists, images).
- **Block Editor (Gutenberg):** Add blocks for paragraphs, headings, images, lists, quotes, etc.
- **Tips:**
  - Use **Heading 2** (`H2`) for main sections and **Heading 3** (`H3`) for subsections.
  - Keep paragraphs short (3-4 sentences) for readability.
  - Add images between paragraphs to break up text.

#### Step 3: Set the Featured Image

- In the right sidebar, find **Featured Image**.
- Click **Set featured image** and upload/select an image.
- **Recommended size:** 1200 x 630 px (optimal for social sharing).
- This image appears as the post thumbnail on blog listing pages.

#### Step 4: Choose a Category

- In the right sidebar, find **Categories**.
- Check the appropriate category or click **+ Add New Category** to create one.
- Common categories: News, Research Highlights, Events, Opinion.

#### Step 5: Add Tags (Optional)

- In the right sidebar, find **Tags**.
- Add relevant tags separated by commas.
- Example: `AI, journalism, media trust, local news`

#### Step 6: Write an Excerpt (Optional)

- In the right sidebar or below the editor, find the **Excerpt** field.
- Write a 1-2 sentence summary. If left blank, WordPress auto-generates one from the first paragraph.

#### Step 7: Publish

- **Save Draft** — Save without publishing (only visible to logged-in editors).
- **Preview** — See how the post will look before publishing.
- **Publish** — Make the post live immediately.
- **Schedule** — Click "Edit" next to the publish date to schedule a future publish date.

### Managing Existing Posts

**Navigate to:** `Dashboard > Posts > All Posts`

| Action | How |
|--------|-----|
| **Edit** | Click the post title |
| **Quick Edit** | Hover > Quick Edit (change title, date, category, status without opening the full editor) |
| **Trash** | Hover > Trash |
| **View** | Hover > View (see the live post) |
| **Bulk Actions** | Check multiple posts > select action from dropdown > Apply |

### Blog Post Fields — Quick Reference

| Field | Required | Description |
|-------|----------|-------------|
| Title | Yes | Blog post headline |
| Content | Yes | Main body text (supports rich text, images, embeds) |
| Featured Image | Recommended | Thumbnail for listings and social shares (1200x630px) |
| Category | Recommended | Organizes posts by topic |
| Tags | Optional | Keywords for search and filtering |
| Excerpt | Optional | Custom summary (auto-generated if blank) |
| Publish Date | Auto | Can be edited to schedule future posts |

---

## 6. Managing Portfolio Categories

Portfolio Categories organize research posts into groups. They appear as **badges on research listing cards** and can be used to filter portfolio items.

### How to Manage Categories

**Navigate to:** `Dashboard > Portfolio > Portfolio Categories`

### Adding a New Category

1. On the left side of the page, fill in:
   - **Name:** The display name (e.g., "Conference Paper", "Journal Article", "Working Paper").
   - **Slug:** Auto-generated from the name, or enter a custom URL-friendly slug (e.g., `conference-paper`).
   - **Parent Category:** Leave as "None" for top-level, or select a parent for subcategories.
   - **Description:** Optional description of the category.
   - **Category Image:** Optional image for category archive pages.
2. Click **Add New Portfolio Category**.

### Editing a Category

1. In the category list on the right, hover over the category name.
2. Click **Edit** to open the full edit page, or **Quick Edit** for inline changes.
3. Make your changes and click **Update**.

### Deleting a Category

1. Hover over the category name and click **Delete**.
2. **Note:** Deleting a category does NOT delete the portfolio items in it — they simply become uncategorized.

### Assigning Categories to Research Posts

- When editing a Portfolio Item, use the **Portfolio Categories** panel in the right sidebar.
- Check one or more categories.
- The first assigned category is displayed as the badge on the listing card.

### Current Category Structure

Review your existing categories at `Dashboard > Portfolio > Portfolio Categories`. Common category patterns for research sites:

| Category | Use For |
|----------|---------|
| Research | General research papers |
| Publication | Published journal articles |
| Conference Paper | Conference presentations/proceedings |
| Working Paper | Pre-publication drafts |
| Report | Technical reports |
| Media Analysis | Media-related studies |

---

## 7. Uploading & Managing Media (Images)

All images, documents, and files used across the site are stored in the **WordPress Media Library**.

### How to Upload Media

**Navigate to:** `Dashboard > Media > Add New`

#### Method 1: Direct Upload

1. Go to `Dashboard > Media > Add New`.
2. **Drag and drop** files onto the upload area, or click **Select Files** to browse.
3. Multiple files can be uploaded at once.

#### Method 2: Upload While Editing

- When editing any post, page, or widget, click **Add Media** or the image icon.
- Upload directly from within the editor.
- The file is automatically added to the Media Library.

### Image Size Guidelines

| Use Case | Recommended Size | Format |
|----------|-----------------|--------|
| **Team Member Photo** | 400 x 400 px (square) | JPG or PNG |
| **Research Featured Image** | 800 x 500 px (landscape) | JPG or PNG |
| **Blog Featured Image** | 1200 x 630 px (landscape) | JPG or PNG |
| **Portfolio Gallery Image** | 1200 x 800 px | JPG or PNG |
| **Logo / Icon** | As needed | PNG (transparent) or SVG |
| **General Page Image** | Max 1920 px wide | JPG or PNG |

### Best Practices

- **File size:** Keep images under **500 KB** for fast loading. Use tools like TinyPNG or ShortPixel to compress before uploading.
- **File naming:** Use descriptive, lowercase names with hyphens: `team-jane-smith-headshot.jpg` (not `IMG_20260115_123456.jpg`).
- **Alt text:** Always fill in the **Alternative Text** field for accessibility and SEO.

### Editing Media Details

1. Go to `Dashboard > Media > Library`.
2. Click on any image to open its details.
3. Editable fields:

| Field | Purpose | Example |
|-------|---------|---------|
| **Alternative Text** | Screen reader description (SEO + accessibility) | "Jane Smith, AIMES Lab Research Assistant" |
| **Title** | Internal reference name | "Jane Smith Headshot" |
| **Caption** | Text displayed below the image (when shown) | "Dr. Jane Smith presenting at AI Conference 2026" |
| **Description** | Longer description (rarely displayed) | Full context about the image |

4. Click **Update** or simply close — changes auto-save.

### Organizing Media

- **Search:** Use the search bar at the top of the Media Library.
- **Filter by date:** Use the date dropdown to filter by upload month.
- **Filter by type:** Use the type dropdown (Images, Audio, Video, Documents).
- **Grid/List view:** Toggle between grid (visual) and list (detailed) views using the icons at the top.

### Deleting Media

1. Click on the image in the Media Library.
2. Click **Delete Permanently** at the bottom right.
3. **Warning:** If the image is used in a post, page, or widget, it will show as a broken image. Check where it's used before deleting.

### Replacing an Image

WordPress doesn't have a built-in "replace" feature. To update an image:

1. Upload the new version with a **different filename**.
2. Edit the post/page where the old image is used.
3. Remove the old image and insert the new one.
4. Delete the old image from the Media Library if no longer needed.

---

## 8. Quick Reference Card

### Common Admin Paths

| Task | Path |
|------|------|
| Add Research Post | `Dashboard > Portfolio > Add New` |
| Add Team Member | `Dashboard > Marity Team > Add New` |
| Edit Team Bios | `Pages > Our Team > Edit with Elementor` |
| Add Announcement | `Dashboard > Announcements > Add New` |
| Write Blog Post | `Dashboard > Posts > Add New` |
| Manage Categories | `Dashboard > Portfolio > Portfolio Categories` |
| Upload Media | `Dashboard > Media > Add New` |
| Edit Contact Form | `Dashboard > Contact > Contact Forms` |
| Edit Footer | `Appearance > Widgets` |
| Edit Navigation | `Appearance > Menus` |
| Edit Any Page | `Pages > All Pages > Edit with Elementor` |

### After Making Changes

| Change Type | Cache Action Needed |
|-------------|-------------------|
| Added/edited a post or page | None (auto-updates) |
| Edited CSS (style.css) | Clear cache: `Marity Dashboard > Qode Optimizer > Clear Cache` + `Elementor > Tools > Regenerate CSS` |
| Edited widgets/footer | None (auto-updates) |
| Images not showing correctly | Try hard refresh: `Ctrl + Shift + R` in browser |

### Important Reminders

- **Always edit the child theme** (`marity-child`), never the parent theme (`marity`) or plugin files.
- **Back up before major changes** — Local by Flywheel provides easy backup/restore.
- **Preview before publishing** — Use the Preview button to check how changes look on the live site.
- **Team bio format** — Accordion title must follow the `Name (Role)` format for the sidebar to work correctly.
- **Research posts** — The description field supports HTML for rich formatting.
- **Announcements** — Only the Title and Link URL fields are used; the content editor area is ignored.

---

*This documentation is specific to the AIMES Lab WordPress site running the Marity theme with child theme customizations. Last generated: February 2026.*
