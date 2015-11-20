=== Go, Baduk, Weiqi ===
Contributors: klangfarbe
Tags: go, baduk, weiqi, sgf, kifu, goban, wgo.js, responsive
Requires at least: 3.6
Tested up to: 4.3.1
License: MIT
Stable tag: 0.5

Display SGF files with a responsive layout on your WordPress site and access
the European Go Database for player information.

== Description ==
This plugin makes use of the great [wgo.js](http://wgo.waltheri.net) library and
adds two new shortcodes to WordPress which allows users to display SGF files and
build player tables by querying the [European Go Database](http://europeangodatabase.eu).

= Documentation =
For the shortcode documentation and examples visit the [Plugin Homepage](http://guzumi.de/wgo-plugin)

== Installation ==
Download the package and extract it to you WordPress plugin folder or use the
admin backend and search for SGF. You should find the latest version of the
plugin and can install it directly from there.

== Frequently Asked Questions ==

= Is this plugin compatible with the EidoGo-Plugin? =
Unfortunately not out of the box. A manual patch in the EidoGo-Plugin is needed
to make sure that it's JavaScript files are only loaded in case the plugin is
actually used on a site. Nevertheless it is not possible to use both plugins
in the same post.

== Changelog ==

= 0.5 =
* Fixed CSS problems with the default WordPress themes
* Updated wgo.js library

= 0.4 =
* Added a link to the settings page on the WordPress plugin page

= 0.3 =
* Fixed embedding the Javascript for pages on front page
* Added new version of wgo.js
* For static diagrams the last made move in the SGF is not marked with a circle anymore

= 0.2 =
First public release
