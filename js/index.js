angular.module('Negocios', ['ngRoute'])
        .config(function ($routeProvider) {
            $routeProvider
                    .when(
                            '/'
                            , {templateUrl: 'templates/negocios.html'
                             , controller: 'NegociosCtrl'})
                    .when(
                            '/mi-negocio'
                            , {templateUrl: 'templates/mi-negocio.html'
                                , controller: 'MiNegocioCtrl'})
                    .when(
                            '/about'
                            , {templateUrl: 'templates/about.html'
                                , controller: 'AboutCtrl'})
                    .when(
                            '/admin'
                            , {templateUrl: 'templates/admin.html'
                                , controller: 'AdminCtrl'})
                    .otherwise({redirectTo: '/'});
        })
        ;