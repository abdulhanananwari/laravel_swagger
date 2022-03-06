admin
    .factory('GaleryModel', function(
        $http,
        LinkFactory) {

        var eventModel = {}

        eventModel.index = function(params) {
            return $http.get(LinkFactory.admin.event)
        }

        eventModel.get = function(id, params) {
            return $http.get(LinkFactory.admin.event+ '/' + id, { params: params })
        }

        eventModel.store = function(body, params) {
            return $http.post(LinkFactory.admin.event, body, { params: params })
        }

        eventModel.update = function(id, body, params) {
            return $http.put(LinkFactory.admin.event+ '/' + id, body, { params: params })
        }
        eventModel.delete = function(id, body, params) {
            return $http.delete(LinkFactory.admin.event+ '/' + id, body, { params: params })
        }

        return eventModel
    })