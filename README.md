# Launch AI Works SEO Meta

A single-file WordPress **must-use plugin** (about 3.5 KB, no settings page, no database tables) that adds the head tags search engines and social networks look for: meta description, meta keywords, Open Graph (Facebook, LinkedIn) and Twitter/X card tags, plus an optional custom SEO title.

## Why it exists

We wanted proper link previews and per-page metadata on launchaiworks.com without installing a full SEO suite. The site is a block theme (Twenty Twenty-Five), and the hard requirement was **not to change the site's design or behavior**. Output goes into `<head>` only: no content edits, no CSS, no templates, no front-end scripts.

## Why lightweight is the benefit

- **One file, about 3.5 KB.** Drop it into `wp-content/mu-plugins/`. Nothing to install, activate or update.
- **Almost no runtime cost.** It hooks `wp_head` and reads four post-meta values on single posts and pages. No queries beyond that, no options, no admin screens, no assets loaded.
- **Nothing to break.** No layout, content or style changes, and it does nothing on archives, feeds or admin screens.
- **Easy to remove.** Delete the file and every trace is gone. Your custom fields stay in the database and are harmless.
- **Polite.** It steps aside automatically if Yoast, Rank Math, SEOPress or All in One SEO is active, so there are no duplicate tags.
- **Safe output.** Everything is escaped with `esc_attr` / `esc_url`.

## Custom fields

Set these as post/page meta (for example through the REST API, WP-CLI, or any custom-field tool):

| Field | Used for |
|---|---|
| `seo_title` | Document title and `og:title` / `twitter:title` |
| `seo_description` | Meta description, `og:description`, `twitter:description` |
| `seo_keywords` | Comma-separated `<meta name="keywords">` and `article:tag` (first 6) |
| `seo_image` | Absolute image URL for `og:image` / `twitter:image` |

## Fallbacks

- Title: the post title.
- Description: the post excerpt, if one is set.
- Image: featured image, then `wp-content/uploads/laiw-share-default.png` (a 1200x630 default; make one with `make-share-image.php`).
- Twitter card: `summary_large_image` when an image exists, otherwise `summary`.

## Install

1. Copy `laiw-seo-meta.php` to `wp-content/mu-plugins/` (create the folder if needed).
2. Upload a 1200x630 image as `wp-content/uploads/laiw-share-default.png`.
3. Set the custom fields on your pages and posts.
4. View the page source and look for the `Launch AI Works SEO Meta` comment block, then test a link in the Facebook Sharing Debugger.

## Honest notes

- Google ignores the meta keywords tag. It is included for other consumers and for `article:tag`. The real ranking value is in titles, descriptions, headings and content.
- This is a focused tool, not a replacement for a full SEO suite. It does not generate sitemaps (WordPress core already provides `/wp-sitemap.xml`), schema markup or redirects.

## License

MIT
