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

	<script src="/scripts/jquery-3.4.1.min.js"></script>
	<script src="/scripts/popper-1.14.7.min.js"></script>
	<script src="/scripts/bootstrap-4.3.1.min.js"></script>
	<script src="/scripts/vue-2.6.10.js"></script>
	<script src="/scripts/secretarium-0.1.14.js"></script>
	<script src="/scripts/secretarium.iu-0.0.2.js"></script>
</head>

<body>
	<div id="app" @dragover.prevent @drop.prevent v-cloak>
		<div id="drop-area"></div>
		<header class="bg-white" style="position: relative;">
			<div class="connection-dropped" v-if="connectionState.retrying">
				{{connectionState.text + (connectionState.connecting ? "": " - ")}}
				<button v-if="!connectionState.connecting" type="button" class="btn btn-link p-0 m-0 text-white"
					@click.prevent.stop="connect">retry now</button>
			</div>
		</header>

		<content class="p-5">
			<h4 class="mb-3">Connection ({{connectionState.text}})</h4>
			<div v-if="!store.user.key">Please drop a key</div>
			<div v-else>
				<span class="btn btn-primary active mr-3">
					{{store.user.key.name}}
					<i class="fas ml-2"
						:class="{
							'fa-lock':!store.user.key.ready,'text-warning':!store.user.key.ready,
							'fa-lock-open':store.user.key.ready,'text-success':store.user.key.ready}"></i>
				</span>
				<form class="form-inline d-inline" @submit.prevent v-if="!store.user.key.ready">
					<input type="password" class="form-control" placeholder="Key Password" required v-model="keyPwd">
					<button type="submit" class="btn btn-primary mx-3" @click.prevent="decrypt">DECRYPT</button>
					<sec-notif-state :state="nsDecrypt.data"></sec-notif-state>
				</form>
			</div>
			<form class="form-inline mt-3" @submit.prevent>
				<input type="text" class="form-control" placeholder="Endpoint" required v-model="endpoint" style="min-width: 20rem;">
				<input type="text" class="form-control ml-3" placeholder="Trusted Key" required v-model="trustedKey" style="min-width: 50rem;">
				<button type="submit" class="btn btn-primary mx-3" @click.prevent="connect">CONNECT</button>
				<button type="submit" class="btn btn-primary mx-3" @click.prevent="disconnect">DISCONNECT</button>
				<sec-notif-state :state="nsConnect.data"></sec-notif-state>
			</form>
			<hr class="my-4">
			<h4 class="mb-3">Commands</h4>
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#single-tx"
						role="tab" aria-controls="single-tx" aria-selected="true">Single Transaction</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#multi-tx"
						role="tab" aria-controls="multi-tx">Multi Transactions</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="single-tx" role="tabpanel" aria-labelledby="single-tx-tab">
					<form @submit.prevent>
						<div class="form-group mt-3">
							<label for="id-app-name" class="fs-85">Application Name</label>
							<input type="text" class="form-control" id="id-app-name" placeholder="Application Name"
								style="min-width: 25rem;" required v-model="appName">
						</div>
						<div class="form-group mt-3">
							<label for="id-fct-name" class="fs-85">Function Name</label>
							<input type="text" class="form-control" id="id-fct-name" placeholder="Function Name"
								style="min-width: 25rem;" required v-model="fctName">
						</div>
						<div class="form-group mt-3">
							<label for="id-args" class="fs-85">Args</label>
							<input type="text" class="form-control" id="id-args" placeholder="Args"
								style="min-width: 25rem;" required v-model="args">
						</div>
						<div class="mt-3">
							<button type="submit" class="btn btn-primary mr-3 mw-10" @click.prevent="runSingle">RUN</button>
							<sec-notif-state :state="nsSingle.data"></sec-notif-state>
						</div>
					</form>
				</div>
				<div class="tab-pane fade" id="multi-tx" role="tabpanel" aria-labelledby="multi-tx-tab">
					<form @submit.prevent>
						<textarea class="form-control mt-3" placeholder="Commands Json" rows="8" required v-model="cmdJson">
						</textarea>
						<div class="mt-3">
							<button type="submit" class="btn btn-primary mr-3 mw-10" @click.prevent="runMulti">RUN</button>
							<sec-notif-state :state="nsMulti.data"></sec-notif-state>
						</div>
						<div class="mt-3">
							<p v-for="r in multiTx">
								{{r.requestId}} <sec-notif-state :state="r.ns.data"></sec-notif-state>
							</p>
						</div>
					</form>
				</div>
			</div>
		</content>
	</div>

	<script>
		var onDrop = null;
		const onResize = {}, isDev = window.location.hostname.includes(".dev") || window.location.hostname.includes("dev-local"),
			store = {
				user: { key: null },
				keysManager: new secretarium.keysManager()
			},
			state = {
				icons: ["fa-hourglass-start", "fa-check", "fa-exclamation-circle"],
				colors: ["text-warning", "text-success", "text-danger"]
			};

		store.keysManager.init();

		const app = new Vue({
			data: () => {
				return {
					store: store, connection: null,
					keyPwd: "", endpoint: "wss://127.0.0.1:5424/",
					trustedKey: "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
					appName: "personal-data", fctName: "", args: "", cmdJson: "", multiTx: [],
					nsSingle: new sec.notifState(), nsMulti: new sec.notifState(),
					nsDecrypt: new sec.notifState(), nsConnect: new sec.notifState()
				}
			},
			computed: {
				connectionState() {
					let con = this.connection, scp = con && con.scp;
					return {
						text: scp ? (con && con.retrying ? con.retryingMsg : sec.states.security[scp.security.state]) : "closed",
						retrying: con && con.retrying,
						connecting: con && con.connecting };
				}
			},
			mounted() {
				setOnDrop(this.onKeyFile);
			},
			beforeDestroy() {
				setOnDrop(null);
			},
			methods: {
				onKeyFile(evt) {
					this.store.keysManager.importKeyFile(evt)
						.catch(err => { alert("invalid-key-file: " + err); })
						.then((key) => { this.setKey(key); });
				},
				setKey(key) {
					this.store.user.key = key;
				},
				async decrypt() {
					try {
						this.nsDecrypt.processing("Decrypting", true);
						await this.store.keysManager.decryptKey(this.store.user.key, this.keyPwd);
						this.nsDecrypt.executed("Success", true);
					} catch (err) {
						this.nsDecrypt.failed(err, true);
					}
				},
				retryConnection() {
					if(!this.connection)
						return; // connection never succeeded
					let scp = this.connection.scp;
					if(!scp || scp.security.state < 2)
						return; // disconnecting, or already connected or connecting

					let timeout = 30 * Math.pow(2, Math.min(this.connection.retryFailures, 4)),
						countDowner = (t) => {
							if (document.hidden) { // user moved on to something else, not retrying
								this.connection.retryingMsg = "Secure connection lost";
								this.connection.timer = setTimeout(() => countDowner(t), 1000);
							}
							else if(--t > 0 && this.connection.timer) {
								let msg = "Secure connection lost - retrying in " + t + " sec";
								this.connection.retryingMsg = msg;
								this.connection.timer = setTimeout(() => countDowner(t), 1000);
							}
							else {
								this.connection.timerElapsed = true;
								setTimeout(() => {
									this.connect().catch(e => {});
								}, 0);
							}
						};
					this.connection.retryingMsg = "Secure connection lost";
					this.connection.retrying = true;
					this.connection.timerElapsed = false;
					this.connection.retryFailures++;
					this.connection.connecting = false;
					this.connection.timer = setTimeout(() => countDowner(timeout), 0);
				},
				connect() {
					if(!store.user.key || !store.user.key.cryptoKey)
						alert ("User key not loaded");
					if(!this.connection) {
						this.connection = {
							endpoint: this.endpoint, scp: null,
							lastState: 0, timer: null, timerElapsed: false, connecting: true,
							retrying: false, retryFailures: 0, retryingMsg: ""
						};
					}
					this.connection.endpoint = this.endpoint;
					if(!this.connection.scp)
						this.connection.scp = new secretarium.scp();
					else if(this.connection.retrying) { // retrying
						clearTimeout(this.connection.timer);
						this.connection.retryingMsg = "Secure connection lost - retrying now";
						if(!this.connection.timerElapsed) this.connection.retryFailures = 0;
					}

					return new Promise((resolve, reject) => {
						this.connection.connecting = true;
						if (this.connection.scp.socket.state < 2 && this.connection.scp.security.state < 2) {
							resolve(); // already connected
							return;
						}

						this.connection.scp.reset()
							.on("statechange", x => {
								if(x == 2) // connection dropped
									this.retryConnection();
								if(this.connection) this.connection.lastState = x;
							})
							.connect(this.connection.endpoint, this.store.user.key.cryptoKey, Uint8Array.secFromBase64(this.trustedKey, true), "pair1")
							.then(() => {
								this.connection.retrying = false;
								this.connection.retryingMsg = "";
								this.connection.retryFailures = 0;
								this.connection.connecting = false;
								resolve();
							})
							.catch(e => {
								this.connection.connecting = false;
								reject(e);
							});
					});
				},
				disconnect() {
					this.store.user.key = null;
					this.connection.scp.close();
					this.connection = null;
				},
				runSingle() {
					this.nsSingle.start("Sending tx...", true);
					try {
						this.connection.scp
							.newTx(this.appName, this.fctName, this.appName + "-" + this.fctName, JSON.parse(this.args))
							.onError(x => { this.nsSingle.failed(x, true); })
							.onAcknowledged(() => { this.nsSingle.acknowledged(); })
							.onProposed(() => { this.nsSingle.proposed(); })
							.onCommitted(() => { this.nsSingle.committed(); })
							.onExecuted(() => { this.nsSingle.executed("Executed", true); })
							.onResult(x => { this.nsSingle.executed(JSON.stringify(x), true); })
							.send();
					} catch (x) {
						this.nsSingle.failed(x, true);
					}
				},
				runMulti() {
					this.nsMulti.start("Parsing txs...", true);
					this.multiTx.splice(0, this.multiTx.length);
					try {
						let all = JSON.parse(this.cmdJson);
						if(!Array.isArray(all)) {
							this.nsMulti.failed("expecting array", true);
							return;
						}

						let fcRunTx = (i) => {
							try {
								let s = { requestId: all[i].requestId, ns: new sec.notifState() };
								this.multiTx.push(s);
								s.ns.start("Running " + all[i].requestId + "...", true);
								this.connection.scp
									.newTx(all[i].dcapp, all[i].function, all[i].requestId, all[i].args)
									.onError(x => { s.ns.failed(x, true); })
									.onAcknowledged(() => { s.ns.acknowledged(); })
									.onProposed(() => { s.ns.proposed(); })
									.onCommitted(() => { s.ns.committed(); })
									.onExecuted(() => {
										s.ns.executed("Executed", true);
										if(++i < all.length) fcRunTx(i);
									})
									.send();
							} catch (x) {
								this.nsMulti.failed(x, true);
							}
						};
						fcRunTx(0);
					} catch (x) {
						this.nsMulti.failed(x, true);
					}
					this.nsMulti.executed().hide(0);
				}
			}
		}).$mount('#app');

		function setOnDrop(cb) {
			$('body').toggleClass('active', cb != null);
			onDrop = cb;
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
			$("#app").css("opacity", 1);
		});
	</script>
</body>

</html>