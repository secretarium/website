﻿<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Secretarium is a distributed confidential computing platform guaranteeing privacy by default and by design">

	<title>Secretarium - Entrusted with secrets</title>
	<link rel="icon" type="image/png" href="/images/secretarium_128x128.png">

	<link rel="stylesheet" href="/styles/bootstrap-4.3.1.min.css" />
	<link rel="stylesheet" href="/styles/fontawesome-5.7.2.all.min.css" />
	<link rel="stylesheet" href="/styles/secretarium-0.0.6.min.css" />

	<script src="/scripts/jquery-3.3.1.min.js"></script>
	<script src="/scripts/popper-1.14.7.min.js"></script>
	<script src="/scripts/bootstrap-4.3.1.min.js"></script>
	<script src="/scripts/vue-2.6.10.min.js"></script>
	<script src="/scripts/vue-router-3.0.2.min.js"></script>
	<script src="/scripts/secretarium-0.1.6.js"></script>
</head>

<body>
	<img src="/images/secretarium_title.svg" id="stage-full-logo" />
	<img src="/images/secretarium_logo_grey.svg" id="stage-logo" />
	<div id="stage"></div>

	<div id="app" @dragover.prevent @drop.prevent v-cloak>
		<div id="drop-area"></div>
		<header>
			<nav class="navbar p-0" :class="{'logo-page':store.isLogoPage, 'fixed-top':store.isPresentationPages}">
				<div id="menu" class="container-fluid py-2" :class="{container:!store.isLogoPage}">
					<router-link v-if="!store.isPresentationPages" to="/#welcome" class="navbar-brand logo"></router-link>
					<a v-else class="navbar-brand logo" href="#welcome"></a>
					<ul id="presentation-menu" class="navbar-nav flex-row d-none d-sm-flex">
						<li class="nav-item py-0 px-2">
							<a class="nav-link" href="#what-it-is">Concept</a>
						</li>
						<li class="nav-item py-0 px-2 d-none d-sm-flex">
							<a class="nav-link" href="#why">Rationale</a>
						</li>
						<li class="nav-item py-0 px-2">
							<a class="nav-link" href="#technology">Technology</a>
						</li>
					</ul>
					<ul id="sec-menu" class="navbar-nav flex-row ml-auto">
						<li v-if="store.isLogoPage" class="nav-item mr-3" >
							<a class="nav-link shift-left" href="#what-it-is">Presentation</a>
						</li>
						<li v-else-if="!store.isPresentationPages" class="nav-item mr-3" >
							<router-link to="/#what-it-is" class="nav-link shift-left">Presentation</router-link>
						</li>
						<li class="nav-item" style="margin-right: 2vw;">
							<router-link to="/demo-apps" class="nav-link">Demos</router-link>
						</li>
						<!-- <li v-if="connection.retrying" class="nav-item">
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
						</li> -->
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
							<li class="nav-item py-0 px-2">
								<a href="#vision" class="nav-link">Our story & vision</a>
							</li>
						</ul>
						<ul id="sub-techno" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<a href="#technology" class="nav-link">How does it work ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#secretive-insight" class="nav-link">Secretive insight</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#sponsors" class="nav-link">Sponsors & partners</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="#documentation" class="nav-link">Documentation</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>

		<content>
			<transition name="page" mode="out-in">
				<keep-alive include="['sec-presentation']">
					<router-view></router-view>
				</keep-alive>
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
					<div class="col-sm">
						<h5 class="footer-category">Community</h5>
						<ul>
							<li><a href="https://twitter.com/secretarium1" target="_blank">Twitter</a></li>
							<li><a href="https://www.linkedin.com/company/secretarium" target="_blank">LinkedIn</a></li>
						</ul>
					</div>
					<div class="col-sm mt-3 mt-sm-0">
						<h5 class="footer-category">Secretarium</h5>
						<ul>
							<li><a href="#">Blog</a></li>
							<li><a href="#">Privacy Notice</a></li>
							<li><a href="#">About</a></li>
						</ul>
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
			<span :title="state.global.title" :style="{ opacity: state.global.opacity||1 }" @click.prevent="state.showChain=!state.showChain">
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
								computing, Secretarium has been designed to run applications on encrypted
								data in a trustable, distributed, scalable and efficient way, with no
								possible data leakage or manipulation, and no single point of failure.
							</p>
						</div>
						<div class="col-md-6 px-0 pl-md-5 mt-5 mt-md-0">
							<div class="sec-img dcApp">
								<img src="images/figure_dcApp.svg" alt="DCApp image"/>
							</div>
							<h4 class="mb-2">Hosting Distributed Confidential Applications </h4>
							<p>
								Distributed Confidential Applications (DCApps) are smart contracts systems
								with cryptographic proof of integrity.
								End-users can grant access to subsets of their private data to DCApps, and
								DCApps can request access to user's data for pre-defined specific processing.
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
								control of their data. Secretarium uses end-to-end encryption: data
								uploaded to Secretarium remains the property of its originators and no one
								can access it in clear-text. DCApps intellectual property remains the
								property of the DCApp writer.
							</p>
						</div>
						<div class="col-md-6 px-0 pl-md-5 mt-5 mt-md-0">
							<div class="sec-img puzzle">
								<img src="images/figure_puzzle.svg" alt="easy integration image"/>
							</div>
							<h4 class="mb-2">Offers easy integration to your processes</h4>
							<p>
								Every Secretarium node is coupled with a web server. Secretarium connection
								protocol integrates easily into recent browsers and tablets.
								Secretarium is real-time and has the capacity of pushing data to end-users.
								Secretarium is designed for speed: we achieve finality of execution within a
								split second, simplifying integration in an unparalleled way for a Blockchain.
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
								compatible with real-life scenarios. We believe in a system that can
								grow organically in the same way the internet did.
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
						<div class="col-md-6 px-0 pl-md-5 mt-5 mt-md-0">
							<div class="sec-img reaper">
								<img src="images/figure_reaper.svg" alt="prevent scavenging image"/>
							</div>
							<h4 class="mb-2">Prevent scavenging and monetization of private data</h4>
							<p>
								The internet was intended for driving collaboration, but the balance between
								data originators and data aggregators has been heavily tilted toward the latter.
								Our goal is to achieve the same level of service automation without disclosing
								of private data.
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
			<section id="vision">
				<div class="container">
					<h2>Our story and vision</h2>
					<h3>From technology, pharma, banking, cryptography, and restoring privacy for all</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-6 px-0 pr-md-5">
							<h4 class="mb-2">Our story</h4>
							<p>
								Secretarium founders are engineers who have worked for many years in challenging
								environments.<br />
								In 2014, they volunteered to create a blockchain lab for a tier-one European bank.
								The numerous prototypes and studies resulting from this experience evidenced the
								unsuitability of blockchains and DLTs when faced with confidentiality, performance
								and user experience constraints.<br />
								Early 2016, they started experiments in trusted execution environments and pivoted
								into confidential computing. At the end of 2016, recognizing the much wider
								applicability of confidential computing, Secretarium was founded.<br />
								Working closely with clients, we have designed and improved our technology from real
								business cases. Starting with Fintech, we are now actively extending our application
								base to Pharma and Bio-medical, with the goal of powering privacy-respecting medical
								progress.
							</p>
						</div>
						<div class="col-md-6 px-0 pl-md-5 mt-5 mt-md-0">
							<h4 class="mb-2">Our vision and philosophy</h4>
							<p>
								Secretarium seeks to restore the right to privacy for people and companies.<br/>
								Our short term objectives are located in the Pharmatech, RegTech and InsurTech
								sectors, where we provide the infrastructure rails for secrets intermediation.<br/>
								We envision Secretarium as the safe harbour for private data. Our goal is to
								simplify the setup and integration of privacy-respecting applications. We are
								building an open ecosystem where anyone can create, host, control and monetise
								applications powered by our technology.<br/>
								We have issued a legally enforceable charter where we commit to preventing any
								usage of our technology facilitating illegal practices.<br/>
							</p>
						</div>
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
			<section id="secretive-insight">
				<div class="container">
					<h2>Secretive insight</h2>
					<h3>Build insight on data, without disclosing the data</h3>
					<div class="row mx-0 mt-4">
						<div class="col-md-6 px-0 pr-md-5">
							<div class="sec-img secretProcessing">
								<img src="images/figure_secretProcessing.svg" alt="secret processing image"/>
							</div>
							<h4 class="mb-2">Secret processing</h4>
							<p>
								Some parties have proprietary algorithms, other parties have confidential
								data-banks. Secret processing involves combining both while guaranteeing
								secrecy.
								Designed for data analytics, it can be used for genomic diagnostic, either
								by individuals, or by medical firm over the DNA bank of another firm.
								When secured with Differential Privacy techniques, it is perfectly adapted for
								data rental, a new way of monetizing confidential data.
							</p>
						</div>
						<div class="col-md-6 px-0 pl-md-5 mt-5 mt-md-0">
							<div class="sec-img secretMixing">
								<img src="images/figure_secretMixing.svg" alt="secret mixing image"/>
							</div>
							<h4 class="mb-2">Secret mixing</h4>
							<p>
								Allows a group to pool private data together and collectively achieve insight.
								Designed for data pooling and data matching, it can be used to compute market
								data benchmarks, detect fraudulent insurance claims, compare reference data.
								Other usages involve enforcement of statistical secrecy, like the pricing of
								insurance policies on real data.
								Finally, it is a solution to the voting problem, Secretarium being able to
								enforce voting secrecy, voting protocol and a guaranteed honest outcome.
							</p>
						</div>
					</div>
				</div>
			</section>
			<section id="sponsors">
				<div class="container">
					<h2>Our sponsors and partners</h2>
					<h3>Reputable partners are helping us growing</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-4 px-0 pr-0 pr-md-4">
							<h4 class="mb-3">Intel Corporation</h4>
							<div class="comp-logo">
								<img src="/images/logo_intel.svg"/>
							</div>
							<p class="mt-3">
								Intel supports Secretarium with sponsored hardware, early access,
								support with engineers, infrastructure and sales teams.
							</p>
						</div>
						<div class="col-md-4 px-0 px-md-4 mt-5 mt-md-0">
							<h4 class="mb-3">Swisscom Blockchain</h4>
							<div class="comp-logo">
								<img src="/images/logo_swisscom_blockchain.jpg"/>
							</div>
							<p class="mt-3">
								Swisscom Blockchain supports Secretarium with engineers, business developers,
								infrastructure. Our partnership includes a comprehensive joined go-to-market
								agreement, as well as a mutually approach to engage with large institutions.
							</p>
						</div>
						<div class="col-md-4 px-0 pl-0 pl-md-4 mt-5 mt-md-0">
							<h4 class="mb-3">Société Générale</h4>
							<div class="comp-logo">
								<img src="/images/logo_soge.svg"/>
							</div>
							<p class="mt-3">
								Société Générale UK hosts Secretarium in its London incubator “the Greenhouse”.
								This partnership grants us access to Société Générale's business leaders,
								influencers and specialists.
							</p>
						</div>
					</div>
				</div>
			</section>
			<section id="documentation">
				<div class="container">
					<h2>Documentation and Contacts</h2>
					<h3>Please get in touch</h3>
					<div class="row mx-0 mt-5">
						<div class="col-md-6 px-0 pr-md-5">
							<h4 class="mb-4">Documentation</h4>
							<p>
								Please provide a short summary of your interest in Secretarium and we will happily provide a copy of our papers.
							</p>
							<form class="form-sec mt-3" @submit.prevent>
								<input type="email" name="email" class="form-control form-control-sm" placeholder="Your professional/academic email">
    							<textarea name="interest" class="form-control form-control-sm my-2" rows="4"
									v-model="message" placeholder="Please detail who you are and a summary of your interest"></textarea>
								<span class="float-right text-black-50 small" style="margin-top: -.4rem;">{{message.length==0||message.length>=50?'':'at least 50 chars'}}</span>
								<div class="custom-control custom-checkbox checkbox-sec"
									v-for="(f) in documents" :key="f.name">
									<input type="checkbox" name="documents" class="custom-control-input form-control-sm" :id="'ckbx-'+f.id" :value="f.id">
									<label class="custom-control-label form-control-sm" :for="'ckbx-'+f.id">{{f.name}}</label>
								</div>
								<div class="mt-2">
									<button type="submit" class="btn btn-sec" @click.prevent="requestDocs">Submit</button>
									<sec-notif-state :state="docsNs.data" class="pl-3"></sec-notif-state>
								</div>
							</form>
						</div>
						<div class="col-md-6 px-0 pl-md-5 mt-5 mt-md-0">
							<h4 class="mb-4">Get in touch</h4>
							<div class="row mx-0 contact">
								<div class="col-sm pb-3">
									<h5>Contact us</h5>
									<p class="pl-3 mb-1" style="min-width: 7rem;">
										<a href="https://secretarium.slack.com" target="_blank">
											<img src="/images/Slack_Mark-120x120-3b17743.png">
											<span>on Slack</span>
										</a>
									</p>
									<p class="pl-3 mb-1" style="min-width: 7rem;">
										<a href="https://github.com/Secretarium" target="_blank">
											<img src="/images/GitHub-Mark-64px.png">
											<span>on GitHub</span>
										</a>
									</p>
									<p class="pl-3 mb-1" style="min-width: 7rem;">
										<a href="mailto:contact@secretarium.org" target="_blank">
											<i class="fas fa-envelope"></i>
											<span>by email</span>
										</a>
									</p>
								</div>
								<div class="col-sm pb-3">
									<h5>Visit us</h5>
									<p class="pl-3" style="min-width: 12rem;">
										Société Générale Incubator<br/>
										The Greenhouse, 6th floor<br/>
										41 Tower Hill,<br/>
										EC3N 4SG, London, UK
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<a class="go-to-next d-none d-sm-inline" href="#next" @click.prevent="goToNext"><i class="fas fa-arrow-down"></i></a>
		</div>
	</script>

	<script type="text/x-template" id="sec-key-loader">
		<div class="container fixed-center mw-md">
			<div class="card card-sec mw-md border-0">
				<div class="card-header">
					<h4>Entrust your secrets with Secretarium</h4>
					<p class="mb-0">Access to the most privacy-respecting apps in the industry</p>
				</div>
				<div class="card-body">
					<router-view></router-view>
				</div>
			</div>
		</div>
	</script>
	<script type="text/x-template" id="sec-key-picker">
		<div id="key-picker">
			<div v-if="$root.keysManager.keys.length>0">
				<div class="py-2">
					<h6 class="card-title">Choose a key</h6>
					<p class="mt-3">The following keys are present on your device</p>
					<div class="form-row sec-key"
						v-for="(key, i) in $root.keysManager.keys" :key="key.name">
						<div class="col">
							<button class="btn btn-sec text-left" @click.prevent="onPick(key, i)">
								{{key.name}}
								<i v-if="key.encrypted" class="fas fa-key fa-flip-both pr-2 text-warning"></i>
							</button>
						</div>
						<div class="col-auto">
							<router-link :to="'/key/manage/'+i" class="btn btn-secondary">
								<i class="fas fa-cogs"></i>
							</router-link>
						</div>
					</div>
				</div>
				<hr class="my-4 sec" />
				<sec-key-create class="hide-desc"></sec-key-create>
				<hr class="my-4 sec" />
				<sec-key-browse></sec-key-browse>
			</div>
			<div v-else>
				<sec-key-create></sec-key-create>
				<hr class="mt-4 mb-3 sec" />
				<sec-key-browse></sec-key-browse>
			</div>
		</div>
	</script>
	<script type="text/x-template" id="sec-key-decrypt">
		<div class="py-2">
			<h6 class="card-title mb-3">Decrypt "{{key.name}}"</h6>
			<p class="card-text">
				Please enter the password used for securing the key
			</p>
			<form class="form-sec" @submit.prevent>
				<div class="form-row">
					<div class="col-sm">
						<input id="ckPwd" type="password" class="form-control" placeholder="Password" autocomplete="current-password">
					</div>
					<div class="col-sm-auto mt-3 mt-sm-0">
						<button type="submit" class="btn btn-sec" @click.prevent="decryptKey">
							<i class="fas fa-fw fa-lock pr-3"></i> Decrypt
						</button>
						<sec-notif-state :state="decryptionNs.data" class="pl-3 d-sm-none"></sec-notif-state>
					</div>
				</div>
				<sec-notif-state :state="decryptionNs.data" class="mt-2 d-none d-sm-block"></sec-notif-state>
			</form>
		</div>
	</script>
	<script type="text/x-template" id="sec-key-create">
		<div class="py-2">
			<h6 class="card-title mb-3">Create a new key</h6>
			<p class="card-text">
				A new key will be generated, locally in your browser.<br />
				It will allow authentication when interacting with the Secretarium platform.
			</p>
			<form class="form-sec" @submit.prevent>
				<div class="form-row">
					<div class="col-sm">
						<input id="ckName" type="text" class="form-control" placeholder="Key name">
					</div>
					<div class="col-sm-auto mt-3 mt-sm-0">
						<button type="submit" class="btn btn-sec" @click.prevent="createKey">Generate a new key</button>
						<sec-notif-state :state="generationNs.data" class="pl-3 d-sm-none"></sec-notif-state>
					</div>
				</div>
				<sec-notif-state :state="generationNs.data" class="mt-2 d-none d-sm-block"></sec-notif-state>
			</form>
		</div>
	</script>
	<script type="text/x-template" id="sec-key-browse">
		<div>
			<p class="card-text">
				Alternatively drag and drop a key or
				<label for="sec-loadkey-file" class="btn btn-link p-0 text-sec">browse from disk</label>
				<input type="file" id="sec-loadkey-file" accept=".secretarium" class="d-none" @change="onKeyFile" />
			</p>
		</div>
	</script>
	<script type="text/x-template" id="sec-key-manage">
		<div>
			<div class="py-2">
				<p class="card-text border rounded-sm bg-light p-2 fs-85">
					<strong>Key Name</strong>: "{{key.name}}"<br />
					<strong>Public Key</strong>: {{publicKeyStr}}
				</p>
			</div>
			<hr class="my-3 sec" />
			<div class="py-2">
				<h6 class="card-title" :class="{'mb-0':key.encrypted}"
					data-toggle="collapse" data-target="#sec-key-manage-encrypt-collapse"
					:aria-expanded="!key.encrypted" aria-controls="sec-key-manage-encrypt-collapse">
					Encrypt your key
					<i class="fas fa-chevron-down float-right" v-if="key.encrypted"></i>
				</h6>
				<div class="mt-3 collapse" id="sec-key-manage-encrypt-collapse" :class="{'show':!key.encrypted}">
					<p class="card-text">
						To safely store your key, please choose a strong password
					</p>
					<form class="form-sec" @submit.prevent>
						<div class="form-row">
							<div class="col-sm">
								<input id="ckPwd" type="password" class="form-control" placeholder="Password" autocomplete="current-password">
							</div>
							<div class="col-sm-auto mt-3 mt-sm-0">
								<button type="submit" class="btn btn-sec" @click.prevent="encryptKey">
									<i class="fas fa-fw fa-lock pr-3"></i> Encrypt
								</button>
								<sec-notif-state :state="encryptionNs.data" class="pl-3 d-sm-none"></sec-notif-state>
							</div>
						</div>
						<sec-notif-state :state="encryptionNs.data" class="mt-2 d-none d-sm-block"></sec-notif-state>
					</form>
				</div>
			</div>
			<hr class="my-3 sec" />
			<div class="py-2">
				<h6 class="card-title">Export your key</h6>
				<p class="card-text mt-3">
					Export your key to back it up locally, or on a secure hardware.
				</p>
				<form class="form-inline" @submit.prevent>
					<div v-if="key.encrypted" class="custom-control custom-checkbox checkbox-lg checkbox-sec ml-0 mr-3 mb-3 mb-sm-0">
						<input type="checkbox" class="custom-control-input" id="ckExportEncrypted" :checked="key.encrypted">
						<label class="custom-control-label" for="ckExportEncrypted">Export encrypted</label>
					</div>
					<a class="btn btn-sec" :href="key.exportUrl"
						:download="key.name+'.secretarium'">
						<i class="fas fa-fw fa-download pr-3"></i> Export
					</a>
				</form>
			</div>
			<div v-if="$root.canStore">
				<hr class="my-3 sec" />
				<div class="py-2">
					<h6 class="card-title">Save in this browser</h6>
					<p class="card-text mt-3">If you trust this machine, save your key in this browser to ease future connections.</p>
					<form class="form-inline" @submit.prevent>
						<div v-if="key.encrypted" class="custom-control custom-checkbox checkbox-lg checkbox-sec ml-0 mr-3 mb-3 mb-sm-0">
							<input type="checkbox" class="custom-control-input" id="ckSaveEncrypted" :checked="key.encrypted">
							<label class="custom-control-label" for="ckSaveEncrypted">Save encrypted</label>
						</div>
						<button type="button" class="btn btn-sec mr-3" :disabled="key.name.length==0" @click.prevent="saveKey">
							<i class="fas fa-fw fa-save pr-3"></i> Save
						</button>
						<sec-notif-state :state="saveNs.data"></sec-notif-state>
					</form>
				</div>
			</div>
			<div v-if="key.saved">
				<hr class="my-3 sec" />
				<div class="py-2">
					<h6 class="card-title">Delete</h6>
					<p class="card-text mt-3">
						Remove this key from this browser.
					</p>
					<button type="button" class="btn btn-sec mr-3" @click.prevent="deleteKey">
						<i class="fas fa-fw fa-trash-alt pr-3"></i> Delete
					</button>
					<sec-notif-state :state="deleteNs.data"></sec-notif-state>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-template" id="sec-identity">
		<div class="container fixed-center mw-md">
			<div class="card card-sec mw-md border-0">
				<div class="card-header">
					<h4>Entrust your secrets with Secretarium</h4>
					<p class="mb-0">Information below cannot be accessed without your consent</p>
				</div>
				<div class="card-body">
					<div v-if="isBlankProfile" class="py-2">
						<h6 class="card-title mb-3">Lets become identifiable</h6>
						<p class="card-text">
							Register personal records, get them verified by Secretarium, and use them as identity proofs.
						</p>
						<button class="btn btn-sec" @click.prevent="start">Start</button>
					</div>
					<div v-else>
						<div class="py-2">
							<h6 class="card-title" :class="{'mb-0':identityInfo.updated}"
								data-toggle="collapse" data-target="#sec-identity-me-collapse"
								:aria-expanded="!identityInfo.updated" aria-controls="sec-identity-me-collapse">
								Identity information
								<i class="fas fa-chevron-down float-right" v-if="identityInfo.updated"></i>
							</h6>
							<div class="mt-3 collapse" id="sec-identity-me-collapse" :class="{'show':!identityInfo.updated}">
								<form @submit.prevent>
									<div class="form-group">
										<label for="prFirstName">First name</label>
										<input type="text" class="form-control" id="prFirstName" placeholder="your first name" :value="firstname">
									</div>
									<div class="form-group">
										<label for="prLastName">Last name</label>
										<input type="text" class="form-control" id="prLastName" placeholder="your last name" :value="lastname">
									</div>
									<div>
										<button type="button" class="btn btn-sec" @click.prevent="save">Save</button>
										<sec-notif-state :state="identityInfo.ns.data"></sec-notif-state>
									</div>
								</form>
							</div>
						</div>
						<hr class="my-3 sec" />
						<div class="py-2">
							<h6 class="card-title" :class="{'mb-0':personalrecord.updated}"
								data-toggle="collapse" data-target="#sec-identity-pr-collapse"
								:aria-expanded="!personalrecord.updated" aria-controls="sec-identity-pr-collapse">
								Personal records
								<i class="fas fa-chevron-down float-right" v-if="personalrecord.updated"></i>
							</h6>
							<div class="mt-3 collapse" id="sec-identity-pr-collapse" :class="{'show':!personalrecord.updated}">
								<sec-identity-personal-record
									:name="'phone'" :placeholder="'+44 7 111 222 333'"
									:help="'Please enter your phone number with its international extension (+x).'"></sec-identity-personal-record>
								<sec-identity-personal-record class="mt-3"
									:name="'email'" :placeholder="'you@example.com'"></sec-identity-personal-record>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</script>
	<script type="text/x-template" id="sec-identity-personal-record">
		<form class="form-sec" @submit.prevent>
			<label :for="'id-pr-'+name">Your {{name}}</label>
			<div class="form-row">
				<div class="col-sm">
					<input type="text" class="form-control" :class="{'border-right-0':record.verified}" :id="'id-pr-'+name"
						:aria-describedby="'id-pr-help-'+name" :placeholder="placeholder" :value="record.value" required>
					<div v-if="record.verified" class="input-group-append">
						<span class="input-group-text bg-white">
							<i  class="fas fa-check-circle text-primary"></i>
						</span>
					</div>
					<small v-if="help" :id="'id-pr-help-'+name" class="form-text text-muted">{{help}}</small>
				</div>
				<div class="col-sm-auto mt-3 mt-sm-0">
					<button type="button" class="btn btn-sec" @click.prevent="send">Send {{newCode?'new':''}} security code</button>
					<sec-notif-state :state="sendCodeNs.data" class="pl-3 d-sm-none"></sec-notif-state>
				</div>
			</div>
			<sec-notif-state :state="sendCodeNs.data" class="mt-2 d-none d-sm-block"></sec-notif-state>
			<div v-if="newCode" class="form-row mt-3">
				<div class="col-sm-4">
					<label :for="'id-pr-code-'+name" class="sr-only">Security code</label>
					<input type="text" class="form-control" :id="'id-pr-code-'+name" placeholder="security code" required>
				</div>
				<div class="col-sm-8">
					<button type="button" class="btn btn-sec" @click.prevent="verify">Verify</button>
					<sec-notif-state :state="verifyNs.data"></sec-notif-state>
				</div>
			</div>
			<div v-else-if="record.verified" class="mt-3">
				<p class="card-text pt-2">Your {{name}} was successfully verified</p>
			</div>
		</form>
	</script>

	<script type="text/x-template" id="sec-demo-apps">
		<div class="container fixed-center mw-md">
			<div class="card card-sec mw-md border-0">
				<div class="card-header">
					<h4>Entrust your secrets with Secretarium</h4>
					<p class="mb-0">Access to the most privacy-respecting apps in the industry</p>
				</div>
				<div class="card-body">
					<router-link class="dcapp-pres" v-for="app in $root.store.dcapps" :key="app.name" :to="'/demo/'+app.name" tag="div">
						<div>
							<i class="fas fa-fw mr-2 text-sec" :class="[app.icon]"></i>
							{{app.display}}
						</div>
						<p class="m-0 mt-2">{{app.description}}</p>
					</router-link>
					<div v-if="Object.keys($root.store.dcapps).length==0">
						{{loaderMsg}}
					</div>
				</div>
			</div>
		</div>
	</script>
	<script type="text/x-template" id="sec-connect">
		<div class="container fixed-center mw-md">
			<div class="card card-sec mw-md border-0">
				<div class="card-header">
					<h4>Entrust your secrets with Secretarium</h4>
					<p class="mb-0">Access to the most privacy-respecting apps in the industry</p>
				</div>
				<div class="card-body">
					<div class="py-2">
						<h6 class="card-title mb-3">Connect to "{{dcapp.display}}"</h6>
						<p class="card-text">
							Please choose the node you would like to use
						</p>
						<form class="form-sec" @submit.prevent>
							<div class="form-row">
								<div class="col-sm">
									<select id="id-connect" class="form-control">
										<option v-for="(g, i) in dcapp.gateways" :value="i">{{g.name}}</option>
									</select>
								</div>
								<div class="col-sm-auto mt-3 mt-sm-0">
									<button type="submit" class="btn btn-sec" @click.prevent="connect">Connect</button>
									<sec-notif-state :state="connectionNs.data" class="pl-3 d-sm-none"></sec-notif-state>
								</div>
							</div>
							<sec-notif-state :state="connectionNs.data" class="mt-2 d-none d-sm-block"></sec-notif-state>
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
				// only interested in 0.5 but sections can be taller than viewport
			}, { threshold: [0.2, 0.3, 0.4, 0.5] });
		const onResize = {},
			store = {
				user: {
					ECDSA: null,
					ECDSAPubHex: null,
					dcapps: { identity: { data: { personalRecords: {} } } }
				},
				isPresentationPages: window.location.pathname == "/",
				isLogoPage: window.location.pathname == "/" && (window.location.hash.length == 0 || window.location.hash == "#welcome"),
				SCPs: {},
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
					if(store.isLogoPage) {
						stop();
						start(10000);
					}
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
					stop();
					stage.empty();
					items = {};
					maxDist = 0;
					fill(true);
					start(100);
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
				this.data.msg = showMsg ? (msg != "" ? msg : state) : "";
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
						["why", "whom-for", "vision"],
						["technology", "secretive-insight", "sponsors", "documentation"]
					],
					documents: [
						{ id: "presentation", name: "Presentation (15 slides)" },
						{ id: "short-presentation", name: "Short presentation (2 pages)" },
						{ id: "short-technical-presentation", name: "Short presentation - technical (2 pages)" },
						{ id: "white-paper", name: "White paper (49 pages)" },
						{ id: "secure-connection-protocol", name: "Secure Connection Protocol (15 pages)" },
					],
					scrolledIn: '',
					message: "",
					docsNs: new notifState(),
				}
			},
			mounted() {
				subscribeOnScroll("presentation", (entries, observer) => {
					entries.forEach(entry => {
						if(entry.isIntersecting && entry.target.id &&
						   (entry.intersectionRatio >= .5 || entry.intersectionRect.height/entry.rootBounds.height>=.5) &&
						   this.scrolledIn != entry.target.id)
								this.onScroll(this.scrolledIn = entry.target.id);
					});
				});
				document.querySelectorAll('#presentation>section').forEach(target => {
					scrollObserver.observe(target);
				});
				setTimeout(() => { $("a.go-to-next").css("opacity", 1); }, 3000);

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
					$("#presentation>a.go-to-next").toggleClass("d-sm-inline", id != "documentation");
				},
				goToNext(e) {
					var s = window.scrollY, r = $("#presentation")[0].scrollHeight,
						h = window.innerHeight,
						p = $("#presentation section").length,
						x = 1 + Math.round(p * s / r);
					window.scrollTo(0, x * h);
				},
				requestDocs(e) {
					this.docsNs.start("Requesting...", true);
					let o = $(e.target).closest("form").serializeObject();
					$.post("/services/", { type: "user.request-docs", data: o }, "json")
						.done(x => {
							if(x.success) this.docsNs.executed("Success", true);
							else this.docsNs.failed("Unable to request documents: " + x.message, true);
						})
						.fail((j, t, e) => {
							this.docsNs.failed("Unable to request documents: " + e, true);
						});
				}
			}
		});

		const KeyLoader = Vue.component('sec-key-loader', {
			template: '#sec-key-loader',
			data: function () { return { referrer: null } },
			beforeRouteEnter(to, from, next) { next(self => { self.referrer = {...from}; }); }
		});
		const KeyPicker = Vue.component('sec-key-picker', {
			template: '#sec-key-picker',
			methods: {
				onPick(key, id) {
					if(key.ready) {
						this.$root.setKey(key);
						router.push(this.$parent.referrer);
					}
					else router.push({ name: 'key-decrypt', params : { id: id }});
				}
			}
		});
		const KeyDecrypt = Vue.component('sec-key-decrypt', {
			template: '#sec-key-decrypt',
			props: ['id'],
			data: () => {
				return {
					decryptionNs: new notifState(),
				}
			},
			computed: {
				key() { return this.$root.keysManager.keys[this.id]; }
			},
			methods: {
				async decryptKey() {
					this.decryptionNs.processing("Decrypting key", true);
					let pwd = $('#ckPwd').val();
					if(pwd.length < 1) { this.decryptionNs.failed("invalid password", true); return; }
					try {
						await this.$root.keysManager.decryptKey(this.key, pwd);
						this.$root.setKey(this.key);
						this.decryptionNs.executed("Success", true).hide(1500);
						router.push(this.$parent.referrer);
					} catch (err) {
						this.decryptionNs.failed(err, true);
					}
				}
			}
		});
		const KeyCreate = Vue.component('sec-key-create', {
			template: '#sec-key-create',
			data: () => {
				return {
					generationNs: new notifState()
				}
			},
			methods: {
				async createKey() {
					this.generationNs.processing("Creating key", true);
					let name = $('#ckName').val();
					if(name.length < 1) {
						this.generationNs.failed("invalid key name", true);
						return;
					}
					try {
						await this.$root.keysManager.createKey(name);
						this.generationNs.executed("Success", true).hide(1500);
						let id = this.$root.keysManager.find(name);
						router.push({ name: 'key-manage', params : { id: id }});
					}
					catch(e) {
						this.generationNs.failed(e, true);
					}
				}
			}
		});
		const KeyBrowse = Vue.component('sec-key-browse', {
			template: '#sec-key-browse',
			mounted() {
				setOnDrop(this.onKeyFile);
			},
			beforeDestroy() {
				setOnDrop(null);
			},
			methods: {
				onKeyFile(evt) {
					this.$root.keysManager.importKeyFile(evt)
					.catch(err => {
						alerts.push({ key: "invalid-key-file", isError: true, html: err });
					})
				}
			}
		});
		const KeyManage = Vue.component('sec-key-manage', {
			template: '#sec-key-manage',
			props: ['id'],
			data: () => {
				return {
					encryptionNs: new notifState(),
					saveNs: new notifState(),
					deleteNs: new notifState()
				}
			},
			computed: {
				key() { return this.$root.keysManager.keys[this.id] || { name: '--deleted--' }; },
				publicKeyStr() {
					if(this.key.encrypted) return "(encrypted)";
					if(this.key.name == "--deleted--") return "(deleted)";
					return this.$root.keysManager.getPublicKeyHex(this.key, " ").toUpperCase();
				}
			},
			methods: {
				async encryptKey() {
					this.encryptionNs.processing("Encrypting key", true);
					let pwd = $('#ckPwd').val();
					if(pwd.length < 1) { this.encryptionNs.failed("invalid password", true); return; }
					try {
						await this.$root.keysManager.encryptKey(this.key, pwd);
						this.encryptionNs.executed("Success", true).hide(1500);
					} catch (err) {
						this.encryptionNs.failed(err, true);
					}
				},
				saveKey() {
					this.saveNs.processing();
					this.key.save = true;
					this.$root.keysManager.save();
					this.saveNs.executed().hide();
				},
				deleteKey() {
					this.deleteNs.processing("Deleting key", true);
					this.$root.keysManager.removeKey(this.key.name);
					this.deleteNs.executed("Success", true).hide(1500);
					router.push("/key/load");
				}
			}
		});

		const Identity = Vue.component('sec-identity', {
			template: '#sec-identity',
			data: () => {
				return {
					started: false,
					identityInfo: { updated: false, ns: new notifState() },
					personalrecord: { updated: false, ns: new notifState() },
				}
			},
			computed: {
				firstname() { return store.user.dcapps.identity.data.firstname || ""; },
				lastname() { return store.user.dcapps.identity.data.lastname || ""; },
				isBlankProfile() { return !this.started && this.firstname != "" && this.lastname != ""; }
			},
			methods: {
				start() { this.started = true; },
				save() {
					this.started = true;
				}
			}
		});
		const IdentityPersonalRecord = Vue.component('sec-identity-personal-record', {
			template: '#sec-identity-personal-record',
			props: ["name", "placeholder", "help"],
			data: () => {
				return {
					newCode: false,
					sendCodeNs: new notifState(2),
					verifyNs: new notifState(2)
				}
			},
			computed: {
				record() { return store.user.dcapps.identity.data.personalRecords[this.name] || {}; }
			},
			methods: {
				send() {
					let name = this.name, value = $('#id-pr-' + name).val(), args = { name: name, value: value };
					this.sendCodeNs.start();
					store.SCPs["sec-demo-1"]
						.sendTx("identity", "set-personal-record", "identity-set-personal-record-" + name, args)
						.onError(x => { this.sendCodeNs.failed(x, true); })
						.onAcknowledged(x => { this.sendCodeNs.acknowledged(); })
						.onProposed(x => { this.sendCodeNs.proposed(); })
						.onCommitted(x => { this.sendCodeNs.committed(); })
						.onExecuted(x => {
							this.sendCodeNs.executed();
							let cmd = "send-personal-record-challenge";
							store.SCPs["sec-demo-1"]
								.sendQuery("identity", cmd, "identity-" + cmd + "-" + name, { name: name })
								.onError(x => { this.sendCodeNs.failed(x, true); })
								.onResult(x => {
									this.sendCodeNs.executed().hide();
									Vue.set(store.user.dcapps.identity.data.personalRecords, name, { value: value, verified: false });
								});
						});
				},
				verify() {
					let name = this.name, code = $('#id-pr-code-' + name).val().toUpperCase(),
						args = { name: name, code: code };
					this.verifyNs.start();
					store.SCPs["sec-demo-1"]
						.sendTx("identity", "verify-personal-record", "identity-verify-personal-record-" + name, args)
						.onError(x => { this.verifyNs.failed(x, true); })
						.onAcknowledged(x => { this.verifyNs.acknowledged(); })
						.onProposed(x => { this.verifyNs.proposed(); })
						.onCommitted(x => { this.verifyNs.committed(); })
						.onExecuted(x => {
							this.verifyNs.executed();
							store.SCPs["sec-demo-1"].sendQuery("identity", "get", "identity-get")
								.onResult(x => { Vue.set(store.user.dcapps.identity, "data", x); })
								.onError(x => { this.verifyNs.failed(x, true); })
								.onResult(x => {
									if(x.personalRecords[name].verified)
										this.verifyNs.executed().hide();
									else
										this.verifyNs.failed("invalid security code", true);
								});
						});
				}
			}
		});

		const DemoApps = Vue.component('sec-demo-apps', {
			template: '#sec-demo-apps',
			data: () => {
				return {
					loaderMsg: "Loading App list..."
				}
			},
			created() {
				if(Object.keys(store.dcapps).length == 0) {
					return $.getJSON("/dcapps-demo/")
						.done(async x => {
							for (var name in x) { x[name].id = await sec.utils.hashBase64(name); }
							Vue.set(store, "dcapps", x);
						})
						.fail((j, t, e) => { this.loaderMsg = "Could not load the demo applications list: " + e; });
				}
			}
		});
		const DemoLoader = Vue.component('sec-demo-loader', {
			template: '<div></div>',
			props: ["name"],
			data: () => {
				return {
					loaderMsg: "Loading App..."
				}
			},
			computed: {
				dcapp() { return store.dcapps[this.name]; }
			},
			created() {
				if(store.user.ECDSA == null)
					router.push("/key"); // key not loaded yet
				else if(!this.dcapp || !store.SCPs[this.dcapp.network])
					router.push("/connect/" + this.name); // not connected yet
				else {
					if(!this.dcapp.loaded) { // we need to load the app code
						$.get("/dcapps-demo/" + this.name + ".html")
							.done(data => {
								$("body").append(data); // will register new view and route
								this.dcapp.loaded = true;
								router.push("/" + this.name);
							})
							.fail((j, t, e) => { this.loaderMsg = "Unable to load the app: " + e });
					} else {
						router.push("/" + this.name);
					}
				}
			}
		});
		const Connect = Vue.component('sec-connect', {
			template: '#sec-connect',
			props: ["name"],
			data: () => {
				return {
					referrer: null,
					connectionNs: new notifState()
				}
			},
			beforeRouteEnter(to, from, next) { next(self => { self.referrer = {...from}; }); },
			computed: {
				dcapp() { return store.dcapps[this.name]; }
			},
			methods: {
				connect() {
					let x = $("#id-connect").val(),
						gateway = this.dcapp.gateways[x];
					this.connectionNs.processing("Connecting...", true);
					this.$root.connect(this.dcapp, gateway.endpoint)
						.then(() => { router.push(this.referrer); })
						.catch((e) => { this.connectionNs.failed(e, true); });
				}
			}
		});

		const AppAccessDenied = Vue.component('sec-app-access-denied', {
			template: '#sec-app-access-denied'
		});

		const router = new VueRouter({
			mode: 'history',
			routes: [
				{ path: '/', component: Presentation },
				{ path: '/key', component: KeyLoader,
					children: [
						{ path: '', redirect: 'load' },
						{ path: 'load', component: KeyPicker },
						{ path: 'decrypt/:id', component: KeyDecrypt, name: 'key-decrypt', props: true },
						{ path: 'manage/:id', component: KeyManage, name: 'key-manage', props: true }
					]
				},
				{ path: '/me', component: Identity },
				{ path: '/demo-apps', component: DemoApps },
				{ path: '/demo/:name', component: DemoLoader, name: 'demo-loader', props: true },
				{ path: '/connect/:name', component: Connect, name: 'connect', props: true },
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
					canStore: sec.utils.localStorage.canUse,
					store: store,
					connections: {},
					keysManager: new secretarium.keysManager(),
				}
			},
			created() {
				try {
					this.keysManager.init().catch(e => { throw e });
				} catch (e) {
					alerts.push({ key: "invalid-stored-keys", isError: true, html: "Could not load stored keys: " + e });
				}
			},
			computed: {
				state() { return { text: "not connected", color: "text-danger", icon: "fa-exclamation-circle" } },
				connectedAs() { return ""; },
				isConnected() { return false; },
				isLoggedIn() { return false; },
			},
			methods: {
				setKey(key) {
					store.user.ECDSA = key.cryptoKey;
					store.user.ECDSAPubHex = this.$root.keysManager.getPublicKeyHex(key, " ").toUpperCase();
				},
				retryConnection(dcapp) {
					let scp = store.SCPs[dcapp.network];
					if(scp.securityState < 2)
						return; // already connected or connecting

					let connection = this.connections[dcapp.network];
					connection.retrying = true;

					let countDowner = (t) => {
							if(--t > 0) {
								connection.retryingMsg = "Connection dropped - retrying in " + t + " sec";
								connection.retrier = setTimeout(() => countDowner(t), 1000);
							}
							else {
								connection.retryingMsg = "Connection dropped - retrying now";
								setTimeout(() => this.connect(dcapp, connection.endpoint), 0);
							}
						},
						timeout = 30 * 2^Math.min(connection.retryFailures++, 4);
					connection.timer = setTimeout(() => countDowner(timeout), 0);
				},
				connect(dcapp, endpoint = "") {
					if(store.user.ECDSA == null)
						throw "User key not loaded";
					if(endpoint == "")
						endpoint = dcapp.gateways[0].endpoint;

					let connection = this.connections[dcapp.network];
					if(!connection) {
						connection = {
							endpoint: endpoint, lastState: 0, timer: null,
							retrying: false, retryFailures: 0, retryingMsg: ""
						};
						Vue.set(this.connections, dcapp.network, connection);
					}

					let scp = store.SCPs[dcapp.network];
					if(!scp) {
						scp = Vue.set(store.SCPs, dcapp.network, new secretarium.scp());
					} else {
						if (scp.socket.state < 2 && scp.securityState < 3)
							return true;
						else { // retrying
							clearTimeout(connection.timer);
							if(connection.retrying) {
								connection.retryingMsg = "Connection dropped - retrying now";
								connection.retryFailures = 0;
							}
						}
					}

					return new Promise((resolve, reject) => {
						scp.reset()
							.on("statechange", x => {
								if(connection.lastState != 0 && x == 0) // connection dropped
									this.retryConnection(dcapp);
								connection.lastState = x;
							})
							.connect(endpoint, store.user.ECDSA, sec.utils.base64ToUint8Array(dcapp.key), "pair1")
							.then(() => {
								connection.retrying = false;
								connection.retryingMsg = "";
								connection.retryFailures = 0;
								resolve();
							})
							.catch(e => {
								this.retryConnection(dcapp);
								reject(e);
							});
					});
				},
				disconnect(dcapp) {
					let scp = store.SCPs[dcapp.network],
						connection = this.connections[dcapp.network];
					if(!scp) return;
					scp.close();
					clearTimeout(connection.timer);
				},
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
					if(e.dataTransfer && e.dataTransfer.files)
						onDrop(e);
					else if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files)
						onDrop(e.originalEvent);
				}
			});

			(() => {
				var resizeTimeout = null;
				$(window).resize(function() {
					if (resizeTimeout !== null) clearTimeout(resizeTimeout);
					resizeTimeout = setTimeout(() => {
						resizeTimeout = null;
						for(let id in onResize) {
							onResize[id]();
						}
					}, 100);
				});
			})();
			$.fn.serializeObject = function () {
				var o = {}, a = this.serializeArray();
				$.each(a, function () {
					if (o[this.name] !== undefined) {
						if (!o[this.name].push) {
							o[this.name] = [o[this.name]];
						}
						o[this.name].push(this.value || '');
					} else {
						o[this.name] = this.value || '';
					}
				});
				return o;
			};

			$("#stage").css("z-index", -1);
			$("#app").css("opacity", 1);
		});
	</script>
</body>

</html>