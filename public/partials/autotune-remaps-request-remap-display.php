<form id="autotune_remap_form" ng-controller="SubmitRemapController" action="" method="POST" enctype="multipart/form-data">

<?php 
    global $wp_session;

    if(isset($wp_session['autotune_submit_remap_errors']) && count($wp_session['autotune_submit_remap_errors']) > 0) {
        echo '<ul class="errors">';
        foreach($wp_session['autotune_submit_remap_errors'] as $key => $value) {
            echo '<li>'.$key.': '.$value.'</li>';
        }
        echo '</ul>';
    } 

    $current_user = wp_get_current_user();
 
    if(isset($wp_session['autotune_submit_remap_id']) && $wp_session['autotune_submit_remap_id'] != 0 && $wp_session['autotune_submit_remap_errors'] != 0) {

        echo "<div class='success'>
                Thanks - your remap request has been received.<br/>
                We will update you via email at ".$current_user->user_email."<br/>
                <a href='/my-account/'>Request another</a>
            </div>";
    } else {
?>

    <div class="input_group">
        <input type="text" name="autotune_manufacturer" value="<?php echo $_POST['autotune_manufacturer'] ?? ''; ?>"/>
        <label for="manufacturer">Manufacturer:</label>
    </div>

    <div class="input_group">
        <input type="text" name="autotune_model" value="<?php echo $_POST['autotune_model'] ?? ''; ?>"/>
        <label for="model">Model:</label>
    </div>

    <div class="input_group">
        <input type="number" name="autotune_year" value="<?php echo $_POST['autotune_year'] ?? ''; ?>"/>
        <label for="year">Year:</label>
    </div>

    <div class="input_group">
        <input type="text" name="autotune_engine_size" value="<?php echo $_POST['autotune_engine_size'] ?? ''; ?>"/>
        <label for="engine_size">Engine Type:</label>
    </div>

    <div class="input_group">
        <input type="file" name="autotune_file" value="<?php echo $_POST['autotune_file'] ?? ''; ?>"/>
        <label for="file">ECU File:</label>
    </div>

    <div class="input_group">
        <input type="text" name="autotune_ecu_type" value="<?php echo $_POST['autotune_ecu_type'] ?? ''; ?>"/>
        <label for="autotune_ecu_type">ECU Type:</label>
    </div>

    <div>
        <input type="checkbox" name="autotune_performance_tuning" id="autotune_performance_tuning" 
        <?php echo isset($_POST['autotune_performance_tuning']) ? 'checked' : ''; ?>/>
        <label for="autotune_performance_tuning">Performance Tuning</label>
    </div>

    <div>
        <input type="checkbox" name="autotune_lambda_o2_decat" id="autotune_lambda_o2_decat"
        <?php echo isset($_POST['autotune_lambda_o2_decat']) ? 'checked' : ''; ?>/>
        <label for="autotune_lambda_o2_decat">Lambda O2 Decat</label>
    </div>

    <div>
        <input type="checkbox" name="autotune_dpf_removal" id="autotune_dpf_removal"
        <?php echo isset($_POST['autotune_dpf_removal']) ? 'checked' : ''; ?>/>
        <label for="autotune_dpf_removal">DPF Removal</label>
    </div>

    <div>
        <input type="checkbox" name="autotune_adblue_scr_nox" id="autotune_adblue_scr_nox"
        <?php echo isset($_POST['autotune_adblue_scr_nox']) ? 'checked' : ''; ?>/>
        <label for="autotune_adblue_scr_nox">Adblue SCR NOx</label>
    </div>

    <div>
        <input type="checkbox" name="autotune_inlet_swirl_throttle" id="autotune_inlet_swirl_throttle"
        <?php echo isset($_POST['autotune_inlet_swirl_throttle']) ? 'checked' : ''; ?>/>
        <label for="autotune_inlet_swirl_throttle">Inlet Swirl Throttle</label>
    </div>

    <div>
        <input type="checkbox" name="autotune_egr_removal" id="autotune_egr_removal"
        <?php echo isset($_POST['autotune_egr_removal']) ? 'checked' : ''; ?>/>
        <label for="autotune_egr_removal">EGR Removal</label>
    </div>

    <div>
        <input type="checkbox" name="autotune_dtc" id="autotune_dtc"
        <?php echo isset($_POST['autotune_dtc']) ? 'checked' : ''; ?>/>
        <label for="autotune_dtc">DTC</label>
    </div>

    <div class="input_group">
        <input type="text" name="autotune_dtc_p_codes" value="<?php echo $_POST['autotune_dtc_p_codes'] ?? ''; ?>"/>
        <label for="autotune_dtc_p_codes">(DTC) P Codes:</label>
    </div>

    <div class="input_group">
        <textarea name="autotune_other_notes"><?php echo $_POST['autotune_other_notes'] ?? ''; ?></textarea>
        <label for="autotune_other_notes">Reading Tool and Other Information:</label>
    </div>

    <input type="hidden" name="autotune_return_url" value=""/>

    <input type="submit" value="Request Remap"/>

</form>

<?php } ?>