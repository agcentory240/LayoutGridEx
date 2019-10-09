/**
 *
 * Layout_Grid_Ex example
 *
 * All the following functions are required in order for the Layout to work
 */
App.service('layout_grid_ex', function ($rootScope, HomepageLayout) {

    var service = {};

    /**
     * Must return a valid template
     *
     * @returns {string}
     */
    service.getTemplate = function() {
        return "modules/layout/home/layout_grid_ex/view.html";
    };

    /**
     * Must return a valid template
     *
     * @returns {string}
     */
    service.getModalTemplate = function() {
        return "templates/home/l10/modal.html";
    };

    /**
     * onResize is used for css/js callbacks when orientation change
     */
    service.onResize = function() {
        /** Do nothing for this particular one */
    };

    /**
     * Manipulate the features objects
     *
     * Examples:
     * - you can re-order features
     * - you can push/place the "more_button"
     *
     * @param features
     * @param more_button
     * @returns {*}
     */
    service.features = function(features, more_button) {
        /** Place more button at the end */
        features.overview.options.push(more_button);

        return features;
    };

    return service;

});

App.controller('agc242templatecontroller', function($scope,Url,$ionicSideMenuDelegate,$rootScope, $timeout, $translate, $location, $compile, $sce, $window, Application, Customer,   Dialog,  HomepageLayout, $log, $http)
{
	$scope.is_loading = true;
	$scope.is_logged_in = Customer.isLoggedIn();
	$scope.avatar_url = null;
	$scope.options = {};
	$scope.features_all = [];
	$scope.features_options = [];
	HomepageLayout.getFeatures().then(function(features) {
		console.log("Get layout options:");
		console.log(features);
		$scope.options = features.layoutOptions;
		features.options.forEach(function(element) {
			if (element.is_visible) $scope.features_all.push(element);
		});
		$scope.features_options = $scope.chunkArray($scope.features_all,3);
		console.log($scope.features_options);
	});
	
	
	
	$scope.chunkArray = function(myArray, chunk_size){
		var index = 0;
		var arrayLength = myArray.length;
		var tempArray = [];
		
		for (index = 0; index < arrayLength; index += chunk_size) {
			myChunk = myArray.slice(index, index+chunk_size);
			// Do something if you want with the group
			tempArray.push(myChunk);
		}

		return tempArray;
	}
	
	$scope.openFeature = function(feature) {
		console.log("openFeature clicked:");
		console.log(feature);
		HomepageLayout.openFeature(feature, $scope);

	};	
	
});