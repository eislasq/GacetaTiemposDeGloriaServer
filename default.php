<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
        <title>Servicios Tiempos de gloria</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/index.css">

        <script src="//code.jquery.com/jquery.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.min.js"></script>
        <script src="//code.angularjs.org/1.2.8/angular-route.min.js"></script>
        <script src="//code.angularjs.org/1.2.27/i18n/angular-locale_es-mx.js"></script>

        <script src="js/index.js"></script>
        <script src="js/services.js"></script>
        <script src="js/AdminCtrl.js"></script>
        <script src="js/AboutCtrl.js"></script>
        <script src="js/MiNegocioCtrl.js"></script>
        <script src="js/NegociosCtrl.js"></script>
    </head>
    <body ng-app="Negocios">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Desplegar navegacion</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Negocios</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#/mi-negocio/">Mi negocio</a></li>
                    <li><a href="#/admin/">Admin</a></li>
                    <li><a href="#/about/">Terminos de uso</a></li>
                    <li><a href="#/about/">Acerca de</a></li>
                    <!------>
                    <li><a href="https://play.google.com/store/apps/details?id=com.IslasCruz.GacetaTiemposDeGloria" target="_blank">
                            <img src="img/android-app-on-google-play.png"/></a>
                    </li>
                    <li>
                        <a href="javascript:jQuery('#form-donar-menu').submit()">
                            <form id="form-donar-menu" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="TKPBHK9ABRYJG">
                                <input type="image" src="https://www.paypalobjects.com/es_XC/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
                                <img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
                            </form>
                        </a>
                    </li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>

        <div class="container">

            <div ng-view></div>

        </div>
        <div id="overlayer-loading">
            <i class="fa fa-cog fa-spin"></i>
        </div>
    </body>
</html>