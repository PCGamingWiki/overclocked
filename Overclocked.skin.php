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
		if( !$out->getUser()->isLoggedIn() ) {
		    $toggleGoogleAds = true;
		}
		else {
		    $user = $out->getUser();
		    $toggleGoogleAds = $user->getOption( 'overclocked-ads' );
		}

		/**
		 * Disable Google Ads on certain namespaces
		 */

		global $wgTitle;
		$namespace = $wgTitle->getNamespace();
		if ( $namespace == -1 || $namespace == 4 ) {
		    $toggleGoogleAds = false;
		}
		if( $toggleGoogleAds ) {
		    $out->addHeadItem('pcgw-admanager', '<script src="https://hb.vntsm.com/v3/live/ad-manager.min.js" type="text/javascript" data-site-id="5ee882ebb519801b8a4d573b" data-mode="scan" async></script>');
		}
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
		
		global $toggleGoogleAds;
		
		if( !$this->data['loggedin'] ) {
			$personalLogin = $personalTools;
			$toggleGoogleAds = true;
			$toggleFloatingTOC = false;
			$loggedIn = false;
		}
		else {
			$loggedIn = true;
			$personalBar[0] = $personalTools['watchlist'];
			$personalBar[1] = $personalTools['mytalk'];
			$personalBar[2] = $personalTools['notifications-alert'];
			$personalBar[3] = $personalTools['notifications-notice'];

			$personalFlyout[0] = $personalTools['mycontris'];
			$personalFlyout[4] = $personalTools['preferences'];
			$personalFlyout[6] = $personalTools['logout'];
			foreach ( $personalFlyout as $key => $item ) {
				$personalFlyout[$key]['class'] = "group-start";
			}
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

	<?php if( $toggleGoogleAds == true ) { ?>
		<?php echo $wgSkinOverclockedAds['tag']; ?>
	<?php } ?>

    <!-- NEW NAV -->

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
				<?php 
					$sidebar = $this->getSidebar();
					if(isset($sidebar["TOOLBOX"]["content"]["print"])) {
						unset($sidebar["TOOLBOX"]["content"]["print"]);
					}
					foreach($sidebar as $boxName => $box) {

						if(!is_array($box['content'])) {
							echo "<li class='header-item dropdown-toggle'><span>Menu</span><div class='dropdown-menu'>".$box['content']."</div></li>";
						}
						else {
							echo "<li class='header-item dropdown-toggle'><span>".htmlspecialchars($box['header'])."</span><div class='dropdown-menu'>";
							foreach ( $box['content'] as $key => $item ) {
								echo "<script>console.log(".$item.")</script>";
								echo "<li class='dropdown-item'><a href='#'>".$item['info']."</a></li>";
							}
							echo "</div></li>";
						}
					}
				?>
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
		<div id="main-column">
			<?php if( $toggleGoogleAds == true ) { ?>
				<!-- horizontal header ad -->
				<div class="ad-header-container">
					<!-- PCGamingWiki - dynamic ad unit -->
					<script>
					    window.isMobile = (/android|ipad|iphone|ipod|samsung/i).test(navigator.userAgent);
					    console.log(isMobile);
					</script>
					<script>
					    var id = window.isMobile ? '5ee8e36fa624ae2e71258530' : '5ee8e359b519801b8a4d57ff';
					    document.write('<div class="vm-placement" data-id="' + id + '"></div>');
					</script>
					<!-- / PCGamingWiki - dynamic ad unit -->
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
							<!-- PCGamingWiki - 300x250 Static (5ee8e351a624ae2e7125852e) - 300x250 - Place in <BODY> of page where ad should appear -->
							<div class="vm-placement" data-id="5ee8e351a624ae2e7125852e"></div>
							<!-- / PCGamingWiki - 300x250 Static (5ee8e351a624ae2e7125852e) -->
						</div>
				<?php } ?>

					<?php $this->html( 'bodytext' ); ?>

					<?php if( $toggleGoogleAds == true ) { ?>
						<!-- PCGamingWiki - Rich Media (5ee8e341a624ae2e7125852c) - 1x1 - Place in <BODY> of page where ad should appear -->
						<div class="vm-placement" data-id="5ee8e341a624ae2e7125852c" style="display:none"></div>
						<!-- / PCGamingWiki - Rich Media (5ee8e341a624ae2e7125852c) -->
					<?php } ?>
						
					<?php $this->html( 'catlinks' ); ?>
					<?php $this->html( 'dataAfterContent' ); ?>
				</div>
			</div>

			<?php if( $toggleGoogleAds == true ) { ?>
				<!-- footer ad -->
				<div class="ad-footer-container">
					<!-- PCGamingWiki - dynamic ad unit -->
					<script>
					    window.isMobile = (/android|ipad|iphone|ipod|samsung/i).test(navigator.userAgent);
					    console.log(isMobile);
					</script>
					<script>
					    var id = window.isMobile ? '5ee8e36fa624ae2e71258530' : '5ee8e359b519801b8a4d57ff';
					    document.write('<div class="vm-placement" data-id="' + id + '"></div>');
					</script>
					<!-- / PCGamingWiki - dynamic ad unit -->
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

				<!-- Twitter Icon -->
				<a href="https://www.twitter.com/PCGamingWiki">
					<div class="icon-container footer-twitter icon"></div>
				</a>

				<!-- YouTube Icon -->
				<a href="//www.youtube.com/user/PCGamingWikiTV">
					<div class="icon-container footer-youtube icon"></div>
				</a>

				<!-- Steam Icon -->
				<a href="//steamcommunity.com/groups/pcgamingwiki">
					<div class="icon-container footer-steam icon"></div>
				</a>
			</div>
		</div>

		<!-- Other links -->
		<div class="pcgw-footer-column">
			<div id="footer-links-container">
				<ul>
					<li>PCGamingWiki
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:About">About us</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:About#Contact">Contact us</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:About#Advertising">Advertising</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:Privacy_policy">Privacy policy</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:General_disclaimer">General disclaimer</a>
				</ul>

				<ul>
					<li>Friends
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:Partnerships">Partnerships</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:Extension">Extension</a>
					<li><a href="//pcgamingwiki.com/wiki/PCGamingWiki:API">API</a>
					<li><a href="https://www.gog.com?pp=708a77db476d737e54b8bf4663fc79b346d696d2">GOG.com</a>
					<li><a href="https://gamesplanet.com?ref=pcgwiki">Gamesplanet</a>
					<li><a href="https://www.cheapshark.com">CheapShark</a>
				</ul>

				<ul>
					<li>Powered by
					<li><a href="https://www.mediawiki.org/wiki/MediaWiki">MediaWiki</a>
					<li><a href="https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a>
					<li><a href="https://www.mediawiki.org/wiki/Extension:Cargo">Cargo</a>
					<li><a href="https://github.com/PCGamingWiki">Open source</a>
					<li><a href="https://www.patreon.com/PCGamingWiki">Patrons</a>
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
 	"url": "//pcgamingwiki.com",
	"logo": "//pcgamingwiki.com/images/d/d8/PCGamingWiki.svg",
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
