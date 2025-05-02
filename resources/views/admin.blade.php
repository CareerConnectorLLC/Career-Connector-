<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Fonts -->
    <!-- Favicon for all browsers -->
    <link rel="icon" href="/admin_assets/assets/favicons/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/admin_assets/assets/favicons/favicon.ico" type="image/x-icon">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/admin_assets/assets/favicons/apple-touch-icon.png">

    <!-- PNG Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/admin_assets/assets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin_assets/assets/favicons/favicon-16x16.png">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="/admin_assets/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/css/rtl/theme-default.css" />
    {{-- <link rel="stylesheet" href="/admin_assets/assets/vendor/css/rtl/theme-default-dark.css" /> --}}
    {{-- <link rel="stylesheet" href="/admin_assets/assets/vendor/css/rtl/theme-raspberry.css" /> --}}
    <link rel="stylesheet" href="/admin_assets/assets/css/demo.css" />

    <link rel="stylesheet" href="/admin_assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/libs/typeahead-js/typeahead.css" />

    <link rel="stylesheet" href="/admin_assets/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />


    <link rel="stylesheet" href="/admin_assets/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/libs/animate-css/animate.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="/admin_assets/assets/vendor/css/pages/cards-advance.css" />
    <link rel="stylesheet" href="/admin_assets/assets/vendor/css/pages/page-profile.css" />

    {{-- <script src="/admin_assets/assets/vendor/js/helpers.js"></script>
        <script src="/admin_assets/assets/js/config.js"></script>
        <script src="/admin_assets/assets/js/main.js"></script> --}}


    {{--
        <script src="/admin_assets/assets/vendor/js/template-customizer.js"></script>
        <script src="/admin_assets/assets/js/config.js"></script> --}}

    <script src="/admin_assets/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/admin_assets/assets/vendor/js/bootstrap.js"></script>
    <script src="/admin_assets/assets/vendor/js/menu.js"></script>
    <script src="/admin_assets/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="/admin_assets/assets/js/ui-modals.js"></script>



    @vite('resources/js/app.js')



    @routes
    @inertiaHead
</head>

<body>
    @inertia

</body>

</html>