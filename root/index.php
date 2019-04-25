<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Secretarium is a distributed confidential computing platform guaranteeing privacy by default and by design">

	<title>Secretarium - Entrusted with secrets</title>
	<link rel="icon" type="image/png" href="images/secretarium_128x128.png">

	<link rel="stylesheet" href="styles/reset-2.0.0.css" />
	<link rel="stylesheet" href="styles/bootstrap-4.3.1.min.css" />
	<link rel="stylesheet" href="styles/fontawesome-5.7.2.all.min.css" />
	<link rel="stylesheet" href="styles/fullpage-3.0.4.min.css" />
	<link rel="stylesheet" href="styles/secretarium-0.0.0.css" />


	<script src="scripts/jquery-3.3.1.min.js"></script>
	<script src="scripts/popper-1.14.7.min.js"></script>
	<script src="scripts/bootstrap-4.3.1.min.js"></script>
	<script src="scripts/vue-2.6.8.min.js"></script>
	<script src="scripts/vue-router-3.0.2.min.js"></script>
	<script src="scripts/scrolloverflow-0.1.2.min.js"></script>
	<script src="scripts/fullpage.vue-0.1.2.min.js"></script>
</head>

<body>
	<img src="images/secretarium_title.png" id="stage-fulllogo" />
	<img src="images/secretarium_logo_grey.png" id="stage-logo" />
	<div id="app" @dragover.prevent @drop.prevent v-cloak>
		<header>
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-10 header-nav">
						<ul id="menu" class="on-left">
							<li data-menuanchor="home" class="logo">
								<a href="#home"></a>
							</li>
							<li data-menuanchor="what-it-is">
								<a href="#what-it-is">What</a>
							</li>
							<li data-menuanchor="why">
								<a href="#why">Why</a>
							</li>
							<li data-menuanchor="whom-for">
								<a href="#whom-for">Whom for</a>
							</li>
							<li data-menuanchor="technology">
								<a href="#technology">Technology</a>
							</li>
							<li data-menuanchor="team">
								<a href="#team">About us</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<div id="submenu">
			<div class="container">
				<div class="row" id="sub-what">
					<ul id="menu" class="on-left">
						<li data-menuanchor="what-it-is">
							<a href="#what-it-is">What is Secretarium ?</a>
						</li>
						<li data-menuanchor="what-it-does">
							<a href="#what-it-does">What does it provide ?</a>
						</li>
						<li data-menuanchor="scaling">
							<a href="#scaling">How does it scale ?</a>
						</li>
					</ul>
				</div>
				<div class="row" id="sub-how">
					<ul id="menu" class="on-left">
						<li data-menuanchor="technology">
							<a href="#technology">How does it work ?</a>
						</li>
						<li data-menuanchor="secret-processing">
							<a href="#secret-processing">Secret processing</a>
						</li>
						<li data-menuanchor="secret-mixing">
							<a href="#secret-mixing">Secret mixing</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<content>
			<transition name="page" mode="out-in">
				<router-view></router-view>
			</transition>
		</content>
	</div>

	<script type="text/x-template" id="sec-home">
		<div>
			<div id="stage"></div>
			<full-page ref="fullpage" :options="options" id="fullpage">
				<div class="section" data-anchor="home">
				</div>
				<div class="section" data-anchor="what-it-is">
					<section>
						<div class="container">
							<h2>What is Secretarium ?</h2>
							<h3>Secretarium is an integrity and confidentiality crypto-platform</h3>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-6 col-md-push-6 col-left">
										<div class="sec-img fencedNetwork"></div>
										<h4>Distributed Confidential computing platform</h4>
										<br />
										<p>
											Relying on trusted execution environments and powered by secure multi-party
											computing. Secretarium has been designed to
											run applications on encrypted data in a trustable, distributed, scalable and
											efficient way, with no possible data
											leakage or manipulation, and no single point of failure.
										</p>
									</div>
									<div class="col-md-6 col-md-pull-6 col-right">
										<div class="sec-img dcApp"></div>
										<h4>Hosting Distributed Confidential Applications </h4>
										<br />
										<p>
											Distributed Confidential Applications (DCApps) are smart contracts systems with
											cryptographic proof of integrity.
											End-users can grant access to subsets of their private data to DCApps; and
											DCApps can request access to user's data for
											pre-defined specific processing.
										</p>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="what-it-does">
					<section>
						<div class="container">
							<h2>What does Secretarium provide ?</h2>
							<h3>Total confidentiality: nobody sees the data, including Secretarium</h3>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-6 col-md-push-6 col-left">
										<div class="sec-img shield"></div>
										<h4>Protects intellectual property and commercial rights</h4>
										<br />
										<p>
											Secretarium guarantees privacy by default and by design: users always keep
											control of their data. Secretarium uses
											end-to-end encryption: data uploaded to Secretarium remains the property of its
											originators and no one can access it in
											clear text. DCApps intellectual property remains the property of the DCApp
											writer.
										</p>
									</div>
									<div class="col-md-6 col-md-pull-6 col-right">
										<div class="sec-img puzzle"></div>
										<h4>Offers easy integration to your processes</h4>
										<br />
										<p>
											Every Secretarium node is coupled with a web server. Secretarium connection
											protocol integrates easily into recent
											browser and tablets. Secretarium is real-time and has the capacity of pushing
											data to end-users. Secretarium is designed
											for speed: we achieve finality of execution within a split second, simplifying
											integration in an unparalleled way for a
											Blockchain.
										</p>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="scaling">
					<section>
						<div class="container">
							<h2>How does Secretarium scale ?</h2>
							<h3>Secretarium commoditises privacy at scale for application editors</h3>
							<br />
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-8 col-left">
										<h4>Facilitates confidential computing at scale</h4>
										<br />
										<p>
											The throughput and latency of a confidential computing system should be
											compatible with real life scenarios. We believe
											in a system that can grow organically in the same way the internet did.
										</p>
									</div>
								</div>
							</div>
							<div class="sec-img scale tall recess"></div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="why">
					<section>
						<div class="container">
							<h2>Why Secretarium ?</h2>
							<h3>A platform built for data privacy, ownership and control</h3>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-6 col-md-push-6 col-left">
										<div class="sec-img restore"></div>
										<h4>Restore the right to privacy FOR PEOPLE AND COMPANIES</h4>
										<br />
										<p>
											Consent, privacy by design and by default, are our DNA. We believe people and
											companies should have the option of using
											a technology that enforces their privacy.
										</p>
									</div>
									<div class="col-md-6 col-md-pull-6 col-right">
										<div class="sec-img reaper"></div>
										<h4>Prevent scavenging and monetization of private data</h4>
										<br />
										<p>
											The internet was intended for driving collaboration, but the balance between
											data originators and data aggregators has
											been heavily tilted toward the latter. Our goal is to achieve the same results
											without visibility of private data.
										</p>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="whom-for">
					<section>
						<div class="container">
							<h2>Who is Secretarium for ?</h2>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12 col-left">
										<br />
										<br />
										<h3>Secretarium enables impartial and trustful exchanges between distrusting
											parties.
											Shared services are distributed and controlled multilaterally, preventing any
											single party from pulling the plug.</h3>
									</div>
								</div>
							</div>
							<div class="sec-img map tall recess" style="height: calc(60vh - 50px); margin: 0;"></div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="technology">
					<section>
						<div class="container">
							<h2>How does Secretarium work ?</h2>
							<h3>Secretarium leverages cryptography and trusted execution environments</h3>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-8 col-md-push-8 col-left">
										<br />
										<br />
										<h4>Confidentiality out of the box</h4>
										<br />
										<p>
											We designed a secure connection protocol to guarantee to end-users the integrity
											of the remote nodes. Our
											crypto-platform is designed to facilitate the development of the business logic
											inside distributed confidential
											applications, so you don’t have to deal with all the complexity.
										</p>
										<br />
										<h4>Peer-to-peer network</h4>
										<br />
										<p>
											We set-up multiple trusted nodes to communicate with one another over an
											encrypted peer to peer network, without a
											single point of failure. Using a protocol inspired by the military aviation that
											we call "identification friend-or-foe",
											we guarantee the integrity of the network at all times.
										</p>
										<br />
										<h4>Secure hardware</h4>
										<br />
										<p>
											To avoid security vs performance trade-offs, we use trusted execution
											environments. Encrypted enclaves on secure
											hardware provide privacy and integrity, even to an attacker with physical access
											to the machine and operating system.
										</p>
									</div>
									<div class="col-md-4 col-md-pull-4 col-right">
										<div class="sec-img technology tall" style="height: calc(100% - 3rem);"></div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="secret-processing">
					<section>
						<div class="container">
							<h2>Secret processing</h2>
							<h3>Provides insight on data without seeing the data</h3>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-8 col-md-push-8 col-left">
										<br />
										<br />
										<p>
											Some parties have proprietary algorithms, other parties have confidential
											data-banks. Secret processing involves
											combining both, whilst guaranteeing secrecy.
											Designed for data analytics, it can be used to risk analyse some parties'
											financial portfolios using other parties'
											quantitative models and/or market data.
											Other usages can be genomic diagnostic, for individuals, or of one medical firm
											over the DNA bank of another firm.
											When secured with Differential Privacy techniques, it is perfectly adapted for
											data rental, a new way of monetizing
											confidential data.
										</p>
									</div>
									<div class="col-md-4 col-md-pull-4 col-right">
										<div class="sec-img secretProcessing tall" style="height: calc(60vh - 100px);"></div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="secret-mixing">
					<section>
						<div class="container">
							<h2>Secret mixing</h2>
							<h3>Allows a group to pool data together and collectively achieve insight</h3>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-6 col-md-push-6 col-left">
										<br />
										<br />
										<p>
											Untrusting parties can use Secretarium to build a consensus using each parties
											private data, without any other party
											getting access, including Secretarium itself.
											Designed for data pooling and data matching, it can be used for example to
											compute market data benchmarks, detect
											fraudulent insurance claims, compare reference data.
											Other usages involve enforcement of statistical secrecy, like the pricing of
											insurance policies on real data.
											Finally, it is a solution to the voting problem, Secretarium being able to
											enforce voting secrecy, voting protocol and a
											guaranteed honest outcome.
										</p>
									</div>
									<div class="col-md-6 col-md-pull-6 col-right">
										<div class="sec-img secretMixing tall" style="height: calc(60vh - 100px);"></div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="section" data-anchor="team">
					<section>
						<div class="container">
							<h2>The Secretarium team</h2>
							<h3>A team of engineers, with deep investment banking and crypto background</h3>
							<div class="container-fluid">
								<br />
								<br />
								<div class="row">
									<div class="col-md-4 col-md-push-4 col-left">
										<img class="team-member" />
										<h4 class="team-member-name">NAME SURNAME</h4>
										<span class="team-member-position">Founder, Chief Technology Officer</span>
									</div>
									<div class="col-md-4 col-md-push-4">
										<img class="team-member" />
										<h4 class="team-member-name">NAME SURNAME</h4>
										<span class="team-member-position">Founder, Chief Science Officer</span>
									</div>
									<div class="col-md-4 col-md-push-4 col-right">
										<img class="team-member" />
										<h4 class="team-member-name">NAME SURNAME</h4>
										<span class="team-member-position">Founder, Chief Event Officer</span>
									</div>
								</div>
								<br />
								<br />
								<div class="row">
									<div class="col-md-4 col-md-push-4 col-left">
										<img class="team-member" />
										<h4 class="team-member-name">NAME SURNAME</h4>
										<span class="team-member-position">Founder, Chief Unicorn Officer</span>
									</div>
									<div class="col-md-4 col-md-push-4">
										<img class="team-member" />
										<h4 class="team-member-name">NAME SURNAME</h4>
										<span class="team-member-position">Founder, Chief Party Officer</span>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="section fp-auto-height" data-anchor="links">
					<footer>
						<div class="container">
							<div class="container-fluid">
								<br />
								<br />
								<div class="row">
									<div class="col-md-3 col-md-push-3 col-left">
										<h5 class="footer-category">Community</h5>
										<ul>
											<li><a href="#">Discourse</a></li>
											<li><a href="#">Twitter</a></li>
										<ul>
									</div>
									<div class="col-md-3 col-md-push-3">
										<h5 class="footer-category">Secretarium</h5>
										<ul>
											<li><a href="#">Privacy Notice</a></li>
										<ul>
									</div>
									<div class="col-md-3 col-md-push-3">
										<h5 class="footer-category">Customer</h5>
										<ul>
											<li><a href="#">Log in</a></li>
										<ul>
									</div>
								</div>
								<br />
								<br />
								<div class="row">
									<div class="col-md-12 col-md-push-12 col-left">
										<p class="copyright">
											All rights reserved. Copyright &copy; 2019 Secretarium Inc. - Webdesign by <a href="https://florian.bzh" target="_blank">Florian Guitton</a>
										</p>
									</div>
								</div>
								<br />
								<br />
							</div>
						</div>
					</footer>
				</div>
			</full-page>
		</div>
	</script>

	<script>
		const store = {}, onDrop = null, onResize = {};

		const Home = Vue.component('sec-home', {
			template: '#sec-home',
			data: () => {
				return {
					options: {
						scrollOverflow: true,
						licenseKey: '98AFD12E-428246E1-B7908FFE-69A37211',
						sectionsColor: ['transparent'],
						touchSensitivity: 30,
						scrollingSpeed: 1000,
						menu: '#menu',
						// The scrollBar behaviour is extra-buggy
						//scrollBar: true
					},
					canvas: { redrawLoop: true, items: {}, maxDist: 0 }
				}
			},
			mounted() {
				Vue.set(this.options, "onLeave", this.onLeave);
				setTimeout(() => { this.fillCanvas(); }, 100);
				setTimeout(() => { this.drawCanvas(); }, 2500);
				subscribeOnResize("home-logo", () => {
					$("#stage").empty();
					this.canvas.items = [];
					this.canvas.maxDist = 0;
					this.fillCanvas();
				});
			},
			methods: {
				onLeave (origin, destination, direction) {
					this.canvas.redrawLoop = destination.index == 0;
					if(this.canvas.redrawLoop) setTimeout(() => { this.drawCanvas(); }, 10000);
					return true;
				},
				fillCanvas() {
					let t = $("#stage-fulllogo"), l = $("#stage-logo"), w = $("#stage"),
						sx = w.width(), sy = w.height(), flx = 5906, fly = 1231, lx = 1665, ly = 1231, lhx = 685, lhy = 680,
						r = 0.08 * sy / fly, y = sy * 0.46, x = (sx - r * flx) / 2, wx = Math.floor(sx / (r * lhx) / 2),
						add = (i, j) => {
							if(i >= 0 && j >= -1 || Math.abs(j) > 12) return;
							let dist = Math.floor(Math.sqrt(i * i + j * j)), nx = x + i * r * lhx, ny = y - j * r * lhy,
								e = l.clone().removeAttr("id").addClass("stage-logo")
									 .css({ position: "absolute", left: nx, top: ny, height: "8vh", display: "none" }).appendTo(w);
							if(!this.canvas.items[dist]) this.canvas.items[dist] = [];
							this.canvas.items[dist].push({ e: e, x: i, y: j, v: false });
							if(dist > this.canvas.maxDist) this.canvas.maxDist = dist;
						};
					t.clone().removeAttr("id").css({ position: "absolute", left: x, top: y, height: "8vh", display: "none" }).appendTo(w).fadeIn(1000);
					let ix = -wx % 3 != -1 ? -wx + wx % 3 - 1 : -wx;
					for(var i = ix; i <= wx; i+= 3) { // horizontal
						for(var j = -13; j <= 12; j+= 3) {
							add(i, j);
							add(i + 1, j + 1);
							add(i + 2, j + 2);
						}
					}
				},
				drawCanvas() {
					if(!this.canvas.redrawLoop) return;
					for(var i in this.canvas.items) {
						this.canvas.items[i].forEach(o => {
							let r = Math.random(), show = (o.x < 0 && o.y <= 0 && r > .2) || (o.x < 0 && o.y < -o.x && r > .3) ||
								(o.x < 0 && r > .6) || (o.x >= 0 && -o.y > .5 * o.x && r > .2) || (o.x >= 0 && r > .6);
							if(o.v != show) {
								if(show) o.e.fadeIn(2500 + 100 * i);
								else o.e.fadeOut(500 + 20 * i);
								o.v = show;
							}
						});
					}
					setTimeout(() => { this.drawCanvas(); }, 10000);
				}
			}
		});
		const router = new VueRouter({
			mode: 'history',
			routes: [
				{ path: '/', component: Home }
			]
		});
		const app = new Vue({
			router,
			data: () => {
				return {
					store: store
				}
			},
		}).$mount('#app');

		function setOnDrop(cb) {
			$('body').toggleClass('active', cb != null);
			onDrop = cb;
		}
		function subscribeOnResize(id, cb) {
			onResize[id] = cb;
		}
		$(function() {
			$('body').on("dragover dragenter", function(e) {
				$('body').addClass('dragging');
			}).on("dragleave", function(e) {
				if(e.clientX == 0 && e.clientY == 0)
					$('body').removeClass('dragging');
			}).on("drop", function(e) {
				$('body').removeClass('dragging');
				if(onDrop != null) {
					if(e.dataTransfer && e.dataTransfer.files) onDrop(e);
					else if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files) onDrop(e.originalEvent);
				}
			});
			(() => {
				var resizeTimeout = null;
				$( window ).resize(function() {
					if (resizeTimeout !== null) clearTimeout(resizeTimeout);
					resizeTimeout = setTimeout(() => {
						resizeTimeout = null;
						for(let id in onResize) {
							onResize[id]();
						}
					}, 100);
				});
			})();
		});
	</script>
</body>

</html>