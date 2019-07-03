"use strict"

var sec, secretarium = sec = {

    states: {
        socket: ["connecting", "open", "closing", "closed"],
        security:[
            "Secure Connection in Progress",
            "Secure Connection Established",
            "Secure Connection Failed"
        ]
    },

	nngWebSocket: class {

		constructor() {
			this._socket = {
				readyState: 3,
				bufferedAmount: 0
			}
			this.handlers = {
				onMessage: null
			}
		}

		get state() {
			return this._socket.readyState;
		}

		get bufferedAmount() {
			return this._socket.bufferedAmount;
		}

		connect(url, protocol) {
			let s = new WebSocket(url, [protocol + ".sp.nanomsg.org"]);
			s.binaryType = "arraybuffer";
			s.requiresHop = protocol == "pair1";
			s.onopen = this._socket.onopen;
			s.onclose = this._socket.onclose;
			s.onmessage = this._socket.onmessage;
			s.onerror = this._socket.onerror;

			this._socket = s;

			return this;
		}

		on(evt, handler) {
			var self = this;
			switch(evt) {
				case "open":
					this._socket.onopen = handler;
					break;
				case "close":
					this._socket.onclose = handler;
					break;
				case "message":
					if(this.handlers.onMessage == null) {
						this._socket.onmessage = e => {
							let data = new Uint8Array(e.data);
							if(self._socket.requiresHop) data = data.subarray(4);
							self.handlers.onMessage(data);
						}
					}
					this.handlers.onMessage = handler;
					break;
				case "error":
					this._socket.onerror = handler;
					break;
			}
			return this;
		}

		send(data) {
			if(this._socket.requiresHop) data = data.nngAddHop();
			this._socket.send(data);
			return this;
		}

		close() {
			this._socket.close();
			return this;
		}
	},

    scp: class {

        constructor() {
            this.reset();
            this.requests = {};
        }

        reset() {
            this.socket = new sec.nngWebSocket();
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

        _updateState(id) {
            this.security.state = id;
            if(this.handlers.state.onChange != null)
                this.handlers.state.onChange(id);
        }

        connect(url, userKey, knownTrustedKey, protocol = "pair1") {
            let s = new sec.nngWebSocket(), self = this;

            this.socket = s;
            this._updateState(0);

            return new Promise((resolve, reject) => {
                new Promise((resolve, reject) => {
                    s.on("open", e => { resolve() })
                    .on("error", e => { reject(e) })
                    .on("close", e => { reject(e) })
                    .connect(url, protocol);
                })
                .then(async () => {
                    s.on("open", e => { })
                    .on("error", e => { self._updateState(2); })
                    .on("close", e => { self._updateState(2); });
                    let userPubExp = await window.crypto.subtle.exportKey("raw", userKey.publicKey);
                    self.security.client.ecdsaPubRaw = new Uint8Array(userPubExp).subarray(1);
                    self.security.client.ecdh = await window.crypto.subtle.generateKey({ name: "ECDH", namedCurve: "P-256" }, true, ["deriveBits"]);
                    let ecdhPubExp = await window.crypto.subtle.exportKey("raw", self.security.client.ecdh.publicKey);
                    self.security.client.ecdhPubRaw = new Uint8Array(ecdhPubExp).subarray(1);
                    return new Promise((resolve, reject) => {
                        let clientHello = self.security.client.ecdhPubRaw;
                        s.on("message", x => { resolve(x); }).send(clientHello);
                    });
                })
                .then(serverHello => {
                    let nonce = serverHello.subarray(0, 32),
                        pow = sec.utils.getRandomUint8Array(32); // pow disabled for this demo
                    return new Promise((resolve, reject) => {
                        let clientProofOfWork = sec.utils.concatUint8Arrays([pow, knownTrustedKey]);
                        s.on("message", x => { resolve(x); }).send(clientProofOfWork);
                    })
                })
                .then(async serverIdentity => {
                    self.security.server.preMasterSecret = new Uint8Array(serverIdentity.subarray(0, 32));
                    self.security.server.ecdhPub = await sec.utils.ecdh.importPub(sec.utils.concatUint8Array(/*uncompressed*/[4], serverIdentity.subarray(32, 96)));
                    self.security.server.ecdsaPub = await sec.utils.ecdsa.importPub(sec.utils.concatUint8Array(/*uncompressed*/[4], serverIdentity.subarray(serverIdentity.length - 64)));

                    // Check inheritance from Secretarium knownTrustedKey
                    let knownTrustedKeyPath = new Uint8Array(serverIdentity.subarray(96));
                    if (knownTrustedKeyPath.length == 64) {
                        if(!knownTrustedKey.secSequenceEqual(knownTrustedKeyPath))
                            throw { message: "Invalid server identity" };
                    }
                    else {
                        for (var i = 0; i < knownTrustedKeyPath.length - 64; i = i + 128) {
                            let key = knownTrustedKeyPath.subarray(i, 64), proof = knownTrustedKeyPath.subarray(i + 64, 64),
                                keyChild = knownTrustedKeyPath.subarray(i + 128, 64),
                                ecdsaKey = await sec.utils.ecdsa.importPub(sec.utils.concatUint8Array(/*uncompressed*/[4], key));
                            if (!await sec.utils.ecdsa.verify(keyChild, proof, ecdsaKey))
                                throw { message: "Invalid server identity chain at #" + i };
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

                    let nonce = sec.utils.getRandomUint8Array(32),
                        signedNonce = new Uint8Array(await sec.utils.ecdsa.sign(nonce, userKey.privateKey)),
                        clientProofOfIdentity = sec.utils.concatUint8Arrays(
                            [nonce, self.security.client.ecdhPubRaw, self.security.client.ecdsaPubRaw, signedNonce]),
                        ivOffset = sec.utils.getRandomUint8Array(16),
                        iv = self.aesctr.iv.secIncrementBy(ivOffset),
                        encryptedClientProofOfIdentity = await window.crypto.subtle.encrypt(
                            { name: "AES-CTR", counter: iv, length: 128 }, self.aesctr.cryptokey, clientProofOfIdentity)
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
                        throw { message: "Invalid server proof of identity" };

                    self._updateState(1);
                    if(self.handlers.onMessage == null)
                        self.handlers.onMessage = self._notify.bind(self);
                    s.on("message", self._onMessage.bind(self));
                    resolve();
                })
                .catch(e => {
                    console.error("secure connection failed", e);
                    self._updateState(2);
                    s.close();
                    reject(e.message || "unable to create the secure connection");
                });
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
                                this.requests[o.requestId]["onError"].forEach(cb => cb(o.error));
                        }
                        else if(o.result) {
                            if(this.requests[o.requestId]["onResult"])
                                this.requests[o.requestId]["onResult"].forEach(cb => cb(o.result));
                        }
                        else if(o.state) {
                            if(this.requests[o.requestId].failed === true)
                                return;
                            o.state = o.state.toLowerCase();
                            if(o.state == "acknowledged") {
                                if(this.requests[o.requestId]["onAcknowledged"])
                                    this.requests[o.requestId]["onAcknowledged"].forEach(cb => cb());
                            }
                            else if(o.state == "proposed") {
                                if(this.requests[o.requestId]["onProposed"])
                                    this.requests[o.requestId]["onProposed"].forEach(cb => cb());
                            }
                            else if(o.state == "committed") {
                                if(this.requests[o.requestId]["onCommitted"])
                                    this.requests[o.requestId]["onCommitted"].forEach(cb => cb());
                            }
                            else if(o.state == "executed") {
                                if(this.requests[o.requestId]["onExecuted"])
                                    this.requests[o.requestId]["onExecuted"].forEach(cb => cb());
                            }
                            else if(o.state == "failed") {
                                this.requests[o.requestId].failed = true;
                                if(this.requests[o.requestId]["onError"])
                                    this.requests[o.requestId]["onError"].forEach(cb => cb("failed"));
                            }
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
                let cbs = this.requests[requestId] = {}, res = {
                        onError: x => { (cbs["onError"] = cbs["onError"] || []).push(x); return res; },
                        onResult: x => { (cbs["onResult"] = cbs["onResult"] || []).push(x); return res; }
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
                let cbs = this.requests[requestId] = {}, res = {
                        onError: x => { (cbs["onError"] = cbs["onError"] || []).push(x); return res; },
                        onAcknowledged: x => { (cbs["onAcknowledged"] = cbs["onAcknowledged"] || []).push(x); return res; },
                        onProposed: x => { (cbs["onProposed"] = cbs["onProposed"] || []).push(x); return res; },
                        onCommitted: x => { (cbs["onCommitted"] = cbs["onCommitted"] || []).push(x); return res; },
                        onExecuted: x => { (cbs["onExecuted"] = cbs["onExecuted"] || []).push(x); return res; }
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
            this.keys = [];
        }

        _createObjectURL(key) {
            let j = JSON.stringify(this._toExportable(key), null, 4),
                b = new Blob([j], { type: 'application/json;charset=utf-8;' });
            return URL.createObjectURL(b);
        }

        _toExportable(key) {
            let exp = { name: key.name };
            if(key.encrypted) {
                exp.iv = key.iv;
                exp.salt = key.salt;
                exp.encryptedKeys = key.encryptedKeys;
            } else {
                exp.keys = key.keys;
            }
            return exp;
        }

        async _setCryptoKey(key, keys) {
            key.cryptoKey = {
                publicKey: await sec.utils.ecdsa.importPub(keys.subarray(0, 65), "raw"),
                privateKey: await sec.utils.ecdsa.importPri(keys.subarray(65), "pkcs8")
            };
            key.ready = true;
        }

        async _addKey(key) {
            // ensure props creation for reactivity
            key.saved = key.saved || false;
            key.save = key.save || false;
            key.modified = key.modified || false;
            key.imported = key.imported || false;
            key.encrypted = key.encryptedKeys !== undefined;
            key.ready = key.cryptoKey !== undefined;
            if(key.exportUrl) URL.revokeObjectURL(key.exportUrl);
            key.exportUrl = this._createObjectURL(key);
            // compute cryptoKey if needed/possible
            if(!key.ready && key.keys) {
                await this._setCryptoKey(key, Uint8Array.secFromBase64(key.keys));
            }
            // unusual logic for reactivity
            let index = this.find(key.name);
            if(index < 0) this.keys.push(key);
            else this.keys.splice(index, 1, key);
            return key;
        }

        async init() {
            if (!sec.utils.localStorage.canUse) return;

            let v = localStorage.getItem('secretarium-keys');
            if (v == null) return;

            let keys = JSON.parse(v);
            for(var key of keys) {
                key.saved = key.save = true;
                await this._addKey(key);
            }
        }

        find(name) {
            for(var i = 0; i < this.keys.length; i++) {
                if(this.keys[i].name == name) return i;
            }
            return -1;
        }

        async createKey(name) {
            if(name.length == 0) throw "Invalid key name";

            let cryptoKey = await sec.utils.ecdsa.generateKeyPair(true),
                publicKey = new Uint8Array(await sec.utils.ecdsa.exportPub(cryptoKey, "raw")),
                privateKey = new Uint8Array(await sec.utils.ecdsa.exportPri(cryptoKey, "pkcs8")),
                keys = sec.utils.concatUint8Array(publicKey, privateKey).secToBase64(),
                key = { name: name, cryptoKey: cryptoKey, keys: keys, newKey: true };

            return await this._addKey(key);
        }

        importKeyFile(evt) {
            return new Promise((resolve, reject) => {
                let e = evt.dataTransfer || evt.target; // dragged or browsed
                if(!e || !e.files) reject("Unsupported, missing key file");
                if(e.files.length != 1) reject("Unsupported, expecting a single key file");

                let reader = new FileReader(), file = e.files[0];
                reader.onloadend = x => {
                    try {
                        let key = JSON.parse(reader.result);
                        key.imported = true;
                        if(key.iv && !key.encryptedKeys && key.keys) { // retro comp
                            key.encryptedKeys = key.keys;
                            delete key.keys;
                        }
                        this._addKey(key).then(k => { resolve(k); });
                    }
                    catch (e) { reject(e.message); }
                };
                reader.onerror = e => { reject(e.message); };
                reader.readAsText(file);
            });
        }

        async encryptKey(key, pwd) {
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
            return await this._addKey(key);
        }

        async decryptKey(key, pwd) {
            if(key.cryptoKey) return key.cryptoKey;

            let iv = Uint8Array.secFromBase64(key.iv),
                salt = Uint8Array.secFromBase64(key.salt),
                encryptedKeys = Uint8Array.secFromBase64(key.encryptedKeys),
                weakpwd = sec.utils.encode(pwd),
                strongPwd = await sec.utils.hash(sec.utils.concatUint8Array(salt, weakpwd)),
                aesgcmKey = await sec.utils.aesgcm.import(strongPwd);
            try {
                let keys = await sec.utils.aesgcm.decrypt(aesgcmKey, iv, encryptedKeys);
                keys = new Uint8Array(keys);
                key.keys = keys.secToBase64();
                await this._setCryptoKey(key, keys);
                return key;
            }
            catch (e) { throw "can't decrypt/invalid password"; }
        }

        getPublicKeyHex(key, delimiter = '') {
            if(!key.keys) throw "Key is encrypted";
            return Uint8Array.secFromBase64(key.keys).subarray(0, 65).secToHex(delimiter);
        }

        removeKey(name) {
            let index = this.find(name);
            if(index < 0) return;
            if(this.keys[index].exportUrl) URL.revokeObjectURL(this.keys[index].exportUrl);
            this.keys.splice(index, 1); // for reactivity purposes
            this.save();
        }

        save() {
            if (!sec.utils.localStorage.canUse) return;
            let toSave = [];
            for(var key of this.keys) {
                if(key.save !== false) {
                    key.saved = true;
                    toSave.push(this._toExportable(key));
                }
            }
            localStorage.setItem('secretarium-keys', JSON.stringify(toSave));
        }
    },

    utils: (function() {
        let compatible = typeof(TextDecoder) != "undefined";

        if(!compatible)
            return { compatible: false, localStorage: { canUse: false }};

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

		Uint8Array.prototype.nngAddHop = function() {
			let c = new Uint8Array(4 + this.length);
			c.set([0, 0, 0, 1], 0);
			c.set(this, 4);
			return c;
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
            compatible: true,
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