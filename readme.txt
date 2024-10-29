=== Apparatus ===
Contributors: mtinsley
Donate link: http://tinsology.net/scripts/apparatus/
Tags: PHP, admin, developer, source code
Requires at least: 2.5
Tested up to: 3.0
Stable tag: 0.4

Apparatus allows you to write and execute PHP code directly from your Wordpress admin area.

== Description ==

Apparatus allows you to write and execute PHP code directly from your wordpress admin area. The source
code is displayed back to you with syntax highlighting along with any output. Errors, warnings, and notices
are also displayed. Apparatus is great for developing code to be added to wordpress posts. Wordpress
developers may also find it useful for testing code, updating options, and debugging, all without needing
to leave your wordpress admin area. Apparatus is also available as a stand-alone application.

== Installation ==

1. Upload the apparatus folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Apparatus should now appear under the tools menu. No further configuration required

== Frequently Asked Questions ==

= What is Error Search? =

When your code generates errors, warnings, or notices they are indicated by icons appearing next to the
line that generated them. Clicking on one of these icons will run a search (either google or errordatabase.info)
for that particular error message.

= What is tab override =

Tab override allows you to use the tab key in textareas just as you would in a desktop text-editor or IDE. In
otherwords pressing the tab key inserts a tab character rather than moving focus to the next form element. Tab
override is also available as a stand-alone wordpress plugin: http://wordpress.org/extend/plugins/tab-override/
There is also a version for MediaWiki: http://tinsology.net/plugins/tab-override/

= Which users can use Apparatus =

Only administrators. Use of Apparatus should be restricted to users who know what they are doing. Appartatus
allows you to run arbitrary PHP code and can do serious damage in the wrongs hands. If you are a Wordpress developer
using Apparatus for testing and debugging it is highly recommend that you do so in a testing environment.

= How can I contribute? =

Donations are a great way to keep me in business. If you are an experienced developer and have ideas (and not just
ideas for apparatus) feel free to contact me. There are several projects I'm working on that could benifit from
some additional help.

= Where can I ask additional questions? =

Here: [Apparatus at Tinsology.net](http://tinsology.net/scripts/apparatus/)

== Changelog ==

= 0.4 =
* Initial Wordpress release