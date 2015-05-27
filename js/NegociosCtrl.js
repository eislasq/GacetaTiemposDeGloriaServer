angular.module('Negocios')
        .controller('NegociosCtrl', function ($scope, WS, loading) {
            loading.show();
            var request = WS.obtenerProveedoresActualizadosDespuesDe('2015-01-01');
            request.then(function (result) {/* success function */
//                console.log(result);
                loading.hide();
                if (!result.data.response) {
                    alert(':( No se pudieron obtener Negocios');
                } else {
                    $scope.negocios = result.data.response.providers;
                    console.log('providers Actualizados:', $scope.negocios);
                }
            },
                    function (result) {/* error function */
                        loading.hide();
//                        console.log('error');
//                        console.log(result);
                        alert(':( Algo anda mal con la conección.' + result.statusText);
                    });

        })
        ;