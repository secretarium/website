"use strict"

var sec, secretarium = sec = {

    knownTrustedKey: "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",

    states: {
        socket: ["connecting", "open", "closing", "closed"],
        security:[
            "",
            "secure connection in progress",
            "secure connection established",
            "secure connection failed"
        ]
    },

    scp: class {

        constructor() {
            this.reset();
            this.requests = {};
        }

        reset() {
            this.socket = new nng.WebSocket();
            this.handlers = {
                socket: {
                    onMessage: null
                },
                onError: null,
                onMessage: null,
                state: {
                    onChange: null
                }
            },
            this.security = {
                state: 0,
                client: {},
                server: {}
            };
            return this;
        }

        get securityState() {
            return this.security.state;
        }

        _updateState(id) {
            this.security.state = id;
            if(this.handlers.state.onChange != null)
                this.handlers.state.onChange(id);
        }

        connect(url, protocol, userKey) {
            let s = new nng.WebSocket(), self = this,
                secKnownPubKey = new Uint8Array(sec.utils.base64ToUint8Array(sec.knownTrustedKey));

            this.socket = s;
            this._updateState(0);

            return new Promise((resolve, reject) => {
                s.on("open", e => { resolve() })
                .on("close", e => { reject() })
                .on("error", e => { reject() })
                .connect(url, protocol);
            })
            .then(async () => {
                self._updateState(1);
                s.on("open", e => { });
                s.on("close", e => { self._updateState(0); });
                s.on("error", e => { self._updateState(0); });
                let userPubExp = await window.crypto.subtle.exportKey("raw", userKey.publicKey);
                self.security.client.ecdsaPubRaw = new Uint8Array(userPubExp).subarray(1);
                console.debug("client ECDSA pub key:" + Array.apply([], self.security.client.ecdsaPubRaw).join(","));
                self.security.client.ecdh = await window.crypto.subtle.generateKey({ name: "ECDH", namedCurve: "P-256" }, true, ["deriveBits"]);
                let ecdhPubExp = await window.crypto.subtle.exportKey("raw", self.security.client.ecdh.publicKey);
                self.security.client.ecdhPubRaw = new Uint8Array(ecdhPubExp).subarray(1);
                console.debug("client ephemereal ECDH pub key:" + Array.apply([], self.security.client.ecdhPubRaw).join(","));
                return new Promise((resolve, reject) => {
                    let clientHello = self.security.client.ecdhPubRaw;
                    s.on("message", x => { resolve(x); }).send(clientHello);
                });
            })
            .then(serverHello => {
                let nonce = serverHello.subarray(0, 32),
                    pow = sec.utils.getRandomUint8Array(32); // pow disabled for this demo
                return new Promise((resolve, reject) => {
                    let clientProofOfWork = sec.utils.concatUint8Arrays([pow, secKnownPubKey]);
                    s.on("message", x => { resolve(x); }).send(clientProofOfWork);
                })
            })
            .then(async serverIdentity => {
                self.security.server.preMasterSecret = new Uint8Array(serverIdentity.subarray(0, 32));
                self.security.server.ecdhPub = await sec.utils.ecdh.importPub(sec.utils.concatUint8Array(/*uncompressed*/[4], serverIdentity.subarray(32, 96)));
                self.security.server.ecdsaPub = await sec.utils.ecdsa.importPub(sec.utils.concatUint8Array(/*uncompressed*/[4], serverIdentity.subarray(serverIdentity.length - 64)));

                // Check inheritance from Secretarium KnownPubKey
                let secKnownPubKeyPath = new Uint8Array(serverIdentity.subarray(96));
                if (secKnownPubKeyPath.length == 64) {
                    if(!secKnownPubKey.secSequenceEqual(secKnownPubKeyPath))
                        throw "Invalid server proof of identity";
                }
                else {
                    for (var i = 0; i < secKnownPubKeyPath.length - 64; i = i + 128) {
                        let key = secKnownPubKeyPath.subarray(i, 64), proof = secKnownPubKeyPath.subarray(i + 64, 64),
                            keyChild = secKnownPubKeyPath.subarray(i + 128, 64),
                            ecdsaKey = await sec.utils.ecdsa.importPub(sec.utils.concatUint8Array(/*uncompressed*/[4], key));
                        if (!await sec.utils.ecdsa.verify(keyChild, proof, ecdsaKey))
                            throw "Invalid server proof of identity #" + i;
                    }
                }

                let commonSecret = await sec.utils.ecdh.deriveBits(self.security.server.ecdhPub, self.security.client.ecdh.privateKey),
                    sha256Common = await sec.utils.hash(commonSecret),
                    symmetricKey = self.security.server.preMasterSecret.secXor(new Uint8Array(sha256Common)),
                    key = symmetricKey.subarray(0, 16);
                self.aesctr = {
                    key: key, iv: symmetricKey.subarray(16),
                    cryptokey: await window.crypto.subtle.importKey("raw", key, { name: "AES-CTR" }, false, ["encrypt", "decrypt"])
                }
                console.debug("aesctr.key:" + Array.apply([], self.aesctr.key).join(","));
                console.debug("aesctr.iv:" + Array.apply([], self.aesctr.iv).join(","));

                let nonce = sec.utils.getRandomUint8Array(32),
                    signedNonce = new Uint8Array(await sec.utils.ecdsa.sign(nonce, userKey.privateKey)),
                    clientProofOfIdentity = sec.utils.concatUint8Arrays(
                        [nonce, self.security.client.ecdhPubRaw, self.security.client.ecdsaPubRaw, signedNonce]),
                    ivOffset = sec.utils.getRandomUint8Array(16),
                    iv = self.aesctr.iv.secIncrementBy(ivOffset),
                    encryptedClientProofOfIdentity = await window.crypto.subtle.encrypt(
                        { name: "AES-CTR", counter: iv, length: 128 }, self.aesctr.cryptokey, clientProofOfIdentity)
                console.debug("ivOffset:" + Array.apply([], ivOffset).join(","));
                console.debug("ivIncremented:" + Array.apply([], iv).join(","));
                console.debug("clientProofOfIdentity:" + Array.apply([], clientProofOfIdentity).join(","));
                return new Promise((resolve, reject) => {
                    let m = sec.utils.concatUint8Arrays([ivOffset, new Uint8Array(encryptedClientProofOfIdentity)]);
                    s.on("message", x => { resolve(x); }).send(m);
                })
            })
            .then(async serverProofOfIdentityEncrypted => {
                let ivOffset = serverProofOfIdentityEncrypted.subarray(0, 16),
                    iv = self.aesctr.iv.secIncrementBy(ivOffset),
                    serverProofOfIdentity = await window.crypto.subtle.decrypt(
                        { name: "AES-CTR", counter: iv, length: 128 }, self.aesctr.cryptokey, serverProofOfIdentityEncrypted.subarray(16)),
                    welcome = sec.utils.encode("Hey you! Welcome to Secretarium!"),
                    toVerify = sec.utils.concatUint8Array(new Uint8Array(serverProofOfIdentity).subarray(0, 32), welcome),
                    serverSignedHash = new Uint8Array(serverProofOfIdentity).subarray(32, 96),
                    ok = await sec.utils.ecdsa.verify(toVerify, serverSignedHash, self.security.server.ecdsaPub);
                if(!ok)
                    throw "Invalid server proof of identity";

                self._updateState(2);
                if(self.handlers.onMessage == null)
                    self.handlers.onMessage = self._notify.bind(self);
                s.on("message", self._onMessage.bind(self));
                self.connectionArgs = [url, protocol, userKey];
            })
            .catch(err => {
                console.error(err);
                self._updateState(3);
                s.close();
                throw "secure connection failed";
            });
        }

        async _onMessage(x) {
            let self = this, iv = self.aesctr.iv.secIncrementBy(x.subarray(0, 16));
            let decrypted = await window.crypto.subtle.decrypt(
                    { name: "AES-CTR", counter: iv, length: 128 }, self.aesctr.cryptokey, x.subarray(16)),
                msg = sec.utils.decode(new Uint8Array(decrypted));
            console.debug("received:" + msg);
            self.handlers.onMessage(msg);
        }

        _notify(msg) {
            try {
                let o = JSON.parse(msg);
                if(o != null && o.requestId) {
                    if(this.requests[o.requestId]) {
                        if(o.error) {
                            this.requests[o.requestId].failed = true;
                            if(this.requests[o.requestId]["onError"])
                                this.requests[o.requestId]["onError"](o.error);
                        }
                        else if(o.result) {
                            if(this.requests[o.requestId]["onResult"])
                                this.requests[o.requestId]["onResult"](o.result);
                        }
                        else if(o.state) {
                            if(this.requests[o.requestId].failed === true)
                                return;
                            o.state = o.state.toLowerCase();
                            if(o.state == "acknowledged") {
                                if(this.requests[o.requestId]["onAcknowledged"])
                                    this.requests[o.requestId]["onAcknowledged"]();
                            }
                            else if(o.state == "proposed") {
                                if(this.requests[o.requestId]["onProposed"])
                                    this.requests[o.requestId]["onProposed"]();
                            }
                            else if(o.state == "committed") {
                                if(this.requests[o.requestId]["onCommitted"])
                                    this.requests[o.requestId]["onCommitted"]();
                            }
                            else if(o.state == "executed") {
                                if(this.requests[o.requestId]["onExecuted"])
                                    this.requests[o.requestId]["onExecuted"]();
                            }
                            else if(o.state == "failed") {
                                this.requests[o.requestId].failed = true;
                                if(this.requests[o.requestId]["onError"])
                                    this.requests[o.requestId]["onError"]("failed");
                            }
                        }
                        else {
                            if(this.requests[o.requestId]["onResult"])
                                if(this.requests[o.requestId]["onResult"](o) !== false)
                                    delete this.requests[o.requestId];
                        }
                    }
                }
            }
            catch(e) {
                let m = "Error '" + e.message + "' when received '" + msg + "'";
                if(this.handlers.onError)
                    this.handlers.onError(m);
                else
                    console.error(m);
            }
        }

        on(evt, handler) {
            if(evt == "message") {
                this.handlers.onMessage = handler;
            } else if(evt == "close") {
                this.socket.on(evt, handler);
            } else if(evt == "open") {
                this.socket.on(evt, handler);
            } else if(evt == "error") {
                this.handlers.onError = handler;
                this.socket.on(evt, handler);
            } else if(evt == "statechange") {
                this.handlers.state.onChange = handler;
            }
            return this;
        }

        sendQuery(dcapp, command, requestId, args) {
            let query = JSON.stringify({ "dcapp": dcapp, "function": command, "requestId": requestId, args: args });
            console.debug("sending:" + query);
            this.send(sec.utils.encode(query));
            if(requestId) {
                let cbs = this.requests[requestId] = {}, p = { resolve: null, reject: null },
                    res = {
                        onError: x => { cbs["onError"] = x; if(p.reject != null) p.reject(); return res; },
                        onResult: x => { cbs["onResult"] = x; if(p.resolve != null) p.resolve(); return res; },
                        promise: () => { return new Promise((resolve, reject) => { p.resolve = resolve; p.reject = reject; })}
                    };
                return res;
            }
            return this;
        }

        sendTx(dcapp, command, requestId, args) {
            let query = JSON.stringify({ "dcapp": dcapp, "function": command, "requestId": requestId, args: args });
            console.debug("sending:" + query);
            this.send(sec.utils.encode(query));
            if(requestId) {
                let cbs = this.requests[requestId] = {}, p = { resolve: null, reject: null },
                    res = {
                        onError: x => { cbs["onError"] = x; if(p.reject != null) p.reject(); return res; },
                        onAcknowledged: x => { cbs["onAcknowledged"] = x; return res; },
                        onProposed: x => { cbs["onProposed"] = x; return res; },
                        onCommitted: x => { cbs["onCommitted"] = x; return res; },
                        onExecuted: x => { cbs["onExecuted"] = x; if(p.resolve != null) p.resolve(); return res; },
                        promise: () => { return new Promise((resolve, reject) => { p.resolve = resolve; p.reject = reject; })}
                    };
                return res;
            }
            return this;
        }

        async send(data) {
            if (this.socket.state !== 1)
                return;

            let ivOffset = sec.utils.getRandomUint8Array(16),
                iv = this.aesctr.iv.secIncrementBy(ivOffset), self = this;

            return window.crypto.subtle.encrypt({ name: "AES-CTR", counter: iv, length: 128 }, this.aesctr.cryptokey, data)
                .then(msg => {
                    let full = sec.utils.concatUint8Array(ivOffset, new Uint8Array(msg));
                    self.socket.send(full);
                });
        }

        close() {
            this.socket.close();
            return this;
        }
    },

    keysManager: class {

        constructor() {
            this.keys = {};
            this.exports = {};
            this.cryptoKeys = {};

            if (sec.utils.localStorage.canUse) {
                let v = localStorage.getItem('secretarium-keys');
                if(v != null) {
                    try {
                        let keys = JSON.parse(v);
                        for(var name in keys) {
                            this.keys[name] = keys[name];
                            this.exports[name] = this._createObjectURL(keys[name]);
                        }
                    } catch (e) { }
                }
            }
        }

        _createObjectURL(key) {
            let j = JSON.stringify(this._toExportable(key)),
                b = new Blob([j], { type: 'application/json;charset=utf-8;' });
            return URL.createObjectURL(b);
        }

        _toExportable(key) {
            let exp = { };
            if(key.encrypted) {
                exp.encrypted = true;
                exp.iv = key.iv;
                exp.salt = key.salt;
                exp.encryptedKeys = key.encryptedKeys;
            } else {
                exp.keys = key.keys;
            }
            return exp;
        }

        async createKey(name, save = true) {
            if(name.length == 0) throw "Invalid key name";

            let cryptoKey = await sec.utils.ecdsa.generateKeyPair(true),
                publicKey = new Uint8Array(await sec.utils.ecdsa.exportPub(cryptoKey, "raw")),
                privateKey = new Uint8Array(await sec.utils.ecdsa.exportPri(cryptoKey, "pkcs8")),
                keys = sec.utils.concatUint8Array(publicKey, privateKey).secToBase64();

            this.cryptoKeys[name] = cryptoKey;
            this.addKey(name, { keys: keys }, save);
            return cryptoKey;
        }

        importKeyFile(evt, save = true) {
            return new Promise((resolve, reject) => {
                let e = evt.dataTransfer || evt.target; // dragged or browsed
                if(!e || !e.files) reject("Unsupported, missing key file");
                if(e.files.length != 1) reject("Unsupported, expecting a single key file");

                let reader = new FileReader(), file = e.files[0], name = file.name;
                reader.onloadend = x => {
                    try {
                        let key = JSON.parse(reader.result);
                        if(key.iv && !key.encrypted) { // retro comp
                            key.encrypted = true;
                            key.encryptedKeys = key.keys;
                            delete key.keys;
                        }
                        this.addKey(name, key, save);
                        resolve(name);
                    }
                    catch (e) { reject(e.message); }
                };
                reader.onerror = e => { reject(e.message); };
                reader.readAsText(file);
            });
        }

        async encryptKey(name, pwd, save = true) {
            let key = this.keys[name];
            if(name.length == 0 || !key) throw "Invalid key name";
            if(!key.keys) throw "Key is encrypted";

            let salt = sec.utils.getRandomUint8Array(32),
                iv = sec.utils.getRandomUint8Array(12),
                weakPwd = sec.utils.encode(pwd),
                strongPwd = await sec.utils.hash(sec.utils.concatUint8Array(salt, weakPwd)),
                aesgcmKey = await sec.utils.aesgcm.import(strongPwd),
                keys = Uint8Array.secFromBase64(key.keys),
                encryptedKeys = await sec.utils.aesgcm.encrypt(aesgcmKey, iv, keys);
            key.salt = salt.secToBase64();
            key.iv = iv.secToBase64();
            key.encryptedKeys = new Uint8Array(encryptedKeys).secToBase64();
            key.encrypted = true;
            this.addKey(name, key, save);
            return key;
        }

        async decryptKey(name, pwd) {
            let key = this.keys[name];
            if(name.length == 0 || !key) throw "Invalid key name";
            if(!key.encrypted) return await this.getCryptoKey(name);

            let iv = Uint8Array.secFromBase64(key.iv),
                salt = Uint8Array.secFromBase64(key.salt),
                encryptedKeys = Uint8Array.secFromBase64(key.encryptedKeys),
                weakpwd = sec.utils.encode(pwd),
                strongPwd = await sec.utils.hash(sec.utils.concatUint8Array(salt, weakpwd)),
                aesgcmKey = await sec.utils.aesgcm.import(strongPwd);
            try {
                let keys = new Uint8Array(await sec.utils.aesgcm.decrypt(aesgcmKey, iv, encryptedKeys));
                key.keys = keys.secToBase64();
                return this.cryptoKeys[name] = {
                    publicKey: await sec.utils.ecdsa.importPub(keys.subarray(0, 65), "raw"),
                    privateKey: await sec.utils.ecdsa.importPri(keys.subarray(65), "pkcs8")
                };
            } catch (e) { throw "can't decrypt/invalid password"; }
        }

        async getCryptoKey(name) {
            let key = this.keys[name];
            if(name.length == 0 || !key) throw "Invalid key name";
            if(!key.keys) throw "The key is encrypted";
            if(this.cryptoKeys[name]) return this.cryptoKeys[name];

            let keys = Uint8Array.secFromBase64(key.keys);
            return this.cryptoKeys[name] = {
                publicKey: await sec.utils.ecdsa.importPub(keys.subarray(0, 65), "raw"),
                privateKey: await sec.utils.ecdsa.importPri(keys.subarray(65), "pkcs8")
            };
        }

        getPublicKeyHex(name, delimiter = '') {
            let key = this.keys[name];
            if(name.length == 0 || !key) throw "Invalid key name";
            return Uint8Array.secFromBase64(key.keys).subarray(0, 65).secToHex(delimiter);
        }

        addKey(name, key, save = true) {
            if(this.exports[name]) {
                URL.revokeObjectURL(this.exports[name]);
            }
            this.keys[name] = this._toExportable(key);
            this.exports[name] = this._createObjectURL(key);
            if(!save) this.keys[name].save = false;
            else this.save();
        }

        removeKey(name) {
            if(this.exports[name]) {
                URL.revokeObjectURL(this.exports[name]);
            }
            delete this.keys[name];
            delete this.exports[name];
            delete this.cryptoKeys[name];
            this.save();
        }

        save() {
            let toSave = {};
            for(var name in this.keys) {
                if(this.keys[name].save !== false)
                    toSave[name] = this._toExportable(this.keys[name]);
            }
            if (sec.utils.localStorage.canUse)
                localStorage.setItem('secretarium-keys', JSON.stringify(toSave));
        }
    },

    utils: (function(){
        var decoder = new TextDecoder("utf-8"),
            encoder = new TextEncoder("utf-8");

        Uint8Array.prototype.secXor = function(a) {
            return this.map((x, i) => x ^ a[i]);
        }

        Uint8Array.prototype.secIncrementBy = function(offset) {
            let inc = Uint8Array.from(this),
                szDiff = this.length - offset.length;

            for (var j = offset.length - 1; j >= 0; j--)
            {
                for (var i = j + szDiff, o = offset[j]; i >= 0; i--)
                {
                    if (inc[i] + o > 255)
                    {
                        inc[i] = inc[i] + o - 256;
                        o = 1;
                    }
                    else
                    {
                        inc[i] = inc[i] + o;
                        break;
                    }
                }
            }

            return inc;
        }

        Uint8Array.prototype.secSequenceEqual = function(other) {
            if (this.length != other.length) return false;
            for (var i = 0; i != this.length; i++)
            {
                if (this[i] != other[i]) return false;
            }
            return true;
        }

        Uint8Array.prototype.secToString = function() {
            return String.fromCharCode.apply(null, this);
        }

        Uint8Array.prototype.secToBase64 = function() {
            return btoa(this.secToString());
        }

        Uint8Array.prototype.secToHex = function(delimiter = '') {
            return Array.prototype.map.call(this, x => ('00' + x.toString(16)).slice(-2)).join(delimiter);
        }

        Uint8Array.secFromString = function(str) {
            var buf = new Uint8Array(str.length);
            for (var i = 0, strLen = str.length; i < strLen; i++) {
                buf[i] = str.charCodeAt(i);
            }
            return buf;
        }

        Uint8Array.secFromBase64 = function(str) {
            return new Uint8Array(atob(str).split('').map(function (c) { return c.charCodeAt(0); }));
        }

        var getRandomUint8Array = function(size = 32) {
            let a = new Uint8Array(size);
            window.crypto.getRandomValues(a);
            return a;
        }

        var canUseLocalStorage = (function() {
            try {
                localStorage.setItem("a", "a");
                localStorage.removeItem("a");
                return true;
            } catch (e) {
                return false;
            }
        })();

        return {
            getRandomUint8Array: getRandomUint8Array,
            getRandomString: function(size = 32) {
                let a = getRandomUint8Array(size);
                return decoder.decode(a);
            },
            concatUint8Array: function(a, b) {
                let c = new Uint8Array(a.length + b.length);
                c.set(a, 0);
                c.set(b, a.length);
                return c;
            },
            concatUint8Arrays: function(arrays) {
                let length = 0;
                for(let i = 0; i < arrays.length; i++) {
                    length += arrays[i].length;
                }
                let c = new Uint8Array(length), j;
                for(let i = 0, j = 0; i < arrays.length; i++) {
                    c.set(arrays[i], j);
                    j += arrays[i].length;
                }
                return c;
            },
            decode: function(bin) {
                return decoder.decode(bin);
            },
            encode: function(str) {
                return encoder.encode(str);
            },
            hash: async function(data) {
                return window.crypto.subtle.digest({ name: "SHA-256" }, data);
            },
            hashBase64: async function(str) {
				let h = await this.hash(encoder.encode(str));
                return new Uint8Array(h).secToBase64();
            },
            base64ToUint8Array: function(str) {
                str = str.replace(/-/g, "+").replace(/_/g, "/");
                return new Uint8Array(atob(str).split('').map(function (c) { return c.charCodeAt(0); }));
            },
            ecdh: {
                importPub: async function (pub, format = "raw", exportable = false) {
                    return window.crypto.subtle.importKey(format, pub, { name: "ECDH", namedCurve: "P-256" }, exportable,  []);
                },
                deriveBits: async function(pub, pri) {
                    return window.crypto.subtle.deriveBits({ name: "ECDH", namedCurve: "P-256", public: pub }, pri, 256);
                }
            },
            ecdsa: {
                generateKeyPair: async function(exportable = false) {
                    return window.crypto.subtle.generateKey({ name: "ECDSA", namedCurve: "P-256" }, exportable, ["sign", "verify"]);
                },
                exportPub: async function(cryptokey, format = "raw") {
                    return window.crypto.subtle.exportKey(format, cryptokey.publicKey);
                },
                exportPri: async function(cryptokey, format = "jwk") {
                    return window.crypto.subtle.exportKey(format, cryptokey.privateKey);
                },
                importPub: async function (pub, format = "raw", exportable = true) {
                    return window.crypto.subtle.importKey(format, pub, { name: "ECDSA", namedCurve: "P-256" }, exportable,  ["verify"]);
                },
                importPri: async function(pri, format = "jwk", exportable = false) {
                    return window.crypto.subtle.importKey(format, pri, { name: "ECDSA", namedCurve: "P-256" }, exportable,  ["sign"]);
                },
                sign: async function(x, cryptokey) {
                    return window.crypto.subtle.sign({ name: "ECDSA", hash: {name: "SHA-256"} }, cryptokey, x);
                },
                verify: async function(x, signature, cryptokey) {
                    return window.crypto.subtle.verify({ name: "ECDSA", hash: {name: "SHA-256"} }, cryptokey, signature, x);
                }
            },
            aesgcm: {
                export: async function(cryptokey, format = "raw") {
                    return window.crypto.subtle.exportKey(format, cryptokey);
                },
                import: async function(key, format = "raw", exportable = false) {
                    return window.crypto.subtle.importKey(format, key, { name: "AES-GCM" }, exportable,  ["encrypt", "decrypt"]);
                },
                encrypt: async function(key, iv, data) {
                    return window.crypto.subtle.encrypt({ name: "AES-GCM", iv: iv, tagLength: 128 }, key, data);
                },
                decrypt: async function(key, iv, data) {
                    return window.crypto.subtle.decrypt({ name: "AES-GCM", iv: iv, tagLength: 128 }, key, data);
                }
            },
            localStorage: {
                canUse: canUseLocalStorage
            }
        }
    })()
}