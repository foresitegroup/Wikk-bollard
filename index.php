<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width">
    
    <title>Wikk Bollard Builder<?php if (isset($PageTitle)) echo " | " . $PageTitle; ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    
    <meta name="description" content="">
    <meta name="keywords" content="">
    
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:600|Poppins:700,800" rel="stylesheet">
    <link rel="stylesheet" href="inc/main.css?<?php echo filemtime('inc/main.css'); ?>">
    
    <script type="text/javascript" src="inc/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#step1').addClass('active');
        $('#left-step H2').html('Step <span></span>:');
        $('#left-step H2 SPAN').text('1');
        $('#left-step H1').text($('.active').data('steptitle'));
        $('[id^=summary-]').not('.nodash').text('-');
        $('#bar1').addClass('active');
        $('#send').hide();
        
        var shape = '';

        $('#round, #square').click(function() {
          shape = $(this).attr('id');

          $('#'+shape).siblings().removeClass('selected');
          $(this).addClass('selected');

          $('.dimensions, .finishes, .mounting, .top').each(function() { $(this).removeClass('on'); });
          $('#'+shape+'-dimensions, #'+shape+'-finishes, #'+shape+'-mounting, #'+shape+'-top').addClass('on');

          $('input:radio').prop('checked', false);
          $('input[name='+shape+'-finish-color]').val('');
          $('.swatch').css('background-color', 'transparent');

          $('[id^='+shape+'-mounting-]').removeClass('selected');

          $('[id^='+shape+'-top-]').removeClass('selected');

          $('[id^=summary-]').text('');
          $('[id^=summary-]').not('.nodash').text('-');

          $('#next, #bar LI').removeClass('link-on');
        });
        
        // STEP 1
        $('input:radio[name$=-diameter]').change(function(){
          $('#summary-diameter').text($('input:radio[name="'+shape+'-diameter"]:checked').val());
          if ($('input:radio[name$=-diameter]').is(':checked') && $('input:radio[name$=-length]').is(':checked')) $('#next, #bar1, #bar2').addClass('link-on');
        });

        $('input:radio[name$=-length]').change(function(){
          $('#summary-length').text(', '+$('input:radio[name="'+shape+'-length"]:checked').val());
          if ($('input:radio[name$=-diameter]').is(':checked') && $('input:radio[name$=-length]').is(':checked')) $('#next, #bar1, #bar2').addClass('link-on');
        });

        // STEP 2
        $('.finishes > input:radio').change(function(){
          $(this).siblings().prop('checked', false);

          if ($('input:radio[name$=-finish-painted]').is(':checked')) {
            $('#'+shape+'-finishes A').addClass('link-on');
            if (!$('input[name='+shape+'-finish-color]').val()) $('#next, #bar3').removeClass('link-on');
          } else {
            $('.finishes A').removeClass('link-on');
            $('input[name='+shape+'-finish-color]').val('');
            $('.swatch').css('background-color', 'transparent');
            $('#summary-color').text('');
            $('#next, #bar3').addClass('link-on');
          }

          $('#summary-finish').text($(this).val());
        });

        // COLOR PICKER
        $("body").on( "click", ".featherlight .picker", function() {
          $('input[name='+shape+'-finish-color]').val('RAL ' + $(this).data('code'));
          $('#'+shape+'-finish-color-swatch').css('background-color', '#'+$(this).data('hex'));
          $('#next, #bar3').addClass('link-on');
          $('#summary-color').text('; RAL ' + $(this).data('code'));
        });

        // STEP 3
        $('[id^=round-mounting-], [id^=square-mounting-]').click(function() {
          $('[id^='+shape+'-mounting-]').removeClass('selected');
          $(this).addClass('selected');
          $('#summary-mounting').text($(this).data('mounting'));
          $('#next, #bar4').addClass('link-on');
        });

        // STEP 4
        $('[id^=round-top-], [id^=square-top-]').click(function() {
          $('[id^='+shape+'-top-]').removeClass('selected');
          $(this).addClass('selected');

          if ($(this).prop('id') == shape+'-top-angled-front' || $(this).prop('id') == shape+'-top-angled-back') {
            $('#'+shape+'-angle').addClass('on');
            $('#summary-angle').text('; '+$('#'+shape+'-angle SELECT').val()+' Degrees');
          } else {
            $('#'+shape+'-angle').removeClass('on');
            $('#'+shape+'-angle SELECT').val('45');
            $('#summary-angle').text('');
          }

          $('#summary-top').text($(this).data('top'));
          $('#next, #bar5').addClass('link-on');
        });

        $('SELECT').on('change', function() {
          $('#summary-angle').text('; '+$(this).val()+' Degrees');
        });

        // NAVIGATION
        $("#prev, #next, #bar LI").click(function(e) {
          e.preventDefault();

          if ($(this).prop('id') == "prev") { var activestep = $('.active').data('num') - 1; }
          else if ($(this).prop('id') == "next") { var activestep = $('.active').data('num') + 1; }
          else { var activestep = $(this).data('num'); }

          $('#step'+$('.active').data('num')).removeClass('active');
          $('#step'+activestep).addClass('active');

          $('#left-step H2').html('Step <span></span>:');
          $('#left-step H2 SPAN').text($('.active').data('num'));
          $('#left-step H1').text($('.active').data('steptitle'));

          $('#bar LI').removeClass('active');
          $('#bar'+$('.active').data('num')).addClass('active');
          
          if ($('input:radio[name$=-diameter]').is(':checked') && $('input:radio[name$=-length]').is(':checked')) $('#next').addClass('link-on');

          if (
            $('#'+shape+'-finishes > input:radio').is(':checked') ||
            $('#'+shape+'-mounting > DIV').hasClass('selected') ||
            $('#'+shape+'-top > DIV').hasClass('selected')
          ) {
            $('#next').addClass('link-on');
          } else {
            $('#next').removeClass('link-on');
          }

          if ($('.active').data('num') > 1) {
            $('#prev').addClass('link-on');
          } else {
            $('#prev').removeClass('link-on');
          }

          if ($('.active').data('num') == 2) {
            $('#'+shape+'-finishes').addClass('on');

            if ($('input:radio[name$=-finish-painted]').is(':checked')) {
              $('.finishes A').addClass('link-on');
            } else {
              $('.finishes A').removeClass('link-on');
            }
          }

          if ($('.active').data('num') == 3) $('#'+shape+'-mounting').addClass('on');

          if ($('.active').data('num') == 4) $('#'+shape+'-top').addClass('on');

          if ($('.active').data('num') == 5) {
            $('#left-step H2').text('Final Step:');
            $('#review').html($('#summary').html());
            $('#send').show();
            $('#next, #left-summary, #bar').hide();
            $('BODY').addClass('bb-background');
          } else {
            $('#send').hide();
            $('#next, #left-summary, #bar').show();
            $('BODY').removeClass('bb-background');
          }
        });

        // SEND
        $("#send").click(function(e) {
          e.preventDefault();

          $.ajax({
            url: 'bollard-builder-send.php',
            type: 'POST',
            data: { 'message': $('#review').html() },
            success: function(data) {
              $('#review-feedback').html('Thank you for building your bollard.');
              $('.step').find('.selected').removeClass('selected');
              $('input:radio').prop('checked', false);
              $('.nav').find('.link-on').removeClass('link-on');
              $("#send").css('pointer-events', 'none');
            }
          });
        });
      });
    </script>
  </head>
  <body>

    <header>
      <div class="site-width">
        <div id="logo"><a href="."><img src="images/logo.png" alt=""></a></div>

        <div id="page-title">Bollard Builder</div>
      </div>
    </header>

    <div class="site-width bb-content">
      <div id="bb-left">
        <div id="left-step">
          <div class="text">
            <h2></h2>
            <h1></h1>
          </div>
        </div>

        <div id="left-summary">
          <h2>My Bollard</h2>

          <div id="summary">
            <strong>Shape + Dimensions</strong><br>
            <span id="summary-diameter"></span><span id="summary-length" class="nodash"></span><br>
            <br>
            
            <strong>Finish</strong><br>
            <span id="summary-finish"></span><span id="summary-color" class="nodash"></span><br>
            <br>
            
            <strong>Mounting</strong><br>
            <span id="summary-mounting"></span><br>
            <br>
            
            <strong>Bollard Top</strong><br>
            <span id="summary-top"></span><span id="summary-angle" class="nodash"></span>
          </div>
        </div>
      </div>

      <div id="bb-right">
        <div id="step1" class="step" data-num="1" data-steptitle="Shape &amp; Dimensions">
          Select the bollard shape, sizes, and length.<br>
          <br>

          <h3>Shape</h3>
          <div id="shapes">
            <div id="round" data-shape="Round">
              <img src="images/bollard-round.png" alt="">
              Round
            </div>

            <div id="square" data-shape="Square">
              <img src="images/bollard-square.png" alt="">
              Square
            </div>
          </div>

          <div id="round-dimensions" class="dimensions">
            <h3>Diameter</h3>
            <input type="radio" name="round-diameter" value="4&quot; D Round" id="rrd4" class="fot">
            <label for="rrd4">4"</label>
            <input type="radio" name="round-diameter" value="6&quot; D Round" id="rrd6">
            <label for="rrd6">6"</label>
            <input type="radio" name="round-diameter" value="8&quot; D Round" id="rrd8">
            <label for="rrd8">8"</label>

            <br><br>
            
            <h3>Length</h3>
            <input type="radio" name="round-length" value="42&quot; H Standard" id="rrl42" class="fot">
            <label for="rrl42">42" Standard</label>
            <input type="radio" name="round-length" value="52&quot; H In Ground" id="rrl52">
            <label for="rrl52">52" In Ground</label>
          </div>

          <div id="square-dimensions" class="dimensions">
            <h3>Diameter</h3>
            <input type="radio" name="square-diameter" value="4&quot; D Square" id="rsd4" class="fot">
            <label for="rsd4">4"</label>
            <input type="radio" name="square-diameter" value="6&quot; D Square" id="rsd6">
            <label for="rsd6">6"</label>
            <input type="radio" name="square-diameter" value="8&quot; D Square" id="rsd8">
            <label for="rsd8">8"</label>

            <br><br>

            <h3>Length</h3>
            <input type="radio" name="square-length" value="42&quot; H Standard" id="rsl42" class="fot">
            <label for="rsl42">42" Standard</label>
            <input type="radio" name="square-length" value="52&quot; H In Ground" id="rsl52">
            <label for="rsl52">52" In Ground</label>
          </div>
        </div> <!-- /#step1 -->

        <div id="step2" class="step" data-num="2" data-steptitle="Finishes">
          Any aluminum bollard needing welding i.e. angle tops, welded flat bottoms, or welded inserts will need to be powder coated or painted.<br>
          <br>

          <h3>Finishes</h3>
          
          <div id="round-finishes" class="finishes">
            <h4>Stainless Steel</h4>
            <input type="radio" name="round-finish-ss" value="Stainless Steel Satin Polished" id="rrfs-sp" class="fot">
            <label for="rrfs-sp">Satin Polished</label>
            <input type="radio" name="round-finish-ss" value="Stainless Steel Mirror Polished" id="rrfs-mp">
            <label for="rrfs-mp">Mirror Polished</label>

            <br><br>

            <h4>Anodized Aluminum</h4>
            <input type="radio" name="round-finish-aa" value="Anodized Aluminum Clear" id="rrfa-cl" class="fot">
            <label for="rrfa-cl">Clear</label>
            <input type="radio" name="round-finish-aa" value="Anodized Aluminum Black" id="rrfa-bl">
            <label for="rrfa-bl">Black</label>
            <input type="radio" name="round-finish-aa" value="Anodized Aluminum Bronze" id="rrfa-br">
            <label for="rrfa-br">Bronze</label>

            <br><br>

            <h4>Painted</h4>
            <input type="radio" name="round-finish-painted" value="Powder Coated" id="rrfp-pc" class="fot">
            <label for="rrfp-pc">Powder Coated Color</label>
            <input type="radio" name="round-finish-painted" value="Painted" id="rrfp-p">
            <label for="rrfp-p">Painted Color</label><br>

            <span class="finish-color-label">Color Code:</span>
            <input type="text" name="round-finish-color" class="finish-color">
            <span id="round-finish-color-swatch" class="swatch"></span><br>
            <a href="#" data-featherlight="color-picker.php" data-featherlight-close-on-click="anywhere">Find A Color</a>
          </div>

          <div id="square-finishes" class="finishes">
            Stainless Steel<br>
            <input type="radio" name="square-finish-ss" value="Stainless Steel Satin Polished" id="rsfs-sp" class="fot">
            <label for="rsfs-sp">Satin Polished</label>
            <input type="radio" name="square-finish-ss" value="Stainless Steel Mirror Polished" id="rsfs-mp">
            <label for="rsfs-mp">Mirror Polished</label>

            <br><br>

            Anodized Aluminum<br>
            <input type="radio" name="square-finish-aa" value="Anodized Aluminum Clear" id="rsfa-cl" class="fot">
            <label for="rsfa-cl">Clear</label>
            <input type="radio" name="square-finish-aa" value="Anodized Aluminum Black" id="rsfa-bl">
            <label for="rsfa-bl">Black</label>
            <input type="radio" name="square-finish-aa" value="Anodized Aluminum Bronze" id="rsfa-br">
            <label for="rsfa-br">Bronze</label>

            <br><br>

            Painted<br>
            <input type="radio" name="square-finish-painted" value="Powder Coated" id="rsfp-pc" class="fot">
            <label for="rsfp-pc">Powder Coated Color</label>
            <input type="radio" name="square-finish-painted" value="Painted" id="rsfp-p">
            <label for="rsfp-p">Painted Color</label><br>

            <span class="finish-color-label">Color Code:</span>
            <input type="text" name="square-finish-color" class="finish-color">
            <span id="square-finish-color-swatch" class="swatch"></span><br>
            <a href="#" data-featherlight="color-picker.php" data-featherlight-close-on-click="anywhere">Find A Color</a>
          </div>
        </div> <!-- /#step2 -->

        <div id="step3" class="step" data-num="3" data-steptitle="Mounting">
          Before you make this mounting type selection, determine the mounting prep location for the device(s) to be used.<br>
          <br>

          <h3>Mounting Bases</h3>
          <div id="mounting">
            <div id="round-mounting" class="mounting">
              <div id="round-mounting-surface-base" data-mounting="Round Surface Base">
                <img src="images/bollard-round-surface-base.png" alt="">
                Round Surface Base
              </div>

              <div id="round-mounting-welded-base" data-mounting="Round Welded Base">
                <img src="images/bollard-round-welded-base.png" alt="">
                Round Welded Base
              </div>
            </div> <!-- /#round-mounting -->

            <div id="square-mounting" class="mounting">
              <div id="square-mounting-surface-base" data-mounting="Square Surface Base">
                <img src="images/bollard-square-surface-base.png" alt="">
                Square Surface Base
              </div>

              <div id="square-mounting-welded-base" data-mounting="Square Welded Base">
                <img src="images/bollard-square-welded-base.png" alt="">
                Square Welded Base
              </div>
            </div> <!-- /#square-mounting -->
          </div> <!-- /#mounting -->
        </div> <!-- /#step3 -->

        <div id="step4" class="step" data-num="4" data-steptitle="Bollard Top Options">
          Select from a variety of top options for your bollard.<br>
          <br>

          <h3>Top Options</h3>
          <div id="top">
            <div id="round-top" class="top">
              <div id="round-top-removable" data-top="Round Removable Black ABS&quot; 6 Only">
                <img src="images/bollard-round-top-removable.png" alt="">
                Round Removable Black ABS 6" Only
              </div>

              <div id="round-top-flat-welded" data-top="Round Flat Welded">
                <img src="images/bollard-round-top-flat-welded.png" alt="">
                Round Flat Welded
              </div>

              <div id="round-top-angled-front" data-top="Round Angled Front">
                <img src="images/bollard-round-top-angled.png" alt="">
                Round Angled Front
              </div>

              <div id="round-top-angled-back" data-top="Round Angled Back">
                <img src="images/bollard-round-top-angled.png" alt="">
                Round Angled Back
              </div>

              <div id="round-top-dome" data-top="Round Dome">
                <img src="images/bollard-round-top-dome.png" alt="">
                Round Dome
              </div>
            </div> <!-- /#round-top -->

            <div id="round-angle" class="angle">
              <h4>Customize Angle</h4>
              Specify the degree of the angle. 45 degrees is standard, but other angles are available in 5 degree increments (i.e. 30, 25, 40, etc).<br>

              <span class="angle-label">Angle:</span>
              <select name="round-angle">
                <?php
                for ($i=5; $i <=85; $i+=5) {
                  echo '<option value="'.$i.'"';
                  if ($i == 45) echo " selected";
                  echo ">".$i."</option>\n";
                }
                ?>
              </select>
            </div> <!-- /#round-angle -->

            <div id="square-top" class="top">
              <div id="square-top-removable" data-top="Square Removable Black ABS 4&quot; &amp; 6&quot; Only">
                <img src="images/bollard-square-top-removable.png" alt="">
                Square Removable Black ABS 4" &amp; 6" Only
              </div>

              <div id="square-top-flat-welded" data-top="Square Flat Welded">
                <img src="images/bollard-square-top-flat-welded.png" alt="">
                Square Flat Welded
              </div>

              <div id="square-top-angled-front" data-top="Square Angled Front">
                <img src="images/bollard-square-top-angled-front.png" alt="">
                Square Angled Front
              </div>

              <div id="square-top-angled-back" data-top="Square Angled Back">
                <img src="images/bollard-square-top-angled-back.png" alt="">
                Square Angled Back
              </div>
            </div> <!-- /#square-top -->

            <div id="square-angle" class="angle">
              <h4>Customize Angle</h4>
              Specify the degree of the angle. 45 degrees is standard, but other angles are available in 5 degree increments (i.e. 30, 25, 40, etc).<br>

              <span class="angle-label">Angle:</span>
              <select name="square-angle">
                <?php
                for ($i=5; $i <=85; $i+=5) {
                  echo '<option value="'.$i.'"';
                  if ($i == 45) echo " selected";
                  echo ">".$i."</option>\n";
                }
                ?>
              </select>
            </div> <!-- /#square-angle -->
          </div> <!-- /#top -->
        </div> <!-- /#step4 -->

        <div id="step5" class="step" data-num="5"  data-steptitle="Review Your Order">
          Please review your order below for accuracy.<br>
          <br>
          <br>

          <h2>My Bollard</h2>
          <div id="review"></div>
          <div id="review-feedback"></div>
        </div> <!-- /#step5 -->
      </div> <!-- /#bb-right -->
    </div> <!-- /.bb-content -->

    <div class="site-width nav">
      <a href="#" id="prev"><span><i class="fas fa-caret-left"></i></span> PREV STEP</a>

      <div id="bar">
        <ul>
          <li id="bar1" data-num="1">Shape + Dimensions</li>
          <li id="bar2" data-num="2">Finishes</li>
          <li id="bar3" data-num="3">Mounting</li>
          <li id="bar4" data-num="4">Top Options</li>
          <li id="bar5" data-num="5">Review Order</li>
        </ul>
      </div>

      <a href="#" id="next">NEXT STEP <span><i class="fas fa-caret-right"></i></span></a>
      <a href="#" id="send">SUBMIT ORDER <span><i class="fas fa-caret-right"></i></span></a>
    </div>
    
    <link rel="stylesheet" href="inc/featherlight.css?<?php echo filemtime("inc/featherlight.css"); ?>">
    <script src="inc/featherlight.min.js"></script>

  </body>
</html>