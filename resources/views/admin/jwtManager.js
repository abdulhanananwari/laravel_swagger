angular
    .module('JwtManager', ['angular-jwt'])
    .factory('JwtValidator', function(
        LinkFactory, jwtHelper) {

        /*
        	This package REQUIRE Link Factory which have LinkFactory.authorization.login and  LinkFactory.authorization.logout links
        	This package REQUIRE URI.js

        */

        var jwtValidator = {};

        var jwtName = 'access_token';

        jwtValidator.decodeToken = function(token) {

            try {

                return jwtHelper.decodeToken(jwtValidator.encodedJwt)

            } catch (e) {

                jwtValidator.unsetJwt()
                return null
            }
        }

        jwtValidator.isLoggedIn = function() {
            console.log(jwtValidator.encodedJwt)
            if (jwtValidator.encodedJwt == null) {
                return false;
            };

            try {

                if (jwtHelper.isTokenExpired(jwtValidator.encodedJwt)) {
                    alert('Sesi sudah berakhir. Harap login ulang.')
                    JwtValidator.unsetJwt();
                    return false;
                };

            } catch (e) {

                jwtValidator.unsetJwt();
                location.reload();
            }

            return true;
        }

        jwtValidator.setJwt = function(jwt) {

            localStorage.setItem(jwtName, jwt);
        }

        jwtValidator.unsetJwt = function() {

            try {
                localforage.clear();
            } catch (e) {
                console.log(e);
            }

            localStorage.clear();
        }

        jwtValidator.login = function(params) {

            var uri = new URI(window.location.href);
            var hash = uri.hash();

            params.redirect = uri.fragment("");
            params.state = hash;

            window.location.href = new URI(LinkFactory.authentication.login).search(params).toString();

        }

        jwtValidator.encodedJwt = localStorage.getItem(jwtName);

        jwtValidator.decodedJwt = jwtValidator.encodedJwt == null ? null : jwtValidator.decodeToken(jwtValidator.encodedJwt);

        return jwtValidator;
    })
    .run(function($rootScope, $state, $location, JwtValidator) {

        $rootScope.$on('$stateChangeStart', function(event, toState) {
            if (toState.requireLogin != false) {

                var isLoggedIn = JwtValidator.isLoggedIn();

                if (!isLoggedIn) {
                    alert('Anda perlu login untuk menggunakan fitur ini.');
                    event.preventDefault();
                    window.location.href = '/login';
                };
            };



        });


        var retrieveJwt = function() {

            var uri = new URI(window.location.href)

            if (typeof uri.search(true).jwt != 'undefined') {

                JwtValidator.setJwt(uri.search(true).jwt);
                var state = uri.search(true).state;
                uri.search('');


                if (typeof state != "undefined") {

                    window.location.href = uri + state;

                } else {

                    window.location.href = uri;
                };

            };

        }

        retrieveJwt();

    })
    .service('JwtInterceptor', function(JwtValidator) {

        this.request = function(config) {

            if (JwtValidator.encodedJwt != null) {
                config.headers['Authorization'] = 'Bearer ' + JwtValidator.encodedJwt;
            };

            return config;
        }

    })
    .service('JwtErrorInterceptor', function($q, JwtValidator) {

        var interceptors = {};

        interceptors.responseError = function(res) {

            if (res.status == 401 && res.data == 'Session verification failed' && JwtValidator.encodedJwt != null) {
                alert('Maaf, sesi Anda telah habis. Silahkan login ulang.');
                JwtValidator.unsetJwt();
                location.reload();
            };

            return $q.reject(res);
        }

        return interceptors;
    })
    .config(function($httpProvider) {
        $httpProvider.interceptors.push('JwtInterceptor');
        $httpProvider.interceptors.push('JwtErrorInterceptor');
    });