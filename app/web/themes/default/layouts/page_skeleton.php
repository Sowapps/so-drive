<?php
/**
 * @var HtmlRendering $rendering
 * @var HttpController $controller
 * @var HttpRequest $request
 * @var HttpRoute $route
 *
 * @var string $CONTROLLER_OUTPUT
 * @var string $content
 */

use Orpheus\InputController\HttpController\HttpController;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpRoute;
use Orpheus\Rendering\HtmlRendering;

global $APP_LANG;

$libExtension = DEV_VERSION ? '' : '.min';

?>
<!DOCTYPE html>
<html lang="<?php echo $APP_LANG; ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo !empty($PageTitle) ? $PageTitle : t('app_name'); ?></title>
	<meta name="Description" content=""/>
	<meta name="Author" content="<?php echo AUTHORNAME; ?>"/>
	<meta name="application-name" content="<?php _t('app_name'); ?>"/>
	<meta name="msapplication-starturl" content="<?php echo WEB_ROOT; ?>"/>
	<meta name="Keywords" content="projet"/>
	<meta name="Robots" content="Index, Follow"/>
	<meta name="revisit-after" content="16 days"/>
	<?php
	foreach( $rendering->listMetaProperties() as $property => $content ) {
		echo '
	<meta property="' . $property . '" content="' . $content . '"/>';
	}
	?>
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="screen"/>
	<?php
	foreach( $rendering->listCssUrls(HtmlRendering::LINK_TYPE_PLUGIN) as $url ) {
		echo '
	<link rel="stylesheet" href="' . $url . '" type="text/css" media="screen" />';
	}
	?>
	
	<link rel="stylesheet" href="<?php echo STATIC_ASSETS_URL; ?>/style/base.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo $rendering->getCssUrl(); ?>style.css" type="text/css" media="screen"/>
	<?php
	foreach( $rendering->listCssUrls() as $url ) {
		echo '
	<link rel="stylesheet" type="text/css" href="' . $url . '" media="screen" />';
	}
	?>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
	<div class="container">
		<a class="navbar-brand" href="<?php echo WEB_ROOT; ?>">
			<?php echo t('app_name'); ?>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#MenuTop" aria-controls="MenuTop" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="MenuTop">
			<?php
//			$rendering->showMenu('topmenu');
			if( !empty($TOPBAR_CONTENTS) ) {
				echo $TOPBAR_CONTENTS;
			}
			?>
		
		</div>
	</div>
</nav>

<div class="container mt-3 my-md-4">
	<?php
	echo $content;
	// If report was not be reported
	$this->display('reports-bootstrap5');
	?>
</div>

<!-- JS libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
<?php
foreach( $this->listJSURLs(HtmlRendering::LINK_TYPE_PLUGIN) as $url ) {
	echo '
	<script type="text/javascript" src="' . $url . '"></script>';
}
?>

<!-- Our JS scripts -->
<script src="<?php echo JS_URL; ?>orpheus.js"></script>
<script src="<?php echo JS_URL; ?>orpheus-confirmdialog.js"></script>

<?php
foreach( $rendering->listJsUrls() as $url ) {
	echo '
	<script type="text/javascript" src="' . $url . '"></script>';
}
?>

</
>
</html>
