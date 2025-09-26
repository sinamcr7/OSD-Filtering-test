<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicon -->
    <link rel="shortcut icon" href="{{asset("assets/images/favicon.jpg")}}">

    <title>test osd filtering plugin</title>

    <!-- Global stylesheets -->
    <link type="text/css" rel="stylesheet" href="{{asset('lib/style.css')}}" />
    <!-- /global stylesheets -->
    <script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>
    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
    <!-- /core JS files -->

    <script>if (window.module) module = window.module;</script>

    <script>
    </script>
    <style>
        @media print {
            .noprint {
                display: none;
            }
            .toprint {
                display: block;
            }
            html, body {
                margin: 0;
                padding: 0;
                background: #FFF;
                font-size: 9.5pt;
            }

        }
        @font-face {
            font-family: "irSans";
            src: url('{{ asset("assets/content/fonts/farsi/IRANSansWeb(FaNum)_Bold.ttf") }}');
            src: local( "?" ), url('{{ asset("assets/content/fonts/farsi/IRANSansWeb(FaNum)_Bold.ttf") }}')format( "truetype" );
        }
        ul{
            list-style: none outside none;
            padding-left: 0;
            margin: 0;
        }
        .d-flex{
            display: flex;
        }

        .viewer-sidebar{
            direction:ltr !important;
            background-color: white;
            padding:15px;
        }
        .filter-icon {
            display: inline-block;
            width: 15px;
            height: 15px;
            background-image: url('{{ asset("assets/images/icon/filter-icon.png") }}');
            background-size: cover;
        }
        .icon-text {
            display: flex; /* فعال‌سازی Flexbox */
            align-items: center; /* تنظیم عمودی آیکون و متن */
            gap: 8px; /* فاصله بین آیکون و متن */
            font-family: "irSans";
            margin-top:30px
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
            margin-right:5px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #6FDDD2;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #6FDDD2;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(14px);
            -ms-transform: translateX(14px);
            transform: translateX(17px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
        .control-panel {
            max-width: 400px;
            margin-top: 10px;
            font-family: Arial, sans-serif;
        }

        .control-item {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 10px;
            color: #333;
        }

        .range-container {
            position: relative;
            width: 100%;
        }

        input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 5px;
            border-radius: 5px;
            background: linear-gradient(to right, #4caf50 50%, #ccc 50%);
            outline: none;
            margin: 0;
            padding: 0;
            transition: background 0.3s ease-in-out;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 15px;
            height: 15px;
            background: #6FDDD2;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
            border: 2px solid #fff;
        }

        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: #6FDDD2;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #fff;
        }

        .value-bubble {
            opacity: 0;
            visibility: hidden;
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #6FDDD2;
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 8px;
            white-space: nowrap;
            pointer-events: none;
            transition: transform 0.3s ease-in-out;
        }

        /* نمایش حباب هنگام Hover یا Focus */
        .range-container input[type='range']:hover + .value-bubble,
        .range-container input[type='range']:focus + .value-bubble {
            opacity: 1;
            visibility: visible;
        }

        /* Button styling (optional) */
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<!-- Page container -->
<div class="page-container" style="min-height: 100vh;">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Content area -->
            <div class="content">
                <div class="container-fluid noprint">
                    <div class="row d-flex">
                        <div class="col-md-10" id="viewer">
                        </div>
                        <div class="col-md-2 viewer-sidebar">
                            <div class="filters">
                                <!-- Invert -->
                                <p class="icon-text"><i class="filter-icon"></i><span style="font-size: 10px">Filters</span></p>
                                <span>Invert</span>
                                <label class="switch" id="switchI">
                                    <input type="checkbox" id="checkbxI">
                                    <span id="invert" class="slider round"></span>
                                </label>
                                <br>
                                <span>Sobel</span>
                                <label class="switch" id="switchS">
                                    <input type="checkbox" id="checkbxS">
                                    <span id="sobel" class="slider round"></span>
                                </label>
                                <br>
                                <span>Grey</span>
                                <label class="switch" id="switchG">
                                    <input type="checkbox" id="checkbxG">
                                    <span id="greyscale" class="slider round"></span>
                                </label>
                                <br>

                                <div class="control-panel">
                                    <!-- Brightness -->
                                    <div class="control-item">
                                        <label for="brightness">Brightness</label>
                                        <div class="range-container">
                                            <input id="brightness" type="range" min="0" max="200" value="100">
                                            <div class="value-bubble">100</div>
                                        </div>
                                    </div>

                                    <!-- Contrast -->
                                    <div class="control-item">
                                        <label for="contrast">Contrast</label>
                                        <div class="range-container">
                                            <input id="contrast" type="range" min="0" max="200" value="100">
                                            <div class="value-bubble">100</div>
                                        </div>
                                    </div>

                                    <!-- Gamma -->
                                    <div class="control-item">
                                        <label for="gamma">Gamma</label>
                                        <div class="range-container">
                                            <input id="gamma" type="range" min="0.1" max="3" step="0.1" value="1">
                                            <div class="value-bubble">1.0</div>
                                        </div>
                                    </div>

                                    <!-- Expposure -->
                                    <div class="control-item">
                                        <label for="exposure">Exposure</label>
                                        <div class="range-container">
                                            <input id="exposure" type="range" min="0" max="200" value="100">
                                            <div class="value-bubble">100</div>
                                        </div>
                                    </div>

                                    <!-- Saturation -->
                                    <div class="control-item">
                                        <label for="saturation">Saturation</label>
                                        <div class="range-container">
                                            <input id="saturation" type="range" min="0" max="200" value="100">
                                            <div class="value-bubble">100</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->
<script src="{{asset('lib/osd/openseadragon/openseadragon.min.js')}}"></script>
<script src="{{asset('lib/osd/OpenSeadragonFiltering/openseadragon-filtering.js')}}"></script>
<script src="{{asset('lib/html2canvas/html2canvas.min.js')}}"></script>
<script src="{{asset('lib/camanjs/caman.full.min.js')}}"></script>
<!--loading osd-->
<script type="text/javascript">
    $(document).ready(function () {
        console.log("path: {{Storage::disk('local')->get('20/20.dzi')}}")
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    $('#sidebarcontrol').click(function() {
        const topNav = $('.top-nav');
        if (topNav.css('padding-right') === '130px') {
            topNav.css('padding-right', '200px');
            $("#aibtn").css('font-size', '15px');
            $(".zoom-items span").css('margin-right', '3px');
        } else     if (topNav.css('padding-right') === '200px') {
            topNav.css('padding-right', '130px');
            $("#aibtn").css('font-size', '12px');
            $(".zoom-items span").css('margin-right', '2px');
        }
    });
    document.querySelectorAll(".range-container").forEach((container) => {
        const rangeInput = container.querySelector("input[type='range']");
        const valueBubble = container.querySelector(".value-bubble");

        function updateRange() {
            const value = rangeInput.value;
            const max = rangeInput.max;
            const min = rangeInput.min;

            // محاسبه موقعیت حباب
            const percent = ((value - min) / (max - min)) * 100;
            valueBubble.style.left = `calc(${percent}% - ${valueBubble.offsetWidth / 2}px)`;

            // تنظیم مقدار حباب
            valueBubble.textContent = value;

            // به‌روزرسانی رنگ پس‌زمینه
            rangeInput.style.background = `linear-gradient(to right, #6FDDD2 ${percent}%, #ccc ${percent}%)`;
        }

        // اجرای اولیه
        updateRange();

        // افزودن رویداد برای تغییر مقدار
        rangeInput.addEventListener("input", updateRange);
    });
    function show_loader_snapshot(){
        // Show the overlay
        const overlay = document.getElementById('overlay-snapshot');
        overlay.classList.remove('hidden');
    }

    function hide_loader_snapshot(){
        // Show the overlay
        const overlay = document.getElementById('overlay-snapshot');
        overlay.classList.add('hidden');
        // alert('Task completed!');
    }

    window.onload = function () {
        let viewer = OpenSeadragon({
            id: "viewer",
            prefixUrl: "/lib/osd/openseadragon/images/",
            tileSources: "storage/5/5.dzi",
            sequenceMode: false,
            overlayPreserveContentDirection: true,
            defaultZoomLevel: 1,
            minZoomLevel: 1,
            maxZoomLevel: 160,
            imageLoaderLimit: 10,
            maxImageCacheCount: 2000,
            preserveImageSizeOnResize: true,
            timeout: 100000,
            tileRetryMax: 0,
            tileRetryDelay: 2500,
            maxTilesPerFrame: 100,
            drawer: "canvas",
            constrainDuringPan: true,
            visibilityRatio: 1,
            showNavigationControl: false,
            showNavigator: true,
            crossOriginPolicy: "Anonymous",
        });

        let navigatorShown = true;

        function showNavigator() {
            if (!navigatorShown) {
                navigatorShown = !navigatorShown;
                viewer.navigator.element.style.display = 'block';
                viewer.scalebarInstance.divElt.style.display = 'block';
            }
        }

        function hideNavigator() {
            if (navigatorShown) {
                navigatorShown = !navigatorShown;
                viewer.navigator.element.style.display = 'none';
                viewer.scalebarInstance.divElt.style.display = 'none';
            }
        }

        Caman.Store.put = function () {
        };
        // Get canvas
        function getCanvas() {
            return viewer.canvas.querySelector("canvas");
        }
        // Store filter values
        const filters = {
            invert: 0,
            greyscale: 0,
            sobel: 0,
            brightness: 1,
            contrast: 1,
            saturation: 1,
            exposure: 1,
            gamma: 1,
        };
        // Apply all filters
        function applyFilters() {
            const canvas = getCanvas();
            if (!canvas) return;
            const { invert, sobel, greyscale, brightness, contrast, saturation, exposure, gamma } = filters;
            // build CSS filter string
            canvas.style.filter =
                `grayscale(${greyscale}) brightness(${brightness * exposure}) contrast(${contrast}) saturate(${saturation}) brightness(${Math.pow(gamma,0.5)}) invert(${invert}) `;
        }

        function applyFiltersBeforeSS(canvas) {
            const { invert, sobel, greyscale, brightness, contrast, saturation, exposure, gamma } = filters;

            // build CSS filter string
            canvas.style.filter =
                `grayscale(${greyscale}) brightness(${brightness * exposure}) contrast(${contrast}) saturate(${saturation}) brightness(${Math.pow(gamma,0.5)}) invert(${invert}) `;
            return canvas;
        }

        const availableFilters = [
            {
                name: 'Invert',
                generate: function() {
                    return {
                        html: '',
                        getParams: function() {
                            return '';
                        },
                        getFilter: function() {
                            /*eslint new-cap: 0*/
                            return OpenSeadragon.Filters.INVERT();
                        }
                    };
                }
            }, {
                name: 'Contrast',
                help: 'Range is from 0 to infinity, although sane values are from 0 ' +
                    'to 4 or 5. Values between 0 and 1 will lessen the contrast ' +
                    'while values greater than 1 will increase it.',
                generate: function(updateCallback) {
                    const $html = $('<div></div>');
                    const spinnerSlider = new SpinnerSlider({
                        $element: $html,
                        init: 1.3,
                        min: 0,
                        sliderMax: 4,
                        step: 0.1,
                        updateCallback: updateCallback
                    });
                    return {
                        html: $html,
                        getParams: function() {
                            return spinnerSlider.getValue();
                        },
                        getFilter: function() {
                            return OpenSeadragon.Filters.CONTRAST(
                                spinnerSlider.getValue());
                        }
                    };
                }
            }, {
                name: 'Exposure',
                help: 'Range is -100 to 100. Values < 0 will decrease ' +
                    'exposure while values > 0 will increase exposure',
                generate: function(updateCallback) {
                    const $html = $('<div></div>');
                    let value = $("#exposure").val()
                    // const spinnerSlider = new SpinnerSlider({
                    //     $element: $html,
                    //     init: 10,
                    //     min: -100,
                    //     max: 100,
                    //     step: 1,
                    //     updateCallback: updateCallback
                    // });
                    return {
                        html: $html,
                        getParams: function() {
                            return value;
                        },
                        getFilter: function() {
                            // const value = spinnerSlider.getValue();
                            return function(context) {
                                const promise = getPromiseResolver();
                                Caman(context.canvas, function() {
                                    this.exposure(value);
                                    this.render(promise.call.back);
                                });
                                return promise.promise;
                            };
                        }
                    };
                }
            }, {
                name: 'Gamma',
                help: 'Range is from 0 to infinity, although sane values ' +
                    'are from 0 to 4 or 5. Values between 0 and 1 will ' +
                    'lessen the contrast while values greater than 1 will increase it.',
                generate: function(updateCallback) {
                    const $html = $('<div></div>');
                    const spinnerSlider = new SpinnerSlider({
                        $element: $html,
                        init: 0.5,
                        min: 0,
                        sliderMax: 5,
                        step: 0.1,
                        updateCallback: updateCallback
                    });
                    return {
                        html: $html,
                        getParams: function() {
                            return spinnerSlider.getValue();
                        },
                        getFilter: function() {
                            const value = spinnerSlider.getValue();
                            return OpenSeadragon.Filters.GAMMA(value);
                        }
                    };
                }
            }, {
                name: 'Saturation',
                help: 'saturation value has to be between -100 and 100',
                generate: function(updateCallback) {
                    const $html = $('<div></div>');
                    const spinnerSlider = new SpinnerSlider({
                        $element: $html,
                        init: 50,
                        min: -100,
                        max: 100,
                        step: 1,
                        updateCallback: updateCallback
                    });
                    return {
                        html: $html,
                        getParams: function() {
                            return spinnerSlider.getValue();
                        },
                        getFilter: function() {
                            const value = spinnerSlider.getValue();
                            return function(context) {
                                const promise = getPromiseResolver();
                                Caman(context.canvas, function() {
                                    this.saturation(value);
                                    this.render(promise.call.back);
                                });
                                return promise.promise;
                            };
                        }
                    };
                }
            }, {
                name: 'Greyscale',
                generate: function() {
                    return {
                        html: '',
                        getParams: function() {
                            return '';
                        },
                        getFilter: function() {
                            return OpenSeadragon.Filters.GREYSCALE();
                        }
                    };
                }
            }, {
                name: 'Sobel Edge',
                generate: function() {
                    return {
                        html: '',
                        getParams: function() {
                            return '';
                        },
                        getFilter: function() {
                            return function(context) {
                                const imgData = context.getImageData(
                                    0, 0, context.canvas.width, context.canvas.height);
                                const pixels = imgData.data;
                                const originalPixels = context.getImageData(0, 0, context.canvas.width, context.canvas.height).data;
                                const oneRowOffset = context.canvas.width * 4;
                                const onePixelOffset = 4;
                                let Gy, Gx, idx = 0;
                                for (let i = 1; i < context.canvas.height - 1; i += 1) {
                                    idx = oneRowOffset * i + 4;
                                    for (let j = 1; j < context.canvas.width - 1; j += 1) {
                                        Gy = originalPixels[idx - onePixelOffset + oneRowOffset] + 2 * originalPixels[idx + oneRowOffset] + originalPixels[idx + onePixelOffset + oneRowOffset];
                                        Gy = Gy - (originalPixels[idx - onePixelOffset - oneRowOffset] + 2 * originalPixels[idx - oneRowOffset] + originalPixels[idx + onePixelOffset - oneRowOffset]);
                                        Gx = originalPixels[idx + onePixelOffset - oneRowOffset] + 2 * originalPixels[idx + onePixelOffset] + originalPixels[idx + onePixelOffset + oneRowOffset];
                                        Gx = Gx - (originalPixels[idx - onePixelOffset - oneRowOffset] + 2 * originalPixels[idx - onePixelOffset] + originalPixels[idx - onePixelOffset + oneRowOffset]);
                                        pixels[idx] = Math.sqrt(Gx * Gx + Gy * Gy); // 0.5*Math.abs(Gx) + 0.5*Math.abs(Gy);//100*Math.atan(Gy,Gx);
                                        pixels[idx + 1] = 0;
                                        pixels[idx + 2] = 0;
                                        idx += 4;
                                    }
                                }
                                context.putImageData(imgData, 0, 0);
                            };
                        }
                    };
                }
            }, {
                name: 'Brightness',
                help: 'Brightness must be between -255 (darker) and 255 (brighter).',
                generate: function(updateCallback) {
                    const $html = $('<div></div>');
                    const spinnerSlider = new SpinnerSlider({
                        $element: $html,
                        init: 50,
                        min: -255,
                        max: 255,
                        step: 1,
                        updateCallback: updateCallback
                    });
                    return {
                        html: $html,
                        getParams: function() {
                            return spinnerSlider.getValue();
                        },
                        getFilter: function() {
                            return OpenSeadragon.Filters.BRIGHTNESS(
                                spinnerSlider.getValue());
                        }
                    };
                }
            }];

        let activeFilters = [];

        function getPromiseResolver() {
            let call = {};
            let promise = new OpenSeadragon.Promise(resolve => {
                call.back = resolve;
            });
            return {call, promise};
        }

        function updateViewerFilters(viewer) {
            const processors = Object.values(activeFilters).map(f => f.getFilter());
            const sync = Object.values(activeFilters).every(f => f.sync !== false);

            viewer.setFilterOptions({
                filters: {
                    items: viewer.world.getItemAt(0),
                    processors: processors
                },
                loadMode: sync ? 'sync' : 'async'
            });
        }

        viewer.gestureSettingsMouse.clickToZoom = false;

        viewer.gestureSettingsMouse.dblClickToZoom = true;
        viewer.addHandler('pan', () => {
            viewer.imageLoader.jobLimit = 1;
            viewer.addOnceHandler('animation-finish', () => {
                viewer.imageLoader.jobLimit = 200; // or whatever it was originally
            });
        });

        $("#snapshot").on("click", function () {
            show_loader_snapshot()
            hideNavigator();
            html2canvas($("#viewer")[0]).then((canvas) => {
                let filteredCanvas = applyFiltersBeforeSS(canvas)
                filteredCanvas.toBlob(function (blob) {
                    var fd = new FormData();
                    fd.append('image', blob);
                    let sett = {
                        "url": url_snapshot,
                        "method": "post",
                        data: fd,
                        contentType: false,
                        processData: false,
                        "headers": {
                            "Accept": "application/json",
                            "Authorization": "Bearer {{$token}}"
                        },
                    };

                    $.ajax(sett).done(function (response) {
                        console.log(response)
                        alert('اسنپ شات با موفقیت ایجاد شد!')
                        showNavigator();
                        hide_loader_snapshot()
                    });
                });
            });
        });

        $("#checkbxI").off("click").on("click", function () {
            // console.log("Invert");
            // Openseadragon filtering
            const filterDef = availableFilters.find(f => f.name === "Invert");
            const generatedFilter = filterDef.generate();
            if (this.checked) {
                // CSS filtering
                // $("#viewer").addClass("invert");
                // filters.invert = 1;
                // applyFilters()

                // Openseadragon filtering
                activeFilters["Invert"] = generatedFilter;

            } else {
                // CSS filtering
                // filters.invert = 0;
                // applyFilters();
                // $("#viewer").removeClass("invert");

                // Openseadragon filtering
                delete activeFilters["Invert"];
            }
            // Openseadragon filtering
            updateViewerFilters(viewer);
        });

        $("#checkbxG").off("click").on("click", function () {
            // console.log("Greyscale");
            // Openseadragon filtering
            // const filterDef = availableFilters.find(f => f.name === "Greyscale");
            // const generatedFilter = filterDef.generate();
            if (this.checked) {
                // CSS filtering
                filters.greyscale = 1;
                applyFilters()
                // $("#viewer").addClass("greyscale");

                // Openseadragon filtering
                // activeFilters["Greyscale"] = generatedFilter;

            } else {
                // CSS filtering
                filters.greyscale = 0;
                applyFilters();
                // $("#viewer").removeClass("invert");

                // Openseadragon filtering
                // delete activeFilters["Greyscale"];
            }
            // Openseadragon filtering
            // updateViewerFilters(viewer);
        });

        $("#checkbxS").off("click").on("click", function () {
            console.log("Sobel");
            // Openseadragon filtering
            const filterDef = availableFilters.find(f => f.name === "Sobel Edge");
            const generatedFilter = filterDef.generate();
            if (this.checked) {
                activeFilters["Sobel Edge"] = generatedFilter;
            } else {
                delete activeFilters["Sobel Edge"];
            }
            updateViewerFilters(viewer);
            // return OpenSeadragon.Filters.INVERT();
        });

        // Brightness (100 = no change, <100 darker, >100 brighter)
        $("#brightness").on("input", function () {
            // Openseadragon filtering
            // const value = parseInt(this.value, 10) - 100;
            // activeFilters["Brightness"] = {
            //     getFilter: () => OpenSeadragon.Filters.BRIGHTNESS(value),
            //     sync: true
            // };
            // updateViewerFilters(viewer);
            // CSS filtering
            // const brightness = this.value / 100;
            //
            // // OSD renders canvas inside viewer.canvas
            // const canvas = viewer.canvas.querySelector("canvas");
            //
            // if (canvas) {
            //     canvas.style.filter = `brightness(${brightness})`;
            // }

            filters.brightness = this.value / 100;
            // filters.brightness = this.value;
            applyFilters();
        });

        // Contrast (100 = 1.0 = no change)
        $("#contrast").on("input", function () {
            // Openseadragon filtering
            // const value = parseInt(this.value, 10) / 100;
            // activeFilters["Contrast"] = {
            //     getFilter: () => OpenSeadragon.Filters.CONTRAST(value),
            //     sync: true
            // };
            // updateViewerFilters(viewer);
            // CSS filtering
            // const contrast = this.value / 100;
            //
            // // OSD renders canvas inside viewer.canvas
            // const canvas = viewer.canvas.querySelector("canvas");
            //
            // if (canvas) {
            //     canvas.style.filter = `contrast(${contrast})`;
            // }

            filters.contrast = this.value / 100;
            // filters.contrast = this.value;
            applyFilters();
        });

        // Gamma (1.0 = no change)
        $("#gamma").on("input", function () {
            // Openseadragon filtering
            // const value = parseFloat(this.value);
            // activeFilters["Gamma"] = {
            //     getFilter: () => OpenSeadragon.Filters.GAMMA(value),
            //     sync: true
            // };
            // updateViewerFilters(viewer);
            // CSS filtering
            // const gamma = this.value / 100;
            //
            // // OSD renders canvas inside viewer.canvas
            // const canvas = viewer.canvas.querySelector("canvas");
            //
            // if (canvas) {
            //     canvas.style.filter = `gamma(${gamma})`;
            // }

            // filters.gamma = this.value / 100;
            filters.gamma = this.value;
            applyFilters();
        });

        // Exposure
        $("#exposure").on("input", function () {
            // const value = parseInt(this.value, 10) - 50; // center at 50 = no change
            // // Openseadragon filtering
            // const filterDef = availableFilters.find(f => f.name === "Exposure");
            // const generatedFilter = filterDef.generate();
            // activeFilters["Exposure"] = generatedFilter(value);
            // // activeFilters["Exposure"] = {
            // //     getFilter: () => (context, callback) => {
            // //         Caman(context.canvas, function () {
            // //             this.exposure(value);
            // //             this.render(callback);
            // //         });
            // //     },
            // //     sync: false
            // // };
            // updateViewerFilters(viewer);

            filters.exposure = this.value / 100;
            // filters.exposure = this.value;
            applyFilters();
        });

        // Saturation
        $("#saturation").on("input", function () {
            // const value = parseInt(this.value, 10) - 100; // center at 100 = no change
            // // Openseadragon filtering
            // const filterDef = availableFilters.find(f => f.name === "Saturation");
            // const generatedFilter = filterDef.generate();
            // activeFilters["Saturation"] = generatedFilter(value);
            //
            // // activeFilters["Saturation"] = {
            // //     getFilter: () => (context, callback) => {
            // //         Caman(context.canvas, function () {
            // //             this.saturation(value);
            // //             this.render(callback);
            // //         });
            // //     },
            // //     sync: false
            // // };
            // updateViewerFilters(viewer);

            filters.saturation = this.value / 100;
            // filters.saturation = this.value;
            applyFilters();
        });

    }
</script>
</body>
</html>
