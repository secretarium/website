'use strict'

var nng = {

	WebSocket: class {

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

	utils: (function(){
		Uint8Array.prototype.nngAddHop = function() {
			let c = new Uint8Array(4 + this.length);
			c.set([0, 0, 0, 1], 0);
			c.set(this, 4);
			return c;
		}
	})()
}