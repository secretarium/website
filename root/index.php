<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Secretarium is a distributed confidential computing platform guaranteeing privacy by default and by design">

	<title>Secretarium - Entrusted with secrets</title>
	<link rel="icon" type="image/png" href="/images/secretarium_128x128.png">

	<link rel="stylesheet" href="/styles/bootstrap-4.3.1.min.css" />
	<link rel="stylesheet" href="/styles/fontawesome-5.7.2.all.min.css" />
	<link rel="stylesheet" href="/styles/fullpage-3.0.4.min.css" />
	<link rel="stylesheet" href="/styles/secretarium-0.0.1.css" />
	<link rel="stylesheet" href="/styles/secretarium.navbar.css" />
	<link rel="stylesheet" href="/styles/secretarium.presentation.css" />
	<link rel="stylesheet" href="/styles/secretarium.forms.css" />
	<link rel="stylesheet" href="/styles/secretarium.alerts.css" />

	<script src="/scripts/jquery-3.3.1.min.js"></script>
	<script src="/scripts/popper-1.14.7.min.js"></script>
	<script src="/scripts/bootstrap-4.3.1.min.js"></script>
	<script src="/scripts/vue-2.6.8.min.js"></script>
	<script src="/scripts/vue-router-3.0.2.min.js"></script>
	<script src="/scripts/scrolloverflow-0.1.2.min.js"></script>
	<script src="/scripts/fullpage.vue-0.1.2.min.js"></script>
</head>

