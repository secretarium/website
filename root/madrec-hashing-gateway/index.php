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
	<link rel="stylesheet" href="/styles/secretarium-0.0.8.min.css" />

	<script src="/scripts/jquery-3.3.1.min.js"></script>
	<script src="/scripts/popper-1.14.7.min.js"></script>
	<script src="/scripts/bootstrap-4.3.1.min.js"></script>
	<script src="/scripts/vue-2.6.10.min.js"></script>
	<script src="/scripts/papaparse-4.6.0.min.js"></script>
	<script src="/scripts/stream-saver-0.0.0.js"></script>
	<script src="/scripts/madrec.formats-0.0.15.js"></script>
	<script src="/scripts/secretarium-0.1.8.js"></script>
</head>

<body>
	<div id="app" @dragover.prevent @drop.prevent v-cloak>
		<div id="drop-area"></div>
		<header>
			<nav class="navbar p-0">
				<div id="menu" class="container py-2">
					<a class="navbar-brand logo" href="/"></a>
					<ul class="navbar-nav flex-row">
						<li class="nav-item py-0 px-2">
							<a class="nav-link" href="#">Hashing Gateway</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<content>
			<sec-hashing-gateway></sec-hashing-gateway>
		</content>

		<footer class="bg-light">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-12 text-muted text-center">© <?=date("Y")?> - Secretarium</div>
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

	<script type="text/x-template" id="sec-hashing-gateway">
		<div class="container fixed-center">
			<div class="card card-sec border-0">
				<div class="card-header">
					<h4><i class="fas fa-cogs fa-fw mr-2 text-sec"></i>Hashing gateway</h4>
					<p class="mb-0">Hash your documents before uploading them to the MADRec app.</p>
				</div>
				<div class="card-body">
					<div class="py-2">
						<h6 class="card-title">Instructions</h6>
						<p class="card-text">
							Please find the
							<a href="https://github.com/Secretarium/MADRec/wiki/Instructions#madrec"
								class="btn-link text-sec" target="_blank">instructions</a>
							on Github.
						</p>
					</div>
					<hr class="my-3 sec" />
					<div class="py-2">
						<h6 class="card-title">Want to test ?</h6>
						<p class="card-text">Please use the samples:<br />
							<a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.250.csv" download="sample.250.csv">250 LEIs</a>
							<a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.20000.csv" download="sample.20000.csv">20k LEIs</a>
							<a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.100000.csv" download="sample.100000.csv">100k LEIs</a>
							<a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.234567.csv" download="sample.234567.csv">234k+ LEIs</a>
							<a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.500000.csv" download="sample.500000.csv">500k LEIs</a>
							<a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.1325966.csv" download="sample.1325966.csv">1.3M+ LEIs</a>
						</p>
					</div>
					<hr class="my-3 sec" />
					<div class="py-2">
						<h6 class="card-title">Hash each field of your MADRec csv file</h6>
						<p v-if="!hasFile" class="card-text">
							Please
							<label for="hashing-gateway-csv-file" class="btn btn-link p-0 text-sec">browse from disk</label>
							<input type="file" id="hashing-gateway-csv-file" accept=".csv" class="d-none" @change="csvFileChange" />
							for your local MADRec csv file or drop it here.
						</p>
						<div v-else class="card-text">
							<p class="card-text">
								<i class="fas fa-check text-success fa-fw mr-2"></i>
								File "{{file.name}}" ({{humanFileSize(file.size)}}) successfully loaded
							</p>
							<div v-if="!verify.done" class="mt-3">
								<p class="card-text" v-if="fileMsg.length>0">
									<i class="fas fa-exclamation-circle text-primary fa-fw mr-2"></i>
									{{fileMsg}}
								</p>
								<p class="card-text">
									<i class="fas fa-hourglass-start text-warning fa-fw mr-2"></i>
									Verifying "{{file.name}}" data formats
								</p>
								<p class="card-text" v-if="verify.msg.length>0">
									{{verify.msg}}
								</p>
								<div class="progress mt-3" style="height: 5px;">
									<div class="progress-bar no-transition bg-success" role="progressbar" :aria-valuenow="verifyBar.verified" aria-valuemin="0" aria-valuemax="100" :style="{'width': `${verifyBar.verified}%`}"></div>
									<div class="progress-bar no-transition bg-secondary" role="progressbar" :aria-valuenow="verifyBar.read" aria-valuemin="0" aria-valuemax="100" :style="{'width': `${verifyBar.read}%`}"></div>
								</div>
							</div>
							<div v-else class="mt-3">
								<p class="card-text">
									<i class="fas fa-check text-success fa-fw mr-2"></i>
									All {{rowsCount}} LEIs and associated fields have been successfully verified
								</p>
								<p class="card-text" v-if="hash.started&&!hash.done">
									<i class="fas fa-hourglass-start text-warning fa-fw mr-2"></i>
									Hashing and streaming ({{hash.hashed}} / {{rowsCount}})
								</p>
								<p class="card-text" v-else-if="hash.done">
									<i class="fas fa-check text-success fa-fw mr-2"></i>
									All {{hash.hashed}} LEIs and associated fields have been successfully hashed and streamed
								</p>
								<div class="mt-2 alert alert-warning alert-dismissible fade show" role="alert" v-if="verify.warnings.length>0">
									<div v-for="warning in verify.warnings" class="warning">{{warning}}</div>
									<button type="button" class="close" aria-label="Close" @click="verify.warnings=[]">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<p v-if="hash.msg.length>0" class="card-text">
									{{hash.msg}}
								</p>
								<div class="mt-4" v-if="!hash.done">
									<div v-if="!hash.started">
										<button type="button" class="btn btn-sec" @click.prevent="hashFile">Start hashing</button>
										<span v-if="rowsCount>50000" class="ml-3">Split into files of
											<select class="custom-select ml-1 mr-1" style="width: auto;" id="hashing-gateway-file-max-leis">
												<option value="50000" selected>50,000</option>
												<option value="100000">100,000</option>
												<option value="150000">150,000</option>
												<option value="200000">200,000</option>
												<option value="0">unlimited</option>
											</select>
											rows
										<span>
									</div>
									<div v-else class="progress mt-3" style="height: 5px;">
										<div class="progress-bar no-transition bg-success" role="progressbar" :aria-valuenow="hashingBar.hashed*100.0/rowsCount" aria-valuemin="0" aria-valuemax="100" :style="{'width': `${hashingBar.hashed*100.0/rowsCount}%`}"></div>
										<div class="progress-bar no-transition bg-secondary" role="progressbar" :aria-valuenow="hashingBar.read*100.0/rowsCount" aria-valuemin="0" aria-valuemax="100" :style="{'width': `${hashingBar.read*100.0/rowsCount}%`}"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</script>


	<script>
		var onDrop = null;
		const store = {
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

		const MADRecUtils = {
			fieldsIndex: Object.assign({}, ...MADRec.fields.map((f, i) => ({[f.name]: i}))),
			fillReport(fields) {
				for(var name in fields) {
					let field = fields[name];
					if(field.total == 1) {
						field.report = "no match";
					}
					else if(field.split.length == 1) {
						field.report = "full consensus";
					}
					else if(field.split.length < field.total) {
						field.report = "split consensus with " + (field.group == 0 ? "majority" : "minority");
					}
					else {
						field.report = "no consensus";
					}
				}
			},
			fillColors(fields) {
				for(var name in fields) {
					let field = fields[name];
					if(field.total == 1) {
						field.colors = ["#28a745"];
					}
					else if(field.split.length == 1) {
						field.colors = field.total == 2 ? ["#28a745"] : ["#218838"];
					}
					else if(field.split.length < field.total) {
						field.colors = field.split[1].count == 1 ? ["#28a745"] : ["#ffc107"];
						for (var j = 1; j < field.split.length; j++) field.colors.push("#fd7e14");
					}
					else {
						field.colors = ["#fd7e14"];
						for (var j = 1; j < field.split.length; j++) field.colors.push("#ffe8a1");
					}
					field.groups = field.split.map(x => x.count);
					field.color = field.colors[field.group];
				}
			},
			verifyFieldNames(data) {
				for (var f in data) {
					if(f != MADRec.lei.name && f != "Original " + MADRec.lei.name && MADRecUtils.fieldsIndex[f.name] != undefined)
						return { success: false, field: f };
				}
				return { success: true };
			},
			verifyData(lei, data) {
				let warnings = {}, isHashed = false;
				if(typeof lei === "string" && lei.length == 44) { // hashed value, can't verify
					warnings["You are using pre-hashed values, can't verify format"] = 1;
					isHashed = true;
				} else {
					let v = MADRec.lei.verifier(lei);
					if(v.success !== true) {
						v.field = MADRec.lei.name;
						v.value = lei;
						return v;
					}
				}
				for(let f of MADRec.fields) {
					if(data[f.name] !== "" && data[f.name] != null) {
						if(isHashed) {
							if(typeof data[f.name] !== "string" || data[f.name].length != 44) {
								return { success: false, warnings: warnings,
									error: "Inconsistent hashing for field '" + f.name + "'. Expecting a hashed value" };
							}
						} else {
							let v = f.verifier(data[f.name]);
							if(v.success !== true) {
								v.field = f.name;
								v.value = data[f.name];
								return v;
							}
							if(v.value !== undefined)
								data[f.name] = v.value;
							if(v.warning) {
								v.warning = "'" + f.name + "' " + v.warning;
								warnings[v.warning] = (warnings[v.warning] || 0) + 1;
							}
						}
					}
				};
				return { success: true, warnings: warnings, isHashed: isHashed };
			},
			toValues(a, names, i, data) {
				if(i + 1 == names.length) {
					a.push({ name: names[i], value: data });
				} else {
					let b = { name: names[i], values: [] };
					a.push(b);
					this.toValues(b.values, names, i + 1, data);
				}
			},
			async hashData(salt, data) {
				if(typeof data === 'boolean') // transform bools and double to avoid cross platform/language issues
					data = data ? "true" : "false";
				else if(typeof data === 'number')
					data = data.toFixed(6); // 6 digits to match c++ std::to_string() precision
				return await sec.utils.hashBase64(salt + data);
			},
			async rowToJson(data, hashOpt) {
				if(data[MADRec.lei.name] === undefined) return false;
				let json = { values: [] }, salt;
				if(hashOpt > 1) json.hashed = true;
				if(hashOpt == 2) salt = await sec.utils.hashBase64(data[MADRec.lei.name]);
				for (var f in data) {
					if(f == "Orignal " + MADRec.lei.name) continue;
					if(data[f] === "" || data[f] == null || data[f] == undefined) continue;
					if(hashOpt == 2) data[f] = await this.hashData(salt, data[f]);
					if(f == MADRec.lei.name) json[f] = data[f];
					else this.toValues(json.values, f.split('/'), 0, data[f]);
				}
				return json;
			}
		};
		const HashingGateway = Vue.component('sec-hashing-gateway', {
			template: '#sec-hashing-gateway',
			mounted() {
				setOnDrop(this.onDrop);
			},
			beforeDestroy() {
				setOnDrop(null);
				this.resetFile();
				this.resetHash();
			},
			data: function () {
				return {
					hasFile: false,
					file: null,
					fileMsg: "",
					rowsCount: 0,
					verify: { blockSize: 250, done: false, msg: "", read: 0, verified: 0, warnings: [] },
					hash: { blockSize: 250, asyncSize: 10000, started: false, done: false, msg: "", read: 0, hashed: 0, writer: null }
				}
			},
			computed: {
				verifyBar() {
					var y = this.verify.verified;
					return { read: Math.min(Math.max(this.verify.read - y, 0), 100), verified: y };
				},
				hashingBar() {
					var y = this.hash.hashed;
					return { read: Math.min(Math.max(this.hash.read - y, 0), this.rowsCount), hashed: y };
				}
			},
			methods: {
				resetHash() {
					this.hash.started = this.hash.done = false;
					this.hash.msg = "";
					this.hash.read = this.hash.hashed = 0;
					this.hash.writer = null;
				},
				resetFile() {
					this.fileMsg = "";
					this.verify.done = false;
					this.verify.msg = "";
					this.verify.warnings = [];
					this.rowsCount = this.verify.read = this.verify.verified = 0;
					this.resetHash();
				},
				csvFileChange(e) {
					this.resetFile();
					if(e.target && e.target.files && e.target.files.length == 1)
						this.checkFile(e.target.files[0]);
					else
						this.fileMsg = "Invalid choice, expecting one file";
				},
				onDrop(e) {
					this.resetFile();
					if(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length == 1)
						this.checkFile(e.dataTransfer.files[0]);
					else
						this.fileMsg = "Invalid drop, expecting one file";
				},
				checkFile(file) {
					if(file.type !== "text/csv" && file.type !== "application/vnd.ms-excel") {
						this.fileMsg = "Unexpected file type '" + file.type + "', expecting text/csv";
					}
					this.hasFile = true;
					this.file = file;
					this.verifyFile();
				},
				humanFileSize(size) {
					if(size <= 0) return "0 bytes";
					var i = Math.floor( Math.log(size) / Math.log(1024) );
    				return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['Bytes', 'kB', 'MB', 'GB', 'TB'][i];
				},
				parseFile(onNewLine, onComplete, onError) {
					Papa.LocalChunkSize = 8192;
					Papa.parse(this.file, {
						header: true,
						trimHeaders: true,
						step: onNewLine,
						complete: onComplete,
						error: onError,
						skipEmptyLines: true
					});
				},
				verifyFile() {
					var self = this, leiBuff = [], hasFailed = false, tempWarnings = {};
					var checkBuff = function() {
							let v = self.verify.read - self.verify.verified;
							if(self.verify.verified == 0) {
								let fieldCheck = MADRecUtils.verifyFieldNames(leiBuff[0]);
								if(fieldCheck.success !== true) {
									self.verify.msg = "Error on row 0, invalid field '" + fieldCheck.field + "'";
									return false;
								}
							}
							for(let i = 0; i < leiBuff.length; i++) {
								let check = MADRecUtils.verifyData(leiBuff[i][MADRec.lei.name], leiBuff[i]);
								if(check.success !== true) {
									let row = (self.rowsCount - self.verify.blockSize + i + 2);
									self.verify.msg = "Error with LEI '" + leiBuff[i][MADRec.lei.name] + "' on row " +
										row + " for field '" + check.field + "' with value '" + check.value + "'";
									return false;
								}
								if(check.isHashed) {
									let row = (self.rowsCount - self.verify.blockSize + i + 2);
									self.verify.msg = "You file contains hashed data for LEI '" + leiBuff[i][MADRec.lei.name] + "' on row " + row;
									return false;
								}
								if(check.warnings) {
									for(let w in check.warnings) {
										if(tempWarnings[w] !== undefined) tempWarnings[w]++;
										else tempWarnings[w] = 1;
									}
								}
								self.verify.verified += v / leiBuff.length;
							}
							self.verify.verified = self.verify.read;
							return true;
						},
						onNewLine = function(row, parser) {
							self.verify.read = row.meta.cursor * 100 / self.file.size;
							leiBuff.push(row.data[0]);
							if(leiBuff.length == self.verify.blockSize) {
								if(!checkBuff()) {
									parser.abort();
									hasFailed = true;
								}
								leiBuff.length = 0;
								self.verify.warnings = Object.keys(tempWarnings).map(x => { return x + (tempWarnings[x] > 1 ? " (" + tempWarnings[x] + " times)" : ""); });
							}
							self.rowsCount++;
						},
						onComplete = function() {
							if(leiBuff.length > 0) {
								if(!checkBuff()) {
									hasFailed = true;
								}
								leiBuff.length = 0;
								self.verify.warnings = Object.keys(tempWarnings).map(x => { return x + (tempWarnings[x] > 1 ? " (" + tempWarnings[x] + " times)" : ""); });
							}
							if(!hasFailed) {
								self.verify.read = self.verify.verified = 100;
								self.verify.done = true;
							}
						},
						onError = function(e) {
							self.verify.msg = "File reading error: " + e;
						};
					this.parseFile(onNewLine, onComplete, onError);
				},
				async hashRow(data) {
					this.hash.hashed++;
					if(data[MADRec.lei.name] === undefined) return false;
					MADRecUtils.verifyData(data[MADRec.lei.name], data); // must call again as verify does transform
					let lei = data[MADRec.lei.name], salt = await sec.utils.hashBase64(lei), row = [];
					for (var f in data) {
						if(data[f] === "" || data[f] == null || data[f] == undefined) continue;
						data[f] = await MADRecUtils.hashData(salt, data[f]);
					}
					row.push(data[MADRec.lei.name]);
					row.push(lei);
					for(let f of MADRec.fields) {
						row.push(data[f.name] || "");
					};
					this.hash.writer.write(sec.utils.encode(row.join(",") + "\r\n"));
				},
				getFileStreamer(fileName) {
					let ws = streamSaver.createWriteStream(fileName),
						writer = ws.getWriter(),
						header = MADRec.lei.name + ",Original " + MADRec.lei.name;
					for(let f of MADRec.fields) {
						header += "," + f.name;
					}
					writer.write(sec.utils.encode(header + "\r\n"));
					return writer;
				},
				hashFile() {
					this.resetHash();

					var self = this, leiBuff = [], hashBuff = [], rowNum = 0, fileCounter = 1,
						fileMaxRows = $('#hashing-gateway-file-max-leis').val();

					this.hash.started = true;
					this.hash.writer = this.getFileStreamer("leis-hashed.csv");

					let onNewLine = function(row, parser) {
							self.hash.read = (++rowNum);
							leiBuff.push(row.data[0]);
							if(leiBuff.length == self.hash.blockSize) {
								let a = leiBuff.map(x => self.hashRow(x));
								hashBuff.push(...a);
								leiBuff.length = 0;
								if(fileMaxRows > 0 && rowNum % self.hash.asyncSize == 0) {
									parser.pause();
									Promise.all(hashBuff).then(u => {
										if(rowNum % fileMaxRows == 0) {
											self.hash.writer.close();
											let name = "leis-hashed-part-" + (++fileCounter) + ".csv";
											self.hash.writer = self.getFileStreamer(name);
										}
										hashBuff.length = 0;
										parser.resume();
									});
								}
							}
						},
						onComplete = function() {
							self.hash.read = self.rowsCount;
							let cb = () => {
								self.hash.hashed = self.rowsCount;
								self.hash.writer.close();
								self.hash.done = true;
								self.hash.msg = "";
								leiBuff.length = 0;
								hashBuff.length = 0;
							}
							if(leiBuff.length > 0) {
								let a = leiBuff.map(x => self.hashRow(x));
								hashBuff.push(...a);
							}
							if(hashBuff.length > 0)
								Promise.all(hashBuff).then(x => { cb() });
							else
								cb();
						},
						onError = function(e) {
							self.hash.msg = "File reading error: " + e;
							self.hash.writer.close();
						};

					this.parseFile(onNewLine, onComplete, onError);
				}
			}
		});

		const app = new Vue({
			data: () => {
				return {
					store: store
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
					if(e.dataTransfer && e.dataTransfer.files)
						onDrop(e);
					else if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files)
						onDrop(e.originalEvent);
				}
			});
			$("#app").css("opacity", 1);
		});
	</script>
</body>

</html>