<div id="autotune_widget" ng-app="autotune" ng-controller="TuningController">
    <div class="left">
        <form id="autotune-api" method="GET">
        
        	<div class="dropdown" ng-if="brands.length > 0">
                <select class="" name="manufacturer"
                ng-init="ctrl.selected.brand = '' || options[0].value"
                ng-model="ctrl.selected.brand"
        		ng-options="item.id as item.name for item in brands track by item.id"
                ng-change="selectBrand()">
                <option value="">Manufacturer...</option>
                </select>
            </div>

        	<div class="dropdown" ng-if="models.length > 0">
                <select class="" name="model"
                ng-init="ctrl.selected.model = '' || options[0].value"
                ng-model="ctrl.selected.model"
        		ng-options="item.id as item.name for item in models track by item.id"
                ng-change="selectModel()">
                <option value="">Model...</option>
                </select>
            </div>
        
        	<div class="dropdown" ng-if="buildyears.length > 0">
                <select name="buildyear"
                ng-init="ctrl.selected.buildyear = '' || options[0].value"
                ng-model="selected.buildyear"
        		ng-options="item.id as item.long_name for item in buildyears track by item.id"
                ng-change="selectBuildYear()">
                    <option value="">Year...</option>
                </select>
            </div>
            
        	<div class="dropdown" ng-if="motors.length > 0">
                <select name="motor"
                ng-init="ctrl.selected.motor = '' || options[0].value"
                ng-model="ctrl.selected.motor"
        		ng-options="item.id as item.engine.name + ' [' + item.engine.power + ']' for item in motors track by item.id"
                ng-change="selectMotor()">
                    <option value="">Engines...</option>
                </select>
            </div>
            <input type="hidden" id="motor_power" name="motor_power" value/>
        
        </form>
    </div>

    <div class="right">
    	<table ng-if="engineSelected()">
            <tr>
            	<td class="head">Stock</td>
                <td class="head">Stage 1</td>
                <!-- <td rowspan=3><img ng-hide="model_icon == ''" ng-src="{{ model_icon }}"/></td> -->
            </tr>
            <tr>
                <td>{{ selected.engine.power }} [BHP]</td>
                <td>{{ selected.engine.stages[0].power }} [BHP]</td>
            </tr>
             <tr>
                <td>{{ selected.engine.torque }} [Nm]</td>
                <td>{{ selected.engine.stages[0].torque }} [Nm]</td>
            </tr>
        </table>

    </div>
    
    <div style="clear:both;"></div>
</div>