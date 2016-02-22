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

class SkinOverclocked extends SkinTemplate {
	var $skinname = 'overclocked', $stylename = 'Overclocked',
		$template = 'OverclockedTemplate', $useHeadElement = true;
 
	/**
	 * Add JavaScript via ResourceLoader
	 *
	 * Uncomment this function if your skin has a JS file or files.
	 * Otherwise you won't need this function and you can safely delete it.
	 *
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		/* Viewport meta tag for mobile users. */
		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1' );
		/* Theme meta tag for "brand" coloring in certain browsers.
		   Off-black to match the fixed header. */
		$out->addMeta( 'theme-color', '#333' );

		$out->addModules( array( 'skins.overclocked.js' ) );
	}
 
	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( array(
			'mediawiki.skinning.elements', 'skins.overclocked.styles'
		) );
	}
}

/**
 * BaseTemplate class for Overclocked skin
 *
 * @ingroup Skins
 */
class OverclockedTemplate extends BaseTemplate {
	/**
	 * Outputs the entire contents of the page
	 */
	public function execute() {
		/**
		 * Personal navigation bar
		 */
		$personalTools = $this->getPersonalTools();
		$pageNav = $this->data['content_navigation'];

		if( !$this->data['loggedin'] ) {
			$personalLogin = $personalTools;
			$toggleGoogleAds = true;
			$loggedIn = false;
			$toggleFloatingTOC = true;
		}
		else {
			$loggedIn = true;
			$personalBar[0] = $personalTools['watchlist'];
			$personalBar[1] = $personalTools['mytalk'];
			$personalBar[2] = $personalTools['notifications'];

			$personalFlyout[0] = $personalTools['mycontris'];
			$personalFlyout[4] = $personalTools['preferences'];
			$personalFlyout[6] = $personalTools['logout'];
			foreach ( $personalFlyout as $key => $item ) {
				$personalFlyout[$key]['class'] = "group-start";
			}
			$personalFlyout[1] = $personalTools['newmessages'];
			$personalFlyout[1] = $personalTools['mytalk'];
			$personalFlyout[3] = $personalTools['watchlist'];
			if ( isset( $personalTools['adminlinks'] ) ) {
				$personalFlyout[5] = $personalTools['adminlinks'];
			}
			foreach ( $personalFlyout as $key => $item ) {
				$personalFlyout[$key]['id'] = rtrim( $personalFlyout[$key]['id'] . '-flyout' );
			}
			/* Work around for Echo preferences. */
			$personalFlyout[4]['id'] = 'pt-preferences';

			ksort( $personalFlyout );

			/**
			 * Replace watch button with star
			 */
			$watchStatus = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';

			if ( isset( $pageNav['actions'][$watchStatus] ) ) {
				$pageNav['views'][$watchStatus] = $pageNav['actions'][$watchStatus];
				$pageNav['views'][$watchStatus]['class'] = rtrim( 'icon ' . $pageNav['views'][$watchStatus]['class'] );
				$pageNav['views'][$watchStatus]['primary'] = true;
				unset( $pageNav['actions'][$watchStatus] );
			}

			/**
			 * Preferences
			 */
			$user = $this->getSkin()->getUser();
			$toggleGoogleAds = $user->getOption( 'overclocked-ads' );
			$toggleFloatingTOC = $user->getOption( 'overclocked-floating-toc' );
		}

		global $wgSkinOverclockedAds;
		global $wgTitle;
		$namespace = $wgTitle->getNamespace();

		/**
		 * Disable Google Ads on certain namespaces
		 */

		if ( $namespace == -1 || $namespace == 4 ) {
			$toggleGoogleAds = false;
		}

		$this->html( 'headelement' ); ?>

	<header id="pcgw-header">
		<div id="pcgw-header-sidebar-toggle"></div>

		<div id="pcgw-header-search-toggle"></div>

		<div id="pcgw-header-logo">
			<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
				<img src="//pcgamingwiki.com/images/0/04/PCGamingWiki_notext.svg" alt="<?php $this->text( 'sitename' ) ?>" width="40px" height="40px"/>
			</a>
		</div>

		<div id="header-search">
			<form action="<?php $this->text( 'wgScript' ); ?>" id="searchform">
				<?php
				echo $this->makeSearchInput( array( 'id' => 'searchInput' ) );
				echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
				?>
			</form>
		</div>

		<div id="pcgw-header-sidebar">
			<ul class="header-item-left-container">
				<li class="header-item current"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">Wiki</a>
				<li class="header-item no-mobile"><a href="//community.pcgamingwiki.com/blog">Blog</a>
				<li class="header-item no-mobile"><a href="//community.pcgamingwiki.com/index">Forums</a>
				<li class="header-item no-mobile"><a href="//community.pcgamingwiki.com/files/">Files</a>
				<li class="header-item no-mobile"><a href="//community.pcgamingwiki.com/page/irc">IRC</a>
				<li class="header-item no-mobile"><a href="/wiki/PCGamingWiki:Extension">Extension</a>
				<li class="header-item"><a href="/wiki/PCGamingWiki:Donate">Donate</a>
				<li class="header-item mobile-only"><a href="/wiki/Special:RecentChanges">Recent changes</a>
				<li class="header-item mobile-only"><a href="/wiki/Special:Random">Random article</a>
			</ul>

			<ul id="p-personal">
				<?php
				if( $loggedIn == false ) {
					foreach ( $personalLogin as $key => $item ) {
						echo $this->makeListItem( $key, $item );
					}
				}
				else {
					?>
					<div id="p-personal-logged-in">
						<?php
						foreach ( $personalBar as $key => $item ) {
							echo $this->makeListItem( $key, $item );
						}
						?>
						
						<div id="personal-bar-flyout">
							<div>
								<a href="<?php echo $personalTools['userpage']['links'][0]['href']; ?>"><?php echo $personalTools['userpage']['links'][0]['text']; ?></a>
								<ul>
									<?php
									foreach ( $personalFlyout as $key => $item ) {
										echo $this->makeListItem( $key, $item );
									}
									?>
								</ul>
							</div>
						</div>
					</div>
				<?php
				}
				?>
			</ul>
		</div>
	</header>

	<div id="masthead" <?php if ( $toggleFloatingTOC ) { ?> class="floating-toc-enabled" <?php } ?>>
		<div id="sidebar">
			<div id="pcgw-logo">
				<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
					<img src="<?php $this->text( 'logopath' ); ?>" alt="<?php $this->text( 'sitename' ) ?>" width="145px" height="88px"/>
				</a>
			</div>

			<nav class="sidebar-nav">
				<?php
				$sidebar = $this->getSidebar();
				if ( isset( $sidebar["TOOLBOX"]["content"]["print"] ) ) {
					unset( $sidebar["TOOLBOX"]["content"]["print"] );
				}
				foreach ( $sidebar as $boxName => $box ) { ?>
				<div id="<?php echo Sanitizer::escapeId( $box['id'] ) ?>"<?php echo Linker::tooltip( $box['id'] ) ?>>
				<?php
					if ( is_array( $box['content'] ) ) { ?>
					<ul>
						<li><?php echo htmlspecialchars( $box['header'] ); ?></li>
						<?php
						foreach ( $box['content'] as $key => $item ) {
							echo $this->makeListItem( $key, $item );
						}
						?>
					</ul>
				<?php
					}
					else {
						echo $box['content'];
					}
				} ?>
			</nav>

			<?php if( $toggleGoogleAds == true ) { ?>
				<!-- sidebar ad -->
				<div class="ad-sidebar-container">
					<?php echo $wgSkinOverclockedAds['sidebar']; ?>
				</div>
			<?php } ?>
		</div>

		<div id="main-column">
			<?php if( $toggleGoogleAds == true ) { ?>
				<!-- horizontal header ad -->
				<div class="ad-header-container">
					<?php echo $wgSkinOverclockedAds['header'];?>
				</div>
			<?php } ?>

			<?php if ( $this->data['sitenotice'] ) { ?>
				<div id="site-notice"><?php $this->html( 'sitenotice' ); ?></div>
			<?php } ?>

			<div id="main-content">
				<div id="mw-head">
					<ul id="mw-head-left">
						<?php		
						foreach ( $pageNav['namespaces'] as $key => $tab ) {
							echo $this->makeListItem( $key, $tab );
						}
						?>
					</ul>

					<ul id="mw-head-right">
						<!-- "View", "Edit", "History" buttons -->
						<?php
						foreach ( $pageNav['views'] as $key => $tab ) {
							echo $this->makeListItem( $key, $tab );
						}

						if ( $this->data['isarticle'] && $loggedIn == true ) {
						?>
							<div id="mw-head-more">
								<div>
									<a href="#">More</a>
									<ul>
										<?php
											foreach ( $pageNav['actions'] as $key => $tab ) {
												echo $this->makeListItem( $key, $tab );
											}
										?>
									</ul>
								</div>
							</div>
						<?php 
						}
						?>
					</ul>
				</div>

				<?php if ( $this->data['newtalk'] ) { ?>
					<div class="user-message"><?php $this->html( 'newtalk' ); ?></div>
				<?php } ?>

				<!-- Article header -->
				<div class="article-header">
					<!-- Indicators -->
					<?php echo $this->getIndicators(); ?>

					<!-- Article title -->
					<h1 class="article-title"><?php $this->html( 'title' ); ?></h1>
				</div>

				<!-- Site subtitle, "From PCGamingWiki, the wiki about fixing PC games" -->
				<div id="site-sub">
					<?php if ( $this->data['isarticle'] ) { $this->msg( 'tagline' ); } ?>
					<?php if ( $this->data['subtitle'] ) { ?><div id="sub-sub-title"><?php $this->html( 'subtitle' ); ?></div><?php } ?>
					<?php $this->html( 'undelete' ); ?>
				</div>

				<!-- Body content container. If ads are enabled, an "mw-body-with-ads" class is added so that ad-specific styles can be applied on certain pages. -->
				<?php if( $toggleGoogleAds == false ) { ?>
					<div id="body-content" class="mw-body">
				<?php } else { ?>
					<div id="body-content" class="mw-body mw-body-with-ads">
					<!-- mpu ad -->
						<div id="mpu">
							<?php echo $wgSkinOverclockedAds['infobox'];?>
						</div>
				<?php } ?>

					<?php $this->html( 'bodytext' ); ?>

					<?php $this->html( 'catlinks' ); ?>
					<?php $this->html( 'dataAfterContent' ); ?>
				</div>
			</div>

			<?php if( $toggleGoogleAds == true ) { ?>
				<!-- footer ad -->
				<div class="ad-footer-container">
					<?php echo $wgSkinOverclockedAds['footer'];?>
				</div>
			<?php } ?>
		</div>
	</div>

	<!-- Footer -->
	<footer id="pcgw-footer" class="pcgw-footer">

		<!-- Social links -->
		<div class="pcgw-footer-column">
			<div id="footer-social-links">
				<!-- Facebook Icon -->
				<a href="https://www.facebook.com/PCGamingWiki">
					<div class="icon-container footer-facebook icon"></div>
				</a>

				<!-- Google+ Icon -->
				<a href="https://www.google.com/+PCGamingWiki">
					<div class="icon-container footer-google icon"></div>
				</a>

				<!-- Twitter Icon -->
				<a href="https://www.twitter.com/PCGamingWiki">
					<div class="icon-container footer-twitter icon"></div>
				</a>

				<!-- YouTube Icon -->
				<a href="https://www.youtube.com/user/PCGamingWikiTV">
					<div class="icon-container footer-youtube icon"></div>
				</a>

				<!-- Steam Icon -->
				<a href="http://steamcommunity.com/groups/pcgamingwiki">
					<div class="icon-container footer-steam icon"></div>
				</a>
			</div>
		</div>

		<!-- Other links -->
		<div class="pcgw-footer-column">
			<div id="footer-links-container">
				<ul>
					<li>PCGamingWiki
					<li><a href="/">Wiki</a>
					<li><a href="//community.pcgamingwiki.com/index">Forums</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:About">About us</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:About#Contact">Contact us</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:About#Advertising">Advertising</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:Privacy_policy">Privacy policy</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:General_disclaimer">General disclaimer</a>
					<li><a href="https://github.com/PCGamingWiki">Open source</a>
				</ul>

				<ul>
					<li>Network
					<li><a href="//ftlwiki.com/wiki/FTL:_Faster_Than_Light_Wiki">FTL Wiki</a>
					<li><a href="//gunpointwiki.net/wiki/Gunpoint_Wiki">Gunpoint Wiki</a>
					<li><a href="//prisonarchitectwiki.com/wiki/Home">Prison Architect Wiki</a>
					<li><a href="//siryouarebeinghuntedwiki.com/wiki/Home">Sir, You Are Being Hunted Wiki</a>
					<li><a href="//www.cheapshark.com/">CheapShark</a>
				</ul>

				<ul>
					<li>Powered by
					<li><a href="https://www.mediawiki.org/wiki/MediaWiki">MediaWiki</a>
					<li><a href="https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a>
					<li><a href="https://www.patreon.com/PCGamingWiki">Our generous patrons</a>
					<li>and You &lt;3
				</ul>
			</div>

			<!-- Page last modified, copyright, and disclaimer texts -->
			<?php
			if ( isset( $this->getFooterLinks()['info'] ) ) {
				$footerNav = $this->getFooterLinks()['info'] ?>
				<div id="footer-info-lastmod"><?php $this->html( $footerNav[0] ) ?></div>
				<div id="footer-info-copyright"><?php $this->html( $footerNav[1] ) ?></div>
			<?php
			}
			?>
			<div id="footer-info-disclaimer">Some store links may include affiliate tags. Buying through these links helps support PCGamingWiki (<a href="/wiki/PCGamingWiki:About#Support_us">Learn more</a>).</div>
		</div>
	</footer>

<!-- See schema.org and developers.google.com/structured-data for more information on what this does. -->
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Organization",
 	"name": "PCGamingWiki",
 	"url": "http://pcgamingwiki.com",
	"logo": "http://pcgamingwiki.com/images/d/d8/PCGamingWiki.svg",
	"sameAs": [ "https://www.facebook.com/PCGamingWiki",
		"https://twitter.com/PCGamingWiki",
		"https://plus.google.com/+PCGamingWiki" ]
}
</script>

<!-- Open Sans font family -->
<link rel="stylesheet" media="screen" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600">

<?php $this->printTrail(); ?>
</body>
</html><?php
	}
}
