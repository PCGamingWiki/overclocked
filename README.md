Overclocked
========

Overclocked is a skin for [MediaWiki](https://www.mediawiki.org/wiki/MediaWiki) built by the [PCGamingWiki](http://pcgamingwiki.com) development team. It supports MediaWiki v1.25 and above. Note that support for specific extensions used by PCGamingWiki are hard-coded into the skin. If you are not using these extensions, you may experience problems as a result.


### Notable features

* Fully responsive design, no `m.example.com` required!
* Custom-built Personal User Bar
* A "floating" Table of Contents
* SVG icons embedded in CSS


### Browser support
* Firefox 39+
* Chrome 43+
* Safari 8+
* Opera 30+

Prior versions of these browsers should work perfectly fine, but any bugs that crop up outside of the above versions will not be given priority.

If, for some reason, you're still supporting IE and would like to use this skin for your wiki, tread carefully.


### File structure

* `Overclocked.php`
* `Overclocked.skin.php`

#### `resources`

**General**
* `general-footer.less` - Footer
* `general-header.less` - Header
* `general-inputfields.less` Input fields, e.g. text areas, text fields, HTML select elements, etc.
* `general-personalbar.less` - User dropdown
* `general-specialpages.less` - Special pages
* `general-toc.less` - Table of Contents

**Page-specific styles**
* `page-home.less` - Styles for Home
* `page-donate.less` - Styles for "PCGamingWiki:Donate"
* `page-editing-guide.less` - Styles for "PCGamingWiki:Editing guide"
* `page-extension.less` - Styles for "PCGamingWiki:Extension page"

**PCGamingWiki-specific styles and scripts**
* `pcgw.less` - html/body styles, custom article header styles, masthead, main-content container, sidebar, PCGamingWiki logo
* `pcgw-templates.less` - PCGamingWiki templates, e.g. infoboxes, settings tables, fixboxes, etc.
* `pcgw-icons.less` - PCGamingWiki icons for templates (from `icons/`)
* `other.less` - Styling for generic MediaWiki tables
* `pcgw.js` - Scripts for the mobile header, the table of contents, and footer icon animations

**MediaWiki-related styles**
* `mediawiki.custom.less` - Custom styles for various built-in MediaWiki features, e.g. Watchlist star, references, categories, Echo notifications, the site notice, etc/
* `mediawiki.mediaviewer.overrides.less` - Stylesheet for overriding some problems with the Wikimedia Foundation's MediaViewer extension
* `mediawiki.special.preferences.less` - Styles for "Special:Preferences", borrowed from the Vector skin


### Credits

* Styling for preferences page and several UI icons borrowed from the [Vector skin](https://www.mediawiki.org/wiki/Skin:Vector)
* Tux icon from @johndrinkwater ([repo](https://github.com/johndrinkwater/svg-tux))
* jQuery


### License

Overclocked is released under the [GNU General Public License (GPL)](http://opensource.org/licenses/GPL-2.0).
