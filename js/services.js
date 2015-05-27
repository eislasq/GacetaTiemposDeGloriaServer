angular.module('Negocios')

        .service('WS', function ($http) {
//            this.serverUrl = 'http://gaceta.bugs3.com/server.php';
//            this.serverUrl = 'http://localhost/~eislas/GacetaServer/server.php';
            this.serverUrl = 'http://resolute-oxygen-95315.appspot.com';
            this.miNegocio = function (llave) {
                var request = $http({
                    url: this.serverUrl + '?action=miNegocio'
                    , async: true
                    , method: 'POST'
                    , data: {llave: llave}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.guardarMiNegocio = function (llave, negocio) {
                var request = $http({
                    url: this.serverUrl + '?action=guardarMiNegocio'
                    , async: true
                    , method: 'POST'
                    , data: {llave: llave, negocio: negocio}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.obtenerCategorias = function () {
                var request = $http({
                    url: this.serverUrl + '?action=obtenerCategorias'
                    , async: true
                    , method: 'POST'
                    , data: {}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.obtenerProveedoresActualizadosDespuesDe = function (lastUpdate) {
                var request = $http({
                    url: this.serverUrl + '?action=obtenerProveedoresActualizadosDespuesDe'
                    , async: true
                    , method: 'POST'
                    , data: {fecha: lastUpdate}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.login = function (adminPasswd) {
                var request = $http({
                    url: this.serverUrl + '?action=adminAccess'
                    , async: true
                    , method: 'POST'
                    , data: {adminPasswd: adminPasswd}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.logout = function () {
                var request = $http({
                    url: this.serverUrl + '?action=adminLogout'
                    , async: true
                    , method: 'POST'
                    , data: {}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.neuevaCategoria = function (categoryName) {
                var request = $http({
                    url: this.serverUrl + '?action=neuevaCategoria'
                    , async: true
                    , method: 'POST'
                    , data: {categoryName: categoryName}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.modificarCategoria = function (categoryId, categoryName) {
                var request = $http({
                    url: this.serverUrl + '?action=modificarCategoria'
                    , async: true
                    , method: 'POST'
                    , data: {categoryId: categoryId, categoryName: categoryName}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.eliminarCategoria = function (categoryId) {
                var request = $http({
                    url: this.serverUrl + '?action=eliminarCategoria'
                    , async: true
                    , method: 'POST'
                    , data: {categoryId: categoryId}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.obtenerLlaves = function () {
                var request = $http({
                    url: this.serverUrl + '?action=obtenerLlaves'
                    , async: true
                    , method: 'POST'
                    , data: {}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
            this.generarLlaves = function (cantidad) {
                var request = $http({
                    url: this.serverUrl + '?action=generarLlaves'
                    , async: true
                    , method: 'POST'
                    , data: {cantidad: cantidad}
                    , headers: {
                        'Accept': 'application/json, text/javascript, */*; q=0.01'
                        , 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
                return request;
            };
        })
        .service('loading', function () {
            this.show = function () {
                document.getElementById('overlayer-loading').style.display = 'table';
            };
            this.hide = function () {
                document.getElementById('overlayer-loading').style.display = 'none';
            };
        })
        ;
