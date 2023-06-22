<script type="text/javascript">
    var contributor = <?php echo in_array('contributor',wp_get_current_user()->roles)?"1":"0";?>;
    var USER_ID = <?php echo wp_get_current_user()->ID; ?>;
</script>

<div ng-app="autotune" ng-controller="MyRemapsController">

<input type="checkbox" id="display-archived" ng-model="displayArchivedRemaps"/> <label for="display-archived">Display Archived</label>

<span style="float:right" ng-show="remaps.length > 0 && !isContributor">Total: {{ user_total | currency : '£' : 2 }}</span>

<table class="user_remaps">

    <thead>
        <tr>
            <td>Status</td>
            <td>Vehicle</td>
            <td ng-hide="isContributor">Price</td>
            <td>Requested</td>
            <td>Updated</td>
            <td>AutoTune Notes</td>
            <td>Actions</td>
        </tr>
    </thead>

    <tbody ng-repeat="remap in remaps" ng-if="(!displayArchivedRemaps && remap.status != 4) || (displayArchivedRemaps && remap.status == 4)">

        <tr ng-if="remap.type == 0">
            <td>{{ statusLabel(remap.status) }}</td>
            <td><a href="javascript:void(0);" ng-click="viewDetails(remap)">{{ remap.manufacturer }} {{ remap.model }} {{ remap.year }}</a></td>
            <td ng-hide="isContributor">{{ remap.price | currency : '£' : 2 }}</td>
            <td>{{ formatDate(remap.created_at) }}</td>
            <td>{{ formatDate(remap.updated_at) }}</td>
            <td style="font-size:14px;">{{remap.autotune_note}}</td>
            <td>
                <form ng-if="remap.status == 2" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_xclick"/>
                    <input type="hidden" name="business" value="<?php echo Autotune_Remaps_Public::$paypal_email; ?>"/>
                    <input type="hidden" name="lc" value="GB"/>
                    <input type="hidden" name="item_name" value="Remap-{{ remap.remap_id }}"/>
                    <input ng-hide="isContributor" type="hidden" name="amount" value="{{ remap.price }}"/>
                    <input type="hidden" name="currency_code" value="GBP"/>
                    <input type="hidden" name="button_subtype" value="services"/>
                    <input type="hidden" name="no_note" value="0"/>
                    <input type="hidden" name="notify_url" value="https://auto-tune.co.uk/wp-json/autotune-remaps/v1/remaps/payment"/>
                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHostedGuest"/>
                    <input ng-if="remap.status == 2" type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!"/>
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
                </form>

                <label ng-if="remap.status == 3" ng-click="downloadFinishedMapFile(remap)" title="Download the finished ECU file"><span class="dashicons dashicons-download"></span></label>

            </td>
        </tr>

        <tr ng-if="remap.type == 1">
            <td colspan="2" style="text-align:center;" title="{{ remap.other_notes }}"> << SERVICE >> </td>
            <td ng-hide="isContributor">{{ remap.price | currency : '£' : 2 }}</td>
            <td>{{ formatDate(remap.created_at) }}</td>
            <td>{{ formatDate(remap.updated_at) }}</td>
            <td>
                <form ng-if="remap.status == 2" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_xclick"/>
                    <input type="hidden" name="business" value="<?php echo Autotune_Remaps_Public::$paypal_email; ?>"/>
                    <input type="hidden" name="lc" value="GB"/>
                    <input type="hidden" name="item_name" value="Remap-{{ remap.remap_id }}"/>
                    <input type="hidden" name="amount" value="{{ remap.price }}"/>
                    <input type="hidden" name="currency_code" value="GBP"/>
                    <input type="hidden" name="button_subtype" value="services"/>
                    <input type="hidden" name="no_note" value="0"/>
                    <input type="hidden" name="notify_url" value="https://auto-tune.co.uk/wp-json/autotune-remaps/v1/remaps/payment"/>
                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHostedGuest"/>
                    <input ng-if="remap.status == 2" type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!"/>
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
                </form>

                <label ng-if="remap.status == 3" ng-click="downloadFinishedMapFile(remap)" title="Download the finished ECU file"><span class="dashicons dashicons-download"></span></label>

            </td>
        </tr>

        <tr ng-if="remap.type == 2">
            <td colspan="2" style="text-align:center;" title="{{ remap.other_notes }}"> << SUBSCRIPTION >> </td>
            <td ng-hide="isContributor">{{ remap.price | currency : '£' : 2 }}</td>
            <td>{{ formatDate(remap.created_at) }}</td>
            <td>{{ formatDate(remap.updated_at) }}</td>
            <td>
                <form ng-if="remap.status == 2" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_xclick"/>
                    <input type="hidden" name="business" value="<?php echo Autotune_Remaps_Public::$paypal_email; ?>"/>
                    <input type="hidden" name="lc" value="GB"/>
                    <input type="hidden" name="item_name" value="Remap-{{ remap.remap_id }}"/>
                    <input type="hidden" name="amount" value="{{ remap.price }}"/>
                    <input type="hidden" name="currency_code" value="GBP"/>
                    <input type="hidden" name="button_subtype" value="services"/>
                    <input type="hidden" name="no_note" value="0"/>
                    <input type="hidden" name="notify_url" value="https://auto-tune.co.uk/wp-json/autotune-remaps/v1/remaps/payment"/>
                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHostedGuest"/>
                    <input ng-if="remap.status == 2" type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!"/>
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
                </form>

                <label ng-if="remap.status == 3" ng-click="downloadFinishedMapFile(remap)" title="Download the finished ECU file"><span class="dashicons dashicons-download"></span></label>

            </td>
        </tr>

        <tr ng-if="remap.type == 3">
            <td colspan="2" style="text-align:center;" title="{{ remap.other_notes }}"> << PAYMENT >> </td>
            <td ng-hide="isContributor">+ {{ remap.price | currency : '£' : 2 }}</td>
            <td colspan="3" style="text-align: center;">{{ formatDate(remap.created_at) }}</td>
        </tr>
    </tbody>

