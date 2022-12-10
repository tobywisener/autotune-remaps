autotune.controller('MyRemapsController', ['$scope', '$timeout', '$filter', 'TuningService', function($scope, $timeout, $filter, TuningService) {

    $scope.ctrl = $scope;

	$scope.statusLabel = TuningService.statusLabel;
	$scope.formatDate = TuningService.formatDate;

    $scope.remaps = [];

    $scope.user_total = 0;

    $scope.modal = null;

    $scope.viewingDetailsRemap = { manufacturer: '' };

    $scope.displayArchivedRemaps = false;

    // Function to download the finished ECU file which has been mapped and paid for
    $scope.downloadFinishedMapFile = function(remap) {
    	if($scope.statusLabel(remap.status) !== "COMPLETE") {
    		return;
    	}

        TuningService.downloadMapFile(remap.remap_id);
    };

    // Function to open the details of a given remap
    $scope.viewDetails = function(remap) {

        $timeout(function() {
            
            $scope.viewingDetailsRemap = remap;

            $scope.modal.style.display = "block";
        });
        
    };

    angular.element(document).ready(function () {

        $scope.isContributor = contributor;
        // Set the modal scope variable
        $scope.modal = document.getElementById("myRemapsModal");

        // Get the <span> element that closes the modal
        jQuery("#myRemapsModal span.close").click(function() {

            $scope.modal.style.display = "none";
        });

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == $scope.modal) {
                $scope.modal.style.display = "none";
            }
        };

        // On load, fetch all the remaps
    	TuningService.getUserRemaps(USER_ID).then(function(response) {
            $scope.user_total = response.data.total_amount;
    		$scope.remaps = response.data.remaps;
    	}, console.error);
    });

}]);