<body>
	<img src="images/secretarium_title.svg" id="stage-full-logo" />
	<img src="images/secretarium_logo_grey.svg" id="stage-logo" />
	<div id="stage"></div>

	<div id="app" @dragover.prevent @drop.prevent v-cloak>
		<header>
			<nav class="navbar sticky-top p-0">
				<div id="menu" class="container container-fluid py-2">
					<router-link class="navbar-brand logo" data-menuanchor="home" to="/#home" :class="{'hide':store.isLogoPage}"> </router-link>
					<ul id="presentation-menu" class="navbar-nav flex-row d-none d-flex">
						<li v-if="store.isPresentationPages&&!store.isLogoPage" class="nav-item py-0 px-2">
							<router-link class="nav-link" data-menuanchor="what-it-is" to="/#what-it-is">Concept</router-link>
						</li>
						<li v-if="store.isPresentationPages&&!store.isLogoPage" class="nav-item py-0 px-2 d-none d-sm-flex">
							<router-link class="nav-link" data-menuanchor="why" to="/#why">Rationale</router-link>
						</li>
						<li v-if="store.isPresentationPages&&!store.isLogoPage" class="nav-item py-0 px-2">
							<router-link class="nav-link" data-menuanchor="technology" to="/#technology">Technology</router-link>
						</li>
						<li v-if="store.isPresentationPages&&!store.isLogoPage" class="nav-item py-0 px-2">
							<router-link class="nav-link" data-menuanchor="team" to="/#team">About us</router-link>
						</li>
						<li v-if="!store.isPresentationPages" class="nav-item py-0 px-2" >
							<router-link class="nav-link" data-menuanchor="what-it-is" :to="{ path: '/', hash: 'what-it-is' }">home</router-link>
						</li>
						<li v-else-if="store.isLogoPage" class="nav-item py-0 px-2" >
							<router-link class="nav-link" data-menuanchor="what-it-is" to="/#what-it-is">learn more</router-link>
						</li>
					</ul>
					<ul id="sec-menu" class="navbar-nav flex-row ml-auto">
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
								<router-link data-menuanchor="what-it-is" to="/#what-it-is">What is Secretarium ?</router-link>
							</li>
							<li class="nav-item py-0 px-2">
								<router-link data-menuanchor="what-it-does" to="/#what-it-does">What does it provide ?</router-link>
							</li>
							<li class="nav-item py-0 px-2">
								<router-link data-menuanchor="scaling" to="/#scaling">How does it scale ?</router-link>
							</li>
						</ul>
						<ul id="sub-rationale" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<router-link data-menuanchor="why" to="/#why">Why Secretarium ?</router-link>
							</li>
							<li class="nav-item py-0 px-2">
								<router-link data-menuanchor="whom-for" to="/#whom-for">Who is it for ?</router-link>
							</li>
						</ul>
						<ul id="sub-techno" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<router-link data-menuanchor="technology" to="/#technology">How does it work ?</router-link>
							</li>
							<li class="nav-item py-0 px-2">
								<router-link data-menuanchor="secret-processing" to="/#secret-processing">Secret processing</router-link>
							</li>
							<li class="nav-item py-0 px-2">
								<router-link data-menuanchor="secret-mixing" to="/#secret-mixing">Secret mixing</router-link>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>

		<content>
			<router-view></router-view>
		</content>

		<footer v-if="!store.isPresentationPages" class="app-footer">
			<div class="container">
				<div class="row no-gutters">
					<div class="col text-muted">
							<i class="fas fa-circle" :class="[state.color]" style="font-size: 60%; vertical-align: 20%;"></i>
							<span>{{state.text}}</span>
					</div>
					<div class="col-4 text-muted text-center">© <?=date("Y")?> - Secretarium</div>
					<div class="col-4 text-muted text-right">{{connectedAs}}</div>
				</div>
			</div>
		</footer>

		<sec-alerts></sec-alerts>
	</div>

	<script type="text/x-template" id="sec-presentation">
		<full-page id="presentation" ref="presentation" :options="options">
			<div class="section" data-anchor="home">
			</div>
			<div class="section" data-anchor="what-it-is">
				<section>
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
			</div>
			<div class="section" data-anchor="what-it-does">
				<section>
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
			</div>
			<div class="section" data-anchor="scaling">
				<section>
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
			</div>
			<div class="section" data-anchor="why">
				<section>
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
			</div>
			<div class="section" data-anchor="whom-for">
				<section>
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
			</div>
			<div class="section" data-anchor="technology">
				<section>
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
			</div>
			<div class="section" data-anchor="secret-processing">
				<section>
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
			</div>
			<div class="section" data-anchor="secret-mixing">
				<section>
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
			</div>
			<div class="section" data-anchor="team">
				<section>
					<div class="container">
						<h2>About us</h2>
						<h3>A team of engineers, with deep investment banking and crypto background</h3>
						<div class="row mx-0 mt-5">
							<div class="col-md-8 pl-0">
								<h4>The secretarium team</h4>
								<br/>
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
							<div class="col-md-4 pr-0">
								<h4>Our sponsor</h4>
								<br/>
								<div class="about-item">
									<img src="/images/logo_intel.svg" class="comp-logo"/>
								</div>
								<br/>
								<br/>
								<br/>
								<br/>
								<h4>Our partner</h4>
								<br/>
								<div class="about-item">
									<img src="/images/logo_swisscom.svg" class="comp-logo"/>
								</div>
							</div>
						</div>
						<br/>
						<br/>
						<h4>Our clients</h4>
						<div class="row mx-0">
							<div class="col-md-6 pl-0">
								<br/>
								<div class="about-item">
									<img src="/images/logo_soge.svg" class="comp-logo"/>
								</div>
								<div class="about-item">
									<img src="/images/logo_ubs.svg" class="comp-logo"/>
								</div>
							</div>
							<div class="col-md-6 pr-0">
								<div class="about-item">
									<img src="/images/logo_creds.svg" class="comp-logo"/>
								</div>
								<div class="about-item">
									<img src="/images/logo_kbc.svg" class="comp-logo"/>
								</div>
							</div>
						</div>
						<br/>
						<br/>
						<br/>
						<br/>
					</div>
				</section>
			</div>
			<div class="section fp-auto-height" data-anchor="links">
				<footer>
					<div class="container py-5">
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
			</div>
		</full-page>
	</script>

	<script type="text/x-template" id="sec-alerts">
		<div v-if="alerts.length>0" id="sec-alert-wrap">
			<div v-for="(a, i) in alerts" class="sec-alert alert alert-warning fade show" role="alert"
				:class="{'alert-dismissible':a.dismissible}" :key="a.key" >
				<div v-html="a.html"></div>
				<button v-if="a.dismissible" type="button" class="close" data-dismiss="alert" aria-label="Close" @click="close(i)">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="sec-connect-header">
		<div class="text-center">
			<h4>Entrust your secrets with Secretarium.</h4>
			<p>Access to the most confidential apps in the industry.</p>
		</div>
	</script>

	<script type="text/x-template" id="sec-connect">
		<div class="container">
			<div class="form-body">
				<div class="row">
					<div class="form-holder">
						<div class="form-content">
							<div class="form-items">
								<sec-connect-header></sec-connect-header>
								<div class="card">
  									<div class="card-body">
										<form @submit.prevent>
											<div v-if="$root.keys.list.length>0">
												<h5 class="pl-0 pt-0">The following keys are present on your device</h5>
												<div class="form-group">
													<div v-for="(key, i) in $root.keys.list" :value="i" class="key-item">
														<router-link :to="`/connect/${i}/validate`" class="btn btn-info btn-sm"><i class="fas fa-key"></i><span>{{key.name}}</span></router-link>
													</div>
												</div>
												<h5 class="pl-0 pt-0">Additionnally you can also create a new key</h5>
												<router-link to="/connect/new-key" tag="button" class="btn btn-secondary btn-sm">Generate a new key</router-link>
											</div>
											<div v-else>
												<h5 class="pl-0 pt-0">No key present on your device</h5>
												<router-link to="/connect/new-key" tag="button" class="btn btn-primary btn-sm">Generate a new key</router-link>
											</div>
											<div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="sec-new-key">
		<div class="container">
			<div class="form-body">
				<div class="row">
					<div class="form-holder">
						<div class="form-content">
							<div class="form-items">	
								<sec-connect-header></sec-connect-header>							
								<div class="card">
  									<div class="card-body">
										<form @submit.prevent>
											<h5 class="pl-0 pt-0">Please enter the following information so we can generate your key</h5>
											<div class="form-group">
												<label for="createdKeyName" class="sr-only">Key name</label>
												<input type="text" class="form-control form-control-sm" id="createdKeyName" placeholder="Key name">
											</div>
											<div class="form-group">
												<label for="createdKeyPassword" class="sr-only">Password</label>
												<input type="password" class="form-control form-control-sm" id="createdKeyPassword" placeholder="Password">
											</div>
											<button type="submit" class="btn btn-primary btn-sm" @click.prevent="$root.createNewKeys">Generate</button>
											<router-link to="/connect" tag="button" class="btn btn-secondary btn-sm">Back</router-link>
											<sec-notif-state :state="$root.keys.generation.ns.data"></sec-notif-state>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</script>
	
	<script type="text/x-template" id="sec-validate-key">
		<div class="container">
			<div class="form-body">
				<div class="row">
					<div class="form-holder">
						<div class="form-content">
							<div class="form-items">
								<sec-connect-header></sec-connect-header>
								<div class="card">
  									<div class="card-body">
										<form @submit.prevent>
											<h5 class="pl-0 pt-0">Please enter the password used for securing the key {{ kid }}</h5>
											<div class="form-group">
												<label for="keyPwd" class="sr-only">Password</label>
												<input type="password" class="form-control form-control-sm" id="keyPwd" placeholder="Password">
											</div>
											<router-link to="/connect/endpoints" tag="button" class="btn btn-primary btn-sm">Validate</router-link>
											<router-link to="/connect" tag="button" class="btn btn-secondary btn-sm">Back</router-link>
											<sec-notif-state :state="$root.keys.generation.ns.data"></sec-notif-state>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="sec-endpoints">
		<div class="container">
			<div class="form-body">
				<div class="row">
					<div class="form-holder">
						<div class="form-content">
							<div class="form-items">
								<sec-connect-header></sec-connect-header>
								<div class="card">
  									<div class="card-body">
										<form @submit.prevent>
											<h5 class="pl-0 pt-0">To finalise the connect please select which endpoint you wish to connect to</h5>
											<div class="form-group">
												<div v-for="gw in $root.store.gateways" :key="gw.endpoint" class="endpoint-item">
													<button type="button" @click.prevent="$root.connect(gw.endpoint)" class="btn btn-info btn-sm"><i class="fas fa-network-wired"></i></i><span>{{gw.name}}</span></router-link>
												</div>
											</div>
											<router-link to="/connect" tag="button" class="btn btn-secondary btn-sm">Back</router-link>
										</form>
									</div>
								</div>
							</div>
						</div>
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
		const onDrop = null, onResize = {},
			store = {
				isPresentationPages: window.location.pathname == "/",
				isLogoPage: true,
				gateways: [
					{ endpoint: "wss://ovh2.node.secretarium.org:443/", name: "PROD (OVH #2)" },
					{ endpoint: "wss://ovh3.node.secretarium.org:443/", name: "PROD (OVH #3)" },
					{ endpoint: "wss://ovh5.node.secretarium.org:443/", name: "PROD (OVH #5)" },
					{ endpoint: "wss://ovh6.node.secretarium.org:443/", name: "PROD (OVH #6)" },
					{ endpoint: "wss://ovh7.node.secretarium.org:443/", name: "PROD (OVH #7)" }
				],
				dcapps: {},
				alerts: []
			};

		const Presentation = Vue.component('sec-presentation', {
			template: '#sec-presentation',
			data: () => {
				return {
					options: {
						scrollOverflow: true,
						anchors: ['home', 'what-it-is', 'what-it-does', 'scaling', 'why', 'whom-for', 'technology', 'secret-processing', 'secret-mixing', 'team', 'links'],
						licenseKey: '98AFD12E-428246E1-B7908FFE-69A37211',
						sectionsColor: ['transparent'],
						// responsiveWidth: 768,
						lockAnchors: true,
						menu: 'nav'
					},
					canvas: { redrawLoop: null, items: {}, maxDist: 0 }
				}
			},
			mounted() {
				Vue.set(this.options, "onLeave", this.onLeave);
				this.fillCanvas();
				setTimeout(() => { $("#stage .full-logo").addClass("active") }, 100);
				this.canvas.redrawLoop = setTimeout(() => { this.drawCanvas(); }, 1500);
				subscribeOnResize("presentation-logo", () => {
					$("#stage").empty();
					this.canvas.items = [];
					this.canvas.maxDist = 0;
					this.fillCanvas(true);
				});
			},
			methods: {
				onLeave (origin, destination, direction) {
					console.log('fullpage >', destination.anchor)
					let x = destination.index == 0;
					this.$root.store.isLogoPage = x;
					if(!x) {
						clearTimeout(this.canvas.redrawLoop);
						this.canvas.redrawLoop = null;
					}
					else if(this.canvas.redrawLoop == null)
						this.canvas.redrawLoop = setTimeout(() => { this.drawCanvas(); }, 1500);
					router.push({hash: destination.anchor})
					return true;
				},
				fillCanvas(onResize = false) {
					let t = $("#stage-full-logo"), l = $("#stage-logo"), w = $("#stage"),
						sx = w.width(), sy = w.height(), flx = 5906, fly = 1231, lx = 1665, ly = 1231, lhx = 650, lhy = 650,
						r = 0.1 * sy / fly, y = sy * 0.42, x = (sx - r * flx) / 2, wx = Math.floor(sx / (r * lhx) / 2),
						add = (i, j) => {
							if(i >= 0 && j >= -1 || Math.abs(j) > 12) return;
							let dist = Math.floor(Math.sqrt(i * i + j * j)), nx = x + i * r * lhx, ny = y - j * r * lhy,
								e = l.clone().removeAttr("id").addClass("stage-logo stage-" + dist)
									 .css({ left: nx, top: ny }).appendTo(w);
							if(!this.canvas.items[dist]) this.canvas.items[dist] = [];
							this.canvas.items[dist].push({ e: e, x: i, y: j, v: false });
							if(dist > this.canvas.maxDist) this.canvas.maxDist = dist;
						},
						fl = t.clone().removeAttr("id").css({ left: x, top: y }).appendTo(w)
							  .addClass("full-logo").toggleClass("active", onResize),
						ix = -wx % 3 != -1 ? -wx + wx % 3 - 1 : -wx;
					for(var i = ix; i <= wx; i+= 3) { // horizontal
						for(var j = -13; j <= 12; j+= 3) {
							add(i, j);
							add(i + 1, j + 1);
							add(i + 2, j + 2);
						}
					}
				},
				drawCanvas() {
					if(!this.$root.store.isLogoPage) return;
					for(var i in this.canvas.items) {
						this.canvas.items[i].forEach(o => {
							let r = Math.random(), show = (o.x < 0 && o.y <= 0 && r > .2) || (o.x < 0 && o.y < -o.x && r > .3) ||
								(o.x < 0 && r > .6) || (o.x >= 0 && -o.y > .5 * o.x && r > .2) || (o.x >= 0 && r > .6);
							if(o.v != show) {
								if(show) o.e.addClass("active");
								else o.e.removeClass("active");
								o.v = show;
							}
						});
					}
					if(this.$root.store.isLogoPage)
						this.canvas.redrawLoop = setTimeout(() => { this.drawCanvas(); }, 10000);
				}
			}
		});

		const Alerts = Vue.component('sec-alerts', {
			template: '#sec-alerts',
			data: () => {
				return {
					alerts: store.alerts
				}
			},
			methods: {
				close(i) {
					store.alerts.splice(i, 1);
				}
			}
		});

		const ConnectHeader = Vue.component('sec-connect-header', {
			template: '#sec-connect-header',
			data: () => {
				return {
				}
			},
			methods: {
			}
		});

		const Connect = Vue.component('sec-connect', {
			template: '#sec-connect',
			data: () => {
				return {
				}
			},
			methods: {
			}
		});

		const NewKey = Vue.component('sec-new-key', {
			template: '#sec-new-key',
			data: () => {
				return {
				}
			},
			methods: {
			}
		});

		const ValidateKey = Vue.component('sec-validate-key', {
			template: '#sec-validate-key',
			data: () => {
				return {
					kid: null
				}
			},
			methods: {
			}
		});

		const EndpointSelection = Vue.component('sec-endpoints', {
			template: '#sec-endpoints',
			data: () => {
				return {
					kid: null
				}
			},
			methods: {
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
				{ path: '/connect/:kid/validate', component: ValidateKey },
				{ path: '/connect/new-key', component: NewKey },
				{ path: '/connect/endpoints', component: EndpointSelection },
				{ path: '/app-store', component: AppStore },
				{ path: '/app/:id', component: AppAccessDenied },
			]
		});
		router.beforeEach((to, from, next) => {
			console.log('router >', to.hash);
			store.isPresentationPages = to.path == '/';
			store.isLogoPage = to.path == '/' && (['', '#', '#home'].includes(to.hash));
			$("body").toggleClass("page-presentation", store.isPresentationPages);
			next();
		});

		const app = new Vue({
			router,
			data: () => {
				return {
					store: store,
					connection: {
						endpoint: "",
						retrying: false, retryingMsg: "", retryFailures: 0, retrier: null, lastState: 0, timeoutSec: 30,
						ns: {data: {}}
					},
					keys: {
						list: [{name: 'LK_Test_002'}, {name: 'LX_Test_052'}], cryptoKeys: {}, exportUrl: "",
						generation: { key: null, ns: {data: {}} }
					},
				}
			},
			computed: {
				state() { return { text: "Not connected", color: "text-danger", icon: "fa-exclamation-circle" } },
				connectedAs() { return ""; },
				isConnected() { return false; },
				isLoggedIn() { return false; }
			},
			methods: {
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
			$("#stage").css("z-index", -1);
			$("#app").css("opacity", 1);
		});
	</script>
</body>

</html>