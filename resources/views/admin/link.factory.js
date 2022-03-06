adminDealer
    .factory('LinkFactory', function() {

        var domains = {
            admin: window.location.protocol + '//' + window.location.host + '/',
        }

        var apps = {
            authentication: domains.admin + 'auth/user/',
            admin: domains.admin
        }


        var urls = {

                authentication: {
                    login: domains.admin + 'auth/login'
                },

                admin: {
                    event: apps.admin + 'api/event'
                },
            }

        return urls
    })