=== Advanced Custom Tooltips ===
Contributors: Shellbot
Tags: tooltips, tooltip, hover, hint, bubble, textbubble, shortcode,
Requires at least: 3.0.1
Tested up to: 4.3
Donate link: http://patreon.com/shellbot
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create tooltips and display automatically or with a shortcode. More advanced features coming soon!

== Description ==

Advanced Custom Tooltips lets you create tooltips that will be automatically inserted into your post
content. Tooltips can contain text, HTML, images, clickable links, shortcodes, whatever you want!

Alternatively, create a tooltip within the post editor and place it exactly where you want it using the
handy shortcode.

Tooltip appearance is completely controlled through the plugin settings. Screenshots coming soon.

**Features coming soon**

* Create tooltip "templates" to make adding a new tooltip with a particular layout as quick as possible. Useful
for creating multiple tooltips for similar types of content.
* Insert Tooltip button for post editor.
* Your feature requests welcome.

If you'd like to support the ongoing development of this plugin, please [take a look at my Patreon page](http://patreon.com/shellbot "Support this plugin on Patreon").

**General usage**

After installing Advanced Custom Tooltips, you can create a new tooltip just like you'd create a new post, under the Tooltips
menu item. The title will be your tooltip trigger text, and the post editor controls the content of the tooltip.

You can also adjust the settings for auto-linking behaviour and tooltip appearance under Tooltips > Settings.

**Tooltip shortcode**

To insert tooltips manually, Advanced Custom Tooltips provides a useful shortcode.

Display a previously created tooltip:

`[act_tooltip id="XX"/]`

Display a one-off custom textual tooltip:

`[act_tooltip title="my tooltip trigger text" content="My awesome tooltip content. It is great."]`

More information on shortcode parameters and usage can be found on [the plugin release page](http://codebyshellbot.com/wordpress-plugins/advanced-custom-tooltips "Advanced Custom Tooltips").


== Installation ==

1. Upload the 'advanced-custom-tooltips' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a tooltip under the new "Tooltips" menu item
4. Enjoy!

== Frequently Asked Questions ==

= How do I find my tooltip ID number for use with the shortcode? =

When editing a tooltip, the URL in your browser will look something like this:
`yoursite.com/wp-admin/post.php?post=XX&action=edit`

The number shown where you see XX in the above example is your ID.

== Changelog ==

= 1.0.1 =
* Bugfix: Activation warning, tooltips limit & Bootstrap class conflict.
= 1.0.0 =
* First release

== Upgrade Notice ==

= 1.0.1 =
* Bugfix: Activation warning, tooltips limit & Bootstrap class conflict.
