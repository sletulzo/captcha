@php ($captchaUuid = \Illuminate\Support\Str::uuid())

<div class="aw-captcha-modal-marker" id="{{ $captchaUuid }}" data-open="modal">
    <input type="hidden" name="_captcha_token" value="{{ csrf_token() }}">
</div>


<script>
    var response = {};

    $(function() {
        $('.aw-captcha-modal-marker').each(function() {
            var uuid = $(this).attr('id');
            submitFormCaptcha(uuid);
        });
    });

    $(document).on('click', '.reload-captcha', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var uuid = $(this).closest('.aw-captcha').attr('data-id');
        loadCaptcha(uuid);
    });

    $(document).on('click', '.valid-captcha', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var uuid = $(this).closest('.aw-captcha').attr('data-id');
        var container = $(this).closest('.aw-captcha');
        var form = $(this).closest('form');
        var input = container.find('input[name="captcha"]');
        var value = parseInt(input.val());

        // Success function
        if (response[uuid] === value) {
            successCaptcha(uuid);
        } else {
            loadCaptcha(uuid);
        }
    });

    // Close modal on click outside
    $(document).click(function(e) { 
        var $target = $(e.target);
        var captchaModal = $('.aw-captcha-modal');
        if (!$target.closest('.aw-captcha').length && captchaModal.length) {
            captchaModal.fadeOut(300, function() {
                $(this).remove();
            });
        }
    });

    function captchaHtml(uuid) {
        var logo = captchaLogoSvg();

        return '<div class="aw-captcha-modal">'
            + '<div class="aw-captcha-backdrop"></div>'
            + '<div class="aw-captcha" data-id="'+ uuid +'">'
            + '<div class="aw-captcha-question">'
            + '<div class="aw-captcha-circle-container"></div>'
            + '</div>'
            + '<div class="aw-captcha-container">'
            + '<div class="aw-captcha-container-title">Combien de barre(s) <span class="aw-color-change"></span> voyez-vous ?</div>'
            + '<div class="aw-captcha-container-bottom">'
            + '<div class="aw-captcha-container-input">'
            + '<input type="text" name="captcha" required>'
            + '<button class="btn btn-secondary reload-captcha"><i class="uil uil-redo"></i></button>'
            + '</div>'
            + '<div class="aw-captcha-container-logo">Captcha Powered by '+ logo +'</div>'
            + '<button class="btn btn-primary valid-captcha"><i class="uil uil-unlock-alt"></i>Je valide ma réponse</button>'
            + '</div></div></div></div>';
    }

    function captchaLogoSvg() {
        return '<svg width="50" height="11" viewBox="0 0 50 11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            + '<path d="M6.13494 8.6763C5.7093 8.90546 5.22132 9.03151 4.70449 9.02191C4.27018 9.01385 3.85974 8.91083 3.49231 8.73288L2.2964 10.1717C2.99011 10.5957 3.80101 10.8462 4.67081 10.8623C5.62238 10.88 6.51378 10.6147 7.2658 10.1433L6.13494 8.6763Z" fill="#FEBD00"/>'
            + '<path d="M4.75328 1.84073C6.36821 1.84073 7.67758 3.15926 7.67758 4.78589C7.67758 5.82795 7.14069 6.74364 6.32966 7.2671L7.47125 8.71236C8.70137 7.84736 9.50526 6.41127 9.50526 4.78589C9.50526 2.14272 7.37772 0 4.75328 0V1.84073Z" fill="#FF84F9"/>'
            + '<path d="M4.75199 0C2.12754 0 0 2.14272 0 4.78589C0 6.49216 0.886714 7.98991 2.22107 8.83729L3.3698 7.38214C2.45167 6.88547 1.82769 5.90902 1.82769 4.78589C1.82769 3.15926 3.13706 1.84073 4.75199 1.84073V0Z" fill="#00A9FA"/>'
            + '<path d="M16.3012 2.53514C15.825 2.47748 15.4446 2.43904 15.1484 2.43904C14.4284 2.43904 14.2029 2.63844 14.2029 3.11414V3.16219H15.2395V4.33942H14.2029V8.14738H12.8518V4.33942H12.2207V3.16219H12.8518V3.11414C12.8518 1.81439 13.3644 1.36752 14.6882 1.36752C15.1302 1.36752 15.661 1.41557 16.3012 1.49966L17.6523 1.35791V8.14738H16.3012V2.53514Z" fill="#1F265A"/>'
            + '<path d="M18.5576 2.71582H20.0064V5.37946C20.0064 6.19356 20.3533 6.74816 21.8216 6.74816C23.0188 6.74816 23.8397 6.1961 23.8983 5.8323V2.71582H25.3471V7.99476H24.0718L23.9765 7.23154L23.9667 7.22136C23.5807 7.61315 22.7891 8.1474 21.4649 8.1474C19.3199 8.1474 18.5576 6.97204 18.5576 5.39218V2.71582Z" fill="#1F265A"/>'
            + '<path d="M34.8525 2.71582H36.3013V5.37946C36.3013 6.19356 36.6482 6.74816 38.1166 6.74816C39.3137 6.74816 40.1346 6.1961 40.1932 5.8323V2.71582H41.642V7.99476H40.3667L40.2714 7.23154L40.2616 7.22136C39.8756 7.61315 39.084 8.1474 37.7599 8.1474C35.6148 8.1474 34.8525 6.97204 34.8525 5.39218V2.71582Z" fill="#1F265A"/>'
            + '<path d="M31.6317 3.15689H32.8671V4.33537H31.6317V8.14738H30.2495V4.33537H27.8277V8.14738H26.4455V4.33537H25.7998V3.15689H26.4455V3.10879C26.4455 1.80766 27.0259 1.36032 28.0584 1.36032C28.3988 1.36032 28.795 1.40842 29.2379 1.49259V2.58449C28.9162 2.55563 28.6575 2.53639 28.4547 2.53639C28.0118 2.53639 27.8277 2.65183 27.8277 3.10639V3.15449H30.2495V3.10639C30.2495 1.80285 30.8299 1.35791 31.8625 1.35791C32.2028 1.35791 32.599 1.40601 33.0419 1.49019V2.58208C32.7202 2.55322 32.4615 2.53398 32.2587 2.53398C31.8159 2.53398 31.6317 2.64943 31.6317 3.10398V3.15208V3.15689Z" fill="#1F265A"/>'
            + '<path d="M43.9282 7.03276L43.9376 7.73852V9.50529H42.5479V2.92049L43.8907 2.78169L43.9282 3.16281L43.9376 3.17222C44.5962 2.89226 45.3368 2.71582 46.2062 2.71582C48.3647 2.71582 49.3373 3.8027 49.3373 5.26364C49.3373 6.72457 48.271 7.87733 46.4008 7.87733C45.0672 7.87733 44.2329 7.31977 43.9376 7.021L43.9282 7.03041V7.03276ZM43.9376 4.29909V5.74121C43.9939 6.07527 45.0766 6.58813 46.225 6.58813C47.3734 6.58813 47.9194 6.03998 47.9194 5.27775C47.9194 4.51552 47.4741 4.01208 46.0656 4.01208C45.3625 4.01208 44.6196 4.15088 43.9353 4.30144L43.9376 4.29909Z" fill="#1F265A"/>'
            + '</svg>';
    }

    function submitFormCaptcha(uuid) {
        var body = $('body');
        var marker = $('#' + uuid);
        var form = marker.closest('form');

        form.on('submit', function(e) {
            var markerOpen = form.find('.aw-captcha-modal-marker').attr('data-open');

            // Let form submit
            if (markerOpen === undefined) {
                return true;
            } else {
                e.preventDefault();
                e.stopPropagation();

                var body = $('body');
                var captcha = captchaHtml(uuid);
                body.append(captcha);
                loadCaptcha(uuid);
            }
        });
    } 

    function loadCaptcha(uuid) {
        hideModalCaptcha();
        
        var colors = [
            {'id': 1, 'name': 'Bleue', 'color': '#4c74ff'},
            {'id': 2, 'name': 'Jaune', 'color': '#ffcb33'},
            {'id': 3, 'name': 'Verte', 'color': '#29cc39'},
            {'id': 4, 'name': 'Violet', 'color': '#f030ff'},
            {'id': 5, 'name': 'Orange', 'color': '#ff6633'},
            {'id': 6, 'name': 'Rose', 'color': '#e62e7b'}
        ];

        // Get vars
        var container = $('.aw-captcha[data-id="'+ uuid +'"]');
        var containerCircle = container.find('.aw-captcha-circle-container');
        var containerLabel = container.find('.aw-color-change');
        var containerInput = container.find('input[name="captcha"]');
        var tmpResponse = 0;

        // Insert value to label
        var finalRandom = Math.floor(Math.random() * 5);
        var finalColor = colors[finalRandom];
        containerLabel.html(finalColor.name);
        containerLabel.css('color', finalColor.color);
        containerInput.val('');

        // Init response to null
        response[uuid] = null;

        // Insert circle insinde container
        containerCircle.html('');

        // Create SVG
        var svg = '<svg width="300" height="300" viewBox="0 0 179 179" fill="none" xmlns="http://www.w3.org/2000/svg">'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="87.5" stroke="#2EE6CA" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M168.107 51.0216C153.869 21.9881 124.019 2 89.5 2" stroke="#3361FF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 89.5 89.5" to="360 89.5 89.5" dur="1s"/></path>'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="76.5625" stroke="#FFCB33" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M148.12 138.752C159.317 125.439 166.062 108.257 166.062 89.5001C166.062 77.5525 163.326 66.2441 158.445 56.1675" stroke="#FFCB33" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="360 89.5 89.5" to="0 89.5 89.5" dur="1s"/></path>'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="65.625" stroke="#29CC39" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M100.955 154.129C116.517 151.39 130.189 143.167 139.904 131.527" stroke="#29CC39" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 89.5 89.5" to="360 89.5 89.5" dur="1s"/></path>'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="54.6875" stroke="#f030ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M66.3291 139.051C73.3645 142.346 81.2169 144.187 89.4994 144.187C92.5123 144.187 95.4683 143.944 98.3488 143.475" stroke="#f030ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="360 89.5 89.5" to="0 89.5 89.5" dur="1s"/></path>'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="43.75" stroke="#FF6633" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M59.9189 121.734C62.9703 124.536 66.4207 126.91 70.175 128.761" stroke="#FF6633" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 89.5 89.5" to="360 89.5 89.5" dur="1s"/></path>'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="32.8125" stroke="#3361FF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M62.3802 71.0234C58.788 76.2859 56.6875 82.6476 56.6875 89.5C56.6875 98.7263 60.4954 107.063 66.6254 113.025" stroke="#3361FF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="360 89.5 89.5" to="0 89.5 89.5" dur="1s"/></path>'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="21.875" stroke="#E62E7B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M96.334 68.7138C94.1837 68.0072 91.8863 67.625 89.4996 67.625C82.4609 67.625 76.1986 70.9495 72.1973 76.1139" stroke="#E62E7B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 89.5 89.5" to="360 89.5 89.5" dur="1s"/></path>'
            + '<circle opacity="0.2" cx="89.5" cy="89.5" r="10.9375" stroke="#33BFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            + '<path d="M100.129 86.9089C99.2876 83.4472 96.7995 80.6298 93.541 79.3335" stroke="#33BFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><animateTransform attributeName="transform" attributeType="XML" type="rotate" from="360 89.5 89.5" to="0 89.5 89.5" dur="1s"/></path>'
            + '</svg>';

        containerCircle.append(svg);

        var htmlSvg = containerCircle.find('svg');

        htmlSvg.find('circle').each(function() {
            var circle = $(this);
            var path = circle.next('path');
            var random = Math.floor(Math.random() * 5);
            var color = colors[random];

            // Update color
            circle.attr('stroke', color.color);
            path.attr('stroke', color.color);
            
            // Insert value to response
            if (color.id == finalColor.id) {
                tmpResponse++;
            }
        });

        response[uuid] = tmpResponse;
    }

    function successCaptcha(uuid) {
        var marker = $('#' + uuid);
        var form = marker.closest('form');

        var container = $('.aw-captcha[data-id="'+ uuid +'"]');
        var containerQuestion = container.find('.aw-captcha-question');
        var containerBottom = container.find('.aw-captcha-container-bottom');
        var containerTitle = container.find('.aw-captcha-container-title');
        var tokenInput = $('input[name="_captcha_token"]').val();
        var svg = '<div class="aw-captcha-success-container"><div class="aw-captcha-success"><span class="icon-line line-tip"></span><span class="icon-line line-long"></span><div class="icon-circle"></div><div class="icon-fix"></div></div></div>';
        
        // Replace token
        form.find('input[name="_token"]').val(tokenInput);

        // Replace dom element
        containerQuestion.html(svg);
        containerTitle.html('Bravo, vous allez être redirigé vers la page demandée.');
        containerBottom.html('');
        marker.remove();

        setTimeout(function(){
            submitFormCaptcha();
            form.submit();
            container.fadeOut(300, function() {
                $(this).remove();
            });
        }, "1000");
    }

    function hideModalCaptcha() {
        $('.modal').modal('hide');
    }

