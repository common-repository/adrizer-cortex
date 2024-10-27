<?php

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$theme_color = cortex_get_option('cortex_theme_color', '#f94213');

$theme_hover_color = cortex_get_option('cortex_theme_hover_color', '#888888');

$debug_mode = cortex_get_option('cortex_enable_debug', false);

$cortexTracking = cortex_get_option('cortex_tracking', false);

$organicCampaignId = cortex_get_option('cortex_organic_campaign_id', '');

$testMode = cortex_get_option('cortex_enable_test_mode', false);

$testPage = cortex_get_option('cortex_test_page', '');

$customTrackingHead = cortex_get_option('cortex_custom_tracking_head', '');

$customTrackingBody = cortex_get_option('cortex_custom_tracking_body', '');

$customTrackingFoot = cortex_get_option('cortex_custom_tracking_foot', '');

// get all posts
$args = array(
  'numberposts' => -1
);
$posts = get_posts($args);
foreach ($posts as $key => $post) {
    if ($post->post_name == "") {
        unset($posts[$key]);
    }
}
?>

<div class="cortex-settings">

    <div class="postbox">

        <!-------------------
        OPTIONS HOLDER START
        -------------------->
        <div class="cortex-menu-options settings-options">

            <div class="cortex-inner">

                <!-------------------  LI TABS -------------------->

                <ul class="cortex-tabs-wrap">
                    <li class="cortex-tab selected" data-target="general"><i
                        class="cortex-icon dashicons dashicons-admin-generic"></i><?php echo __('General', 'cortex') ?>
                    </li>
                    <li class="cortex-tab" data-target="custom-tracking"><i
                        class="cortex-icon dashicons dashicons-editor-code"></i><?php echo __('Custom Tracking', 'cortex') ?>
                    </li>
                    <li class="cortex-tab" data-target="debugging"><i
                        class="cortex-icon dashicons dashicons-warning"></i><?php echo __('Debugging', 'cortex') ?>
                    </li>
                </ul>

                <!------------------- General TAB -------------------->

                <div class="cortex-tab-content general">

                    <!---- Enable Cortex Tracking -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Tracking', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">
                        <div class="cortex-spacer" style="height: 15px"></div>
                        <label
                            class="cortex-label cortex-label-outside"><?php echo __('Enable Cortex Tracking', 'cortex') ?></label>
                        <div class="cortex-row cortex-type-checkbox cortex-field">
                            <p class="cortex-desc"><?php echo __('Enables cortex tracking on your site.', 'cortex') ?></p>
                            <div class="cortex-toggle">
                                <input type="checkbox" class="cortex-checkbox" name="cortex_tracking" id="cortex_tracking"
                                       data-default="" value="<?php echo $cortexTracking ?>" <?php echo checked(!empty($cortexTracking), 1, false) ?>>
                                <label for="cortex_tracking"></label>
                            </div>
                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                    <!---- Organic Campaign ID -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Organic Campaign ID', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">

                        <div class="cortex-row cortex-field cortex-organic-campaign-id">
                            <label
                                class="cortex-label"><?php echo __('Organic Campaign ID', 'cortex') ?></label>
                            <div class="cortex-spacer" style="height: 5px"></div>
                            <p class="cortex-desc"><?php echo __('Enter an optional organic campaign id', 'cortex') ?></p>

                            <div class="cortex-spacer" style="height: 15px"></div>

                            <input class="cortex-textarea" name="cortex_organic_campaign_id" id="cortex_organic_campaign_id" value="<?php echo $organicCampaignId ?>" rows="20" cols="120"></input>

                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                    <!---- Enable Testing Mode -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Testing Mode', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">
                        <div class="cortex-spacer" style="height: 15px"></div>
                        <label
                            class="cortex-label cortex-label-outside"><?php echo __('Enable Testing Mode', 'cortex') ?></label>
                        <div class="cortex-row cortex-type-checkbox cortex-field">
                            <p class="cortex-desc"><?php echo __('Only attaches tracking scripts to the specified test page.', 'cortex') ?></p>
                            <div class="cortex-toggle">
                                <input type="checkbox" class="cortex-checkbox" name="cortex_enable_test_mode" id="cortex_enable_test_mode"
                                       data-default="" value="<?php echo $testMode ?>" <?php echo checked(!empty($testMode), 1, false) ?>>
                                <label for="cortex_enable_test_mode"></label>
                            </div>
                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                    <!---- Test Page -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Test Page', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">

                        <div class="cortex-row cortex-field cortex-organic-campaign-id">
                            <label
                                class="cortex-label"><?php echo __('Test Page', 'cortex') ?></label>
                            <div class="cortex-spacer" style="height: 5px"></div>
                            <p class="cortex-desc"><?php echo __('Enter the slug for the test page.', 'cortex') ?></p>

                            <div class="cortex-spacer" style="height: 15px"></div>

                            <select id="cortex_test_page" name="cortex_test_page">
                              <option value="" <?php if ($testPage == '' || $testPage == null): ?> selected <?php endif; ?>>Select A Test Post</option>
                              <?php foreach ($posts as $post) {
    ?>
                                <option value="<?php echo $post->post_name ?>" <?php if ($testPage == $post->post_name):?> selected <?php endif; ?>><?php echo $post->post_title ?></option>
                              <?php

} ?>
                            </select>

                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                </div>

                <!------------------- Custom Tracking TAB -------------------->

                <div class="cortex-tab-content custom-tracking">

                    <!---- Custom Tracking (Header) -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Custom Tracking (Header)', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">

                        <div class="cortex-row cortex-field cortex-custom-css">
                            <label
                                class="cortex-label"><?php echo __('Custom Tracking (Header)', 'cortex') ?></label>
                            <div class="cortex-spacer" style="height: 5px"></div>
                            <p class="cortex-desc"><?php echo __('Please enter additional tracking js. These scripts will appear in the header.', 'cortex') ?></p>

                            <div class="cortex-spacer" style="height: 15px"></div>

                            <textarea class="cortex-textarea" name="cortex_custom_tracking_head" id="cortex_custom_tracking_head" rows="20" cols="120"><?php echo $customTrackingHead ?></textarea>

                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                    <!---- Custom Tracking (Body) -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Custom Tracking (Body)', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">

                        <div class="cortex-row cortex-field cortex-custom-css">
                            <label
                                class="cortex-label"><?php echo __('Custom Tracking (Body)', 'cortex') ?></label>
                            <div class="cortex-spacer" style="height: 5px"></div>
                            <p class="cortex-desc"><?php echo __('Please enter additional tracking js. These scripts will appear in the body.', 'cortex') ?></p>

                            <div class="cortex-spacer" style="height: 15px"></div>

                            <textarea class="cortex-textarea" name="cortex_custom_tracking_body" id="cortex_custom_tracking_body" rows="20" cols="120"><?php echo $customTrackingBody ?></textarea>

                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                    <!---- Custom Tracking (Footer) -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Custom Tracking (Footer)', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">

                        <div class="cortex-row cortex-field cortex-custom-css">
                            <label
                                class="cortex-label"><?php echo __('Custom Tracking (Footer)', 'cortex') ?></label>
                            <div class="cortex-spacer" style="height: 5px"></div>
                            <p class="cortex-desc"><?php echo __('Please enter additional tracking js. These scripts will appear in the footer.', 'cortex') ?></p>

                            <div class="cortex-spacer" style="height: 15px"></div>

                            <textarea class="cortex-textarea" name="cortex_custom_tracking_foot" id="cortex_custom_tracking_foot" rows="20" cols="120"><?php echo $customTrackingFoot ?></textarea>

                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                </div>

                <!------------------- Debugging TAB -------------------->

                <div class="cortex-tab-content debugging">

                    <!---- Enable script debugging -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('Debug Mode', 'cortex') ?></h3>
                    </div>
                    <div class="cortex-inner cortex-box-inner">
                        <div class="cortex-spacer" style="height: 15px"></div>
                        <label
                            class="cortex-label cortex-label-outside"><?php echo __('Enable Script Debug Mode', 'cortex') ?></label>
                        <div class="cortex-row cortex-type-checkbox cortex-field">
                            <p class="cortex-desc"><?php echo __('Use unminified Javascript files instead of minified ones to help developers debug an issue', 'cortex') ?></p>
                            <div class="cortex-toggle">
                                <input type="checkbox" class="cortex-checkbox" name="cortex_enable_debug" id="cortex_enable_debug"
                                       data-default="" value="<?php echo $debug_mode ?>" <?php echo checked(!empty($debug_mode), 1, false) ?>>
                                <label for="cortex_enable_debug"></label>
                            </div>
                        </div>
                    </div>

                    <div class="cortex-clearfix"></div>

                    <!---- System Info -->
                    <div class="cortex-box-side">
                        <h3><?php echo __('System Info', 'cortex') ?></h3>
                    </div>

                    <div class="cortex-inner cortex-box-inner">
                      <div id="cortex-buttons-wrap" style="float:right; margin-top:20px; margin-right:20px">
                        <a class="cortex-button" data-action="cortex_save_settings" id="create" style="background:#40bca4">
                          Export
                        </a>
                      </div>

                        <div class="cortex-row cortex-field">
                            <label
                                class="cortex-label"><?php echo __('System Information', 'cortex') ?></label>
                            <p class="cortex-desc"><?php echo __(
                              'Server setup information useful for debugging purposes.
                              </br>
                              <a download="sysInfo.txt" id="downloadlink" style="display: none">Download</a>',
                              'cortex'); ?></p>

                            <div class="cortex-spacer" style="height: 15px"></div>

                            <p class="debug-info" id="sysInfo"><?php echo nl2br(cortex_get_sysinfo()); ?></p>

                        </div>

                    </div>

                    <div class="cortex-clearfix"></div>

                </div>

                <!-------------------  OPTIONS HOLDER END  -------------------->
            </div>

        </div>

        <!------------------- BUILD PANEL SETTINGS -------------------->

    </div>

</div>

<script>
(function () {
var textFile = null,
  makeTextFile = function (text) {
    var data = new Blob([text], {type: 'text/plain'});

    // If we are replacing a previously generated file we need to
    // manually revoke the object URL to avoid memory leaks.
    if (textFile !== null) {
      window.URL.revokeObjectURL(textFile);
    }

    textFile = window.URL.createObjectURL(data);

    return textFile;
  };


  var create = document.getElementById('create'),
    textbox = document.getElementById('sysInfo');

  create.addEventListener('click', function () {
    var link = document.getElementById('downloadlink');
    link.href = makeTextFile(textbox.innerText);
    link.click();
  }, false);
})();
</script>
