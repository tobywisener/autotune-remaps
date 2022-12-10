autotune.controller('AdminRemapsController', ['$scope', 'TuningService', '$timeout', function($scope, TuningService, $timeout) {

	$scope.ctrl = $scope;

    $scope.loaded = false;

	$scope.formatDate = TuningService.formatDate;
	$scope.STATUS = TuningService.STATUS;

    $scope.remaps = [];

    $scope.users = [];

    $scope.noteSaved = false;
    $scope.modal = null;

    $scope.viewingDetailsRemap = {};

    $scope.displayArchivedRemaps = false;

    $scope.hidePricesToggle = false;

    $scope.allRemapsSelected = false;

    $scope.setAllRemapsStatus = null;

    $scope.history_user_id = null;

    $scope.user_total = 0;

    $scope.user_id = 0;

    // Function to handle the toggling of all remaps being selected
    $scope.selectAllRemaps = function() {

        angular.forEach($scope.remaps, function(remap, key) {
            remap.selected = $scope.allRemapsSelected;
        });
    };

    // Function to view all remaps and charges for a given user
    $scope.getUserHistory = function(item, model) {

        var user_id = item.id;
        $scope.user_id = user_id;

        if(user_id === "" || user_id === null) {
            $scope.loadAllRemaps();
            return;
        }

        TuningService.getUserRemaps(user_id).then(function(response) {
            $scope.user_total = response.data.total_amount;
            $scope.remaps = response.data.remaps;
        }, console.error);
    };

    $scope.loadAllRemaps = function() {
        TuningService.getAllRemaps().then(function(response) {
            $scope.remaps = response.data;
            $scope.loaded = true;
        }, console.error);
    };

    // Function to derermine whether the admin can upload a map or not
    $scope.canUploadMap = function(remap) {
    	return (remap.type == 0 /* Remap */ || remap.type == 1 /* Service */) 
            &&(remap.status >= 1 && remap.status < 3 || remap.status == 6);
    };

    // Function to return an appropriate CSS class for colour coding
    $scope.getClassFor = function(remap) {

        if(remap.status == 0) {
            return "pending";
        }

        if(remap.status == 1) {
            return "progress";
        }

        if(remap.status == 2) {
            return "payment";
        }

        if(remap.status == 3) {
            return "complete";
        }
    }

    // Function to determine whether the admin can download the original map or not
    $scope.canDownloadOriginalMap = function(remap) {
    	return ((remap.type == 0 /* Remap */ || remap.type == 1 /* Service */) 
            && remap.price != null /* IN_PROGRESS */);
    };
    
    // Function to determine whether the admin can download the completed map or not
    $scope.canDownloadCompletedMap = function(remap) {
        return ((remap.type == 0 /* Remap */ || remap.type == 1 /* Service */)
            && remap.price != null && remap.price > 0 && remap.status > 1 /* IN_PROGRESS */);
    };
/**
 * Removed remap.price == 0 for: 
 *      inProgressStatusTitle
 *      paymentStatusTitle 
 * 
 */
    $scope.inProgressStatusTitle = function(remap) {
    	if(remap.price == null) return "Please set a price before starting this job";

    	return "Set the remap to in progress and email the user to notify them";
    };

    // Function to return an appropriate title="" attribute for the 'payment' option on the status dropdown
    $scope.paymentStatusTitle = function(remap) {
    	if(remap.price == null) 
        return "Please set a price before requesting payment";

    	return "Request payment via PayPal before allowing download";
    };

    // Function to update the price of a remap
    $scope.updatePrice = function(remap, keyEvent) {
		if (keyEvent.which !== 13 /* 'Enter' */) return;

		$scope.updateRemap(remap);
	};

	// Function to call the TuningService to update a particular remap
	$scope.updateRemap = function(remap) {
        if(remap.status == null) {
            return;
        }
        
		TuningService.updateRemap(remap).then(function(response) {

			$timeout(function() {
				// Use $timeout to ensure this code is executed indepentently to other operations affecting the array

				// Get the current index of the remap in question within the scope array
				var index = $scope.remaps.map(function(remap) { return remap.remap_id; }).indexOf(remap.remap_id);

				// Update the model with the freshly updated resource returned from the REST API
				$scope.remaps[index] = response.data;
			});

		}, console.error);
	};

    $scope.getAllSelectedRemapIds = function(status) {
        var selected_remap_ids = [];

        angular.forEach($scope.remaps, function(remap, key) {
            if(!remap.selected) return;

            if(typeof status !== "undefined" && remap.status !== status) {
                // If a status was passed in to filter on, check it
                return;
            }

            selected_remap_ids.push(remap.remap_id);
        });

        return selected_remap_ids;
    };

    // Function to call the back end to export all remaps in Excel format
    $scope.exportAllRemaps = function() {

        var remapArray = [];
        //iterate through user files to find remaps

        for (var i in $scope.remaps) {
            //check if file is a remap
            if($scope.remaps[i]['type'] == 0 ){
                remapArray.push($scope.remaps[i])
            }
        }
        console.log(remapArray);
        if(remapArray.length > 0){
            TuningService.exportAllRemaps($scope.user_id);
        }else {
            alert('There are no remaps to export')
        }
    };

    // Function to update the statuses for all remaps currently selected
    $scope.batchUpdate = function() {
        if(!$scope.setAllRemapsStatus) {
            return;
        }

        var update_ids = $scope.getAllSelectedRemapIds();

        // Double check with the user that they want to update these files
        if(update_ids.length === 0 || 
            !confirm("Are you sure you want to update status for " + update_ids.length + " files?")) {
            $scope.setAllRemapsStatus = null;
            return; // Quit the function
        }

        TuningService.updateRemaps(update_ids, $scope.setAllRemapsStatus,$scope.user_id).then(function(response) {
            $scope.remaps = response.data;
        }, console.error);

        $scope.setAllRemapsStatus = null;
    };

	// Function which gets called before the upload of an updated ECU begins
	$scope.ecuUploadFileSelected = function(remap, changeEvent) {

		// Submit the form manually
		var thisForm = document.getElementById('remap_' + remap.remap_id + '_upload_ecu');
		if(thisForm != null) {
			thisForm.submit();
		}
	};

	// Function to fetch the download link for the (original) given remap
	$scope.downloadMapFile = function(remap) {

		TuningService.downloadOriginalMapFile(remap.remap_id);
	};

    // Function to fetch the download link for the (completed) given remap
    $scope.downloadCompletedMapFile = function(remap) {

        TuningService.downloadMapFile(remap.remap_id);
    };

	// Function to open the details of a given remap
	$scope.viewDetails = function(remap) {
		$scope.viewingDetailsRemap = remap;

		$scope.modal.style.display = "block";
	};

    // Function to return a tick or an x depending on a value being truthy/falsy
    $scope.displayOption = function(value) {
        if(value == 1) {
            return '✔';
        }  

        return '✘';
    };

    $scope.addRemapNote = function(remap){
        $scope.updateRemap(remap);
        $scope.noteSaved = true;
        setTimeout(function ()
        {
            $scope.$apply(function()
            {
                $scope.noteSaved = false;
            });
        }, 2000);
    }

    angular.element(document).ready(function () {
        
        // Set the modal scope variable
        $scope.modal = document.getElementById("adminRemapsModal");

        // Get the <span> element that closes the modal
        jQuery("#adminRemapsModal span.close").click(function() {

			$scope.modal.style.display = "none";
		});

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
        	if (event.target == $scope.modal) {
        		$scope.modal.style.display = "none";
        	}
        };

        // On load, fetch all user ids
        TuningService.getAllUsers().then(function(response) {
            //jQuery('#autotune_history_user').select2({ data: response.data });
            $scope.users = response.data;

        }, console.error);

        // On load, fetch all the remaps
    	$scope.loadAllRemaps();
    });

}]);