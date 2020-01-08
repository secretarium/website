"use strict";

(function (global) {
    if(!global.jQuery) throw "secretarium.ui requires jQuery";
    if(!global.Vue) throw "secretarium.ui requires Vue js";
    if(!global.secretarium) throw "secretarium.ui requires secretarium";
}(this));

sec.notifState = class {
    constructor(chained = 1, states) {
        let defaults = {
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
        this.notifStates = Object.assign({}, defaults, states);
        this.data = {
            msg: "", visible: false, showChain: chained > 1,
            chained: [], global: { title: "", opacity: chained > 1 ? 0.4 : 1, icons: this.notifStates['sent'] }
        };
        for(let i = 0; i < chained - 1; i++) {
            this.data.chained.push({ title: "", opacity: i == 0 ? 1 : 0.4, icons: this.notifStates['sent'] });
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

sec.userFields = {
    firstname: {
        display: "First name",
        description: "The person's first name",
        type: "string"
    },
    lastname: {
        display: "Last name",
        description: "The person's last name",
        type: "string"
    },
    personalRecords: {
        email: {
            display: "Email",
            description: "The person's email address",
            type: "string"
        },
        phone: {
            display: "Phone",
            description: "The person's phone number",
            type: "string"
        }
    }
};

sec.requestConsent = (name, id, requested, userData, scp) => {
    let args = { from: { name: name, id: id }, requested, fields: sec.userFields, userData, scp };
    sec.ui.app.setContent("sec-request-consent", args).open();
    return new Promise((resolve, reject) => {
        sec.ui._callbacks["onRequestConsented"] = resolve;
        sec.ui._callbacks["onRequestRejected"] = reject;
    });
};

sec.ui = {
    utils: (function() {
        return {
            injectCss(rules) {
                let node = document.createElement('style');
                node.type = 'text/css';
                node.appendChild(document.createTextNode(rules));
                document.getElementsByTagName("head")[0].appendChild(node);
            }
        }
    })(),

    _appInst: null,
    _callbacks: {}
};
Object.defineProperty(sec.ui, "app", { // lazy inst
    get() {
        let app = sec.ui._appInst || (() => {
            sec.ui.utils.injectCss(sec.ui.cssRules);
            let x = document.createElement("div");
            x.id = "sec-modal";
            x.innerHTML = '<sec-modal></sec-modal>';
            document.body.insertBefore(x, document.body.firstChild);
            return sec.ui._appInst = new Vue({ el: "#sec-modal" }).$children[0];
        })();
        return app;
    }
});

// Vue components
sec.ui.notifState = Vue.component('sec-notif-state', {
    template: `
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
    `,
    props: ['state']
});
sec.ui.cssRules = [`.sec-modal{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1000;display:flex;visibility:hidden;flex-direction:column;align-items:center;overflow:hidden;background:rgba(0,0,0,.8);opacity:0;user-select:none;cursor:pointer}.sec-modal *{box-sizing:border-box;margin:0;padding:0;font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"}body.sec-modal-show{position:fixed;right:0;left:0;overflow:hidden}body:not(.sec-modal-show) .sec-modal{display:none}body.sec-modal-show .sec-modal{visibility:visible;opacity:1}body.sec-modal-show .sec-modal-wrap{animation:scale .2s cubic-bezier(.68,-.55,.265,1.55) forwards}.sec-modal-overflow{overflow-y:scroll;padding-top:8vh;padding-bottom:8vh}.sec-modal-wrap{position:relative;flex-shrink:0;margin-top:auto;margin-bottom:auto;width:60%;border-radius:4px;background:#fff;opacity:1;cursor:auto;will-change:transform,opacity;background-color:#fff;box-shadow:0 0 3rem .3rem rgba(0,0,0,.4)}.sec-modal-header{background-color:#f5f5f5;border-bottom:.1rem solid rgba(230,74,60,.85);border-radius:.25rem .25rem 0 0;overflow:hidden}.sec-modal-close{position:absolute;top:1rem;right:1rem;width:1.25rem;height:1.25rem;z-index:1000;cursor:pointer;border:0;background-color:transparent}.sec-modal-close:focus{outline:0}.sec-modal-bar{padding:1rem}.sec-modal-bar>svg{max-width:5.4rem;float:left;padding:.3rem 1rem 0 0}.sec-modal-bar>h4{margin-bottom:.5rem;font-weight:500;line-height:1.2;font-size:1.5rem}.sec-modal-content{padding:1rem;border-radius:0 0 .25rem .25rem}.sec-modal-btn{display:inline-block;font-weight:400;color:#212529;text-align:center;vertical-align:middle;user-select:none;background-color:transparent;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out}.sec-modal-btn:focus{outline:0}.sec-modal-btn:disabled{opacity:.65}.sec-modal-btn-primary,.sec-modal-btn-primary:disabled{color:#fff;background-color:#e64a3c;border-color:#e64a3c}.sec-modal-btn-primary:hover{color:#fff;background-color:#cd291a;border-color:#c02618}.sec-modal-btn-primary:focus{box-shadow:0 0 0 .2rem rgba(230,74,60,.5)}.sec-modal-btn-secondary,.sec-modal-btn-secondary:disabled{color:#fff;background-color:#6c757d;border-color:#6c757d}.sec-modal-btn-secondary:hover{color:#fff;background-color:#5a6268;border-color:#545b62}.sec-modal-btn-secondary:focus{box-shadow:0 0 0 .2rem rgba(130,138,145,.5)}.sec-custom-custom-checkbox{position:relative;display:block;min-height:1.5rem;padding-left:1.5rem}.sec-modal-custom-checkbox input{position:absolute;z-index:-1;opacity:0}.sec-modal-custom-checkbox input:checked~label::before{color:#fff;border-color:#e64a3c;background-color:#e64a3c}.sec-modal-custom-checkbox input:checked~label::after{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e")}.sec-modal-custom-checkbox input:checked~label::before{border-color:#e64a3c;background-color:#e64a3c}.sec-modal-custom-checkbox input:focus~label::before{box-shadow:0 0 0 .2rem rgba(230,74,60,.5)}.sec-modal-custom-checkbox input:focus:not(:checked)~label::before{border-color:rgba(230,74,60,.5)}.sec-modal-custom-checkbox input:disabled:checked~label::before{background-color:rgba(230,74,60,.7)}.sec-modal-custom-checkbox{padding-left:1.5rem}.sec-modal-custom-checkbox label{position:relative;margin-bottom:0;vertical-align:top}.sec-modal-custom-checkbox label::before{border-radius:.25rem;position:absolute;top:.25rem;left:-1.5rem;display:block;width:1rem;height:1rem;pointer-events:none;content:"";background-color:#fff;border:#adb5bd solid 1px}.sec-modal-custom-checkbox label::after{position:absolute;top:.25rem;left:-1.5rem;display:block;width:1rem;height:1rem;content:"";background:no-repeat 50%/50% 50%}.sec-modal-columns{margin:1rem 0;column-count:auto;column-width:10rem}.sec-modal-text-red{color:#e64a3c}.sec-modal-badge{font-size:60%;padding:.05rem .2rem;vertical-align:.25rem;border-radius:.15rem}.sec-modal hr{height:1px;background-color:rgba(230,74,60,.7);border:none;color:rgba(230,74,60,.7)}.sec-modal b{font-weight:700}.sec-modal i.fas{font-weight:900}.sec-modal .small{font-size:80%}@media (min-width :700px){.sec-modal-wrap{min-width:700px;max-width:900px}}@media (max-width :700px){.sec-modal{top:0;display:block;padding-top:60px;width:100%}.sec-modal-wrap{width:auto;border-radius:0}.sec-modal-header{border-radius:0}.sec-modal-close{display:none}.sec-modal-bar>svg{max-width:3rem;padding:0 .75rem 0 0}.sec-modal-bar>h4{font-size:1rem;margin:.15rem 0 .35rem 0}.sec-modal-bar>p{display:none}.sec-modal-content{overflow-y:scroll;border-radius:0}}@keyframes scale{0%{opacity:0;transform:scale(.9)}100%{opacity:1;transform:scale(1)}}`];
sec.ui.modal = Vue.component('sec-modal', {
    template: `
        <div class="sec-modal" v-show="showModal" v-on:keyup.esc="_onEsc" @mousedown="_onClickOverlay" tabindex="0">
            <div class="sec-modal-wrap">
                <button v-if="showCloseBtn" type="button" class="sec-modal-close" @click="close">
                    <svg viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg"><path d="M.3 9.7c.2.2.4.3.7.3.3 0 .5-.1.7-.3L5 6.4l3.3 3.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L6.4 5l3.3-3.3c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L5 3.6 1.7.3C1.3-.1.7-.1.3.3c-.4.4-.4 1 0 1.4L3.6 5 .3 8.3c-.4.4-.4 1 0 1.4z" fill="#616569" fill-rule="nonzero"/></svg>
                </button>
                <component :is="content" v-bind="args"></component>
            </div>
        </div>
    `,
    data: () => {
        return {
            showCloseBtn: true,
            content: "sec-modal-empty",
            args: {},
            showModal: false,
            closeOnEsc: true,
            elements: { modal: null, wrap: null }
        }
    },
    mounted() {
        this.elements = { modal: $(".sec-modal"), wrap: $(".sec-modal-wrap") };
        window.addEventListener('resize', this._checkOverflow);
    },
    beforeDestroy() {
        window.removeEventListener('resize', this._checkOverflow)
    },
    methods: {
        _checkOverflow() {
            if (this.showModal)
                this.elements.modal.toggleClass('sec-modal-overflow', this.elements.wrap[0].clientHeight >= window.innerHeight);
        },
        _onEsc() {
            if (this.showModal)
                this.close();
        },
        _onClickOverlay(e) {
            if (!$(e.target).closest('.sec-modal-wrap').length && e.clientX < this.elements.modal[0].clientWidth) this.close();
        },
        open() {
            $("body").addClass('sec-modal-show');
            this.showModal = true;
            return this;
        },
        setContent(component, args) {
            this.args = args;
            this.content = component;
            return this;
        },
        close() {
            $("body").removeClass('sec-modal-show');
            this.showModal = false;
            return this;
        }
    }
});
sec.ui.modalEmptyContent = Vue.component('sec-modal-empty', { template: "<p>Loading...</p>" });
sec.ui.requestConsent = Vue.component('sec-request-consent', {
    template: `
        <div>
            <div class="sec-modal-header">
                <div class="sec-modal-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340.67 249.86"><title>SecretariumLogo</title><path d="M119,39.06h0a29.61,29.61,0,0,1,29.6,29.46v.3A29.61,29.61,0,1,1,119,39.06m0-13.83A43.44,43.44,0,1,0,162.4,68.67,43.44,43.44,0,0,0,119,25.23" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M283.11,39.06h0a29.61,29.61,0,0,1,29.61,29.39v.44a29.61,29.61,0,0,1-59.21-.07v-.3a29.61,29.61,0,0,1,29.6-29.46m0-13.83a43.44,43.44,0,1,0,43.44,43.44,43.44,43.44,0,0,0-43.44-43.44" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M253.51,231.5a29.61,29.61,0,0,1,59.21-.07v.44a29.61,29.61,0,0,1-59.21-.07v-.3m29.6-43.29a43.44,43.44,0,1,0,43.44,43.44,43.44,43.44,0,0,0-43.44-43.44" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M31.79,154a6.91,6.91,0,0,0,9.78.14l30.27-29.42a73.52,73.52,0,0,1-9.49-10.07L31.93,144.18A6.92,6.92,0,0,0,31.79,154Z" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M226.5,114.61l-25.63,24.93-25.4-24.8A73,73,0,0,1,166,124.79l30,29.3a7.22,7.22,0,0,0,5,2,6.94,6.94,0,0,0,4.82-2L236,124.68A73.06,73.06,0,0,1,226.5,114.61Z" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M329.06,124.1,354,149.74l-24.79,25.4a72.93,72.93,0,0,1,10,9.52l29.3-30a7.22,7.22,0,0,0,2-5,6.89,6.89,0,0,0-2-4.82l-29.41-30.26A73.52,73.52,0,0,1,329.06,124.1Z" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/></svg>
                    <h4>Entrust your secrets with Secretarium</h4>
                    <p>{{from.name}} would like to access your private data</p>
                </div>
            </div>
            <div class="sec-modal-content">
                <p><b>{{from.name}}</b> would like to access elements of your personal information</p>
                <div class="sec-modal-columns">
                    <div class="sec-modal-custom-checkbox" v-for="f in flattenedFields" :id="f.name">
                        <input type="checkbox" :value="f.name" :id="'id-ckbx-'+f.name" v-model="checkedFields">
                        <label :for="'id-ckbx-'+f.name">
                            <span :class="{'sec-field-selected':requested.includes(f.name)}">{{f.display}}</span>
                            <span v-if="!f.value" class="sec-modal-badge" style="background-color: orange">unfilled</span>
                            <svg v-else-if="f.value.verified" style="height:.75rem; vertical-align: unset;" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#e64a3c" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>
                        </label>
                    </div>
                </div>
                <div style="margin-top: 1.5rem">
                    <button type="button" class="sec-modal-btn sec-modal-btn-secondary" style="min-width: 6rem" @click="onCancel">Cancel</button>
                    <button type="button" class="sec-modal-btn sec-modal-btn-primary" style="min-width: 6rem; margin: 0 1rem" @click="onOk">OK</button>
                    <sec-notif-state :state="sharingNs.data"></sec-notif-state>
                </div>
            </div>
        </div>
    `,
    props: ["from", "requested", "fields", "userData", "scp"],
    data: function() {
        return {
            checkedFields: [],
            unfilled: [],
            sharingNs: new sec.notifState()
        }
    },
    mounted() {
        this.checkedFields = [...this.requested];
    },
    computed: {
        flattenedFields() {
            let x = [], flattener = (name, fieldNode, dataNode) => {
                    let k = Object.keys(fieldNode);
                    if(k.includes("type")) {
                        if(!dataNode) this.unfilled.push(fieldNode.display);
                        x.push({ name: name, display: fieldNode.display, value: dataNode });
                    }
                    else
                        k.forEach(e => flattener(e, fieldNode[e], dataNode?dataNode[e]:null));
                };
            flattener("root", this.fields, this.userData);
            return x;
        }
    },
    methods: {
        onOk() {
            this.sharingNs.start();
            for(let r of this.requested) {
                if(!this.checkedFields.includes(r)) {
                    this.sharingNs.failed("please share " + r, true);
                    return;
                }
            }
            this.scp.newTx("identity", "share-with", "identity-share-with", { id: this.from.id, items: this.requested })
                    .onError(x => { this.sharingNs.failed(x, true); })
                    .onAcknowledged(() => { this.sharingNs.acknowledged(); })
                    .onProposed(() => { this.sharingNs.proposed(); })
                    .onCommitted(() => { this.sharingNs.committed(); })
                    .onExecuted(() => {
                        this.sharingNs.executed();
                        sec.ui._appInst.close();
                        sec.ui._callbacks.onRequestConsented&&sec.ui._callbacks.onRequestConsented();
                    })
                    .send();
        },
        onCancel() {
            sec.ui._appInst.close();
            sec.ui._callbacks.onRequestRejected&&sec.ui._callbacks.onRequestRejected();
        }
    }
});
