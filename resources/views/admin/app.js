var adminDealer = angular
    .module('Admin', [
        'ui.router', 'JwtManager', 'ui.bootstrap','Pagination'
    ])
    .factory('AppFactory', function() {

        var appFactory = {};

        return appFactory;
    });