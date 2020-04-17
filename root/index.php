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
	<link rel="stylesheet" href="/styles/secretarium-0.0.15.min.css" />

	<script src="/scripts/jquery-3.3.1.min.js"></script>
	<script src="/scripts/popper-1.14.7.min.js"></script>
	<script src="/scripts/bootstrap-4.3.1.min.js"></script>
	<script src="/scripts/vue-2.6.10.js"></script>
	<script src="/scripts/vue-router-3.0.2.min.js"></script>
</head>

<body class="page-presentation">
	<img src="/images/secretarium_title.svg" id="stage-full-logo" />
	<img src="/images/secretarium_logo_grey.svg" id="stage-logo" />
	<div id="stage"></div>

	<div id="app" @dragover.prevent @drop.prevent v-cloak>
		<div id="drop-area"></div>
		<header>
			<nav class="navbar p-0" :class="{'logo-page':store.isLogoPage, 'fixed-top':store.isPresentationPages}">
				<div id="menu" :class="{'container-fluid':store.isLogoPage, container:!store.isLogoPage}" class="py-2">
					<router-link to="/#welcome" class="navbar-brand logo"></router-link>
					<ul id="presentation-menu" class="navbar-nav flex-row d-none d-sm-flex" v-if="store.isPresentationPages">
						<li class="nav-item py-0 px-2">
							<router-link to="/#what-it-is" class="nav-link">Concept</router-link>
						</li>
						<li class="nav-item py-0 px-2 d-none d-sm-flex">
							<router-link to="/#why" class="nav-link">Rationale</router-link>
						</li>
						<li class="nav-item py-0 px-2">
							<router-link to="/#technology" class="nav-link shift-right">Technology</router-link>
						</li>
					</ul>
					<ul id="sec-menu" class="navbar-nav flex-row ml-auto" :style="{marginRight:(store.isLogoPage?'2vw':0)}">
						<li class="nav-item mr-3" v-if="store.isLogoPage">
							<router-link to="/#what-it-is" class="nav-link shift-left">Presentation</router-link>
						</li>
						<li class="nav-item mr-3" v-else-if="!store.isPresentationPages">
							<router-link to="/#welcome" class="nav-link shift-left">Home</router-link>
						</li>
						<li class="nav-item">
							<router-link to="/blog" class="nav-link">Blog</router-link>
						</li>
						<!-- <li class="nav-item">
							<a class="nav-link" href="/demos">Demos</a>
						</li> -->
					</ul>
				</div>
				<div id="presentation-sub-menu" class="d-none d-sm-flex" v-if="store.isPresentationPages">
					<div class="container small">
						<ul id="sub-concept" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<a href="/#what-it-is" class="nav-link">What is Secretarium ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="/#what-it-does" class="nav-link">What does it provide ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="/#scaling" class="nav-link">How does it scale ?</a>
							</li>
						</ul>
						<ul id="sub-rationale" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<a href="/#why" class="nav-link">Why Secretarium ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="/#whom-for" class="nav-link">Who is it for ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="/#vision" class="nav-link">Our story & vision</a>
							</li>
						</ul>
						<ul id="sub-techno" class="navbar-nav flex-row m-0 p-0 my-2">
							<li class="nav-item py-0 px-2">
								<a href="/#technology" class="nav-link">How does it work ?</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="/#secretive-insight" class="nav-link">Secretive insight</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="/#sponsors" class="nav-link">Sponsors & partners</a>
							</li>
							<li class="nav-item py-0 px-2">
								<a href="/#documentation" class="nav-link">Documentation</a>
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

		<footer class="presentation">
			<div class="container pt-3 pb-1">
				<h6 class="text-white">Find us on
					<a href="https://discordapp.com/channels/670348155682029588/" target="_blank" class="mx-5">Discord</a>
					<a href="https://twitter.com/secretarium" target="_blank" class="mr-5">Twitter</a>
					<a href="https://www.linkedin.com/company/secretarium" target="_blank">LinkedIn</a>
				</h6>
				<p class="copyright mt-3 mb-0 text-center">
					All rights reserved. Copyright &copy; <?=date("Y")?> Secretarium Ltd. - Webdesign by
					<a href="https://florian.bzh" target="_blank" style="color:#eee">Florian Guitton</a>
				</p>
			</div>
		</footer>
	</div>

	<script type="text/x-template" id="sec-presentation">
		<div id="presentation">
			<section id="welcome" style="position: relative;">
				<div class="events p-sm-3">
					<div class="events-box px-4 py-3">
						<h5>COVID-19</h5>
						<p>
							See our <router-link to="/blog" class="text-sec">collaboration proposition</router-link>
						</p>
					</div>
				</div>
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
							<h4 class="mb-3">Swisscom</h4>
							<div class="comp-logo">
								<img src="/images/swisscom_logo.png"/>
							</div>
							<p class="mt-3">
								Swisscom supports Secretarium with engineers, business developers,
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
								<div class="custom-control custom-checkbox"
									v-for="(f) in documents" :key="f.name">
									<input type="checkbox" name="documents" class="custom-control-input form-control-sm" :id="'ckbx-'+f.id" :value="f.id">
									<label class="custom-control-label form-control-sm" :for="'ckbx-'+f.id">{{f.name}}</label>
								</div>
								<div class="mt-2">
									<button type="submit" class="btn btn-sec" @click.prevent="requestDocs">Submit</button>
									<span v-if="docsNs.visible" class="notif-state pl-3">
										<i class="fas fa" :class="[docsNs.icon, docsNs.color]"></i>
										<span v-show="docsNs.msg" class="small text-muted" style="vertical-align: 10%;">{{docsNs.msg}}</span>
									</span>
								</div>
							</form>
						</div>
						<div class="col-md-6 px-0 pl-md-5 mt-5 mt-md-0">
							<h4 class="mb-4">Get in touch</h4>
							<div class="row mx-0 contact">
								<div class="col-sm pb-3">
									<h5>Contact us</h5>
									<p class="pl-3 mb-1" style="min-width: 7rem;">
										<a class="link-unstyled" href="https://discordapp.com/channels/670348155682029588/" target="_blank">
											<img src="/images/discord-logo-black.png">
											<span>on Discord</span>
										</a>
									</p>
									<p class="pl-3 mb-1" style="min-width: 7rem;">
										<a class="link-unstyled" href="https://github.com/Secretarium" target="_blank">
											<img src="/images/GitHub-Mark-64px.png">
											<span>on GitHub</span>
										</a>
									</p>
									<p class="pl-3 mb-1" style="min-width: 7rem;">
										<a class="link-unstyled" href="mailto:contact@secretarium.com" target="_blank">
											<i class="fas fa-envelope"></i>
											<span>by email</span>
										</a>
									</p>
								</div>
								<div class="col-sm pb-3">
									<h5>Visit us</h5>
									<p class="pl-3" style="min-width: 12rem;">
										Société Générale<br/>
										Innovation Center, 5th floor<br/>
										1 Bank Street,<br/>
										E14 4SG, London, UK
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<a class="go-to-next d-none d-sm-inline" href="/#next" @click.prevent="goToNext"><i class="fas fa-arrow-down"></i></a>
		</div>
	</script>

	<script type="text/x-template" id="sec-blog">
		<div id="blog" class="container">
			<article class="mt-5">
				<h4>
					COVID-19<br />
					Privacy-respecting alerts
				</h4>
				<p class="font-italic">London, 3rd of April 2020</p>
				<p class="mt-5">
					Governments across the world are evaluating means to move out of COVID-19 lockdown. The
					<a href="http://www.ox.ac.uk/news/2020-03-17-infectious-disease-experts-provide-evidence-coronavirus-mobile-app-instant-contact" target="_blank">most effective solutions</a>
					appear to be contact-tracing mobile applications, to notify infected people encounters to self-isolate and get tested.
				</p>
				<p>
					The Imperial College has proposed
					<a href="https://cpg.doc.ic.ac.uk/blog/evaluating-contact-tracing-apps-here-are-8-privacy-questions-we-think-you-should-ask/" target="_blank">simple and efficient privacy-respecting solutions</a>,
					based on anonymised identifiers and consent. Many initiatives are following these guidelines or have a similar approach.
				</p>
				<p>
					These apps need to be installed by
					<a href="https://www.theguardian.com/uk-news/2020/mar/31/nhs-developing-app-to-trace-close-contacts-of-coronavirus-carriers" target="_blank">60% of the population</a>
					to be effective. Some apps will reach this threshold locally but not globally. Once the lockdown is released, people will start travelling again, crossing borders: a minimum of collaboration is required.
				</p>
				<p>
					On top of these new apps, tech giants and many mobile applications (for commuters, joggers) already have the necessary data to compute contacts.
					We believe that reconciling contacts from all possible sources, globally and in a privacy-respecting way will maximise the impact.
				</p>
				<p>
					As the most advanced company in <router-link to="/#what-it-is">distributed confidential computing</router-link>,
					we can perform reconciliations of encrypted data, at scale, without disclosing anything to anyone, not even us.
				</p>
				<p>
					We have spent the last few weeks designing a neutral, auditable, and remotely verifiable service. When a user self-reports as infected, the encrypted
					service will securely reconcile data from all sources, and each source will be able to inform users via their app.
					To seed the service, we are building and open-sourcing a mobile application relying on
					<a href="https://privacyinternational.org/explainer/3536/bluetooth-tracking-and-covid-19-tech-primer" target="_blank">Bluetooth</a> tracing.
				</p>
				<p>
					We have also designed ways for users reporting as infected to request a confirmation from their local health professionals.
					The goal is to prevent misuse and reduce the level of anxiety this service could generate.
				</p>
				<p>
					The Secretarium engineering team
				</p>
			</article>
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
				isLogoPage: window.location.pathname == "/" && (window.location.hash.length == 0 || window.location.hash == "#welcome")
			},
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
			})();

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
					docsNs: { visible: false, msg: "", icon: "fa-clock", color: "text-secondary" }
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
				setTimeout(() => { $(".events").css("opacity", 1); }, 1000);
				canvas.fill();
				canvas.start(1500);
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
					this.docsNs.msg = "Requesting...";
					this.docsNs.icon = "fa-clock";
					this.docsNs.color = "text-secondary";
					this.docsNs.visible = true;
					let o = $(e.target).closest("form").serializeObject();
					$.post("/services/", { type: "user.request-docs", data: o }, "json")
						.done(x => {
							if(x.success) {
								this.docsNs.msg = "Success";
								this.docsNs.icon = "fa-check";
								this.docsNs.color = "text-success";
							}
							else {
								this.docsNs.msg = "Unable to request documents: " + x.message;
								this.docsNs.icon = "fa-times";
								this.docsNs.color = "text-danger";
							}
						})
						.fail((j, t, e) => {
							this.docsNs.msg = "Unable to request documents: " + e;
							this.docsNs.icon = "fa-times";
							this.docsNs.color = "text-danger";
						});
				}
			}
		});

		const Blog = Vue.component('sec-blog', {
			template: '#sec-blog',
			data: () => { return {} },
			props: ["article"]
		});

		const router = new VueRouter({
			mode: 'history',
			routes: [
				{ path: '/', component: Presentation },
				{ path: '/blog/:article?', component: Blog, name: 'blog', props: true },
				{ path: '*', component: Presentation },
			],
			scrollBehavior: function (to) {
				if (to.hash) return { selector: to.hash };
			}
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
					store: store,
				}
			}
		}).$mount('#app');

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