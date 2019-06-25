"use strict"

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
const MADRecCluster = store.dcapps["madrec"].cluster;

Vue.set(store.user.dcapps, "madrec", {});
Vue.set(store.user.dcapps.madrec, "data", { accessStatus: "denied", grants: 0 });
const MADRecApp = Vue.component('sec-madrec', {
    template: '#sec-madrec',
    computed: {
        status() { return store.user.dcapps.madrec.data.accessStatus; },
        requestingMembers() { return store.user.dcapps.madrec.data.requestingMembers || 0; }
    }
});
const MADRecAppAccessDenied = Vue.component('sec-madrec-access-denied', {
    template: '#sec-madrec-access-denied',
    data: function () {
        return {
            nsRequest: new notifState(2)
        }
    },
    computed: {
        records() {
            var user = store.user.dcapps.identity.data, pr = user && user.personalRecords,
                phone = pr && pr.phone, email = pr && pr.email,
                hasFirstname = user && user.firstname && user.firstname.length > 0,
                hasLastname = user && user.lastname && user.lastname.length > 0,
                hasPhoneVerified = phone && phone.value && phone.verified,
                hasEmailVerified = email && email.value && email.verified;
            return {
                hasEnough: hasFirstname && hasLastname && (hasPhoneVerified || hasEmailVerified),
                hasFirstname: hasFirstname,
                hasLastname: hasLastname,
                hasPhoneVerified: hasPhoneVerified,
                hasEmailVerified: hasEmailVerified
            }
        }
    },
    methods: {
        requestAccess() {
            let args = { id: store.dcapps.madrec.id, items: [ "firstname", "lastname" ] },
                dcapp = store.dcapps["madrec"];
            if($("#madrec_ra_phone").is(":checked")) args.items.push("phone");
            if($("#madrec_ra_email").is(":checked")) args.items.push("email");
            this.nsRequest.start();
            store.SCPs[MADRecCluster]
                .sendTx("identity", "share-with", "identity-share-with", args)
                .onError(x => { this.nsRequest.failed(x, true); })
                .onAcknowledged(() => { this.nsRequest.acknowledged(); })
                .onProposed(() => { this.nsRequest.proposed(); })
                .onCommitted(() => { this.nsRequest.committed(); })
                .onExecuted(() => {
                    this.nsRequest.executed();
                    store.SCPs[MADRecCluster]
                        .sendTx("madrec", "request-access", "madrec-request-access", { role: "participant" })
                        .onError(x => { this.nsRequest.failed(x, true); })
                        .onAcknowledged(() => { this.nsRequest.acknowledged(); })
                        .onProposed(() => { this.nsRequest.proposed(); })
                        .onCommitted(() => { this.nsRequest.committed(); })
                        .onExecuted(() => { this.nsRequest.executed().hide(); });
                });
        }
    }
});
const MADRecAppAccessRequested = Vue.component('sec-madrec-access-requested', {
    template: '#sec-madrec-access-requested'
});
const MADRecAppWelcome = Vue.component('sec-madrec-welcome', {
    template: '#sec-madrec-welcome'
});
const MADRecAppMembers = Vue.component('sec-madrec-members', {
    template: '#sec-madrec-members',
    data: function () {
        return {
            ns: new notifState(),
            members: []
        }
    },
    beforeMount: function() {
        this.getMembers();
    },
    methods: {
        getMembers() {
            this.ns.start("Updating members list", true);
            store.SCPs[MADRecCluster]
                .sendQuery("madrec", "get-members", "madrec-get-members")
                .onError(x => { this.ns.failed(x, true); })
                .onResult(x => {
                    this.ns.executed().hide(1);
                    let rp = 0;
                    this.members = x.map(p => {
                        let o = {
                            firstname: p.identity.firstname, lastname: p.identity.lastname, cooptionId: p.cooptionId,
                            role: p.role, status: p.status, isSelf: p.isSelf, grants: p.grants || 0, vote: p.vote || ""
                        };
                        if(p.identity.personalRecords.phone) {
                            o.phone = p.identity.personalRecords.phone.value;
                            o.phoneVerified = p.identity.personalRecords.phone.verified;
                        }
                        if(p.identity.personalRecords.email) {
                            o.email = p.identity.personalRecords.email.value;
                            o.emailVerified = p.identity.personalRecords.email.verified;
                        }
                        if(p.status == "requested") rp++;
                        return o;
                    });
                    Vue.set(store.user.dcapps.madrec.data, "requestingMembers", rp);
                });
        },
        vote(status, cooptionId) {
            let args = { status: status, cooptionId: cooptionId };
            this.ns.start("Registering your vote", true);
            store.SCPs[MADRecCluster]
                .sendTx("madrec", "coopt", "madrec-coopt", args)
                .onError(x => { this.ns.failed(x, true); })
                .onAcknowledged(() => { this.ns.acknowledged(); })
                .onProposed(() => { this.ns.proposed(); })
                .onCommitted(() => { this.ns.committed(); })
                .onExecuted(() => {
                    this.ns.executed();
                    this.getMembers();
                });
        }
    }
});
const MADRecAppSingleLEI = Vue.component('sec-madrec-single-lei', {
    template: '#sec-madrec-single-lei',
    data: function () {
        return {
            nsPut: new notifState(),
            nsGet: new notifState(),
            values: {},
            results: {},
            exportUrl: "",
            warnings: [],
            fields: MADRec.fields,
            leiState: 0, //[invalid,valid,loaded]
            modifiedFields: {},
            toggledReport: -1
        }
    },
    mounted: function() {
        let lei = this.values.lei = MADRec.lei.sample;
        this.loadLEI(lei).onError(x => {
            this.nsGet.failed().hide(0);
            MADRec.fields.forEach(e => {
                if(e.sample) {
                    Vue.set(this.values, e.name, e.sample);
                    Vue.set(this.modifiedFields, e.name, true);
                }
            });
            this.leiState = 2;
        });
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
        getExportUrl(result) {
            let json = JSON.stringify(result, null, 4),
                blob = new Blob([json], { type: 'application/json;charset=utf-8;' });
            return URL.createObjectURL(blob);
        },
        loadLEI(lei = "", subscribe = true) {
            this.nsGet.start();
            lei = lei || $("#madrecSingleLei").val();
            return store.SCPs[MADRecCluster]
                .sendQuery("madrec", "get", "madrec-single-get", { [MADRec.lei.name]: lei, subscribe: subscribe })
                .onError(x => { this.nsGet.failed(x, true); })
                .onResult(x => {
                    this.nsGet.executed().hide();
                    MADRecUtils.fillReport(x.fields);
                    if(this.exportUrl != "") URL.revokeObjectURL(this.exportUrl);
                    this.exportUrl = this.getExportUrl(x.fields);
                    MADRecUtils.fillColors(x.fields);
                    Vue.set(this, "modifiedFields", {});
                    Vue.set(this, "values", { lei: lei });
                    Vue.set(this, "results", x.fields);
                    this.leiState = 2;
                    let mf = MADRec.fields.reduce((a, c) => { a[c.name] = c; return a; }, {});
                    for(let name in x.fields) {
                        let f = x.fields[name];
                        Vue.set(this.values, name, f.hashed ? (mf[name].type == "text" ? "(hashed)" : "") : f.contribution);
                        if(f.hashed && !this.results.hashed) Vue.set(this.results, "hashed", true);
                    }
                });
        },
        onLeiChanged() {
            let lei = $("#madrecSingleLei").val();
            Vue.set(this, "values", {});
            if(MADRec.lei.verifier(lei).success) {
                this.leiState = 1;
                Vue.set(this.values, "lei", lei);
            }
            else
                this.leiState = 0;
        },
        onFieldChanged(name, i) {
            let v = $("#madrecSingle-" + i).val(), x = this.results[name];
            Vue.set(this.modifiedFields, name, x == undefined || x.contribution != v);
        },
        onHashChanged() {
            let h = $('#madrecSingleHashOpt').val(), hashed = h == 2;
            for(let [i, f] of MADRec.fields.entries()) {
                let v = $("#madrecSingle-" + i).val() || "", x = this.results[f.name];
                if(hashed != this.results.hashed) { // hashing changed, update all fields with value
                    if(v != "") Vue.set(this.modifiedFields, f.name, true);
                } else {
                    Vue.set(this.modifiedFields, f.name, x && x.contribution != v || x == undefined && v != "");
                }
            }
        },
        async contributeLEI() {
            // Filter modified fields
            let lei = this.values.lei, val = {};
            this.onHashChanged(); // forces update of modified fields list
            for(let name in this.modifiedFields) {
                if(this.modifiedFields[name]) {
                    val[name] = this.values[name];
                }
            }
            // Verify data
            this.warnings = [];
            let check = MADRecUtils.verifyData(lei, val), w = check.warnings;
            if(check.success !== true) {
                this.nsPut.failed(check.field + " " + check.error, true);
                return;
            }
            if(check.isHashed) {
                this.nsPut.failed("Some of your data seem to be already hashed", true);
                return;
            }
            if(w) this.warnings = Object.keys(w).map(x => { return x + (w[x] > 1 ? " (" + w[x] + "  times)" : ""); });
            // Hashing
            let hashOpt = $('#madrecSingleHashOpt').val(),
                args = { [MADRec.lei.name]: lei, hashed: hashOpt == 2, values: [] };
            for(let v in val) {
                args.values.push({ name: v, value: val[v]});
            }
            if(hashOpt == 2) {
                let salt = await sec.utils.hashBase64(args[MADRec.lei.name]);
                args[MADRec.lei.name] = await MADRecUtils.hashData(salt, args[MADRec.lei.name]);
                for(let v of args.values) {
                    v.value = await MADRecUtils.hashData(salt, v.value);
                }
            }
            // Upload
            this.nsPut.start();
            store.SCPs[MADRecCluster]
                .sendTx("madrec", "put", "madrec-single-put", args)
                .onError(x => { this.nsPut.failed(x, true); })
                .onAcknowledged(() => { this.nsPut.acknowledged(); })
                .onProposed(() => { this.nsPut.proposed(); })
                .onCommitted(() => { this.nsPut.committed(); })
                .onExecuted(() => {
                    this.nsPut.executed().hide();
                    this.loadLEI(lei);
                });
        }
    }
});
const MADRecAppMultiLEI = Vue.component('sec-madrec-multi-lei', {
    template: '#sec-madrec-multi-lei',
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
                blockSize: 250, done: false, msg: "", read: 0, verified: 0, warnings: [], isHashed: undefined
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
            this.verify.isHashed = undefined;
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
                        if(check.isHashed && self.verify.isHashed == undefined) self.verify.isHashed = true;
                        else if(check.isHashed && self.verify.isHashed == false) {
                            let row = (self.rowsCount - self.verify.blockSize + i + 2);
                            self.verify.msg = "Inconsistent hashing for LEI '" + leiBuff[i][MADRec.lei.name] + "' on row " + row;
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
        updateUploadBlockState(id, state, msg = "") {
            this.upload.blocks[id].state = state;
            this.upload.blocks[id].class = this.progressColors[state];
            this.upload.blocks[id].title = msg || state;
        },
        async uploadBlock(leiBuff, blockId, hashOpt) {
            let args = [], items = leiBuff.length;
            for(let i = 0; i < leiBuff.length; i++) {
                if(hashOpt < 3) MADRecUtils.verifyData(leiBuff[i][MADRec.lei.name], leiBuff[i]); // verify does transform
                args.push(await MADRecUtils.rowToJson(leiBuff[i], hashOpt));
            }
            store.SCPs[MADRecCluster]
                .sendTx("madrec", "put-many", "madrec-multi-put-" + blockId, args)
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
                });
        },
        uploadFile() {
            var self = this, leiBuff = [], hashOpt = $('#madrecMultiPutHash').val();
            if(hashOpt < 3 && this.verify.isHashed) {
                this.upload.msg = "Your data is hashed, please select the 'Already hashed' option";
                return;
            } else if(hashOpt == 3 && !this.verify.isHashed) {
                this.upload.msg = "Your data is not hashed, can't send it with the 'Already hashed' option";
                return;
            }
            this.resetUpload();
            this.upload.showProgress = true;
            let onNewLine = function(row, parser) {
                    self.upload.read++;
                    leiBuff.push(row.data[0]);
                    if(leiBuff.length == self.upload.blockSize) {
                        self.upload.blocks.push({ state: "sent", items: leiBuff.length, class: "bg-secondary", title: "loading" });
                        self.uploadBlock(leiBuff.slice(0), self.upload.blocks.length - 1, hashOpt);
                        leiBuff.length = 0;
                    }
                },
                onComplete = function() {
                    self.upload.read = self.rowsCount;
                    if(leiBuff.length > 0) {
                        self.upload.blocks.push({ state: "sent", items: leiBuff.length, class: "bg-secondary", title: "loading" });
                        self.uploadBlock(leiBuff.slice(0), self.upload.blocks.length - 1, hashOpt);
                        leiBuff.length = 0;
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
            u.msg = "Uploading... " + u.executed + " / " + this.rowsCount + " LEIs done. ";
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
                    v.issueMsg += u.failed + " LEIs with errors";
                    c = ", ";
                }
                let unprocessed = u.acknowledged - u.failed - u.executed;
                if(unprocessed > 0) {
                    v.issueMsg += c + unprocessed + " LEIs unprocessed";
                    c = ", ";
                }
                let unconfirmed = u.read - u.failed - u.acknowledged - u.executed;
                if(unconfirmed > 0) {
                    v.issueMsg += c + unconfirmed + " LEIs unconfirmed";
                }
                v.issueMsg += ". Please try uploading these again.";
                v.showRetry = true;
            }
            v.prevState = { read: u.read, acknowledged: u.acknowledged, executed: u.executed, failed: u.failed };
            setTimeout(function() { self.verifyUpload(); }, 1600);
        },
        retryMissing() {
            var toRetry = [], hashOpt = $('#madrecMultiPutHash').val();
            for (let i = 0; i < this.upload.blocks.length; i++) {
                if(this.upload.blocks[i].state != "executed") {
                    toRetry.push(i);
                    this.upload[this.upload.blocks[i].state] -= this.upload.blockSize;
                }
            }
            var self = this, leiBuff = [], blockId = 0,
                onNewLine = function(row, parser) {
                    leiBuff.push(row.data[0]);
                    if(leiBuff.length == self.upload.blockSize) {
                        if(toRetry.includes(blockId)) {
                            self.updateUploadBlockState(blockId, "sent", "retrying");
                            self.uploadBlock(leiBuff.slice(0), blockId, hashOpt);
                        }
                        leiBuff.length = 0;
                        blockId++;
                    }
                },
                onComplete = function() {
                    if(leiBuff.length > 0 && toRetry.includes(blockId)) {
                        self.updateUploadBlockState(blockId, "sent", "retrying");
                        self.uploadBlock(leiBuff.slice(0), blockId, hashOpt);
                        leiBuff.length = 0;
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
const MADRecAppReports = Vue.component('sec-madrec-report', {
    template: '#sec-madrec-report',
    data: function () {
        return {
            personalReport: [],
            personalReportChart: null,
            personalReportObjUrl: "",
            consortiumReport: [],
            consortiumReportChart: null,
            consortiumReportObjUrl: "",
            nsUserReport: new notifState(),
            nsConsortiumReport: new notifState(),
            download: {
                started: false, done: false, start: 0, step: 100, cursor: 0, stopped: false,
                msg: "", retries: 0, showRetry: false,
                writeStream: null, writer: null, transformer: null, beforeClose: null
            }
        }
    },
    beforeMount: function() {
        this.nsUserReport.start("Loading ...", true);
        store.SCPs[MADRecCluster]
            .sendQuery("madrec", "get-report", "madrec-get-report-user")
            .onError(x => {
                this.nsUserReport.failed(x, true);
                if(this.consortiumReportChart != null)
                    this.consortiumReportChart.clear();
                this.personalReport = [];
            })
            .onResult(x => {
                this.download.done = false;
                this.nsUserReport.executed().hide(0);
                this.personalReport = x;
                setTimeout(() => { this.drawUserReport(x); }, 200);
            });

        this.nsConsortiumReport.start("Loading ...", true);
        store.SCPs[MADRecCluster]
            .sendQuery("madrec", "get-report-consortium", "madrec-get-report-consortium")
            .onError(x => {
                this.nsConsortiumReport.failed(x, true);
                if(this.consortiumReportChart != null)
                    this.consortiumReportChart.clear();
                this.consortiumReport = [];
            })
            .onResult(x => {
                this.download.done = false;
                this.nsConsortiumReport.executed().hide(0);
                this.consortiumReport = x;
                setTimeout(() => { this.drawConsortiumReport(x); }, 200);
            });
    },
    beforeDestroy() {
        URL.revokeObjectURL(this.personalReportObjUrl);
        URL.revokeObjectURL(this.consortiumReportObjUrl);
    },
    beforeRouteLeave (to, from, next) {
        if(!this.download.started || this.download.stopped || this.download.done ||
                window.confirm('Leaving will cancel the download. Do you really want to leave ?')) {
            next();
        } else {
            next(false);
        }
    },
    computed: {
        personalReportExportUrl() {
            let json = JSON.stringify(this.personalReport, null, 4),
                blob = new Blob([json], { type: 'application/json;charset=utf-8;' });
            if(this.personalReportObjUrl != "") URL.revokeObjectURL(this.personalReportObjUrl);
            this.personalReportObjUrl = URL.createObjectURL(blob);
            return this.personalReportObjUrl;
        },
        consortiumReportExportUrl() {
            let json = JSON.stringify(this.consortiumReport, null, 4),
                blob = new Blob([json], { type: 'application/json;charset=utf-8;' });
            if(this.consortiumReportObjUrl != "") URL.revokeObjectURL(this.consortiumReportObjUrl);
            this.consortiumReportObjUrl = URL.createObjectURL(blob);
            return this.consortiumReportObjUrl;
        }
    },
    methods: {
        createChart(el, labels, datasets) {
            return new Chart(el, {
                type: 'horizontalBar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    scales: {
                        xAxes: [{ stacked: true }],
                        yAxes: [{ stacked: true, categoryPercentage: 0.5 }]
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: { /*classic tooltip drawn into the canvas do not have enough space*/
                        enabled: false,
                        custom: function(m) {
                            var e = $('#chartjs-tooltip');
                            if(e.length == 0) e = $('<div id="chartjs-tooltip"><table style="line-height: 1;"></table></div>').appendTo('body');
                            if (m.opacity === 0) { e.css({"opacity": 0, display: "none" }); return; }
                            e.removeClass('above', 'below', 'no-transform');
                            if (m.yAlign) e.addClass(m.yAlign);
                            else e.addClass('no-transform');
                            if (m.body) {
                                let innerHtml = '<thead>';
                                (m.title || []).forEach(function(title) {
                                    innerHtml += '<tr><th>' + title + '</th></tr>';
                                });
                                innerHtml += '</thead><tbody>';
                                m.body.map(x => x.lines).forEach(function(body, i) {
                                    let style = 'background:' + m.labelColors[i].backgroundColor +
                                        '; border: 1px solid rgba(255,255,255,.9); display: inline-block' +
                                        '; height: 10px; width: 10px; margin-right: 3px;';
                                    innerHtml += '<tr><td><span style="' + style + '"></span>' + body + '</td></tr>';
                                });
                                innerHtml += '</tbody>';
                                e.find('table').html(innerHtml);
                            }
                            var position = this._chart.canvas.getBoundingClientRect();
                            e.css({
                                opacity: 1, position: 'absolute', padding: '6px', display: "block",
                                left: position.left + m.caretX + 'px', top: position.top + m.caretY + 'px',
                                //fontFamily: m._bodyFontFamily, fontSize: m.bodyFontSize + 'px', fontStyle: m._bodyFontStyle,
                                'background-color': 'rgba(0, 0, 0, .8)', 'border-radius': '4px', color: '#fff'
                            });
                        }
                    }
                }
            });
        },
        drawUserReport(o) {
            let c = $('#madrec-user-report')[0];
            if(c === undefined) return;
            let sets = ["full consensus", "split consensus with majority", "no consensus", "split consensus with minority", "no match"],
                colors = ["#218838", "#28a745", "#ffc107", "#fd7e14", "#dee2e6"],
                fields = o.map(x => x.name), self = this;
            if(this.personalReportChart == null) {
                let datasets = sets.map((z, i) => {
                    return { label: z, backgroundColor: colors[i], data: o.map(x => (x[z] || 0)) };
                });
                this.personalReportChart = this.createChart(c, fields, datasets);
            } else {
                this.personalReportChart.data.labels = fields;
                sets.map((z, i) => {
                    return self.personalReportChart.data.datasets[i].data = o.map(x => (x[z] || 0));
                });
            }
            this.personalReportChart.canvas.parentNode.style.height = (70 + 16 * o.length) + "px";
            this.personalReportChart.update();
        },
        drawConsortiumReport(o) {
            let c = $('#madrec-consortium-report')[0];
            if(c === undefined) return;
            let sets = ["full consensus", "split consensus", "no consensus", "no match"],
                colors = ["#218838", "#28a745", "#ffc107", "#dee2e6"],
                fields = o.map(x => x.name + " (" + x.participants + ")"), self = this;
            if(this.consortiumReportChart == null) {
                let datasets = sets.map((z, i) => {
                    return { label: z, backgroundColor: colors[i], data: o.map(x => (x[z] || 0)) };
                });
                this.consortiumReportChart = this.createChart(c, fields, datasets);
            } else {
                this.consortiumReportChart.data.labels = fields;
                sets.map((z, i) => {
                    return self.consortiumReportChart.data.datasets[i].data = o.map(x => (x[z] || 0));
                });
            }
            this.consortiumReportChart.canvas.parentNode.style.height = (60 + 15 * o.length) + 'px';
            this.consortiumReportChart.update();
        },
        downloadNextBlock() {
            this.download.msg = "Streaming ... " + this.download.cursor + "/" + (this.download.cursor + this.download.step);
            store.SCPs[MADRecCluster]
                .sendQuery("madrec", "get-leis", "madrec-get-leis", { cursor: this.download.cursor, maxLEIs: this.download.step })
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
                    if(x["LEI Codes"].length == 0 && !x.last) {
                        this.download.done = true;
                        this.download.msg = "Stream error, nothing retrieved in this block.";
                        return;
                    }
                    this.download.retries = 0;
                    this.download.showRetry = false;
                    this.download.writer.write(sec.utils.encode(this.download.transformer(x)));
                    this.download.cursor += x["LEI Codes"].length;
                    if(x.last) {
                        this.download.done = true;
                        this.download.msg = "Stream completed. " + (this.download.cursor - this.download.start) + " LEIs retrieved.";
                        if(this.download.beforeClose != null) {
                            this.download.beforeClose();
                        }
                        this.download.writer.close();
                    } else {
                        this.downloadNextBlock();
                    }
                });
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
            this.download.msg = "Stream stopped. " + (this.download.cursor - this.download.start) + " LEIs retrieved.";
            if(this.download.beforeClose != null) {
                this.download.beforeClose();
            }
            this.download.writer.close();
        },
        leisToCsv(x) {
            let rows = "";
            for(let lei of x["LEI Codes"]) {
                MADRecUtils.fillReport(lei.fields);
                let row = Array(1 + 4 * MADRec.fields.length).fill("");
                row[0] = lei[MADRec.lei.name];
                for(let f in lei.fields) {
                    let i = MADRecUtils.fieldsIndex[f], j = 1 + i * 4;
                    row[j] = f;
                    row[j+1] = lei.fields[f].total;
                    row[j+2] = "\"" + lei.fields[f].contribution + "\"";
                    row[j+3] = "\"" + lei.fields[f].report + "\"";
                }
                rows += row.join(",") + "\r\n";
            }
            return rows;
        },
        leisToJson(x) {
            let rows = "", addComma = this.download.cursor != 0;
            for(let lei of x["LEI Codes"]) {
                MADRecUtils.fillReport(lei.fields);
                if(addComma)
                    rows += ",";
                else
                    addComma = true;
                rows += "\r\n  " + JSON.stringify(lei);
            }
            return rows;
        },
        startDownload() {
            let cursor = parseInt($("#madrec-leis-report-cursor").val(), 10) || 0,
                step = parseInt($("#madrec-leis-report-step").val(), 10) || 100;
            this.download.start = this.download.cursor = cursor;
            this.download.step = step;
            this.download.started = true;
            this.download.stopped = this.download.done = false;
            this.download.msg = "Starting ...";
            this.downloadNextBlock();
        },
        downloadAsCsv() {
            this.download.writeStream = streamSaver.createWriteStream('leis-report.csv');
            this.download.writer = this.download.writeStream.getWriter();
            this.download.transformer = this.leisToCsv;
            this.download.beforeClose = null;

            let header = MADRec.lei.name;
            for(let f of MADRec.fields) {
                header += ",name,total,contribution,report";
            }
            this.download.writer.write(sec.utils.encode(header + "\r\n"));
            this.startDownload();
        },
        downloadAsJson() {
            this.download.writeStream = streamSaver.createWriteStream('leis-report.json');
            this.download.writer = this.download.writeStream.getWriter();
            this.download.transformer = this.leisToJson;
            this.download.beforeClose = () => {
                this.download.writer.write(sec.utils.encode("\r\n}"));
            };

            this.download.writer.write(sec.utils.encode("{"));
            this.startDownload();
        }
    }
});
const PieChart = Vue.component('pie-chart', {
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
    { path: '/madrec', component: MADRecApp,
        children: [
            { path: '', component: MADRecAppWelcome },
            { path: 'members', component: MADRecAppMembers },
            { path: 'single-lei', component: MADRecAppSingleLEI, meta: { dcappName: "MADRec" } },
            { path: 'multi-lei', component: MADRecAppMultiLEI },
            { path: 'reports', component: MADRecAppReports }
        ]
    }
]);
store.dcapps["madrec"].onLoad = new Promise((resolve, reject) => {
    store.SCPs[MADRecCluster]
        .sendQuery("madrec", "get-status", "madrec-get-status")
        .onResult(x => {
            Vue.set(store.user.dcapps.madrec.data, "accessStatus", x.status);
            Vue.set(store.user.dcapps.madrec.data, "grants", x.grants || 0);
            resolve();
        })
        .onError(x => { resolve(); })
});