<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Signature Pad demo</title>
  <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <link rel="stylesheet" href="{{ URL::to('/') }}/css/signature-pad.css">

  <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="css/ie9.css">
  <![endif]-->

  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-39365077-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>

</head>


<body onselectstart="return false">
  <div id="signature-pad" class="signature-pad">
    <div class="signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="signature-pad--footer">
      <div class="description">Sign above</div>

      <div class="signature-pad--actions">
        <div>
          <button type="button" class="button clear" data-action="clear">Clear</button>
          <button type="button" class="button" data-action="change-color">Change color</button>
          <button type="button" class="button" data-action="undo">Undo</button>
          <button><a href="{{url()->previous()}}">Back</a></button>

        </div>
        <div>
          <button type="button" class="button save" data-action="save-png">Save as PNG</button>
          <button type="button" class="button save" data-action="save-jpg">Save as JPG</button>
          <button type="button" class="button save" data-action="save-svg">Save as SVG</button>
        </div>
      </div>
    </div>
  </div>
  <div style="display: none;">
    <form action="{{ URL::to('/') }}/saveSignature" id="saveSign" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
      <input type="text" id="sign" name="sign">
    </form>
  </div>
  <script src="{{ URL::to('/')}}/js/app.js"></script>
  <script src="{{ URL::to('/') }}/js/signature_pad.umd.js"></script>
  <script src="{{ URL::to('/') }}/js/app2.js"></script>
</body>
</html>