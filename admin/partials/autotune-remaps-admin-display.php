<?php

/**
 * Provides an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       wisener.me
 * @since      1.0.0
 *
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/admin/partials
 */
?>

<h1>Autotune Tuning System</h1>

<script type="text/javascript">
    <?php
    global $wp_session;
    ?>
    var OPEN_TAB = <?php echo isset($wp_session['open_tab']) ? '"'.$wp_session['open_tab'].'"' : '"RemapsTab"'; ?>;
</script>
<div ng-app="autotune" id="autotune-admin-display">

    <div id="tab-layout">
        
        <div class="tab">
          <button class="tablinks" onclick="openCity(event, 'Remaps')" id="RemapsTab">Remaps</button>
          <button class="tablinks" onclick="openCity(event, 'Charges')" id="ChargesTab">Charges</button>
        </div>

        <div id="Remaps" class="tabcontent">
        
        <?php
        global $wp_session;

        if(isset($wp_session['autotune_admin_submit_ecu_errors']) && count($wp_session['autotune_admin_submit_ecu_errors']) > 0) {
            echo '<ul class="errors">';
            foreach($wp_session['autotune_admin_submit_ecu_errors'] as $key => $value) {
                echo '<li>'.$key.': '.$value.'</li>';
            }
            echo '</ul>';
        }
        ?>

        <div ng-controller="AdminRemapsController">

            <div ng-show="!ctrl.loaded">Loading, please wait...</div>
            <div ng-show="ctrl.loaded" ng-cloak>
                <div id="adminControls">
                    <div id="setMultiple"><label for="setMultipleStatus">Update Selected: </label> <select id="setMultipleStatus" ng-model="setAllRemapsStatus" ng-change="batchUpdate()">
                        <option value>Choose...</option> 
                        <option value="4">ARCHIVED</option> 
                        <option value="0">PENDING</option> 
                        <option value="5">DELETED</option>
                    </select></div>
                    <div id="displayArchived"><input type="checkbox" id="display-archived" ng-model="displayArchivedRemaps"/> <label for="display-archived">Display Archived</label></div>    
                 
                    <div id="hidePrices"><input type="checkbox" id="hide-prices" ng-model="hidePricesToggle"/> <label for="hide-prices">Hide Prices</label></div>
                  
                    <div id="exportAll"><input type="button" id="export-all" value="Export All (Excel)" ng-click="exportAllRemaps()"/></div> 

                    <div class="">

                        <ui-select ng-model="ctrl.history_user_id" title="Choose a user"
                        theme="select2"
                        on-select="ctrl.getUserHistory($item, $model)" style="width: 200px; float: left;">
                          <ui-select-match placeholder="Choose User..." style="width:100%;">{{$select.selected.text}}</ui-select-match>
                          <ui-select-choices repeat="user in ctrl.users | filter: $select.search">
                            <span ng-bind-html="user.text"></span>
                          </ui-select-choices>
                        </ui-select>

                        <span class="user_total" ng-if="history_user_id != null && history_user_id != 0 && user_total != 0">Total Bill: {{ user_total | currency : "£" : 2 }}</span>
                    </div>

                    <div style="clear: both;"></div>
                </div>

                <table class="admin-remaps-display">

                    <thead>
                        <tr>
                            <td>
                                <input type="checkbox" title="Select/Unselect all" ng-model="allRemapsSelected" ng-change="selectAllRemaps()"/>
                            </td>
                            <td>ID</td>
                            <td>Status</td>
                            <td>Vehicle</td>
                            <td>Requested By</td>
                            <td ng-if="!hidePricesToggle">Price</td>
                            <td>Requested</td>
                            <td>Updated</td>
                            <td>Actions</td>
                        </tr>
                    </thead>

                    <tbody ng-repeat="remap in remaps" ng-if="(!displayArchivedRemaps && remap.status != 4) || (displayArchivedRemaps && remap.status == 4)">

                    <tr ng-if="remap.type == 0" class="{{ getClassFor(remap) }}">
                        <td align="center">
                            <input type="checkbox" ng-if="remap.type != 3" ng-model="remap.selected"/>
                        </td>
                        <td>{{ remap.remap_id }}</td>
                        <td>

                            <select ng-model="remap.status" 
                                ng-change="updateRemap(remap)">
                                <option value="0">PENDING</option> 
                                <option value="1" ng-disabled="remap.price == null" title="{{ inProgressStatusTitle(remap) }}">IN PROGRESS</option>   
                                <option value="2" ng-disabled="remap.price == null" title="{{ paymentStatusTitle(remap) }}">PAYMENT</option>   
                                <option value="3">COMPLETE</option>   
                                <option value="4">ARCHIVED</option>     
                                <option value="5">DELETED</option>       
                            </select>

                        </td>
                        <td><a href="javascript:void(0);" ng-click="viewDetails(remap)">{{ remap.manufacturer }} {{ remap.model }} {{ remap.year }}</a></td>
                        <td>{{ remap.username }}</td>
                        <td ng-if="!hidePricesToggle"><input type="text" ng-model="remap.price" placeholder="0.00" title="Type a price and hit 'Enter' to update" ng-keypress="updatePrice(remap, $event)" size="10"/></td>
                        <td>{{ formatDate(remap.created_at) }}</td>
                        <td>{{ formatDate(remap.updated_at) }}</td>
                        <td>

                            <form class="upload_map" id="remap_{{ remap.remap_id }}_upload_ecu"
                            action="" method="POST" enctype="multipart/form-data">
                            
                                <label ng-if="canDownloadOriginalMap(remap)" ng-click="downloadMapFile(remap)" title="Download the original ECU file"><span class="dashicons dashicons-download"></span></label>

                                <label ng-if="canDownloadCompletedMap(remap)" ng-click="downloadCompletedMapFile(remap)" title="Download the completed ECU file"><span class="dashicons dashicons-download completed"></span></label>

                                <input type="hidden" name="autoune_remap_id" value="{{ remap.remap_id }}"/>

                                <input data-file type="file" name="autotune_updated_ecu" style="display: none;"
                                title="Upload the finished ECU file" id="file_{{ remap.remap_id }}" ng-model="remap.completed_file"
                                ng-change="ecuUploadFileSelected(remap, $event)" />
                                <label ng-if="canUploadMap(remap)" title="Upload the finished ECU file" for="file_{{ remap.remap_id }}"><span class="dashicons dashicons-upload"></span></label>
                            </form>

                            
                        </td>
                    </tr>

                    <tr ng-if="remap.type >= 1" class="{{ getClassFor(remap) }}">
                        <td align="center">
                            <input type="checkbox" ng-if="remap.type != 3" ng-model="remap.selected"/>
                        </td>
                        <td>{{ remap.remap_id }}</td>
                        <td>
                            <select ng-if="remap.type == 1 || remap.type == 2" ng-model="remap.status" 
                                ng-change="updateRemap(remap)">
                                <option value="2">PAYMENT</option>   
                                <option value="3">COMPLETE</option>   
                                <option value="4">ARCHIVED</option>     
                                <option value="5">DELETED</option>       
                            </select>

                            <span ng-if="remap.type == 3" style="text-align: center;"> << PAID >> </span>
                        </td>

                        <td ng-if="remap.type == 1" colspan="2" style="text-align: center;"> << SERVICE >> <br/>
                        <small style="color: #8b8b8b;">{{ remap.other_notes }}</small> </td>
                        <td ng-if="remap.type == 2" colspan="2" style="text-align: center;" title="{{ remap.other_notes }}"> << SUBSCRIPTION >> </td>
                        <td ng-if="remap.type == 3" colspan="2" style="text-align: center;" title="{{ remap.other_notes }}"> << PAYMENT >> </td>
                        
                        <td><input type="text" ng-model="remap.price" placeholder="0.00" title="Type a price and hit 'Enter' to update" ng-keypress="updatePrice(remap, $event)" size="10"/></td>
                        <td>{{ formatDate(remap.created_at) }}</td>
                        <td>{{ formatDate(remap.updated_at) }}</td>
                        <td>

                            <form class="upload_map" id="remap_{{ remap.remap_id }}_upload_ecu"
                            action="" method="POST" enctype="multipart/form-data">
                            
                                <label ng-if="canDownloadOriginalMap(remap)" ng-click="downloadMapFile(remap)" title="Download the original ECU file"><span class="dashicons dashicons-download"></span></label>

                                <input type="hidden" name="autoune_remap_id" value="{{ remap.remap_id }}"/>

                                <input data-file type="file" name="autotune_updated_ecu" style="display: none;"
                                title="Upload the finished ECU file" id="file_{{ remap.remap_id }}" ng-model="remap.completed_file"
                                ng-change="ecuUploadFileSelected(remap, $event)" />
                                <label ng-if="canUploadMap(remap)" title="Upload the finished ECU file" for="file_{{ remap.remap_id }}"><span class="dashicons dashicons-upload"></span></label>
                            </form>

                            
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

    <div id="adminRemapsModal" class="modal">

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
                    <td>{{ displayOption(viewingDetailsRemap.performance_tuning) }}</td>
                </tr>
                <tr>
                    <td>Lambda</td>
                    <td>{{ displayOption(viewingDetailsRemap.lambda_o2_decat) }}</td>
                </tr>
                <tr>
                    <td>DPF Removal</td>
                    <td>{{ displayOption(viewingDetailsRemap.dpf_removal) }}</td>
                </tr>
                <tr>
                    <td>Adblue/SCR/NOx</td>
                    <td>{{ displayOption(viewingDetailsRemap.adblue_scr_nox) }}</td>
                </tr>
                <tr>
                    <td>Inlet/Swirl/Throttle</td>
                    <td>{{ displayOption(viewingDetailsRemap.inlet_swirl_throttle) }}</td>
                </tr>
                <tr>
                    <td>EGR Removal</td>
                    <td>{{ displayOption(viewingDetailsRemap.egr_removal) }}</td>
                </tr>
                <tr>
                    <td>DTC</td>
                    <td>{{ displayOption(viewingDetailsRemap.dtc) }}</td>
                </tr>
                <tr>
                    <td>(DTC) P Codes</td>
                    <td>{{ viewingDetailsRemap.dtc_p_codes }}</td>
                </tr>
                <tr>
                    <td>Other Notes</td>
                    <td>{{ viewingDetailsRemap.other_notes }}</td>
                </tr>

            </table>

        </div>
        <div class="modal-footer">
          <h3></h3>
        </div>
      </div>

        </div>

        </div> <!-- End of -->

        </div>

        <div id="Charges" ng-controller="AdminChargesController" class="tabcontent" ng-cloak>
          <?php include("autotune-remaps-admin-charges.php"); ?>
        </div>

    

    <div style="clear: both;"></div>

    </div>

    </div><!-- end of ng-app-->

</div>