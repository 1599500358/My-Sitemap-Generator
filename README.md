# My Sitemap Generator

My Sitemap Generator is a fully automatic sitemap.xml generator for WordPress. It scans all your published posts, pages, categories, and tags, and creates a search engine friendly sitemap.xml. The sitemap is always up-to-date, helping search engines discover your content faster and improve your site's SEO. No configuration or manual updates required.

## Features
- Fully automatic sitemap.xml generation and updates
- Includes homepage, all published posts, pages, categories, and tags
- Sitemap is always available at `https://your-domain.com/sitemap.xml`
- Updates automatically every hour and on plugin activation
- Zero configuration—just activate and go

## Installation
1. Upload the `my-sitemap-generator.php` file to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit `https://your-domain.com/sitemap.xml` to view your sitemap.

## FAQ
**Where is the sitemap file stored?**  
The sitemap is saved in your WordPress uploads directory and is also dynamically served at `/sitemap.xml`.

**How often is the sitemap updated?**  
The sitemap is regenerated every hour automatically, and also when the plugin is activated.

## Changelog
### 1.0.0
- Initial release: fully automatic sitemap.xml generation and hourly updates.
