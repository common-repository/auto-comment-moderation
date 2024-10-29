=== Plugin Name ===
Contributors: moderatehatespeech.com
Donate link: https://moderatehatespeech.com
Tags: comments, moderation, toxic, content
Requires at least: 4.5.1
Tested up to: 6.0.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically send toxic and hateful comments to the comment moderation queue.

== Description ==

This plugin implements ModerateHatespeech.com's machine learning API to detect and flag potentially toxic and hateful comments. A configurable threshold can be set and comments with scores above that threshold will be sent to the moderation queue for administrative approval.

A free API key from [ModerateHatespeech.com](https://moderatehatespeech.com) is required. 

### Defining Toxic

We define "Toxic" as "any content a reasonable, PG-13 forum wouldn't want for content reasons."

This does not include:

* Spam or Advertisements
* NSFW/Explicit Content
* Forum-specific Etiquette

This does include:

* Hate Speech
* Verbal Insults
* Slurs Directed Towards a Group

More details on toxicity, as well as model accuracy and bias can be found on [ModerateHatespeech.com](https://moderatehatespeech.com/framework/)

This plugin is part of the ModerateHatespeech project, an initiative to research and reduce online hate. Use of the API constitues acceptance of [ModerateHatespeech's Terms of Service and Privacy Policies](https://moderatehatespeech.com/terms-and-privacy).

== Installation ==

1. Upload the .zip file of this plugin to WP Admin
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0.0 =
* Initial plugin release

