"use strict"

const SemaphoreUtils = {
    fieldsIndex: Object.assign({}, ...Semaphore.fields.map((f, i) => ({[f.name]: i}))),
    fillReport(fields) {
        for(var name in fields) {
            let field = fields[name];
            if(field.total == 1) {
                field.report = "no match";
            }
            else if(field.split.length == 1) {
                field.report = "full consensus";
            }
            else {
                if(field.split.length < field.total) {
                    field.report = "split consensus with " + (field.group == 0 ? "majority" : "minority");
                }
                else {
                    field.report = "no consensus";
                }
                let latest = false;
                for(let i = 0; i < field.split.length; i++) {
                    if(i != field.group && field.split[i].latest < field.split[field.group].latest) {
                        latest = true; break;
                    }
                }
                field.report += " (" + (latest ? "ahead" : "late") + ")"
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
            if(f != "id" && f != "Original " + "id" && SemaphoreUtils.fieldsIndex[f.name] != undefined)
                return { success: false, field: f };
        }
        return { success: true };
    },
    verifyData(data) {
        let warnings = {};
        for(let f of Semaphore.fields) {
            if(data[f.name] !== "" && data[f.name] != null) {
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
        };
        return { success: true, warnings: warnings };
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
    rowToJson(data) {
        if(data["id"] === undefined) return false;
        let json = { values: [] }, salt;
        for (var f in data) {
            if(f == "Orignal " + "id") continue;
            if(data[f] === "" || data[f] == null || data[f] == undefined) continue;
            if(f == "id") json[f] = data[f];
            else this.toValues(json.values, f.split('/'), 0, data[f]);
        }
        return json;
    }
};
const semaphoreCluster = store.dcapps["semaphore"].cluster;


Vue.set(store.user.dcapps, "semaphore", {});
Vue.set(store.user.dcapps.semaphore, "data", {});
const SemaphoreApp = Vue.component('sec-semaphore', {
    template: '#sec-semaphore'
});
const SemaphoreConnect = Vue.component('semaphore-connect', {
    template: '#semaphore-connect',
    data: () => {
        return {
            ns: new sec.notifState()
        }
    },
    mounted() {
        setOnDrop(this.onKeyFile);
    },
    beforeDestroy() {
        setOnDrop(null);
    },
    computed: {
        dcapp() { return store.dcapps["semaphore"]; },
        cluster() { return store.clusters[this.dcapp.cluster]; }
    },
    methods: {
        onKeyFile(evt) {
            this.$root.keysManager.importKeyFile(evt)
                .then((key) => {
                    let id = this.$root.keysManager.find(key.name);
                })
                .catch(err => {
                    alerts.push({ key: "invalid-key-file", isError: true, html: err });
                })
        },
        connect(key) {
            this.$root.setKey(key);
            this.ns.processing("Connecting...", true);
            this.$root.connect(this.dcapp.cluster, this.cluster.gateways[0].endpoint, this.cluster.key)
                .then(() => {
                    this.ns.executed();
                    this.ns.start("Loading profile...", true);
                    store.SCPs[this.dcapp.cluster]
                        .newQuery("identity", "get", "identity-get", "")
                        .onError(x => { this.ns.failed(x, true); })
                        .onResult(x => {
                            this.ns.executed("Success", true);
							Vue.set(store.user.dcapps.identity.data, "firstname", x.firstname);
							Vue.set(store.user.dcapps.identity.data, "lastname", x.lastname);
							Vue.set(store.user.dcapps.identity.data, "personalRecords", x.personalRecords || {});
                            setTimeout(() => { router.push("/semaphore"); }, 500);
                        })
                        .send();
                })
                .catch((e) => {
                    this.ns.failed("Connection failed: " + e, true);
                });
        }
    }
});
const SemaphoreKeyPicker = Vue.component('semaphore-account-picker', {
    template: '#semaphore-account-picker',
    data: () => {
        return {
            selectedKey: null
        }
    },
    methods: {
        onPick(key, id) {
            this.selectedKey = key;
            if(key.ready) this.$parent.connect(key);
            else setTimeout(() => { $('#ckPwd').focus(); }, 10);
        },
        async decryptKey() {
            this.$parent.ns.processing("Decrypting key", true);
            let pwd = $('#ckPwd').val();
            if(pwd.length < 1) { this.$parent.ns.failed("invalid password", true); return; }
            try {
                await this.$root.keysManager.decryptKey(this.selectedKey, pwd);
                this.$parent.ns.executed("Success", true);
                this.$parent.connect(this.selectedKey, this.ns);
            } catch (err) {
                this.$parent.ns.failed(err, true);
            }
        }
    }
});
const SemaphoreRegister = Vue.component('semaphore-register', {
    template: '#semaphore-register',
    data: () => {
        return {
            firstname: "", password: "",
            registering: false,
            ns: new sec.notifState()
        }
    },
    computed: {
        dcapp() { return store.dcapps["semaphore"]; },
        cluster() { return store.clusters[this.dcapp.cluster]; }
    },
    methods: {
        verify() {
            this.ns.processing("Verifying inputs", true);
            if(this.firstname == "") {
                this.ns.failed("Invalid firstname", true);
                return false;
            }
            if(this.password == "") {
                this.ns.failed("Invalid password", true);
                return false;
            }
            this.ns.executed().hide(0);
            return true;
        },
        async createKey() {
            this.ns.processing("Creating a new authentication key", true);
            try {
                let key = await this.$root.keysManager.createKey(this.firstname);
                key = await this.$root.keysManager.encryptKey(key, this.password);
                key.save = true;
                this.$root.keysManager.save();
                this.$root.setKey(key);
                this.ns.executed().hide(0);
                return true;
            }
            catch(e) {
                this.ns.failed(e, true);
                return false;
            }
        },
        async register() {
            if(!this.verify()) return;
            if(!await this.createKey()) return;

            this.ns.processing("Connecting...", true);
            this.$root.connect(this.dcapp.cluster, this.cluster.gateways[0].endpoint, this.cluster.key)
                .then(() => {
                    this.ns.executed();
                    this.ns.start("Registering...", true);
                    let args = { firstname: this.firstname, lastname: "" };
                    store.SCPs[cluster]
                        .newTx("identity", "set", "identity-set", args)
                        .onError(x => { this.ns.failed(x, true); })
                        .onAcknowledged(() => { this.ns.acknowledged(); })
                        .onProposed(() => { this.ns.proposed(); })
                        .onCommitted(() => { this.ns.committed(); })
                        .onExecuted(() => {
                            this.ns.executed("Success", true);
                            Vue.set(store.user.dcapps.semaphore.data, "firstname", this.firstname);
                            Vue.set(store.user.dcapps.semaphore.data, "lastname", "");
                            setTimeout(() => { router.push("/semaphore"); }, 500);
                        })
                        .send();
                })
                .catch((e) => {
                    this.ns.failed("Connection failed: " + e, true);
                });
        }
    }
});
const SemaphoreAppWelcome = Vue.component('sec-semaphore-welcome', {
    template: '#sec-semaphore-welcome'
});
const SemaphoreAppSingleContribution = Vue.component('sec-semaphore-single-contribution', {
    template: '#sec-semaphore-single-contribution',
    data: () => {
        return {
            ns: new sec.notifState(),
            values: {},
            results: {},
            exportUrl: "",
            warnings: [],
            fields: Semaphore.fields,
            modifiedFields: {},
            toggledReport: -1
        }
    },
    mounted() {
        this._initAutoCompleteFields();
    },
    computed: {
        modified() {
            for(let name in this.modifiedFields) {
                if(this.modifiedFields[name]) return true;
            }
            return false;
        }
    },
    methods: {
        _initAutoCompleteFields() {
            for(let i = 0; i < Semaphore.fields.length; i++)
            {
                let f = Semaphore.fields[i];
                if(f.type != 'list' || f.values.length < 10) continue;

                let values = f.values.sort().map(e => ({ value: e })),
                    input = $("#semaphoreSingle-"+i)
                        .on("focusout", () => { input.removeClass("autocomplete-active"); })
                        .autocomplete({
                            lookup: values,
                            beforeRender() { input.addClass("autocomplete-active"); },
                            onSelect: (e) => {
                                Vue.set(this.values, f.name, e.value);
                                this.onFieldChanged(f.name, i);
                            }
                        });
            }
            let leiInput = $("#semaphoreSingle-6")
                .on("focusout", () => { leiInput.removeClass("autocomplete-active"); })
                .autocomplete({
                    lookup: [
                        { value: "549300LRI77T5F28OH18" },
                        { value: "549300TK7G7NZTVM1Z30" },
                        { value: "029200013A5N6ZD0F605" },
                        { value: "029200137F2K8AH5C573" },
                        { value: "315700Q9JC71ZUHQY351" },
                        { value: "529900NNMOAG69TT1614" },
                        { value: "969500X58FN3MWUL0B51" },
                        { value: "815600115305C78FDE40" },
                        { value: "635400JPZI3FORYGL643" },
                        { value: "894500XEZHM2QO1Q4V38" },
                        { value: "5299000IXAIL707XIT12" },
                        { value: "335800H652ZAKERWKL73" },
                        { value: "259400S8P72Y8W83QB51" },
                        { value: "4469000001C3URXXX277" },
                        { value: "4469000001C3YPQ9CX02" },
                        { value: "969500JPGSZN1GFY9J93" }
                    ],
                    beforeRender() { leiInput.addClass("autocomplete-active"); }
                });
        },
        getExportUrl(result) {
            let json = JSON.stringify(result, null, 4),
                blob = new Blob([json], { type: 'application/json;charset=utf-8;' });
            return URL.createObjectURL(blob);
        },
        onFieldChanged(name, i) {
            let v = $("#semaphoreSingle-" + i).val(), x = this.results[name];
            Vue.set(this.modifiedFields, name, x == undefined || x.contribution != v);
        },
        async contribute() {
            // Verified required fields
            this.ns.processing("Verifying inputs", true);
            let country = this.values["Country"], regNum = this.values["Company Registration Number"];
            if(!country || !regNum) {
                this.ns.failed("Company Registration Number and Country must be filled", true);
                return;
            }
            this.ns.executed().hide(0);

            // Compute id
            this.ns.processing("Computing id", true);
            let id = await sec.utils.hashBase64(country + regNum);

            // Verify inputs
            this.warnings = [];
            let val = {};
            for(let name in this.modifiedFields) {
                if(this.modifiedFields[name]) {
                    val[name] = this.values[name];
                }
            }
            let check = SemaphoreUtils.verifyData(val), w = check.warnings;
            if(check.success !== true) {
                this.ns.failed(check.field + " " + check.error, true);
                return;
            }
            if(w) this.warnings = Object.keys(w).map(x => { return x + (w[x] > 1 ? " (" + w[x] + "  times)" : ""); });

            // Filter modified fields
            let args = { "id": id, values: [] };
            for(let v in val) {
                args.values.push({ name: v, value: val[v]});
            }

            // Upload
            this.ns.start();
            store.SCPs[semaphoreCluster]
                .newTx("semaphore", "put", "semaphore-single-put", args)
                .onError(x => { this.ns.failed(x, true); })
                .onAcknowledged(() => { this.ns.acknowledged(); })
                .onProposed(() => { this.ns.proposed(); })
                .onCommitted(() => { this.ns.committed(); })
                .onExecuted(() => {
                    this.ns.executed().hide();
                    this.ns.start();
                    store.SCPs[semaphoreCluster]
                        .newQuery("semaphore", "get", "semaphore-single-get", { "id": id, subscribe: true })
                        .onError(x => { this.ns.failed(x, true); })
                        .onResult(x => {
                            this.ns.executed().hide();
                            SemaphoreUtils.fillReport(x.fields);
                            if(this.exportUrl != "") URL.revokeObjectURL(this.exportUrl);
                            this.exportUrl = this.getExportUrl(x.fields);
                            SemaphoreUtils.fillColors(x.fields);
                            Vue.set(this, "modifiedFields", {});
                            Vue.set(this, "values", {});
                            Vue.set(this, "results", x.fields);
                            for(let name in x.fields) {
                                let f = x.fields[name];
                                Vue.set(this.values, name, f.contribution);
                            }
                        })
                        .send();
                })
                .send();
        }
    }
});
const SemaphoreAppMultiContribution = Vue.component('sec-semaphore-multi-contribution', {
    template: '#sec-semaphore-multi-contribution',
    mounted() {
        setOnDrop(this.onDrop);
    },
    beforeDestroy() {
        setOnDrop(null);
    },
    data: function () {
        return {
            hasFile: false,
            file: null,
            fileMsg: "",
            rowsCount: 0,
            verify: {
                blockSize: 250, done: false, msg: "", read: 0, verified: 0, warnings: []
            },
            progressColors: {
                sent: "bg-secondary", acknowledged: "bg-primary",
                executed: "bg-success", failed: "bg-danger" },
            upload: {
                blockSize: 100, done: false, msg: "", showProgress: false, blocks: [],
                read: 0, acknowledged: 0, executed: 0, failed: 0,
                verify: {
                    prevState: {}, counter: 0, issueMsg: "", showRetry: false
                }
            },
            timeBeforeRetry: 5
        }
    },
    beforeRouteLeave (to, from, next) {
        if(!this.upload.showProgress || window.confirm('Leaving will cancel the upload. Do you really want to leave ?')) {
            next();
        } else {
            next(false);
        }
    },
    computed: {
        verifyBar() {
            var y = this.verify.verified;
            return { read: Math.min(Math.max(this.verify.read - y, 0), 100), verified: y };
        }
    },
    methods: {
        resetUpload() {
            this.upload.done = false;
            this.upload.msg = "";
            this.upload.showProgress = false;
            this.upload.blocks = [];
            this.upload.read = this.upload.acknowledged = this.upload.executed = this.upload.failed = 0;
            this.upload.verify.prevState = {};
            this.upload.verify.counter = 0;
            this.upload.verify.issueMsg = "";
            this.upload.verify.showRetry = false;
        },
        resetFile() {
            this.fileMsg = "";
            this.verify.done = false;
            this.verify.msg = "";
            this.verify.warnings = [];
            this.rowsCount = this.verify.read = this.verify.verified = 0;
            this.resetUpload();
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
                this.fileMsg = "Unexpected file type '" + file.type + "', expecting 'text/csv'";
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
            var self = this, signalsBuff = [], hasFailed = false, tempWarnings = {};
            var checkBuff = function() {
                    let v = self.verify.read - self.verify.verified;
                    if(self.verify.verified == 0) {
                        let fieldCheck = SemaphoreUtils.verifyFieldNames(signalsBuff[0]);
                        if(fieldCheck.success !== true) {
                            self.verify.msg = "Error on row 0, invalid field '" + fieldCheck.field + "'";
                            return false;
                        }
                    }
                    for(let i = 0; i < signalsBuff.length; i++) {
                        let check = SemaphoreUtils.verifyData(signalsBuff[i]);
                        if(check.success !== true) {
                            let row = (self.rowsCount - self.verify.blockSize + i + 2);
                            self.verify.msg = "Error with '" + signalsBuff[i]["id"] + "' on row " +
                                row + " for field '" + check.field + "' with value '" + check.value + "'";
                            return false;
                        }
                        if(check.warnings) {
                            for(let w in check.warnings) {
                                if(tempWarnings[w] !== undefined) tempWarnings[w]++;
                                else tempWarnings[w] = 1;
                            }
                        }
                        self.verify.verified += v / signalsBuff.length;
                    }
                    self.verify.verified = self.verify.read;
                    return true;
                },
                onNewLine = function(row, parser) {
                    self.verify.read = row.meta.cursor * 100 / self.file.size;
                    signalsBuff.push(row.data[0]);
                    if(signalsBuff.length == self.verify.blockSize) {
                        if(!checkBuff()) {
                            parser.abort();
                            hasFailed = true;
                        }
                        signalsBuff.length = 0;
                        self.verify.warnings = Object.keys(tempWarnings).map(x => { return x + (tempWarnings[x] > 1 ? " (" + tempWarnings[x] + " times)" : ""); });
                    }
                    self.rowsCount++;
                },
                onComplete = function() {
                    if(signalsBuff.length > 0) {
                        if(!checkBuff()) {
                            hasFailed = true;
                        }
                        signalsBuff.length = 0;
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
        updateUploadBlockState(id, state, msg = "") {
            this.upload.blocks[id].state = state;
            this.upload.blocks[id].class = this.progressColors[state];
            this.upload.blocks[id].title = msg || state;
        },
        async uploadBlock(signalsBuff, blockId) {
            let args = [], items = signalsBuff.length;
            for(let i = 0; i < signalsBuff.length; i++) {
                SemaphoreUtils.verifyData(signalsBuff[i]["id"], signalsBuff[i]); // verify does transform
                args.push(await SemaphoreUtils.rowToJson(signalsBuff[i]));
            }
            store.SCPs[semaphoreCluster]
                .newTx("semaphore", "put-many", "semaphore-multi-put-" + blockId, args)
                .onError(x => {
                    this.updateUploadBlockState(blockId, "failed", x);
                    this.upload.failed += items;
                })
                .onAcknowledged(() => {
                    if(this.upload.blocks[blockId].state != "failed") {
                        this.updateUploadBlockState(blockId, "acknowledged");
                        this.upload.acknowledged += items;
                    }
                })
                .onExecuted(() => {
                    if(this.upload.blocks[blockId].state != "failed") {
                        this.updateUploadBlockState(blockId, "executed");
                        this.upload.executed += items;
                    }
                })
                .send();
        },
        uploadFile() {
            var self = this, signalsBuff = [];
            this.resetUpload();
            this.upload.showProgress = true;
            let onNewLine = function(row, parser) {
                    self.upload.read++;
                    signalsBuff.push(row.data[0]);
                    if(signalsBuff.length == self.upload.blockSize) {
                        self.upload.blocks.push({ state: "sent", items: signalsBuff.length, class: "bg-secondary", title: "loading" });
                        self.uploadBlock(signalsBuff.slice(0), self.upload.blocks.length - 1);
                        signalsBuff.length = 0;
                    }
                },
                onComplete = function() {
                    self.upload.read = self.rowsCount;
                    if(signalsBuff.length > 0) {
                        self.upload.blocks.push({ state: "sent", items: signalsBuff.length, class: "bg-secondary", title: "loading" });
                        self.uploadBlock(signalsBuff.slice(0), self.upload.blocks.length - 1);
                        signalsBuff.length = 0;
                    }
                    self.upload.verify.counter = 0;
                    self.upload.verify.issueMsg = "";
                    self.upload.verify.showRetry = false;
                    setTimeout(function() { self.verifyUpload(); }, 1600);
                },
                onError = function(e) {
                    self.upload.msg = "File reading error: " + e;
                };
            this.parseFile(onNewLine, onComplete, onError);
        },
        verifyUpload() {
            let u = this.upload, self = this, v = u.verify;
            if(u.executed == this.rowsCount) {
                u.msg = "Upload success";
                u.showProgress = u.showRetry = false;
                u.done = true;
                v.issueMsg = "";
                v.counter = 0;
                return;
            }
            u.msg = "Uploading... " + u.executed + " / " + this.rowsCount + " rows done. ";
            if(v.prevState.read !== undefined) {
                if(v.prevState.read == u.read && v.prevState.acknowledged == u.acknowledged &&
                v.prevState.executed == u.executed && v.prevState.failed == u.failed) {
                    v.issueMsg = "It is taking more time than usual. Waiting a bit... ";
                    v.counter++;
                } else {
                    v.counter = 0;
                    v.showRetry = false;
                }
            }
            if((u.read == (u.executed + u.failed)) || v.counter > this.timeBeforeRetry) {
                let c = "";
                v.issueMsg = "There are ";
                if(u.failed > 0) {
                    v.issueMsg += u.failed + " rows with errors";
                    c = ", ";
                }
                let unprocessed = u.acknowledged - u.failed - u.executed;
                if(unprocessed > 0) {
                    v.issueMsg += c + unprocessed + " rows unprocessed";
                    c = ", ";
                }
                let unconfirmed = u.read - u.failed - u.acknowledged - u.executed;
                if(unconfirmed > 0) {
                    v.issueMsg += c + unconfirmed + " rows unconfirmed";
                }
                v.issueMsg += ". Please try uploading these again.";
                v.showRetry = true;
            }
            v.prevState = { read: u.read, acknowledged: u.acknowledged, executed: u.executed, failed: u.failed };
            setTimeout(function() { self.verifyUpload(); }, 1600);
        },
        retryMissing() {
            var toRetry = [];
            for (let i = 0; i < this.upload.blocks.length; i++) {
                if(this.upload.blocks[i].state != "executed") {
                    toRetry.push(i);
                    this.upload[this.upload.blocks[i].state] -= this.upload.blockSize;
                }
            }
            var self = this, signalsBuff = [], blockId = 0,
                onNewLine = function(row, parser) {
                    signalsBuff.push(row.data[0]);
                    if(signalsBuff.length == self.upload.blockSize) {
                        if(toRetry.includes(blockId)) {
                            self.updateUploadBlockState(blockId, "sent", "retrying");
                            self.uploadBlock(signalsBuff.slice(0), blockId);
                        }
                        signalsBuff.length = 0;
                        blockId++;
                    }
                },
                onComplete = function() {
                    if(signalsBuff.length > 0 && toRetry.includes(blockId)) {
                        self.updateUploadBlockState(blockId, "sent", "retrying");
                        self.uploadBlock(signalsBuff.slice(0), blockId);
                        signalsBuff.length = 0;
                    }
                    self.upload.verify.counter = 0;
                    self.upload.verify.issueMsg = "";
                    setTimeout(function() { self.verifyUpload(); }, 1600);
                },
                onError = function(e) {
                    self.upload.msg = "File reading error: " + e;
                };
            this.parseFile(onNewLine, onComplete, onError);
        }
    }
});
const SemaphoreAppSignals = Vue.component('sec-semaphore-signals', {
    template: '#sec-semaphore-signals',
    data: function () {
        return {
            signals: [],
            ns: new sec.notifState(),
            download: {
                started: false, done: false, start: 0, step: 100, cursor: 0, stopped: false,
                msg: "", retries: 0, showRetry: false,
                writeStream: null, writer: null, transformer: null, beforeClose: null
            }
        }
    },
    beforeMount: function() {
        this.ns.start("Loading ...", true);
        store.SCPs[semaphoreCluster]
            .newQuery("semaphore", "get-signals", "semaphore-get-signals", { cursor: 0, max: 100 })
            .onError(x => {
                this.ns.failed(x, true);
                this.signals = [];
            })
            .onResult(x => {
                this.download.done = false;
                this.ns.executed().hide(0);
                let t = [];
                for(let i = 0; i < x.ids.length; i++) {
                    let fs = x.ids[i].fields, c = fs["Country"].contribution, n = fs["Company Registration Number"].contribution, s = [];
                    for(let name in fs) {
                        if(name == "Country" || name == "Company Registration Number") continue;
                        let f = fs[name];
                        if(f.split.length > 1) s.push(name);
                    }
                    if(s.length > 0) t.push({ country: c, regNum: n, fields: s.join(", ") });
                }
                this.signals = t;
            })
            .send();
    },
    beforeRouteLeave (to, from, next) {
        if(!this.download.started || this.download.stopped || this.download.done ||
                window.confirm('Leaving will cancel the download. Do you really want to leave ?')) {
            next();
        } else {
            next(false);
        }
    },
    methods: {
        downloadNextBlock() {
            this.download.msg = "Streaming ... " + this.download.cursor + "/" + (this.download.cursor + this.download.step);
            store.SCPs[semaphoreCluster]
                .newQuery("semaphore", "get-signals", "semaphore-get-signals", { cursor: this.download.cursor, max: this.download.step })
                .onError(x => {
                    if(this.download.stopped) return;
                    if(this.download.retries++ == 3) {
                        this.download.msg += " failed after 3 retries (" + x + ")";
                        this.download.showRetry = true;
                        return;
                    }
                    this.downloadNextBlock();
                })
                .onResult(x => {
                    if(this.download.stopped) return;
                    if(x["ids"].length == 0 && !x.last) {
                        this.download.done = true;
                        this.download.msg = "Stream error, nothing retrieved in this block.";
                        return;
                    }
                    this.download.retries = 0;
                    this.download.showRetry = false;
                    this.download.writer.write(sec.utils.encode(this.download.transformer(x)));
                    this.download.cursor += x["ids"].length;
                    if(x.last) {
                        this.download.done = true;
                        this.download.msg = "Stream completed. " + (this.download.cursor - this.download.start) + " signals retrieved.";
                        if(this.download.beforeClose != null) {
                            this.download.beforeClose();
                        }
                        this.download.writer.close();
                    } else {
                        this.downloadNextBlock();
                    }
                })
                .send();
        },
        retry() {
            this.download.retries = 0;
            this.download.showRetry = false;
            this.download.msg = "Retrying ...";
            this.downloadNextBlock();
        },
        stop() {
            this.download.retries = 0;
            this.download.showRetry = false;
            this.download.stopped = true;
            this.download.started = false;
            $("#semaphore-signals-report-cursor").val(this.download.cursor);
            this.download.msg = "Stream stopped. " + (this.download.cursor - this.download.start) + " signals retrieved.";
            if(this.download.beforeClose != null) {
                this.download.beforeClose();
            }
            this.download.writer.close();
        },
        signalToCsv(x) {
            let rows = "";
            for(let s of x["ids"]) {
                SemaphoreUtils.fillReport(s.fields);
                let row = Array(1 + 4 * Semaphore.fields.length).fill("");
                row[0] = s["id"];
                for(let f in s.fields) {
                    let i = SemaphoreUtils.fieldsIndex[f], j = 1 + i * 4;
                    row[j] = f;
                    row[j+1] = s.fields[f].total;
                    row[j+2] = "\"" + s.fields[f].contribution + "\"";
                    row[j+3] = "\"" + s.fields[f].report + "\"";
                }
                rows += row.join(",") + "\r\n";
            }
            return rows;
        },
        signalToJson(x) {
            let rows = "", addComma = this.download.cursor != 0;
            for(let s of x["ids"]) {
                SemaphoreUtils.fillReport(s.fields);
                if(addComma)
                    rows += ",";
                else
                    addComma = true;
                rows += "\r\n  " + JSON.stringify(s);
            }
            return rows;
        },
        startDownload() {
            let cursor = parseInt($("#semaphore-signals-report-cursor").val(), 10) || 0,
                step = parseInt($("#semaphore-signals-report-step").val(), 10) || 100;
            this.download.start = this.download.cursor = cursor;
            this.download.step = step;
            this.download.started = true;
            this.download.stopped = this.download.done = false;
            this.download.msg = "Starting ...";
            this.downloadNextBlock();
        },
        downloadAsCsv() {
            this.download.writeStream = streamSaver.createWriteStream('signals-report.csv');
            this.download.writer = this.download.writeStream.getWriter();
            this.download.transformer = this.signalToCsv;
            this.download.beforeClose = null;

            let header = "id";
            for(let f of Semaphore.fields) {
                header += ",name,total,contribution,report";
            }
            this.download.writer.write(sec.utils.encode(header + "\r\n"));
            this.startDownload();
        },
        downloadAsJson() {
            this.download.writeStream = streamSaver.createWriteStream('signals-report.json');
            this.download.writer = this.download.writeStream.getWriter();
            this.download.transformer = this.signalToJson;
            this.download.beforeClose = () => {
                this.download.writer.write(sec.utils.encode("\r\n}"));
            };

            this.download.writer.write(sec.utils.encode("{"));
            this.startDownload();
        }
    }
});
const SemaphoreAppPieChart = Vue.component('pie-chart', {
    template: "#sec-pie-chart",
    mounted() {
        this.anim = 0;
        setTimeout(() => this.animate(), 0);
    },
    props: ['data', 'colors'],
    data() {
        return {
            radius: 42,
            width: 14,
            space: 0.06,
            anim: 0
        }
    },
    computed: {
        total() {
            this.anim = 0;
            setTimeout(() => this.animate(), 10);
            return this.data.reduce((previous, current) => previous + current);
        },
        items() {
            let offset = -0.25, r = this.radius, w = r - this.width;
            return this.data.map((item, i) => {
                let sz = Math.sin(this.anim * Math.PI / 2) * item / this.total,
                    d1 = offset * 2 * Math.PI, cd1 = Math.cos(d1), sd1 = Math.sin(d1),
                    d2 = (offset + sz) * 2 * Math.PI,
                    x1 = r + r * cd1, y1 = r + r * sd1,
                    x2 = r + r * Math.cos(d2 - this.space * w / r), y2 = r + r * Math.sin(d2 - this.space * w / r),
                    x3 = r + w * Math.cos(d2 - this.space), y3 = r + w * Math.sin(d2 - this.space),
                    x4 = r + w * cd1, y4 = r + w * sd1,
                    f = sz > .5 ? 1 : 0;
                offset += sz;
                return {
                    path: `M ${x1},${y1} A ${r},${r} 0,${f},1 ${x2} ${y2} L ${x3},${y3} A ${w},${w} 0,${f},0 ${x4} ${y4} z`,
                    color: this.colors[i],
                    text: {
                        x: sz < 0.1 ? 0 : (r + (r - this.width / 2) * Math.cos((d1 + d2) / 2) - 2.5),
                        y: sz < 0.1 ? 0 : (r + (r - this.width / 2) * Math.sin((d1 + d2) / 2) + 2.5),
                        value: sz < 0.1 ? "" : item
                    }
                };
            })
        }
    },
    methods: {
        animate() {
            if(this.anim < 1) {
                this.anim += 0.02;
                setTimeout(() => this.animate(), 10);
            }
        }
    }
});
router.addRoutes([
    { path: '/semaphore', component: SemaphoreApp,
        children: [
            { path: '', component: SemaphoreAppWelcome },
            { path: 'connect', component: SemaphoreConnect },
            { path: 'register', component: SemaphoreRegister },
            { path: 'signals', component: SemaphoreAppSignals },
            { path: 'single-contribution', component: SemaphoreAppSingleContribution },
            { path: 'multi-contribution', component: SemaphoreAppMultiContribution }
        ]
    }
]);
store.dcapps["semaphore"].onLoad = () => (new Promise((resolve, reject) => {
    if(store.user.ECDSA == null || !store.SCPs[semaphoreCluster])
        router.replace("/semaphore/connect"); // key not loaded yet or not connected
    else
        resolve();
}));
store.dcapps["semaphore"].reset = () => (new Promise((resolve, reject) => {
    Vue.set(store.user.dcapps, "semaphore", { data: {}});
    resolve();
}));