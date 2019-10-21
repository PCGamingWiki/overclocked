<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

$GLOBALS['wgExtensionCredits']['skin'][] = array(
	'path'           => __FILE__,
	'name'           => 'Overclocked',
	'namemsg'        => 'skinname-overclocked',
	'descriptionmsg' => 'overclocked-desc',
	'author'         => 'PCGamingWiki Team',
	'url'            => "https://github.com/PCGamingWiki/Overclocked",
	'license-name'   => 'GPL-2.0+',
);

$GLOBALS['wgAutoloadClasses']['SkinOverclocked'] = __DIR__ . '/Overclocked.skin.php';
$GLOBALS['wgMessagesDirs']['Overclocked'] = __DIR__ . '/i18n';

$GLOBALS['wgValidSkinNames']['overclocked'] = 'Overclocked';

$GLOBALS['wgHooks']['GetPreferences'][] = 'wfPrefHook';

function wfPrefHook( $user, &$preferences ) {
	$preferences['overclocked-ads'] = array(
		'type' => 'toggle',
		'label-message' => 'tog-ads',
		'section' => 'rendering/skin',
	);
	$preferences['overclocked-floating-toc'] = array(
		'type' => 'toggle',
		'label-message' => 'tog-floating-toc',
		'section' => 'rendering/skin',
	);

	return true;
}

$GLOBALS['wgDefaultUserOptions']['overclocked-ads'] = 0;
$GLOBALS['wgDefaultUserOptions']['overclocked-floating-toc'] = 0;

$GLOBALS['wgResourceModules']['skins.overclocked.styles'] = array(
	'styles' => array(
		'resources/general-header.less',
		'resources/general-footer.less',
		'resources/general-personalbar.less',
		'resources/general-toc.less',
		'resources/general-inputfields.less',
		'resources/general-specialpages.less',
		'resources/pcgw.less',
		'resources/pcgw-templates.less',
		'resources/pcgw-icons.less',
		'resources/mediawiki.custom.less',
		'resources/mediawiki.mediaviewer.overrides.less',
		'resources/page-home.less',
		'resources/page-extension.less',
		'resources/page-donate.less',
		'resources/page-editing-guide.less',
		'resources/other.less',
	),
	'remoteSkinPath' => 'overclocked',
	'localBasePath' => __DIR__,
);

$GLOBALS['wgResourceModules']['skins.overclocked.js'] = array(
	'scripts' => array(
		'resources/jquery/jquery.waypoints.min.js',
		'resources/jquery/jquery.ba-outside-events.min.js',
		'resources/pcgw.js',
	),
	'remoteSkinPath' => 'overclocked',
	'localBasePath' => __DIR__,
);

$GLOBALS['wgResourceModuleSkinStyles']['overclocked'] = array(
	'mediawiki.special.preferences' => 'resources/mediawiki.special.preferences.less',
	'remoteSkinPath' => 'overclocked',
	'localBasePath' => __DIR__,
);

/**
 * Ad set up - set these in your LocalSettings.php
 * header - banner above article body
 * sidebar - in sidebar (not visible on mobile)
 * footer - above footer, below content
 * infobox - intended for a square ad above infobox
 */
$GLOBALS['wgSkinOverclockedAds'] = array(
	'tag'     => '
<script>
!function(o,n){if(o._localCS={consent:null,loaded:!1,tx:null},o._comscore=o._comscore||[],o.__cmp){
o.__cmp("getVendorConsents",[77],function(o,c){function e(o){return"object"==typeof o}c&&(_localCS.consent=e(o)
&&e(o.vendorConsents)&&o.vendorConsents[77],_localCS.consent=_localCS.consent?"1":"0",t())}),function c(){
o.__cmp("ping",null,function(o){if(!1===o.cmpLoaded){if(null===_localCS.tx)return void(_localCS.tx=setTimeout(c,3e3));
t()}o.cmpLoaded&&(clearTimeout(_localCS.tx),t())})}()}function t(){var o,c,e;_localCS.loaded||
(_comscore.push({c1:"2",c2:"25110922",cs_ucfr:_localCS.consent}),c=(o=n).createElement("script"),
e=o.getElementsByTagName("script")[0],c.async=!0,c.src=("https:"==o.location.protocol?"https://sb":"http://b")
+".scorecardresearch.com/beacon.js",e.parentNode.insertBefore(c,e),_localCS.loaded=!0)}o.__cmp||t()}(window,document);
</script>
<noscript><img src="https://sb.scorecardresearch.com/p?c1=2&c2=25110922&cv=2.0&cj=1" /></noscript>
<!-- End ComScore Tag -->
<script src="/resources/network-n.min.js" async>
</script>',
	'header'  => '<div id="nn_lb1"></div><div id="nn_mobile_mpu1"></div>',
	'sidebar' => '<div id="nn_sky1"></div>',
	'footer'  => '<div id="nn_mobile_lb1_sticky"></div><div id="nn_mobile_mpu3"></div>',
	'infobox' => '<div id="nn_mpu1"></div><div id="nn_mobile_mpu2"></div>',
);
