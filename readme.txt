=== Go, Baduk, Weiqi ===
Contributors: klangfarbe
Tags: go, baduk, weiqi, sgf, kifu, goban, wgo.js, responsive
Requires at least: 3.0
Tested up to: 3.9.1
License: MIT
Stable tag: trunk

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

= 0.2 =
First public release
