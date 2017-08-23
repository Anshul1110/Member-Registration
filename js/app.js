var app = angular.module('app', ['ui.router']);

app.config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise("/home");

    $stateProvider
    .state('home', {
      url: "/home",
      templateUrl: "templates/home.html",
      controller: 'HomeCtrl'
    })
    .state('login', {
      url: "/login",
      params:{role:null},
      controller: 'LoginCtrl',
      templateUrl: "templates/login.html"
    })
    .state('agentHome', {
      url: "/oe/home",
      controller: 'AgentHomeCtrl',
      templateUrl: "templates/agenthome.html"
    })
    .state('agentRegister', {
      url: "/oe/register",
      params: {role: 'Agent'},
      controller: 'AgentRegCtrl',
      templateUrl: "templates/opreg.html"
    })
    .state('customerHome', {
      url: "/customer/home",
      controller: 'CustHomeCtrl',
      templateUrl: "templates/custhome.html"
    })
    .state('customerRegister', {
      url: "/customer/register",
      params: {role: 'Customer'},
      controller: 'CustRegCtrl',
      templateUrl: "templates/customer.html"
    })
    .state('merchantHome', {
      url: "/merchant/home",
      controller: 'MerchHomeCtrl',
      templateUrl: "templates/merchhome.html"
    })
    .state('merchantRegister', {
      url: "/merchant/register",
      params: {role: 'Merchant'},
      controller: 'MerchRegCtrl',
      templateUrl: "templates/merchant.html"
    })
    .state('adminHome', {
      url: "/admin/home",
      controller: 'AdminHomeCtrl',
      templateUrl: "templates/adminhome.html"
    });
});
     
app.directive('headerTpl', function () {
    return {
        templateUrl: 'templates/header.html'
    }
});

app.directive('contenteditable', function() {
    return {
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {
            // view -> model
            elm.bind('blur', function() {
                scope.$apply(function() {
                    ctrl.$setViewValue(elm.html());
                });
            });

            // model -> view
            ctrl.$render = function() {
                elm.html(ctrl.$viewValue);
            };

            // load init value from DOM
            ctrl.$setViewValue(elm.html());
        }
    };
});