</script>

<style>

    .aw-captcha-backdrop {
        opacity: 0.5;
        position: fixed;
        width: 100vw;
        height: 100vh;
        background: #000;
        top: 0;
        left: 0;
        z-index: 2040;
    }

    .aw-captcha {
        width: 400px;
        height: 500px;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: calc(50% - 250px);
        left: calc(50% - 200px);
        z-index: 2050;
        background: #fff;
        box-shadow: 0px 2.8712751865386963px 8.613825798034668px 0px #0000000F;
        border-radius: 10px;
    }

    .aw-captcha .aw-captcha-question {
        cursor: pointer;
        position: relative;
    }

    .aw-captcha .aw-captcha-question .aw-captcha-circle-container {
        margin: 20px 0px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .aw-captcha .aw-captcha-question .aw-captcha-circle-container svg circle {
        transition: all 0.5s;
    }

    .aw-captcha .aw-captcha-container {
        position: relative;
        height: 100%;
    }

    .aw-captcha .aw-captcha-container-title {
        color: #4D5E80;
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 10px;
        text-align: center;
        padding: 0 15px;
    }

    .aw-captcha .aw-captcha-container input {
        width: 85px;
        height: 40px;
        border-radius: 5px;
        background-color: #fff;
        border: 2px solid #F5F6F7;
        box-shadow: 0px 0.9444444179534912px 2.3611111640930176px 0px #26334D08;
        text-align: center;
    }

    .aw-captcha .aw-captcha-container-input {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
    }

    .aw-captcha .aw-captcha-container-logo {
        text-align: center;
        font-size: 8px;
        font-weight: bold;
        color: #000;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .aw-captcha .aw-captcha-container-logo svg {
        width: 80px;
        height: 19px;
        margin-left: 8px;
    }

    .aw-captcha .aw-captcha-container button.valid-captcha {
        height: 45px;
        padding: 5px;
        font-size: 14px;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15px;
        border-radius: 0;
        width: 100%;
        font-weight: 500;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .aw-captcha .aw-captcha-container button.valid-captcha i {
        font-size: 18px;
        line-height: 18px;
        height: 18px;
        margin-right: 5px;
    }

    .aw-captcha .aw-captcha-container button.reload-captcha {
        flex: 0 auto;
        width: 40px;
        height: 39px;
        margin-left: 5px;
        background: #fff;
        color: var(--main-primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .aw-captcha .aw-captcha-container button.reload-captcha i {
        font-size: 19px;
        margin-right: 0;
    }

    .aw-captcha .aw-captcha-question .circle {
        border-radius: 50%;
        position: absolute;
        border: 2px solid #eee;
    }

    .aw-captcha .aw-captcha-question .circle::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid #fff;
        z-index: 1;
    }

    .aw-captcha button:focus, .aw-captcha button:active, .aw-captcha input:focus {
        outline: none !important;
    }

    /* ---------------------------------------------------------- */
    /* ------------------ Success captcha ----------------------- */
    /* ---------------------------------------------------------- */
    .aw-captcha .aw-captcha-success-container {
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px;
        height: 300px;
        margin: 20px 0px;
    }

    .aw-captcha .aw-captcha-success {
        width: 80px;
        height: 80px;
        zoom: 0.92;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #4CAF50;
    }

    .aw-captcha .aw-captcha-success::before, .aw-captcha .aw-captcha-success::after {
        content: "";
        height: 100px;
        position: absolute;
        background: #FFFFFF;
        transform: rotate(-45deg);
    }

    .aw-captcha .aw-captcha-success::before {
        top: 3px;
        left: -2px;
        width: 30px;
        transform-origin: 100% 50%;
        border-radius: 100px 0 0 100px;
    }
    
    .aw-captcha .aw-captcha-success::after {
        top: 0;
        left: 30px;
        width: 60px;
        transform-origin: 0 50%;
        border-radius: 0 100px 100px 0;
        animation: rotate-circle 4.25s ease-in;
    }

    .aw-captcha .aw-captcha-success .icon-line {
        height: 5px;
        background-color: #4CAF50;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }
    
    .aw-captcha .aw-captcha-success .icon-line.line-tip {
        top: 46px;
        left: 14px;
        width: 25px;
        transform: rotate(45deg);
        animation: icon-line-tip 0.75s;
    }

    .aw-captcha .aw-captcha-success .icon-line.line-long {
        top: 38px;
        right: 8px;
        width: 47px;
        transform: rotate(-45deg);
        animation: icon-line-long 0.75s;
    }

    .aw-captcha .aw-captcha-success .icon-circle {
        top: -4px;
        left: -4px;
        z-index: 10;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        position: absolute;
        box-sizing: content-box;
        border: 4px solid rgba(76, 175, 80, 0.5);
    }

    .aw-captcha .aw-captcha-success .icon-fix {
        top: 8px;
        width: 5px;
        left: 26px;
        z-index: 1;
        height: 85px;
        position: absolute;
        transform: rotate(-45deg);
        background-color: #FFFFFF;
    }

    @media screen and (max-width: 450px) {
        .aw-captcha {
            width: calc(100% - 30px);
            left: 0;
            margin: 0 15px;
        }
    }

    @keyframes rotate-circle {
        0% {
            transform: rotate(-45deg);
        }
        5% {
            transform: rotate(-45deg);
        }
        12% {
            transform: rotate(-405deg);
        }
        100% {
            transform: rotate(-405deg);
        }
    }

    @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        54% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }
        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }
        100% {
            width: 25px;
            left: 14px;
            top: 45px;
        }
    }

    @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        84% {
            width: 55px;
            right: 0px;
            top: 35px;
        }
        100% {
            width: 47px;
            right: 8px;
            top: 38px;
        }
    }

</style>
