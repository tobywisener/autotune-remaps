<div ng-show="!ctrl.loaded">Loading, please wait...</div>
<form id="autotune_remap_charges_form" action="" method="POST" enctype="multipart/form-data" ng-cloak>
<h3>Charges</h3>
<?php
    global $wp_session;

    if(isset($wp_session['autotune_submit_charge_errors']) && count($wp_session['autotune_submit_charge_errors']) > 0) {
        echo '<ul class="errors">';
        foreach($wp_session['autotune_submit_charge_errors'] as $key => $value) {
            echo '<li>'.$key.': '.$value.'</li>';
        }
        echo '</ul>';
    } 

    if(isset($wp_session['autotune_submit_remap_id'])) {
        echo "<div>Successfully created!</div>";
    }
?>

    <ui-select ng-model="ctrl.new_charge.user" title="Choose a user"
    theme="select2"
    on-select="ctrl.getUserHistory($item, $model)" style="width: 200px;">
      <ui-select-match placeholder="Choose User..." style="width:100%;">{{$select.selected.text}}</ui-select-match>
      <ui-select-choices repeat="user in ctrl.users | filter: $select.search">
        <span ng-bind-html="user.text"></span>
      </ui-select-choices>
    </ui-select>
    <input type="hidden" name="autotune_charge_user_id" ng-if="ctrl.new_charge.user != null" value="{{ ctrl.new_charge.user.id }}"/>
    <br/><br/>

    <div class="input_group">
        <select name="autotune_charge_type" id="autotune_charge_type" ng-model="ctrl.new_charge.type">
            <option value="1" selected>SERVICE</option>
            <option value="2">SUBSCRIPTION</option>
            <option value="3">PAYMENT</option>
        </select>
        <label for="autotune_charge_type">Charge Type:</label>
    </div>
    
    <div class="input_group">
        <input type="text" id="autotune_price" name="autotune_price" placeholder="0.00" />
        <label for="autotune_price">Price:</label>
    </div>

    <div class="input_group">
        <textarea name="autotune_other_notes"></textarea>
        <label for="autotune_other_notes">Notes:</label>
    </div>

    <div class="input_group" ng-if="ctrl.new_charge.type == 1">
        <input type="file" name="autotune_file"/>
        <label for="file">File:</label>
    </div>

    <input type="submit" value="Submit Charge"/>

</form>