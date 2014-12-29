	<?php

		$client_name = 'Upstatement';


		/*	This array fills in the tabs and includes the files for each section of the style guide
			Delete a section if you don't need it */
		
		$style_tabs = array();
		$style_tabs[] = array('title' => 'Type', 'file' => '_includes/_style/type.php');
		$style_tabs[] = array('title' => 'Objects', 'file' => '_includes/_style/objects.php');
		$style_tabs[] = array('title' => 'Forms', 'file' => '_includes/_style/forms.php');
 		$style_tabs[] = array('title' => 'Tables', 'file' => '_includes/_style/tables.php');
/* 		$style_tabs[] = array('title' => 'Layout', 'file' => '_includes/_style/layout.php'); */
/* 		$style_tabs[] = array('title' => 'Color', 'file' => '_includes/_style/color.php'); */
	
	?>

<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6 ie oldie" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7 ie oldie" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8 ie oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?php echo $client_name ?> Pattern Library</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">

  <link rel="stylesheet" href="_css/screen.css?v=2">
  <link rel="stylesheet" href="_css/style-guide.css" media="screen"/>  
  <script src="_js/libs/modernizr-2.0.6.min.js"></script>

	<?php 
               $code_snip =
               '<dt>Cope Snip</dt>
               <dd class="code-snip"><pre class="code prettyprint linenums"><code class="php boc-html-script"></code></pre></dd>';
       ?>

</head>

<body onload="prettyPrint()">

	<nav id="style-hdr">
		<div class="style-hdr-group">		
			<h1 class="style-page-h">Pattern <span class="thin">Library</thin></h1>
			<img class="style-logo" src="_img/upstatement-logo.png" alt="Upstatement" />
			<ul class="style-tabs clearfix">
			<?php
				/* Create a tab for each section of the style guide */
				foreach($style_tabs as $tab){
					echo '<li class="style-tab"><a href="#'.strtolower($tab['title']).'">'.$tab['title'].'</a></li>';
				}
			?>
			</ul>
		</div> <!-- style-hdr-group -->
	</nav> <!--style-hdr -->

	<div class="style-body style-panes">
		
		<?php
			/* Create a tab pane for each section of the style guide */
			foreach($style_tabs as $tab){
			include $tab['file'];
			}
		?>

	</div> <!-- panes -->


  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
  <script>window.jQuery || document.write('<script src="_js/libs/jquery-1.6.2.min.js"><\/script>')</script>

  <script src="_js/libs/jquery.tools.min.js"></script>  

  <!-- Modals: Do you enjoy modals? Yes you do -->
  <script type="text/javascript" src="_js/libs/jquery.simplemodal.1.4.2.min.js"></script>

  <!-- scripts concatenated and minified via ant build script-->
  <script src="_js/plugins.js"></script>
  <script type="text/javascript" src="/_js/libs/prettify.js"></script>
  <script type="text/javascript" language="javascript" src="_js/mylibs/style-guide.js"></script>
  <script src="_js/script.js"></script>
  <script src="_js/mylibs/up-base.js"></script>
  <!-- end scripts-->


  <!-- PNG fix for IE6 -->
  <!--[if lt IE 7 ]>
    <script src="_js/libs/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix("img, .png_bg");</script>
  <![endif]-->


  <!-- change the UA-XXXXX-X to be your site's ID -->
  <script>
    var _gaq=[["_setAccount","UA-XXXXX-X"],["_trackPageview"]]; // Change UA-XXXXX-X to be your site's ID 
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
    g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
    s.parentNode.insertBefore(g,s)}(document,"script"));
  </script>


  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->

</body>
</html>