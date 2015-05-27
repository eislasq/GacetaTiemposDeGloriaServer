angular.module('Negocios')
        .controller('AdminCtrl', function ($scope, WS, loading) {
            $scope.currentTab = 'keys';
            $scope.cantidadAgenerar = 1;
            function handleRequestError(result) {
                loading.hide();
                alert(':( Algo anda mal con la conección.' + result.statusText);
            }
            $scope.cargarCategorias = function () {
                var request = WS.obtenerCategorias();
                loading.show();
                request.then(
                        function (result) {/* success function */
                            loading.hide();
                            $scope.categories = result.data.response;
                        },
                        handleRequestError);
            }
            $scope.cargarLlaves = function () {
                var request = WS.obtenerLlaves();
                loading.show();
                request.then(
                        function (result) {/* success function */
                            loading.hide();
                            $scope.llaves = result.data.response;
                        },
                        handleRequestError);
            }

            $scope.login = function (adminPasswd) {
                loading.show();
                var request = WS.login(adminPasswd);
                $scope.adminPasswd = '';
                request.then(
                        function (result) {/* success function */
//                console.log(result);
                            loading.hide();
                            if (result.data.response) {
                                $scope.loginError = false;
                                $scope.logedIn = true;
                                $scope.cargarCategorias();
                                $scope.cargarLlaves();
                            } else {
                                $scope.loginError = true;
                                $scope.logedIn = false;
                            }
                        },
                        handleRequestError);
            };

            $scope.login();

            $scope.logout = function () {
                loading.show();
                var request = WS.logout();
                request.then(
                        function (result) {/* success function */
                            loading.hide();
                            $scope.loginError = false;
                            $scope.logedIn = false;

                        },
                        handleRequestError);
            };

            $scope.addCategory = function (categoryName) {
                var request = WS.neuevaCategoria(categoryName);
                loading.show();
                request.then(
                        function (result) {/* success function */
                            loading.hide();
                            if (result.data.response) {
                                $scope.categories.push(result.data.response);
                                $scope.categoryName = '';
                            } else {
                                alert('Ups, no se guardó');
                            }
                        },
                        handleRequestError);
            };
            $scope.updateCategory = function (category) {
                var request = WS.modificarCategoria(category.categoria_id, category.nombre);
                loading.show();
                request.then(
                        function (result) {/* success function */
                            loading.hide();
                            if (result.data.response) {
                                category.editando = false;
                            } else {
                                alert('Ups, no se guardó');
                            }
                        },
                        handleRequestError);
            };
            $scope.removeCategory = function (category) {
                var request = WS.eliminarCategoria(category.categoria_id);
                loading.show();
                request.then(
                        function (result) {/* success function */
                            loading.hide();
                            if (result.data.response) {
//                                delete(category);
                                var index = $scope.categories.indexOf(category);
                                if (index >= 0) {
                                    $scope.categories.splice(index, 1);
                                }
                            } else {
                                alert('Ups, no se eliminó');
                            }
                        },
                        handleRequestError);
            };
            $scope.generarLlaves = function (cantidad) {
                var request = WS.generarLlaves(cantidad);
                loading.show();
                request.then(
                        function (result) {/* success function */
                            loading.hide();
                            if (result.data.response) {
//                                delete(category);
                                $scope.llaves = $scope.llaves.concat(result.data.response);
                            } else {
                                alert('Ups, no se pudieron generar las llaves');
                            }
                        },
                        handleRequestError);
            };
            $scope.downloadAll = function () {
                console.log('descargar todas');
                for (var k in $scope.llaves) {
                    var llave = $scope.llaves[k];
                    window.open('generateKeyPng.php?text=' + llave.llave);
                }
            };
            $scope.downloadUnused = function () {
                for (var k in $scope.llaves) {
                    var llave = $scope.llaves[k];
                    if (llave.negocio_id) {
                        continue;
                    }
                    window.open('generateKeyPng.php?text=' + llave.llave);
                }
            };
        })
        ;