</table>

<div id="myRemapsModal" class="modal">

<!-- Modal content -->
<div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>{{ viewingDetailsRemap.manufacturer }} {{ viewingDetailsRemap.model }} {{ viewingDetailsRemap.year }}</h2>
    </div>
    <div class="modal-body">
        <table>
            <tr>
                <td>Manufacturer</td>
                <td>{{ viewingDetailsRemap.manufacturer }}</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>{{ viewingDetailsRemap.model }}</td>
            </tr>
            <tr>
                <td>Build Year</td>
                <td>{{ viewingDetailsRemap.year }}</td>
            </tr>
            <tr>
                <td>Engine Type</td>
                <td>{{ viewingDetailsRemap.engine_size }}</td>
            </tr>
            <tr>
                <td>ECU Type</td>
                <td>{{ viewingDetailsRemap.ecu_type }}</td>
            </tr>
            <tr>
                <td>Performance Tuning</td>
                <td ng-bind="viewingDetailsRemap.performance_tuning ? '✔' : '✘'"></td>
            </tr> 
            <tr>
                <td>Lambda</td>
                <td ng-bind='viewingDetailsRemap.lambda_o2_decat == 1 ? "✔" : "✘"'></td>
            </tr>
            <tr>
                <td>DPF Removal</td>
                <td ng-bind='viewingDetailsRemap.dpf_removal == 1 ? "✔" : "✘"'></td>
            </tr>
            <tr>
                <td>Adblue/SCR/NOx</td>
                <td ng-bind='viewingDetailsRemap.adblue_scr_nox == 1 ? "✔" : "✘"'></td>
            </tr>
            <tr>
                <td>Inlet/Swirl/Throttle</td>
                <td ng-bind='viewingDetailsRemap.inlet_swirl_throttle == 1 ? "✔" : "✘"'></td>
            </tr>
            <tr>
                <td>EGR Removal</td>
                <td ng-bind='viewingDetailsRemap.egr_removal == 1 ? "✔" : "✘"'></td>
            </tr>
            <tr>
                <td>DTC</td>
                <td ng-bind='viewingDetailsRemap.dtc == 1 ? "✔" : "✘"'></td>
            </tr>
            <tr>
                <td>(DTC) P Codes</td>
                <td>{{ viewingDetailsRemap.dtc_p_codes }}</td>
            </tr>
            <tr>
                <td>Other Notes</td>
                <td>{{ viewingDetailsRemap.other_notes }}</td>
            </tr>
            <tr>
                <td>AutoTune Notes</td>
                <td>{{viewingDetailsRemap.autotune_note}}</td>
            </tr>

        </table>

    </div>
    <div class="modal-footer">
      <h3></h3>
    </div>
  </div>

</div>

</div>