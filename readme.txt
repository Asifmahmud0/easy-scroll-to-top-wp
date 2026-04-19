=== Simple Smooth Scroll Up ===
Contributors:      your-wp-username
Tags:              scroll to top, scroll up, back to top, smooth scroll, scroll button
Requires at least: 5.0
Tested up to:      6.9
Requires PHP:      7.4
Stable tag:        1.0.0
License:           GPLv3
License URI:       https://www.gnu.org/licenses/gpl-3.0.html

Lightweight and customizable Scroll to Top button with SVG icons, hover color, animation options, and a mobile-hide toggle.

== Description ==

Simple Smooth Scroll Up adds a fully customizable "scroll to top" button to your WordPress site. Everything is controlled from a clean settings page — no coding required.

**Features**

* Three built-in SVG icons: arrow up, chevron up, double chevron up
* Choose button position: right, left, or center-bottom
* Customize background color, hover color, and icon color
* Set button size, icon size, and border radius independently
* Control how far the user must scroll before the button appears
* Control how fast the page scrolls back to the top
* Three appearance animations: fade, slide, or none
* Optional: hide the button on mobile screens (< 768px)
* Accessible — button receives a proper `aria-label` and `role` attribute automatically
* Lightweight — one small JS file (scrollUp library, MIT) + minimal CSS
* No jQuery bloat beyond what WordPress already loads

**Third-party libraries**

This plugin bundles scrollUp v2.4.1 by Mark Goodyear (http://markgoodyear.com/labs/scrollup/).
License: MIT — https://opensource.org/licenses/MIT
Source: https://github.com/markgoodyear/scrollup

== Installation ==

1. Upload the `simple-smooth-scroll-up` folder to `/wp-content/plugins/`.
2. Activate the plugin in **Plugins → Installed Plugins**.
3. Go to **Settings → Scroll Up** to configure the button.

== Frequently Asked Questions ==

= The button is not appearing. What should I check? =

Make sure jQuery is loaded on your theme's front end (it is included with WordPress by default). Also check that no caching plugin is serving a stale version of your pages.

= Can I change the button position precisely with CSS? =

Yes. The button uses the ID `#scrollUp`, so you can add custom CSS in **Appearance → Customize → Additional CSS** to override the position.

= Does this plugin work with page builders? =

Yes. The button is injected via JavaScript and is independent of your page builder.

= Is the button accessible? =

Yes. The plugin automatically adds `aria-label="Scroll to top"` and `role="button"` to the generated anchor tag.

== Screenshots ==

1. Settings page showing all customization options.
2. Example of the scroll button on a live site (right side, rounded, dark background).

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release — no upgrade steps required.