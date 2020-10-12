/*
 * Directive for ensuring <input type="file"/> ng-model and ng-change events work
 *
 * See: No binding support for file controls
 * https://github.com/angular/angular.js/issues/1375
 */
 autotune.directive('file', function() {
    return {
        require:"ngModel",
        restrict: 'A',
        link: function($scope, el, attrs, ngModel){
            el.bind('change', function(event){
                var files = event.target.files;
                var file = files[0];

                ngModel.$setViewValue(file);
                $scope.$apply();
            });
        }
    };
});