<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Secretarium is a distributed confidential computing platform guaranteeing privacy by default and by design">

	<title>Secretarium - Entrusted with secrets</title>
	<link rel="icon" type="image/png" href="images/secretarium_128x128.png">

	<link rel="stylesheet" href="styles/bootstrap-4.3.1.min.css" />
	<link rel="stylesheet" href="styles/fontawesome-5.7.2.all.min.css" />
	<link rel="stylesheet" href="styles/secretarium-0.0.1.css" />
	<link rel="stylesheet" href="styles/secretarium.navbar.css" />
	<link rel="stylesheet" href="styles/secretarium.presentation.css" />
	<link rel="stylesheet" href="styles/secretarium.connect.css" />
	<link rel="stylesheet" href="styles/secretarium.alerts.css" />

	<script src="scripts/jquery-3.3.1.min.js"></script>
	<script src="scripts/popper-1.14.7.min.js"></script>
	<script src="scripts/bootstrap-4.3.1.min.js"></script>
	<script src="scripts/vue-2.6.10.js"></script>
	<script src="scripts/vue-router-3.0.2.min.js"></script>
	<script src="scripts/nng-0.1.0.js"></script>
	<script src="scripts/secretarium-0.1.4.js"></script>
</head>

<body>
	<img src="images/secretarium_title.svg" id="stage-full-logo" />
	<img src="images/secretarium_logo_grey.svg" id="stage-logo" />
	<div id="stage"></div>
	<div id="drop-area"></div>

	<div id="app" @dragover.prevent @drop.prevent v-cloak>
		<header>
			<nav class="navbar p-0" :class="{'logo-page':store.isLogoPage, 'fixed-top':store.isPresentationPages}">
				<div id="menu" class="container-fluid py-2" :class="{container:!store.isLogoPage}">
					<router-link v-if="!store.isPresentationPages" to="/#welcome" class="navbar-brand logo"></router-link>
					<a v-else class="navbar-brand logo" href="#welcome"></a>
					<ul id="presentation-menu" class="navbar-nav flex-row d-none d-flex">
						<li class="nav-item py-0 px-2">
							<a class="nav-link" href="#what-it-is">Concept</a>
						</li>
						<li class="nav-item py-0 px-2 d-none d-sm-flex">
							<a class="nav-link" href="#why">Rationale</a>
						</li>
						<li class="nav-item py-0 px-2">
							<a class="nav-link" href="#technology">Technology</a>
						</li>
						<li class="nav-item py-0 px-2">
							<a class="nav-link" href="#team">About us</a>
						</li>
					</ul>
					<ul id="sec-menu" class="navbar-nav flex-row ml-auto">
						<li v-if="store.isLogoPage" class="nav-item mr-3" >
							<a class="nav-link shift-left" href="#what-it-is">Presentation</a>
						</li>
						<li v-else-if="!store.isPresentationPages" class="nav-item mr-3" >
							<router-link to="/#what-it-is" class="nav-link shift-left">Presentation</router-link>
						</li>
						<li v-if="connection.retrying" class="nav-item">
							<div class="alert alert-warning py-1 px-2 m-0 mr-3 d-inline-block btn-sm" role="alert" v-if="connection.retryingMsg.length>0">
								{{connection.retryingMsg}}
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-outline-primary btn-sm" @click.prevent="connect('')">Retry now</button>
								<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split"
										data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu dropdown-menu-right">
									<button type="button" class="dropdown-item btn-sm"
											v-for="gw in store.gateways" :key="gw.endpoint" @click.prevent="connect(gw.endpoint)">{{gw.name}}</button>
								</div>
							</div>
						</li>
						<li v-if="isLoggedIn" class="nav-item dropdown" style="margin-right: 2vw;">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">me</a>
							<div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="navbarDropdown">
								<router-link to="/apps" class="dropdown-item">DCApp store</router-link>
								<div class="dropdown-divider"></div>
								<router-link to="/app/identity" class="dropdown-item">Personal settings</router-link>
								<button type="button" class="dropdown-item" @click.prevent="disconnect">Disconnect</button>
							</div>
						</li>
						<li v-else class="nav-item" style="margin-right: 2vw;">
							<router-link to="/connect" class="nav-link">Connect</router-link>
						</li>
					</ul>
				</div>
				<div id="presentation-sub-menu" class="d-none d-sm-flex">
					<div class="container small">
						<ul id="sub-concept" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<a href="#what-it-is" class="nav-link">What is Secretarium ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#what-it-does" class="nav-link">What does it provide ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#scaling" class="nav-link">How does it scale ?</a>
							</li>
						</ul>
						<ul id="sub-rationale" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<a href="#why" class="nav-link">Why Secretarium ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#whom-for" class="nav-link">Who is it for ?</a>
							</li>
						</ul>
						<ul id="sub-techno" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<a href="#technology" class="nav-link">How does it work ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#secret-processing" class="nav-link">Secret processing</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#secret-mixing" class="nav-link">Secret mixing</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>

		<content>
			<transition name="page" mode="out-in">
				<router-view></router-view>
			</transition>
		</content>

		<footer v-if="!store.isPresentationPages" class="bg-light">
			<div class="container">
				<div class="row no-gutters">
					<div class="col text-muted">
						<small>
							<i class="fas fa-circle" :class="[state.color]" style="font-size: 60%; vertical-align: 20%;"></i>
							<span>{{state.text}}</span>
						</small>
					</div>
					<div class="col-4 text-muted text-center">© <?=date("Y")?> - Secretarium</div>
					<div class="col-4 text-muted text-right"><small>{{connectedAs}}</small></div>
				</div>
			</div>
		</footer>
		<footer v-else>
			<div class="container py-4">
				<div class="row">
					<div class="col-md-3">
						<h5 class="footer-category">Community</h5>
						<ul>
							<li><a href="https://twitter.com/secretarium1" target="_blank">Twitter</a></li>
							<li><a href="https://www.linkedin.com/company/secretarium" target="_blank">LinkedIn</a></li>
						<ul>
					</div>
					<div class="col-md-3">
						<h5 class="footer-category">Secretarium</h5>
						<ul>
							<li><a href="#">Blog</a></li>
							<li><a href="#">Privacy Notice</a></li>
							<li><a href="#">About</a></li>
						<ul>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-md-12">
						<p class="copyright">
							All rights reserved. Copyright &copy; <?=date("Y")?> Secretarium Ltd. - Webdesign by
							<a href="https://florian.bzh" target="_blank" style="color:#eee">Florian Guitton</a>
						</p>
					</div>
				</div>
			</div>
		</footer>

		<sec-alerts></sec-alerts>
	</div>

	<script type="text/x-template" id="sec-notif-state">
		<span v-if="state.visible" class="notif-state">
			<span class="notif-state-icon notif-state-chain" v-for="s in state.chained" :key="s.id" v-show="state.showChain"
					:title="s.title" :style="{ opacity: s.opacity||1, 'margin-right': '0.2em' }">
				<i v-for="(i, k) in s.icons" :key="s.id+'_'+k" class="fas fa" :class="[i.icon, i.color]" :style="i.styles"></i>
			</span>
			<span :title="state.global.title" :style="{ opacity: state.global.opacity||1 }" @click.predent="state.showChain=!state.showChain">
				<i v-for="(i, k) in state.global.icons" :key="'g_'+k" class="fas fa" :class="[i.icon, i.color]" :style="i.styles"></i>
			</span>
			<span v-show="state.msg" class="small text-muted" style="vertical-align: 10%;">{{state.msg}}</span>
		</span>
	</script>

	<script type="text/x-template" id="sec-alerts">
		<div v-if="alerts.length>0" id="sec-alert-wrap">
			<div v-for="(a, i) in alerts" class="sec-alert alert alert-warning fade show" role="alert"
				:class="{'alert-dismissible':!a.undismissible,'alert-danger':a.isError}" :key="a.key" >
				<div v-html="a.html"></div>
				<button v-if="!a.undismissible" type="button" class="close" data-dismiss="alert" aria-label="Close" @click="close(i)">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="sec-presentation">
		<div id="presentation">
			<section id="welcome">
			</section>
			<section id="what-it-is">
				<div class="container">
					<h2>What is Secretarium ?</h2>
					<h3>Secretarium is an integrity and confidentiality crypto-platform</h3>
					<div class="row mx-0 mt-4">
						<div class="col-md-6 px-0 pr-md-5">
							<div class="sec-img fencedNetwork">
								<img src="images/figure_fencedNetwork.svg" alt="confidential computing platform image"/>
							</div>
							<h4 class="mb-2">Distributed Confidential computing platform</h4>
							<p>
								Relying on trusted execution environments and powered by secure multi-party
								computing. Secretarium has been designed to
								run applications on encrypted data in a trustable, distributed, scalable and
								efficient way, with no possible data
								leakage or manipulation, and no single point of failure.
							</p>
						</div>
						<div class="col-md-6 px-0 pl-md-5">
							<div class="sec-img dcApp">
								<img src="images/figure_dcApp.svg" alt="DCApp image"/>
							</div>
							<h4 class="mb-2">Hosting Distributed Confidential Applications </h4>
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
			</section>
			<section id="what-it-does">
				<div class="container">
					<h2>What does Secretarium provide ?</h2>
					<h3>Total confidentiality: nobody sees the data, including Secretarium</h3>
					<div class="row mx-0 mt-4">
						<div class="col-md-6 px-0 pr-md-5">
							<div class="sec-img shield">
								<img src="images/figure_shield.svg" alt="intellectual property image"/>
							</div>
							<h4 class="mb-2">Protects intellectual property and commercial rights</h4>
							<p>
								Secretarium guarantees privacy by default and by design: users always keep
								control of their data. Secretarium uses
								end-to-end encryption: data uploaded to Secretarium remains the property of its
								originators and no one can access it in
								clear text. DCApps intellectual property remains the property of the DCApp
								writer.
							</p>
						</div>
						<div class="col-md-6 px-0 pl-md-5">
							<div class="sec-img puzzle">
								<img src="images/figure_puzzle.svg" alt="easy integration image"/>
							</div>
							<h4 class="mb-2">Offers easy integration to your processes</h4>
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
			</section>
			<section id="scaling">
				<div class="container">
					<h2>How does Secretarium scale ?</h2>
					<h3>Secretarium commoditises privacy at scale for application editors</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-8 px-0">
							<h4 class="mb-2">Facilitates confidential computing at scale</h4>
							<p>
								The throughput and latency of a confidential computing system should be
								compatible with real life scenarios. We believe
								in a system that can grow organically in the same way the internet did.
							</p>
						</div>
					</div>
					<div class="sec-img scale">
						<img src="images/figure_scale.png" alt="scale image"/>
					</div>
				</div>
			</section>
			<section id="why">
				<div class="container">
					<h2>Why Secretarium ?</h2>
					<h3>A platform built for data privacy, ownership and control</h3>
					<div class="row mx-0 mt-4">
						<div class="col-md-6 px-0 pr-md-5">
							<div class="sec-img restore">
								<img src="images/figure_restore.svg" alt="restore right to privacy image"/>
							</div>
							<h4 class="mb-2">Restore the right to privacy for people and companies</h4>
							<p>
								Consent, privacy by design and by default, are our DNA. We believe people and
								companies should have the option of using
								a technology that enforces their privacy.
							</p>
						</div>
						<div class="col-md-6 px-0 pl-md-5">
							<div class="sec-img reaper">
								<img src="images/figure_reaper.svg" alt="prevent scavenging image"/>
							</div>
							<h4 class="mb-2">Prevent scavenging and monetization of private data</h4>
							<p>
								The internet was intended for driving collaboration, but the balance between
								data originators and data aggregators has
								been heavily tilted toward the latter. Our goal is to achieve the same results
								without visibility of private data.
							</p>
						</div>
					</div>
				</div>
			</section>
			<section id="whom-for">
				<div class="container">
					<h2>Who is Secretarium for ?</h2>
					<h3>Secretarium enables impartial and trustful exchanges between distrusting
						parties.
						Shared services are distributed and controlled multilaterally, preventing any
						single party from pulling the plug.</h3>
					<div class="sec-img map">
						<img src="images/figure_map.png" alt="map image"/>
					</div>
				</div>
			</section>
			<section id="technology">
				<div class="container">
					<h2>How does Secretarium work ?</h2>
					<h3>Secretarium leverages cryptography and trusted execution environments</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-8 px-0 pr-md-2">
							<h4 class="mb-2">Confidentiality out of the box</h4>
							<p>
								We designed a secure connection protocol to guarantee to end-users the integrity
								of the remote nodes. Our
								crypto-platform is designed to facilitate the development of the business logic
								inside distributed confidential
								applications, so you don’t have to deal with all the complexity.
							</p>
							<h4 class="mt-5 mb-2">Peer-to-peer network</h4>
							<p>
								We set-up multiple trusted nodes to communicate with one another over an
								encrypted peer to peer network, without a
								single point of failure. Using a protocol inspired by the military aviation that
								we call "identification friend-or-foe",
								we guarantee the integrity of the network at all times.
							</p>
							<h4 class="mt-5 mb-2">Secure hardware</h4>
							<p>
								To avoid security vs performance trade-offs, we use trusted execution
								environments. Encrypted enclaves on secure
								hardware provide privacy and integrity, even to an attacker with physical access
								to the machine and operating system.
							</p>
						</div>
						<div class="col-md-4 px-0">
							<div class="sec-img technology my-md-0">
								<img src="images/figure_technology.svg" alt="technology image"/>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section id="secret-processing">
				<div class="container">
					<h2>Secret processing</h2>
					<h3>Provides insight on data without seeing the data</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-7 px-0">
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
						<div class="col-md-5 px-0">
							<div class="sec-img secretProcessing my-md-0">
								<img src="images/figure_secretProcessing.svg" alt="secret processing image"/>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section id="secret-mixing">
				<div class="container">
					<h2>Secret mixing</h2>
					<h3>Allows a group to pool data together and collectively achieve insight</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-6 px-0">
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
						<div class="col-md-6 px-0">
							<div class="sec-img secretMixing my-md-0">
								<img src="images/figure_secretMixing.svg" alt="secret mixing image"/>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section id="team">
				<div class="container">
					<h2>The Secretarium team</h2>
					<h3>A team of engineers, with deep investment banking and crypto background</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-4 px-0 py-2 pr-md-2">
							<h4 class="mb-4">The secretarium team</h4>
							<div class="about-item">
								<img class="team-member" />
								<h5 class="team-member-name">Bertrand Foing</h5>
								<span class="team-member-position">laoreet non curabitur gravida arcu ac tortor dignissim convallis aenean et tortor at risus viverra adipiscing at in tellus integer</span>
							</div>
							<div class="about-item">
								<img class="team-member" />
								<h5 class="team-member-name">Cédric Wahl</h5>
								<span class="team-member-position">aliquam etiam erat velit scelerisque in dictum non consectetur a erat nam at lectus urna duis convallis convallis tellus id</span>
							</div>
							<div class="about-item">
								<img class="team-member" />
								<h5 class="team-member-name">Axel Oehmichen</h5>
								<span class="team-member-position">sed enim ut sem viverra aliquet eget sit amet tellus cras adipiscing enim eu turpis egestas pretium aenean pharetra magna</span>
							</div>
						</div>
						<div class="col-md-4 px-0 py-2 pr-md-2">
							<h4 class="mb-4">Our sponsors</h4>
							<div class="about-item">
								<img src="/images/logo_intel.svg" class="comp-logo"/>
							</div>
							<h4 class="my-4">Our partners</h4>
							<div class="about-item">
								<img src="/images/logo_swisscom.svg" class="comp-logo"/>
							</div>
						</div>
						<div class="col-md-4 px-0 py-2 pr-md-2">
							<h4 class="mb-4">Our clients</h4>
							<div class="about-item">
								<img src="/images/logo_soge.svg" class="comp-logo"/>
							</div>
							<div class="about-item">
								<img src="/images/logo_ubs.svg" class="comp-logo"/>
							</div>
							<div class="about-item">
								<img src="/images/logo_creds.svg" class="comp-logo"/>
							</div>
						</div>
					</div>
				</div>
			</section>
			<a class="go-to-next" href="#next" @click.prevent="goToNext"><i class="fas fa-arrow-down"></i></a>
		</div>
	</script>

	<script type="text/x-template" id="sec-connect">
		<div id="connect" class="container center">
			<div class="card sec-card border-0">
				<h1 class="card-header text-center"><i class="sec-logo mr-1"></i>Connect</h1>
				<div class="card-body lato">
					<div v-if="$root.keys.list.length>0" class="py-3">
						<h5 class="card-title">Choose a key</h5>
						<ul>
							<li v-for="(key, i) in $root.keys.list" :key="i">
								<p>{{key.name}}</p>
								<button type="button" class="btn-sm" @click.prevent="$root.removeKey(i)" title="Delete key">
									<i class="fas fa-fw fa-trash-alt pr-3"></i>
								</button>
								<a class="dropdown-item" :href="$root.keys.exports[i].url" :download="$root.keys.exports[i].name" title="Export">
									<i class="fas fa-fw fa-download"></i>
								</a>
							</li>
						</ul>
					</div>
					<hr v-if="$root.keys.list.length>0" class="my-4 sec" />
					<div class="py-3">
						<h6 class="card-title mb-3">Load a key</h6>
						<p class="card-text">
							Please drag and drop a key here, or load from disk.
						</p>
						<div class="custom-file">
							<input type="file" id="sec-loadkey-file" accept=".secretarium" class="custom-file-input" @change="keyFileChange">
							<label for="sec-loadkey-file sec" class="custom-file-label">Browse</label>
						</div>
						<p v-if="importedKeyMsg.length>0" class="small text-alert">
							{{importedKeyMsg}}
						</p>
					</div>
					<hr class="my-4 sec" />
					<div class="py-3">
						<h6 class="card-title mb-3">Create a new key</h6>
						<sec-create-key></sec-create-key>
					</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="sec-create-key">
		<div id="create-key">
			<p class="card-text">
				A new key will be generated, locally in your browser.<br />
				It will allow authentication when interacting with the Secretarium platform.
			</p>
			<form @submit.prevent>
				<button type="submit" class="btn btn-sec mr-3" @click.prevent="createKey">Generate</button>
				<sec-notif-state :state="generation.ns.data"></sec-notif-state>
			</form>
		</div>
	</script>

	<script type="text/x-template" id="sec-export-key">
		<div id="export-key" class="container center">
			<div class="card sec-card border-0 mw-md">
				<h3 class="card-header text-center"><i class="sec-logo mr-1"></i>Export your new key</h3>
				<div class="card-body lato">

					<h6 class="card-title mb-3">Name your key</h6>
					<form @submit.prevent>
						<div class="form-group mb-1">
							<label for="ckName" class="sr-only">Key name</label>
							<input id="ckName" type="text" class="form-control" placeholder="Key name" :value="key.name||''">
						</div>
					</form>

					<hr class="mt-4 sec" />

					<h6 class="card-title my-3">Encrypt your key for storage</h6>
					<p class="card-text">
						To safely store your key, please choose a strong password.
					</p>
					<form @submit.prevent>
						<div class="form-group mt-3">
							<label for="ckPwd" class="sr-only">Password</label>
							<input id="ckPwd" type="password" class="form-control" placeholder="Password" autocomplete="current-password">
						</div>
						<button type="submit" class="btn btn-sec mr-3" @click.prevent="encryptKey">
							<i class="fas fa-fw fa-lock pr-3"></i> Encrypt
						</button>
						<sec-notif-state :state="encryption.ns.data"></sec-notif-state>
					</form>

					<hr class="mt-4 sec" />

					<h6 class="card-title my-3">Export your key</h6>
					<p class="card-text">
						Export your key to back it up locally, or on a secure hardware.
					</p>
					<form class="form-inline" @submit.prevent>
						<a class="btn btn-sec mr-3" :href="exportUrl"
							:disabled="key.name.length==0" :download="(key.name||'new-key')+'.secretarium'">
							<i class="fas fa-fw fa-download pr-3"></i> Export
						</a>
						<div class="form-check lg">
							<input type="checkbox" class="form-check-input" id="ckExportEncrypted"
								:disabled="!encryption.success" :checked="encryption.success">
							<label class="form-check-label" for="ckExportEncrypted">Export encrypted</label>
						</div>
					</form>

					<div v-if="$root.canStore">
						<hr class="mt-4 sec" />
						<h6 class="card-title my-3">Save in this browser</h6>
						<p class="card-text">If you trust this machine, save your key in this browser to ease future connections.</p>
						<form class="form-inline" @submit.prevent>
							<button type="button" class="btn btn-sec mr-3" :disabled="key.name.length==0" @click.prevent="saveKey">
								<i class="fas fa-fw fa-save pr-3"></i> Save
							</button>
							<sec-notif-state :state="save.ns.data"></sec-notif-state>
							<div class="form-check lg">
								<input type="checkbox" class="form-check-input" id="ckSaveEncrypted" :disabled="!encryption.success">
								<label class="form-check-label" for="ckSaveEncrypted">Save encrypted</label>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="sec-app-access-denied">
		<div class="container mt-4 mb-4">
			<div class="card mb-4">
				<h5 class="card-header">
					<i class="fas fa-fw mr-2 text-primary" :class="$root.store.dcapps[$route.params.id].icon"></i>
					{{ $root.store.dcapps[$route.params.id].display }}
				</h5>
				<div class="card-body">
					<h5 class="card-title">Restricted access</h5>
					<p class="card-text">You do not have access to this application.</p>
				</div>
			</div>
		</div>
	</script>

	<script>
		var onDrop = null, scrollSpies = {},
			scrollObserver = new IntersectionObserver((entries, observer) => {
				for(let id in scrollSpies) {
					scrollSpies[id](entries, observer);
				}
			}, { threshold: 0.5 });
		const onResize = {},
			canStore = (function() {
				try {
					localStorage.setItem("a", "a");
					localStorage.removeItem("a");
					return true;
				} catch (e) {
					return false;
				}
			})(),
			store = {
				user: {
					ECDSA: undefined,
					ECDSAPubBase64: undefined,
					dcapps: {}
				},
				isPresentationPages: window.location.pathname == "/",
				isLogoPage: window.location.pathname == "/" && (window.location.hash.length == 0 || window.location.hash == "#welcome"),
				gateways: [
					{ endpoint: "wss://ovh2.node.secretarium.org:443/", name: "PROD (OVH #2)" },
					{ endpoint: "wss://ovh3.node.secretarium.org:443/", name: "PROD (OVH #3)" },
					{ endpoint: "wss://ovh5.node.secretarium.org:443/", name: "PROD (OVH #5)" },
					{ endpoint: "wss://ovh6.node.secretarium.org:443/", name: "PROD (OVH #6)" },
					{ endpoint: "wss://ovh7.node.secretarium.org:443/", name: "PROD (OVH #7)" }
				],
				dcapps: {}
			},
			alerts = [],
			canvas = (function() {
				var stage = $("#stage"), fullLogo = $("#stage-full-logo"), logo = $("#stage-logo"),
					redrawLoop = null, items = {}, maxDist = {}, onResiseSubscribed = false;
				function fill(resizing = false) {
					let t = fullLogo, l = logo, w = stage,
						sx = w.width(), sy = w.height(), flx = 5906, fly = 1231, lx = 1665, ly = 1231, lhx = 650, lhy = 650,
						r = 0.1 * sy / fly, y = sy * 0.42, x = (sx - r * flx) / 2, wx = Math.floor(sx / (r * lhx) / 2),
						add = (i, j) => {
							if(i >= 0 && j >= -1 || Math.abs(j) > 12) return;
							let dist = Math.floor(Math.sqrt(i * i + j * j)), nx = x + i * r * lhx, ny = y - j * r * lhy,
								e = l.clone().removeAttr("id").addClass("stage-logo stage-" + dist)
									 .css({ left: nx, top: ny }).appendTo(w);
							if(!items[dist]) items[dist] = [];
							items[dist].push({ e: e, x: i, y: j, v: false });
							if(dist > maxDist) maxDist = dist;
						},
						fl = t.clone().removeAttr("id").css({ left: x, top: y }).appendTo(w)
							  .addClass("full-logo").toggleClass("active", resizing),
						ix = -wx % 3 != -1 ? -wx + wx % 3 - 1 : -wx;
					for(var i = ix; i <= wx; i+= 3) { // horizontal
						for(var j = -13; j <= 12; j+= 3) {
							add(i, j);
							add(i + 1, j + 1);
							add(i + 2, j + 2);
						}
					}
					if(!onResiseSubscribed)
						subscribeOnResize("canvas", () => { onResize() });
				};
				function draw() {
					if(!store.isLogoPage) return;
					for(var i in items) {
						items[i].forEach(o => {
							let r = Math.random(), show = (o.x < 0 && o.y <= 0 && r > .2) || (o.x < 0 && o.y < -o.x && r > .3) ||
								(o.x < 0 && r > .6) || (o.x >= 0 && -o.y > .5 * o.x && r > .2) || (o.x >= 0 && r > .6);
							if(o.v != show) {
								if(show) o.e.addClass("active");
								else o.e.removeClass("active");
								o.v = show;
							}
						});
					}
					if(store.isLogoPage) start(10000);
				};
				function start(delay = 1500) {
					if(redrawLoop != null) return; // already started
					setTimeout(() => { $("#stage .full-logo").addClass("active") }, 100);
					redrawLoop = setTimeout(() => { draw() }, delay);
				};
				function stop() {
					clearTimeout(redrawLoop);
					redrawLoop = null;
				};
				function onResize() {
					stage.empty();
					items = {};
					maxDist = 0;
					fill(true);
				};
				return {
					fill: fill, draw: draw, start: start, stop: stop
				}
			})(),
			state = {
				icons: ["fa-check", "fa-hourglass-start", "fa-exclamation-circle"],
				colors: ["text-success", "text-warning", "text-danger"]
			},
			notifStates = {
				processing: [
					{ icon: "fa-cogs", color: "text-secondary" }
				],
				sent: [
					{ icon: "fa-clock", color: "text-secondary" }
				],
				acknowledged: [
					{ icon: "fa-check", color: "text-secondary" }
				],
				proposed: [
					{ icon: "fa-check", color: "text-secondary" },
					{ icon: "fa-check", color: "text-secondary", styles: { "margin-left": "-.5em" } }
				],
				committed: [
					{ icon: "fa-check", color: "text-secondary" },
					{ icon: "fa-check", color: "text-secondary", styles: { "margin-left": "-.5em" } },
					{ icon: "fa-check", color: "text-secondary", styles: { "margin-left": "-.5em" } }
				],
				executed: [
					{ icon: "fa-check", color: "text-success" }
				],
				failed: [
					{ icon: "fa-times", color: "text-danger" }
				]
			};

		class notifState {
			constructor(chained = 1, states = notifStates) {
				this.notifStates = states;
				this.data = {
					msg: "", visible: false, showChain: chained > 1,
					chained: [], global: { title: "", opacity: chained > 1 ? 0.4 : 1, icons: states['sent'] }
				};
				for(let i = 0; i < chained - 1; i++) {
					this.data.chained.push({ title: "", opacity: i == 0 ? 1 : 0.4, icons: states['sent'] });
				}
				this.state = "";
				this.reset();
			}
			_current() {
				return this.chainId < this.data.chained.length ? this.data.chained[this.chainId] : this.data.global;
			}
			_setState(state, msg = "", showMsg = false) {
				clearTimeout(this.timeout);
				this.data.msg = showMsg ? (msg != "" ? msg : this.title) : "";
				if(this.state == "failed" && state != "sent" && state != "processing") return this;
				this.state = state;
				let target = this._current();
				target.icons = this.notifStates[state];
				target.title = msg != "" ? msg : (state +  " @" + (new Date()).toTimeString().substr(0, 5));
				return this.show();
			}
			show() { this.data.visible = true; return this; }
			showChain() { this.data.showChain = true; return this; }
			hide(waitMs = 5000) {
				if(this.state == "failed") return;
				if(waitMs > 0) this.timeout = setTimeout(() => { this.data.visible = false; }, waitMs);
				else this.data.visible = false;
				return this;
			}
			hideChain() { this.data.showChain = false; return this; }
			reset() { this.chainId = 0; return this; }
			processing(msg = "", showMsg = false) { return this.reset()._setState("processing", msg, showMsg).show(); }
			start(msg = "", showMsg = false) { return this.reset()._setState("sent", msg, showMsg).show(); }
			acknowledged(msg = "", showMsg = false) { return this._setState("acknowledged", msg, showMsg); }
			proposed(msg = "", showMsg = false) { return this._setState("proposed", msg, showMsg); }
			committed(msg = "", showMsg = false) { return this._setState("committed", msg, showMsg); }
			executed(msg = "", showMsg = false) {
				this._setState("executed", msg, showMsg);
				if(this.chainId < this.data.chained.length) { this.chainId++; }
				else { this.data.showChain = false; }
				this._current().opacity = 1;
				return this;
			}
			failed(msg = "", showMsg = false) { return this._setState("failed", msg, showMsg); }
		};

		const SecNotifState = Vue.component('sec-notif-state', {
			template: "#sec-notif-state",
			props: ['state']
		});

		const Alerts = Vue.component('sec-alerts', {
			template: '#sec-alerts',
			data: () => {
				return {
					alerts: alerts
				}
			},
			methods: {
				close(i) {
					this.alerts.splice(i, 1);
				}
			}
		});

		const Presentation = Vue.component('sec-presentation', {
			template: '#sec-presentation',
			data: () => {
				return {
					subMenu: [
						["what-it-is", "what-it-does", "scaling"],
						["why", "whom-for"],
						["technology", "secret-processing", "secret-mixing"],
					]
				}
			},
			mounted() {
				subscribeOnScroll("presentation", (entries, observer) => {
					entries.forEach(entry => {
						if(entry.isIntersecting && entry.target.id)
							this.onScroll(entry.target.id);
					});
				});
				document.querySelectorAll('#presentation>section').forEach(target => {
					scrollObserver.observe(target);
				});
			},
			beforeDestroy() {
				unsubscribeOnScroll("presentation");
				document.querySelectorAll('#presentation>section').forEach(target => {
					scrollObserver.unobserve(target);
				});
			},
			methods: {
				onScroll(id) {
					// Welcome page canvas
					if(!(store.isLogoPage = id == "welcome"))
						canvas.stop();
					else
						canvas.start(1500);

					let x = this.subMenu[0].includes(id) ? 1 :
						this.subMenu[1].includes(id) ? 2 :
						this.subMenu[2].includes(id) ? 3 : 0;

					// Toggle link
					$("header a[href].active").removeClass("active");
					$("header a[href='#" + id + "']").addClass("active");

					// Sub menu display
					$("#presentation-sub-menu").toggleClass("active", x > 0);
					$("#presentation-sub-menu #sub-concept").toggle(x === 1);
					$("#presentation-sub-menu #sub-rationale").toggle(x === 2);
					$("#presentation-sub-menu #sub-techno").toggle(x === 3);

					// Menu effects
					$("#presentation-menu a[href='#what-it-is']").toggleClass("active", x === 1);
					$("#presentation-menu a[href='#why']").toggleClass("active", x === 2);
					$("#presentation-menu a[href='#technology']").toggleClass("active", x === 3);

					// Go to next visibility
					$("#presentation>a").toggle(id != "team");
				},
				goToNext(e) {
					var s = window.scrollY, r = $("#presentation")[0].scrollHeight,
						h = window.innerHeight,
						p = $("#presentation section").length,
						x = 1 + Math.round(p * s / r);
					window.scrollTo(0, x * h);
				}
			}
		});

		const Connect = Vue.component('sec-connect', {
			template: '#sec-connect',
			data: () => {
				return {
					importedKeyMsg: ""
				}
			},
			mounted() {
				setOnDrop(this.importDroppedKeyFile);
			},
			beforeDestroy() {
				setOnDrop(null);
			},
			methods: {
				keyFileChange(e) {
					if(e.target && e.target.files && e.target.files.length == 1)
						this.importKeyFile(e.target.files[0]);
					else
						this.importedKeyMsg = "Invalid choice, expecting one file";
				},
				importDroppedKeyFile(e) {
					if(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length == 1)
						this.importKeyFile(e.dataTransfer.files[0]);
					else
						alerts.push({ key: "invalid-key-file", isError: true, html: "Invalid drop, expecting one file" });
				},
				importKeyFile(file) {
					let reader = new FileReader();
					reader.onloadend = x => {
						try {
							let key = JSON.parse(reader.result), replaced = false;
							for(let i = 0; i < this.keys.list.length; i++) {
								if(this.keys.list[i].name == key.name) {
									this.keys.list[i] = key;
									replaced = true;
									//$("#keysListSelect").val(i);
									break;
								}
							}
							if(!replaced) {
								this.keys.list.push(key);
							}
						}
						catch (e) {
							alerts.push({ key: "invalid-key-file", isError: true, html: "Invalid key file" });
						}
					};
					reader.onerror = e => {
						alerts.push({ key: "invalid-key-file", isError: true, html: "Error when reading file: " + e });
					};
					reader.readAsText(file);
				},
			}
		});
		const createdKey = { name: ""};
		const CreateKey = Vue.component('sec-create-key', {
			template: '#sec-create-key',
			data: () => {
				return {
					key: createdKey,
					generation: { ns: new notifState() }
				}
			},
			methods: {
				async createKey() {
					this.generation.success = false;
					this.generation.ns.processing();
					try {
						this.key.cryptoKey = await sec.utils.ecdsa.generateKeyPair(true);
						this.key.publicKey = new Uint8Array(await sec.utils.ecdsa.exportPub(this.key.cryptoKey, "raw")),
						this.key.privateKey = new Uint8Array(await sec.utils.ecdsa.exportPri(this.key.cryptoKey, "pkcs8"));
						this.generation.ns.executed().hide();
						setTimeout(() => { router.push('export-key'); }, 600);
					}
					catch(e) {
						this.generation.ns.failed(e.message);
					}
				}
			}
		});
		const ExportKey = Vue.component('sec-export-key', {
			template: '#sec-export-key',
			data: () => {
				return {
					key: createdKey,
					exportUrl: null,
					encryption: { ns: new notifState(), success: false },
					save: { ns: new notifState() }
				}
			},
			created() {
				this.exportUrl = this.getExportUrl();
			},
			destroyed() {
				URL.revokeObjectURL(this.exportUrl);
			},
			methods: {
				getExportUrl() {
					let j = JSON.stringify(this.getExportableKey()),
					    b = new Blob([j], { type: 'application/json;charset=utf-8;' });
					return URL.createObjectURL(b);
				},
				getExportableKey() {
					let key = { name: this.key.name };
					if(this.encryption.success) {
						key.encrypted = true;
						key.iv = this.key.iv.secToBase64();
						key.salt = this.key.salt.secToBase64();
						key.keys = this.key.encryptedKeys;
					} else {
						key.keys = sec.utils.concatUint8Array(this.key.publicKey, this.key.privateKey).secToBase64();
					}
					return key;
				},
				async encryptKey() {
					this.encryption.success = false;
					try {
						this.encryption.ns.processing();
						let name = $('#ckName').val();
						if(name.length < 1) { this.encryption.ns.failed("invalid key name", true); return; }
						this.key.name = name;
						this.key.salt = sec.utils.getRandomUint8Array(32);
						this.key.iv = sec.utils.getRandomUint8Array(12);
						let weakpwd = sec.utils.encode($('#ckPwd').val()),
							strongPwd = await sec.utils.hash(sec.utils.concatUint8Array(this.key.salt, weakpwd)),
							aesgcmKey = await sec.utils.aesgcm.import(strongPwd),
							keys = sec.utils.concatUint8Array(this.key.publicKey, this.key.privateKey);
							encryptedKeys = await sec.utils.aesgcm.encrypt(aesgcmKey, this.key.iv, keys);
						this.key.encryptedKeys = new Uint8Array(encryptedKeys).secToBase64();
						this.encryption.ns.executed();
						this.encryption.success = true;
						URL.revokeObjectURL(this.exportUrl);
						this.exportUrl = this.getExportUrl();
					}
					catch(e) {
						this.encryption.ns.failed(e.message);
					}
				},
				saveKey() {
					this.save.ns.processing();
					let name = $('#ckName').val();
					if(name.length < 1) { this.save.ns.failed("invalid key name", true); return; }
					this.key.name = name;
					this.$root.addKey(this.getExportableKey(), this.key.cryptoKey, true);
					this.save.ns.executed();
				}
			}
		});

		const AppStore = Vue.component('sec-app-store', {
			template: '#sec-appstore',
			data: () => {
				return {
				}
			},
			methods: {
			}
		});

		const AppAccessDenied = Vue.component('sec-app-access-denied', {
			template: '#sec-app-access-denied'
		});

		const router = new VueRouter({
			mode: 'history',
			routes: [
				{ path: '/', component: Presentation },
				{ path: '/connect', component: Connect },
				{ path: '/create-key', component: CreateKey },
				{ path: '/export-key', component: ExportKey },
				{ path: '/app-store', component: AppStore },
				{ path: '/app/:id', component: AppAccessDenied },
			]
		});
		router.beforeEach((to, from, next) => {
			store.isPresentationPages = to.path == '/';
			store.isLogoPage = store.isPresentationPages && (to.hash.length == 0 || to.hash == "#welcome");
			$("body").toggleClass("page-presentation", store.isPresentationPages);
			next();
		});

		const app = new Vue({
			router,
			data: () => {
				return {
					canStore: canStore,
					store: store,
					connection: {
						endpoint: "",
						retrying: false, retryingMsg: "", retryFailures: 0, retrier: null, lastState: 0, timeoutSec: 30,
						ns: {data: {}}
					},
					keys: {
						list: [], exports: [], cryptoKeys: {}
					},
				}
			},
			beforeMount() {
				if (canStore) {
					let v = localStorage.getItem('secretarium-keys');
					if(v != null) {
						try {
							this.keys.list = JSON.parse(v);
							for(let i = 0; i < this.keys.list.length; i++) {
								this.keys.exports.push(this.getExport(this.keys.list[i]));
							}
						}
						catch (e) { }
					}
				}
			},
			computed: {
				state() { return { text: "not connected", color: "text-danger", icon: "fa-exclamation-circle" } },
				connectedAs() { return ""; },
				isConnected() { return false; },
				isLoggedIn() { return false; },
			},
			methods: {
				getExport(key) {
					let b = new Blob([JSON.stringify(key)], { type: 'application/json;charset=utf-8;' });
					return { name: key.name + ".secretarium", url: URL.createObjectURL(b) };
				},
				storeKeys() {
					if (canStore)
						localStorage.setItem('secretarium-keys', JSON.stringify(this.keys.list));
				},
				addKey(key, cryptoKey, store) {
					this.keys.list.push(key);
					this.keys.exports.push(this.getExport(key));
					this.keys.cryptoKeys[key.name] = cryptoKey;
					if(store) this.storeKeys();
				},
				removeKey(i) {
					delete this.keys.cryptoKeys[this.keys.list[i].name];
					this.keys.list.splice(i, 1);
					this.storeKeys();
				},
				async connect(endpoint = "") {
				},
				getDCApps() {
					return $.getJSON("https://secretarium.org/dcapps/")
						.done(async x => {
							for (var name in x) { x[name].id = await sec.utils.hashBase64(name); }
							Vue.set(store, "dcapps", x);
						});
				}
			}
		}).$mount('#app');

		function setOnDrop(cb) {
			$('body').toggleClass('active', cb != null);
			onDrop = cb;
		}
		function subscribeOnResize(id, cb) {
			onResize[id] = cb;
		}
		function subscribeOnScroll(id, cb) {
			scrollSpies[id] = cb;
		}
		function unsubscribeOnScroll(id) {
			delete scrollSpies[id];
		}
		$(function() {
			canvas.fill();
			canvas.start(1500);

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

			$("#stage").css("z-index", -1);
			$("#app").css("opacity", 1);
			$("a.go-to-next").css("opacity", 1);
		});
	</script>
</body>

</html>