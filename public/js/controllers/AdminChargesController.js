autotune.controller('AdminChargesController', ['$scope', 'TuningService', '$timeout', function($scope, TuningService, $timeout) {

	$scope.ctrl = $scope;

    $scope.loaded = false;

    $scope.users = [];

	$scope.formatDate = TuningService.formatDate;
	$scope.STATUS = TuningService.STATUS;

    $scope.new_charge = {
        type: "1",
        user: null,
        notes: "",
        price: 0
    }

    $scope.submitCharge = function($event) {
        
        $event.preventDefault();        

        // Create a FormData object based on the form values
        var formData = new FormData(document.getElementById('autotune_remap_charges_form')); 

        TuningService.createCharge(formData).then(function(response) {
            console.log(response);
        }, console.error);

        return false;
         
    }

    angular.element(document).ready(function () {

        // On load, fetch all the remaps
    	TuningService.getAllUsers().then(function(response) {
            $scope.users = response.data;

            $scope.loaded = true;
    	}, console.error);
    });

}]);