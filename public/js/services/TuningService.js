autotune.service('TuningService', function($http) {
  	
  	// The endpoint for the plugin's internal REST API
  	var api_url = "/testsite/wp-json/autotune-remaps/v1";

  	// The full URI for the plugin's checker internal API
  	// TODO: Move this functionality within the plugins official REST API
  	var checker_api_url = "/testsite/wp-content/plugins/autotune-remaps/autotune-api.php";

  	// The status enum for remaps
  	var STATUS = {
  		"PENDING": 0,
		"IN_PROGRESS": 1,
		"PAYMENT": 2,
		"COMPLETE": 3,
		"ARCHIVED": 4,
		"DELETED": 5
  	};

  	// Function to get a humanized version of an enum key in Sherlayer JS object.
  	this.statusLabel = function(status) {
  		return humanizeString(getKeyByValue(STATUS, status));
  	};

  	// Function to open a Download URL in a new tab
  	this.downloadOriginalMapFile = function(remap_id) {
  		var win = window.open(api_url + '/remaps/' + remap_id + '/download/original', '_blank');
		win.focus();
  	};

  	// Function to open a Download URL in a new tab
  	this.downloadMapFile = function(remap_id) {
  		var win = window.open(api_url + '/remaps/' + remap_id + '/download', '_blank');
		win.focus();
  	};

  	// Function to open the export all remaps URL in a new tab
  	this.exportAllRemaps = function(remap_id) {
  		var win = window.open(api_url + '/remaps/all', '_blank');
		win.focus();
  	};

	this.getBrands = function() {
		return $http.get(checker_api_url, { 'lol': true });
	};
    
    this.getModels = function(selected) {
		return $http.get(checker_api_url + "?manufacturer="+selected.brand);
	};
    
    this.getBuildYears = function(selected) {
		return $http.get(checker_api_url + "?manufacturer="+selected.brand+"&model="+selected.model);
	};
    
    this.getMotors = function(selected) {
		return $http.get(checker_api_url + "?manufacturer="+selected.brand
        +"&model="+selected.model
        +"&build_year="+selected.buildyear);
	};
    
    this.getModelIcon = function(selected) {
		return $http.get(checker_api_url + "?manufacturer="+selected.brand
        +"&model="+selected.model
        +"&model_icon=true");
	};
    
    this.getStages = function(selected) {
		return $http.get(checker_api_url + "?manufacturer="+selected.brand
        +"&model="+selected.model
        +"&build_year="+selected.buildyear
        +"&motor="+selected.motor
        +"&motor_power="+selected.motor);
	};

	// Function to return all users
	this.getAllUsers = function() {

		return $http.get(api_url + '/users');
	};

	// Function to return all remaps
	this.getAllRemaps = function() {

		return $http.get(api_url + '/remaps');
	};

	// Function to return all remaps belonging to the logged in user
	this.getUserRemaps = function(user_id) {

		return $http.get(api_url + '/user/' + user_id + '/remaps');
	};

	// Function to update a remap in storage
	this.updateRemap = function(remap) {

		return $http.post(api_url + '/remaps', remap);
	};

	// Function to update a set of remaps in storage
	this.updateRemaps = function(remap_ids, status) {

		return $http.put(api_url + '/remaps/all', {
			remap_ids: remap_ids,
			status: status
		});
	};

	// Function to return the download link for an updated remap file
	this.getUpdatedMapUrl = function(remap_id) {

		return $http.get(api_url + '/remaps/' + remap_id + '/download');
	};

	// Function to format a date string
	this.formatDate = function(mysql_datetime) {
		if(mysql_datetime == null) return "...";
		
		return Date.createFromMysql(mysql_datetime).toLocaleString("en-GB");
	};

	// Function to create a charge relating to a user
	this.createCharge = function(formData) {
		return $http.post(api_url + '/charges', 
			{ 'charge': formData }, 
			{ 
				transformRequest: angular.identity,
				headers: {'Content-Type': undefined} 
			});
	};
});