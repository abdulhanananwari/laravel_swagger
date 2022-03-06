adminDealer
    .config(function($stateProvider, $urlRouterProvider, $locationProvider) {

        $locationProvider.hashPrefix('');
        $urlRouterProvider.otherwise('/index');

        $stateProvider
            .state('index', {
                url: '/index',
                templateUrl: '/admin/index/index.html',
                controller: 'IndexController as ctrl',
                requireLogin: true,
                pageTitle: 'Cashier'
            })
            .state('eventIndex', {
                url: '/event/index',
                templateUrl: '/admin/event/index/index.html',
                controller: 'EventIndexController as ctrl',
                requireLogin: true,
                pageTitle: 'Website Admin | Event List'
            })
            .state('eventShow', {
                url: '/event/show/:id',
                templateUrl: '/admin/event/show/eventShow.html',
                controller: 'EventShowController as ctrl',
                requireLogin: true,
                pageTitle: 'Website Admin | Event'
            })
            
    })