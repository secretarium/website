<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">

	<title>Secretarium - Entrusted with secrets</title>
	<link rel="icon" type="image/png" href="images/secretarium_128x128.png">
	
	<link rel="stylesheet" href="styles/bootstrap-4.3.1.min.css" />
	<link rel="stylesheet" href="styles/fontawesome-5.7.2.all.min.css" />
	<link rel="stylesheet" href="styles/fullpage-3.0.4.min.css" />
	<link rel="stylesheet" href="styles/secretarium-0.0.0.css" />

	<script src="scripts/jquery-3.3.1.min.js"></script>
	<script src="scripts/popper-1.14.7.min.js"></script>
	<script src="scripts/bootstrap-4.3.1.min.js"></script>
	<script src="scripts/vue-2.6.8.min.js"></script>
	<script src="scripts/vue-router-3.0.2.min.js"></script>
	<script src="scripts/scrolloverflow-3.0.4.min.js"></script>
	<script src="scripts/fullpage-3.0.4.min.js"></script>
</head>
<body>
	<style>
		.section {
		  text-align:center;
		  font-size: 3em;
		}
		.content{
		  margin:50px
		}
	</style>
	<div id="fullpage">
		<div class="section fp-auto-height-responsive">One</div>
		<div class="section fp-auto-height-responsive">Two</div>
		<div class="section fp-auto-height-responsive">
			Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />
			Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />
			Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />
		</div>
		<div class="section fp-auto-height">
		  <div class="content">Footer</div>
	  </div>
	</div>
	<script>
		new fullpage('#fullpage', {
			scrollOverflow: true,
			licenseKey: '98AFD12E-428246E1-B7908FFE-69A37211',
			anchors: ['page1', 'page2', 'page3', 'page4'],
			sectionsColor: ['yellow', 'orange', '#C0C0C0', '#ADD8E6']
		});
	</script>
</body>
</html>
