var app = angular.module('app', ['ui.router']);

app.config(function($stateProvider, $urlRouterProvider) {

$urlRouterProvider.otherwise("/home");

$stateProvider
    .state('home', {
      url: "/home",
      templateUrl: "templates/home.html",
      controller: 'HomeCtrl'
    })
    .state('agentRegister', {
      url: "/register/agent",
      params: {role: 'Agent'},
      controller: 'AgentRegCtrl',
      templateUrl: "templates/opreg.html"
    })
    .state('customerRegister', {
      url: "/register/customer",
      params: {role: 'Customer'},
      controller: 'CustRegCtrl',
      templateUrl: "templates/customer.html"
    })
    .state('merchantRegister', {
      url: "/register/merchant",
      params: {role: 'Merchant'},
      controller: 'MerchRegCtrl',
      templateUrl: "templates/merchant.html"
    })
    .state('login', {
      url: "/login",
      params:{role:null},
      controller: 'LoginCtrl',
      templateUrl: "templates/login.html"
    });
});
     
app.directive('headerTpl', function () {
    return {
        restrict: 'E',
        templateUrl: 'templates/header.html',
        controller: function() {},
        link: function (scope, element, attrs, ctrl) {
        }
    }
});
