<div class="spbsm-follow-wrapper">
					<h2><?php printf(__('%s: Shortcode', 'spbsm'), $currentButtons);?></h2>
					<div class="short-code-container">
						<div class="short-code-container-explanation">
							<p class="headline-description"><?php printf(__('Copy & paste the shortcode below to show the %s.', 'spbsm'), strtolower($currentButtons));?></p>
						</div>
						<div class="short-code-container-inner">
							<span class="short-code-text"><?php echo __("Shortcode", 'spbsm');?>:</span>
							<span class="short-code-result">[<?php echo $currentsc ?>]</span>
						</div>
					</div>

					<hr>



					<h2><?php printf(esc_html__('%s: Posts & Pages', 'spbsm'), $currentButtons);?></h2>
					<p class="headline-description"><?php printf(esc_html__('Choose where you want the social media %s to display on posts and pages.', 'spbsm'), strtolower($currentButtons));?></p>
					<table>
						<tr>
							<td>
								<?php echo esc_html__("Start of Posts", 'spbsm');?>
							</td>
							<td>
								<input type="hidden" value="0" name="general[posts_addAtStart]">
								<input name="general[posts_addAtStart]" type="checkbox" <?php echo($positionSettings['posts_addAtStart']==1?'checked':'')?>>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo esc_html__("End of Posts", 'spbsm');?>
							</td>
							<td>
								<input type="hidden" value="0" name="general[posts_addAtEnd]">
								<input name="general[posts_addAtEnd]" type="checkbox" <?php echo($positionSettings['posts_addAtEnd']==1?'checked':'')?>>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo esc_html__("Start of Pages", 'spbsm');?>
							</td>
							<td>
								<input type="hidden" value="0" name="general[pages_addAtStart]">
								<input name="general[pages_addAtStart]" type="checkbox" <?php echo($positionSettings['pages_addAtStart']==1?'checked':'')?>>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo esc_html__("End of Pages", 'spbsm');?>
							</td>
							<td>
								<input type="hidden" value="0" name="general[pages_addAtEnd]">
								<input name="general[pages_addAtEnd]" type="checkbox" <?php echo($positionSettings['pages_addAtEnd']==1?'checked':'')?>>
							</td>
						</tr>
					</table>

					<hr>

					<h2><?php printf(esc_html__('%s: Floating Sidebar', 'spbsm'), $currentButtons);?></h2>
					<p class="headline-description"><?php printf(__('Choose settings for the social media %s floating sidebar.', 'spbsm'), strtolower($currentButtons));?></p>
					<table>
						<tr>
							<td>
								<?php echo esc_html__("Floating Sidebar", 'spbsm');?>
							</td>
							<td>
								<select name="floatingSidebar">
									<option value="0" <?php echo($positionSettings['floatingSidebar']==0?'selected':'')?>><?php echo esc_html__("Off", 'spbsm');?></option>
									<option value="1" <?php echo($positionSettings['floatingSidebar']==1?'selected':'')?>><?php echo esc_html__("Left Center", 'spbsm');?></option>
									<option value="2" <?php echo($positionSettings['floatingSidebar']==2?'selected':'')?>><?php echo esc_html__("Right Center", 'spbsm');?></option>
									<option value="3" <?php echo($positionSettings['floatingSidebar']==3?'selected':'')?>><?php echo esc_html__("Bottom Right", 'spbsm');?></option>
									<option value="4" <?php echo($positionSettings['floatingSidebar']==4?'selected':'')?>><?php echo esc_html__("Bottom Left", 'spbsm');?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo __("Background color", 'spbsm');?></td>
						<td><a href="<?php echo esc_url('https://superbthemes.com/plugins/social-media-share-and-follow-buttons/') ?>" target="_blank"><img src="<?php echo $this->base_url ?>assets/img/po-select.png"></a></td>
						</tr>
						<tr>
							<td><?php echo __("Hide on mobile", 'spbsm');?></td>
							<input type="hidden" value="0" name="general[floatingSidebar_hideOnMobile]">
							<td><input type="checkbox" name="general[floatingSidebar_hideOnMobile]" <?php echo($positionSettings['floatingSidebar_hideOnMobile']==1?'checked':'')?>></td>
						</tr>
					</table>
				</div>