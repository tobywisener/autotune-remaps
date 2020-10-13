autotune.controller('TuningController', ['$scope', '$timeout', 'TuningService', function($scope, $timeout, TuningService) {
  $scope.ctrl = $scope;

  $scope.greeting = 'Hola!';
  $scope.brands = [];
  $scope.selected = {
  	brand: '',
    model: '',
    buildyear: '',
    motor: '',
    engine: {}
  };
  $scope.models = [];
  $scope.selectedModel = "";
  $scope.buildyears = [];
  $scope.selectedBuildYear = "";
  $scope.powertrains = [];
  $scope.selectedPowertrain = "";
  $scope.model_icon = "";
  
  $scope.engineSelected = function() {
  	return typeof($scope.selected.engine.stages) !== "undefined";
  };
  
  angular.element(document).ready(function () {
  // check if there is query in url
  // and fire search in case its value is not empty
  TuningService.getBrands().then(function(response){
    console.log(response);
  	$timeout(function() {

    $scope.brands = response.data;
    $scope.selected.brand = '';
    })

  }, function(error) {
    console.log(error);
  });
  
  });
  
  $scope.selectBrand = function() {
  
  TuningService.getModels($scope.selected).then(function(response){
  	$scope.models = response.data;
  });
  };
  
  $scope.selectModel = function() {
  
  TuningService.getBuildYears($scope.selected).then(function(response){
  	$scope.buildyears = response.data;
    
    // Fetch the model icon
    TuningService.getModelIcon($scope.selected).then(function(response) {
    	$scope.model_icon = response.data;
    });
    
  });
  };
  
  $scope.selectBuildYear = function() {
  
  TuningService.getMotors($scope.selected).then(function(response){
  	$scope.motors = response.data;
  });
  };
  
  $scope.selectMotor = function() {
  
  TuningService.getStages($scope.selected).then(function(response){
  	$scope.selected.engine = response.data.engine;

    // Get the brand, model and year name
    var brand = $scope.brands.find(element => element.id === $scope.selected.brand),
        model = $scope.models.find(element => element.id === $scope.selected.model),
        year = $scope.buildyears.find(element => element.id === $scope.selected.buildyear);

    if(!$scope.selected.engine.name){ 
      // We don't have the engine selected, don't populate contact form
      return false;
    }
    
    // Update the contact form on the page if it exists
    jQuery('textarea.car_details').text(
               "Make: " + brand.name + ",\r\n" + 
               "Model: " + model.name + ",\r\n" + 
               "Year: " + year.long_name + "\r\n" +
               "Engine: " + $scope.selected.engine.name + " " + $scope.selected.engine.power
            );
  });
  };
  
}]);