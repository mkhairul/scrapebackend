<!DOCTYPE html>
<html lang="en" ng-app="jace" ng-class="{'full-page-map': isFullPageMap}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="msapplication-config" content="assets/img/favicon/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">

  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon-precomposed" sizes="60x60" href="apple-touch-icon-60x60.png" />
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="apple-touch-icon-152x152.png" />
  <link rel="icon" type="image/png" href="favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="favicon-128.png" sizes="128x128" />
  <meta name="application-name" content="&nbsp;"/>
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta name="msapplication-TileImage" content="mstile-144x144.png" />
  <meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
  <meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
  <meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
  <meta name="msapplication-square310x310logo" content="mstile-310x310.png" />


  <link rel="manifest" href="assets/img/favicon/manifest.json">
  <link rel="shortcut icon" href="assets/img/favicon/favicon.ico">

  <title ng-bind="pageTitle">Automation System</title>

  <link rel="stylesheet" href="assets/css/vendors.min.css"/>
  <link rel="stylesheet" href="assets/css/styles.min.css"/>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body ng-controller="MainController" scroll-spy id="top" ng-class="[theme.template, theme.color]">
  <main>
    <div ng-include src="'assets/tpl/partials/sidebar.html'"></div>
    <div class="main-container">
      <div ng-include src="'assets/tpl/partials/topnav.html'"></div>
      <div class="main-content" autoscroll="true" ng-cloak ng-view bs-affix-target init-ripples></div>
    </div>
  </main>

  <div class="alert-container-top-right"></div>
  <script src="assets/js/vendors.min.js"></script>
  <script charset="utf-8" src="assets/js/vendors/angular-placeholders.js"></script>
  <script charset="utf-8" src="assets/js/vendors/side-nav.js"></script>
  <script charset="utf-8" src="assets/js/vendors/ripples.js"></script>
  <script charset="utf-8" src="assets/js/vendors/fsm-sticky-header.js"></script>
  <script charset="utf-8" src="assets/js/vendors/angular-smooth-scroll.js"></script>
  <script charset="utf-8" src="assets/js/colors.js"></script>

  <script charset="utf-8" src="assets/js/app.js"></script>
  <script charset="utf-8" src="assets/js/app.constants.js"></script>
  <script charset="utf-8" src="assets/js/app.config.js"></script>
  <script charset="utf-8" src="assets/js/app.filters.js"></script>
  <script charset="utf-8" src="assets/js/app.demo.js"></script><!--@grep dist-->

  <script charset="utf-8" src="assets/js/directives/formcontrol.js"></script>
  <script charset="utf-8" src="assets/js/directives/navbar-hover.js"></script>
  <script charset="utf-8" src="assets/js/directives/navbar-search.js"></script>
  <script charset="utf-8" src="assets/js/directives/navbar-toggle.js"></script>
  <script charset="utf-8" src="assets/js/directives/noui-slider.js"></script>
  <script charset="utf-8" src="assets/js/directives/todo-widget.js"></script>
  <script charset="utf-8" src="assets/js/directives/menu-link.js"></script>
  <script charset="utf-8" src="assets/js/directives/menu-toggle.js"></script>
  <script charset="utf-8" src="assets/js/directives/vectormap.js"></script>
  <script charset="utf-8" src="assets/js/directives/autofocus.js"></script>
  <script charset="utf-8" src="assets/js/directives/card-flip.js"></script>
  <script charset="utf-8" src="assets/js/directives/scroll-spy.js"></script>
  <script charset="utf-8" src="assets/js/directives/init-ripples.js"></script>
  <script charset="utf-8" src="assets/js/directives/keypress.js"></script>

  <script charset="utf-8" src="assets/js/services/color-service.js"></script>
  <script charset="utf-8" src="assets/js/services/todo-service.js"></script>
  <script charset="utf-8" src="assets/js/services/quote-service.js"></script>

  <script charset="utf-8" src="assets/js/controllers/main.js"></script>
  <script charset="utf-8" src="assets/js/controllers/dashboard.js"></script>
  <script charset="utf-8" src="assets/js/controllers/charts.js"></script>
  <script charset="utf-8" src="assets/js/controllers/colors.js"></script>
  <script charset="utf-8" src="assets/js/controllers/buttons.js"></script>
  <script charset="utf-8" src="assets/js/controllers/lists.js"></script>
  <script charset="utf-8" src="assets/js/controllers/maps/full-map.js"></script>
  <script charset="utf-8" src="assets/js/controllers/maps/basic-map.js"></script>
  <script charset="utf-8" src="assets/js/controllers/maps/clickable-map.js"></script>
  <script charset="utf-8" src="assets/js/controllers/maps/searchable-map.js"></script>
  <script charset="utf-8" src="assets/js/controllers/maps/zoomable-map.js"></script>
  <script charset="utf-8" src="assets/js/controllers/maps/vector-map.js"></script>
  <script charset="utf-8" src="assets/js/controllers/forms.js"></script>
  <script charset="utf-8" src="assets/js/controllers/upload.js"></script>
  <script charset="utf-8" src="assets/js/controllers/tables/basic.js"></script>
  <script charset="utf-8" src="assets/js/controllers/tables/data.js"></script>
  <script charset="utf-8" src="assets/js/controllers/apps/crud.js"></script>
  <script charset="utf-8" src="assets/js/controllers/apps/todo.js"></script>
  <script charset="utf-8" src="assets/js/controllers/category.js"></script>
  <script charset="utf-8" src="assets/js/controllers/logistics.js"></script>
  <script charset="utf-8" src="assets/js/controllers/quote.js"></script>
  <script charset="utf-8" src="assets/js/controllers/user.js"></script>
</body>
</html>

