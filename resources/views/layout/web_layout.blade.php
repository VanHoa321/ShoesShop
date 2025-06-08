<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>ShoesShop</title>

    <link rel="stylesheet" href="{{ asset('web-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/all-fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/flexslider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{asset("assets/plugins/select2/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{asset("assets/plugins/toastr/toastr.min.css")}}" rel="stylesheet" />
    <link href="{{asset("assets/plugins/toastr/toastr.css")}}" rel="stylesheet" />

    <style>
        .favourite-btn.favourited i {
            color: #e74c3c;
            font-weight: bold;
        }

        .favourite-btn i {
            transition: color 0.3s;
        }
    </style>

</head>

<body class="home-9">
    @include('layout.partial.header')

    @if(Session::has('messenge') && is_array(Session::get('messenge')))
        @php
            $messenge = Session::get('messenge');
        @endphp
        @if(isset($messenge['style']) && isset($messenge['msg']))
            @php
                Session::forget('messenge');
            @endphp
        @endif
    @endif

    @yield('content')

    @include('layout.partial.footer')

    <script src="{{ asset('web-assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/counter-up.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/countdown.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/flexslider-min.js') }}"></script>
    <script src="{{ asset('web-assets/js/main.js') }}"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset("assets/plugins/toastr/toastr.min.js")}}"></script>
    @yield('scripts')
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-bottom-right',
            timeOut: 2000,
            "extendedTimeOut": "1000"
        };
    </script>

    <script>
        $('#lfm').filemanager('image', {
            prefix: '/files-manager'
        });

        $(document).ready(function () {
            var lfm = function (options, cb) {
                var route_prefix = (options && options.prefix) ? options.prefix : '/files-manager';
                var type = options.type || 'image';
                window.open(route_prefix + '?type=' + type, 'FileManager', 'width=700,height=400');
                window.SetUrl = cb;
            };

            var LFMButton = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="note-icon-picture"></i>',
                    tooltip: 'Insert image with filemanager',
                    click: function () {
                        lfm({
                            type: 'image',
                            prefix: '/files-manager'
                        }, function (lfmItems, path) {
                            lfmItems.forEach(function (lfmItem) {
                                context.invoke('insertImage', lfmItem.url);
                            });
                        });
                    }
                });
                return button.render();
            };

            var initialUrl = $('#thumbnail').val();
            if (initialUrl) {
                $('#holder').attr('src', initialUrl);
            } else {
                $('#holder').attr('src', '/storage/photos/1/Avatar/no-image.jpg');
            }

            $('#lfm').filemanager('image');

            $('#lfm').on('click', function () {
                var route_prefix = '/files-manager';
                window.open(route_prefix + '?type=image', 'FileManager', 'width=700,height=400');
                window.SetUrl = function (items) {
                    var url = items[0].url;
                    $('#holder').attr('src', url);
                    $('#thumbnail').val(url);
                    $('#thumbnail').trigger('change');
                };
            });
        });
    </script>
</body>

</html>