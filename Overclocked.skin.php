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
		if( !$out->getUser()->isRegistered() ) {  // 1.43: was isLoggedIn
		    $toggleGoogleAds = true;
		}
		else {
		    $user = $out->getUser();
		    // 1.43: was getOption
		    $toggleGoogleAds = MediaWiki\MediaWikiServices::getInstance()
		        ->getUserOptionsLookup()->getOption( $user, 'overclocked-ads' );
		}

		/**
		 * Disable Ads on certain namespaces
		 */

		global $wgTitle;
		$namespace = $wgTitle->getNamespace();
		if ( $namespace == -1 || $namespace == 4 ) {
		    $toggleGoogleAds = false;
		}
		if( $toggleGoogleAds ) {
		    $out->addHeadItem('pcgw-admanager', '<script data-cfasync="false">   window.nitroAds = window.nitroAds || {     createAd: function() {       return new Promise(e => { window.nitroAds.queue.push(["createAd", arguments, e]) })     },     addUserToken: function() {       window.nitroAds.queue.push(["addUserToken", arguments])     },     queue: []   }; </script><script data-cfasync="false" async src="https://s.nitropay.com/ads-51.js"></script>');
		}
		$out->addModules( array( 'skins.overclocked.js' ) );
		// 1.43: was setupSkinUserCss; base styles now come from the SkinModule
		$out->addModuleStyles( array( 'skins.overclocked.styles' ) );
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
			$personalBar[2] = $personalTools['notifications-alert'];
			$personalBar[3] = $personalTools['notifications-notice'];

			$personalFlyout[0] = $personalTools['watchlist'];
			$personalFlyout[1] = $personalTools['mytalk'];
			$personalFlyout[2] = $personalTools['mycontris'];
			$personalFlyout[3] = $personalTools['preferences'];
			$personalFlyout[5] = $personalTools['logout'];
			
			foreach ( $personalFlyout as $key => $item ) {
				$personalFlyout[$key]['class'] = "group-start";
			}
			
			if ( isset( $personalTools['adminlinks'] ) ) {
				$personalFlyout[4] = $personalTools['adminlinks'];
			}
			foreach ( $personalFlyout as $key => $item ) {
				$personalFlyout[$key]['id'] = rtrim( $personalFlyout[$key]['id'] . '-flyout' );
			}
			/* Work around for Echo preferences. */
			$personalFlyout[3]['id'] = 'pt-preferences';

			ksort( $personalFlyout );

			/**
			 * Replace watch button with star
			 */
			// 1.43: was isWatched
			$watchStatus = MediaWiki\MediaWikiServices::getInstance()->getWatchlistManager()
				->isWatched( $this->getSkin()->getUser(), $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';

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
			// 1.43: was getOption
			$userOptionsLookup = MediaWiki\MediaWikiServices::getInstance()->getUserOptionsLookup();
			$toggleGoogleAds = $userOptionsLookup->getOption( $user, 'overclocked-ads' );
			$toggleFloatingTOC = $userOptionsLookup->getOption( $user, 'overclocked-floating-toc' );
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

		?>

	<?php if( $toggleGoogleAds == true ) { ?>
		<?php echo $wgSkinOverclockedAds['tag']; ?>
	<?php } ?>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-10XTTXMB7R"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-10XTTXMB7R');
	</script>

    <!-- NEW NAV -->

	<header id="pcgw-header">
		<div class="pcgw-header_limit">
			<div id="pcgw-header-sidebar-toggle"></div>

			<div id="pcgw-header-search-toggle"></div>

			<!-- LOGO -->
			<div id="pcgw-header-logo">
				<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
					<img src="//pcgamingwiki.com/images/0/04/PCGamingWiki_notext.svg" alt="<?php $this->text( 'sitename' ) ?>" width="40px" height="40px"/>
				</a>
			</div>

			<div style="display: flex; align-items: center; justify-content: space-between; flex: 1;">
				<!-- MENU -->
				<div id="pcgw-header-sidebar">
					<ul class="header-item-left-container">
					<?php
						$sidebar = $this->getSidebar();

						foreach($sidebar as $boxName => $box) {
							?>
							<li   id='<?php echo Sanitizer::escapeIdForAttribute( $box['id'] ) ?>'<?php echo Linker::tooltip( $box['id'] ) ?>>
								<span class="header-item dropdown-toggle"><?php echo htmlspecialchars( $box['header'] ); ?></span>
								<?php
									if(is_array($box['content'])) { ?>
										<ul class="dropdown-menu">
											<?php foreach($box['content'] as $key => $item) {
												echo $this->makeListItem($key, $item);
											} ?>
										</ul>
								<?php
									}
								?>
							</li>
							<?php
						} ?>
                    </ul>
                    <ul id="p-personal-mobile">
				<?php
				if( $loggedIn == false ) {
					foreach ( $personalLogin as $key => $item ) {
						echo $this->makeListItem( $key, $item );
					}
				}
				else {
					?>
					<div id="p-personal-logged-in">						
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
				<div class="rightside-wrap">
                    <!-- SEARCH -->
                    <div id="header-search">
                        <form action="<?php $this->text( 'wgScript' ); ?>" id="searchform">
                            <?php
                            echo $this->makeSearchInput( array( 'id' => 'searchInput' ) );
                            echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
                            ?>
                        </form>
                    </div>
                    <!-- LOGIN -->
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
            </div>
		</div>
	</header>

	<div id="masthead" <?php if ( $toggleFloatingTOC ) { ?> class="floating-toc-enabled" <?php } ?>>
		<div id="main-column">
			<?php if( $toggleGoogleAds == true ) { ?>
				<!-- Ad - Horizontal banner -->
				<div class="ad-header-container">
					<div id="ad-header" style="height:250px"></div>

					<script>
					window['nitroAds'].createAd('ad-header', {
					"height": 250,
					"delayLoading": true,
					"report": {
						"enabled": true,
						"icon": true,
						"wording": "Report Ad",
						"position": "bottom-right"
					}
					});
					</script>
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
				
				<?php if( $toggleGoogleAds == true ) { ?>
				<!-- Ad - Video -->
					<div id="ad-video"></div>

					<script>
					window['nitroAds'].createAd('video', {
					"format": "video-nc",
					"video": {}
					});
					</script>
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
					<!-- Ad - MPU -->
						<div id="mpu">
							<div id="ad-mpu" style="height:250px"></div>

							<script>
							window['nitroAds'].createAd('ad-mpu', {
							"height": 250,
							"delayLoading": true,
							"report": {
								"enabled": true,
								"icon": true,
								"wording": "Report Ad",
								"position": "bottom-right"
							}
							});
							</script>
						</div>
				<?php } ?>

					<?php $this->html( 'bodytext' ); ?>

					<?php if( $toggleGoogleAds == true ) { ?>
					<!-- Ad - Interstital -->
						<script>
						window['nitroAds'].createAd('ad-interstital', {
						"format": "interstitial"
						});
						</script>

					<!-- Ad - Anchor -->
						<script>
						window['nitroAds'].createAd('ad-anchor', {
						"format": "anchor-v2",
						"anchor": "bottom",
						"anchorBgColor": "rgb(0 0 0 / 80%)",
						"anchorClose": true,
						"anchorPersistClose": false,
						"anchorStickyOffset": 0,
						"mediaQuery": "(min-width: 0px)",
						"report": {
							"enabled": true,
							"icon": true,
							"wording": "Report Ad",
							"position": "top-right"
						}
						});
						</script>

					<!-- Ad - Sticky side rail 1 -->
						<script>
						window['nitroAds'].createAd('ad-stick-side-rail-1', {
						"format": "rail",
						"rail": "left",
						"railOffsetTop": 0,
						"railOffsetBottom": 0,
						"railCollisionWhitelist": ["*"],
						"railCloseColor": "#666666",
						"railSpacing": 10,
						"railStack": false,
						"railStickyTop": 0,
						"railVerticalAlign": "center",
						"report": {
							"enabled": true,
							"icon": true,
							"wording": "Report Ad",
							"position": "top-right"
						}
						});
						</script>

					<!-- Ad - Sticky side rail 2 -->
						<script>
						window['nitroAds'].createAd('ad-stick-side-rail-2', {
						"format": "rail",
						"rail": "right",
						"railOffsetTop": 0,
						"railOffsetBottom": 0,
						"railCollisionWhitelist": ["*"],
						"railCloseColor": "#666666",
						"railSpacing": 10,
						"railStack": false,
						"railStickyTop": 0,
						"railVerticalAlign": "center",
						"report": {
							"enabled": true,
							"icon": true,
							"wording": "Report Ad",
							"position": "top-right"
						}
						});
						</script>
					<?php } ?>
						
					<?php $this->html( 'catlinks' ); ?>
					<?php $this->html( 'dataAfterContent' ); ?>
				</div>
			</div>

			<?php if( $toggleGoogleAds == true ) { ?>
				<!-- Ad - Footer -->
				<div class="ad-footer-container">
					<div id="ad-footer" style="height:250px"></div>

					<script>
					window['nitroAds'].createAd('ad-footer', {
					"height": 250,
					"delayLoading": true,
					"report": {
						"enabled": true,
						"icon": true,
						"wording": "Report Ad",
						"position": "bottom-right"
					}
					});
					</script>
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
				
				<!-- Discord Icon -->
				<a href="https://discord.gg/SU27ykMcsD">
					<div class="icon-container footer-discord icon"></div>
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
					<li><a href="https://www.applegamingwiki.com">AppleGamingWiki</a>
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
			// 1.43: getFooterLinks()['info'] is a list of key names
			$footerInfo = $this->getFooterLinks()['info'] ?? [];
			foreach ( $footerInfo as $footerKey ) { ?>
				<div id="footer-info-<?php echo $footerKey ?>"><?php $this->html( $footerKey ) ?></div>
			<?php }
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

<?php
// 1.43: printTrail() removed
echo $this->get( 'bottomscripts' );
echo $this->get( 'reporttime' );
?>

<!-- GOG Affiliate Link Tag -->
<script type="text/javascript" src="https://cdn.adt598.com/atag.js?as=1649876489" charset="UTF-8"></script>
		
</body>
</html><?php
	}
}
