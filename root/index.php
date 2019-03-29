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
	<script src="scripts/fullpage.vue-0.1.2.min.js"></script>
</head>
<body>
	<div id="app" @dragover.prevent @drop.prevent v-cloak>
	<header></header>
	<content>
		<transition name="page" mode="out-in">
			<router-view></router-view>
		</transition>
	</content>
	<footer></footer>
	</div>
	
	<script type="text/x-template" id="sec-home">
		<div>
			<img src="images/secretarium_title.png" style="display: none" id="sec_title" />
			<img src="images/secretarium_logo_grey.png" style="display: none" id="sec_logo_grey" />
			<full-page ref="fullpage" :options="options" id="fullpage">
				<div class="section">
					<div id="welcome" style="width: 100%; height: 100%; position: relative; overflow: hidden;"></div>
				</div>
				<div class="section fp-auto-height-responsive">					
					<div style="margin: 20%; font-size: 3em">Two</div>
				</div>
				<div class="section fp-auto-height-responsive">					
					<div style="margin: 20%; font-size: 3em">	
						Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />
						Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />
						Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />Three <br />
					</div>
				</div>
				<div class="section fp-auto-height">
					<div style="margin: 20%; font-size: 3em">Footer</div>
				</div>
			</full-page>
		</div>
	</script>
	
	<script>
		const Home = Vue.component('sec-home', {
			template: '#sec-home',
			data: function() {
				return {
					options: {
						scrollOverflow: true,
						licenseKey: '98AFD12E-428246E1-B7908FFE-69A37211',
						sectionsColor: ['#ff', 'orange', '#C0C0C0', '#ADD8E6']
					}
				}
			},
			mounted() {
				setTimeout(() => { this.drawLogo(); }, 100);
			},
			methods: {
				drawLogo() {
					let t = $("#sec_title"), tnw = t[0].naturalWidth, tnh = t[0].naturalHeight,
						l = $("#sec_logo_grey"), lnw = l[0].naturalWidth, lnh = l[0].naturalHeight,
						w = $("#welcome"), ww = w.parent().width(), wh = w.parent().height(),
						th = 0.08 * wh, r = th / tnh, tw = tnw * r, z = 1, y = wh * 0.46, x = (ww - tw) / 2,
						rw = 683 / 1665, rh = 680 / 1231, p = [],
						add = (nx, ny, a) => {
							if(nx + r * lnw > 0 && ny + r * lnh > 0 && ny < wh && !p.find(pe => { return Math.abs(pe[0] - nx) < 1 && Math.abs(pe[1] - ny) < 1; })) {
								if(a) l.clone().css({ position: "absolute", left: nx, top: ny, height: "8%", "z-index": z }).appendTo(w).show();
								p.push([nx, ny]);
								setTimeout(() => {
									if (Math.random() > .5) add(nx + r * rw * lnw, ny + r * rh * lnh * 2, 1);
									if (Math.random() > .3) add(nx - r * rw * lnw, ny - r * rh * lnh * 2, 1);
									add(nx - r * rw * lnw * 2, ny - r * rh * lnh, 1);
									add(nx - r * rw * lnw, ny + r * rh * lnh, 1);
								}, 40 + Math.random() * 80);
							}
						};
					t.clone().css({ position: "absolute", left: x, top: y, height: "8%", "z-index": z++ }).appendTo(w).show();
					setTimeout(() => { add(x, y) }, 200);
				},
			}
		});		
		const store = {};
		const router = new VueRouter({
			mode: 'history',
			routes: [
				{ path: '/', component: Home }
			]
		});
		const app = new Vue({
			router,
			data: function () {
				return {
					store: store
				}
			},
		}).$mount('#app');
	</script>
</body>
</html>
