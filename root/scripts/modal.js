"use strict"

/* Licences
   - we use SVGs from FontAwsesome - https://fontawesome.com/license/free - CC BY 4.0
   - we uses NNG for communications - https://github.com/nanomsg/nng - MIT
   - our modal is inspired by Tingle - https://github.com/robinparisi/tingle - MIT
*/

var sec = {

    userFields:	{
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
    },

    utils: (function() {
        let modalInstance = null,
            modal = class {
                constructor() {
                    this._build();
                    this._bindEvents();
                    document.body.insertBefore(this.modal, document.body.firstChild);
                }

                _build() {
                    this.modal = document.createElement('div');
                    this.modal.className = 'sec-modal';

                    this.modalBox = document.createElement('div');
                    this.modalBox.className = 'sec-modal-box';

                    this.modalBoxHeader = document.createElement('div');
                    this.modalBoxHeader.className = 'sec-modal-box-header';
                    this.modalBoxHeaderBar = document.createElement('div');
                    this.modalBoxHeaderBar.className = 'sec-modal-bar';
                    this.modalBoxHeaderCloseBtn = document.createElement('button');
                    this.modalBoxHeaderCloseBtn.className = 'sec-modal-close';
                    this.modalBoxHeaderCloseBtn.type = 'button';
                    this.modalBoxHeaderCloseBtn.innerHTML = `
                        <svg viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                            <path d="M.3 9.7c.2.2.4.3.7.3.3 0 .5-.1.7-.3L5 6.4l3.3 3.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L6.4 5l3.3-3.3c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L5 3.6 1.7.3C1.3-.1.7-.1.3.3c-.4.4-.4 1 0 1.4L3.6 5 .3 8.3c-.4.4-.4 1 0 1.4z" fill="#616569" fill-rule="nonzero"/>
                        </svg>
                    `;

                    this.modalBoxContent = document.createElement('div');
                    this.modalBoxContent.className = 'sec-modal-box-content';

                    this.modalBoxHeader.appendChild(this.modalBoxHeaderCloseBtn);
                    this.modalBoxHeader.appendChild(this.modalBoxHeaderBar);
                    this.modalBox.appendChild(this.modalBoxHeader);
                    this.modalBox.appendChild(this.modalBoxContent);
                    this.modal.appendChild(this.modalBox);
                }
                _handleKeyboardNav(event) {
                    if (event.which === 27 && this.isOpen()) {
                        this.close()
                    }
                }
                _handleClickOutside(event) {
                    if (!sec.utils.findAncestor(event.target, 'sec-modal') && event.clientX < this.modal.clientWidth) {
                        this.close()
                    }
                }
                _bindEvents() {
                    this._events = {
                        clickCloseBtn: this.close.bind(this),
                        clickOverlay: this._handleClickOutside.bind(this),
                        resize: this._checkOverflow.bind(this),
                        keyboardNav: this._handleKeyboardNav.bind(this)
                    }
                    this.modalBoxHeaderCloseBtn.addEventListener('click', this._events.clickCloseBtn);
                    this.modal.addEventListener('mousedown', this._events.clickOverlay);
                    window.addEventListener('resize', this._events.resize);
                    document.addEventListener('keydown', this._events.keyboardNav);
                }
                _unbindEvents() {
                    this.modalBoxHeaderCloseBtn.removeEventListener('click', this._events.clickCloseBtn)
                    this.modal.removeEventListener('mousedown', this._events.clickOverlay)
                    window.removeEventListener('resize', this._events.resize)
                    document.removeEventListener('keydown', this._events.keyboardNav)
                }
                _checkOverflow() {
                    if (document.body.classList.contains('sec-modal-show')) {
                        var viewportHeight = window.innerHeight,
                            modalHeight = this.modalBox.clientHeight;
                        if (modalHeight >= viewportHeight) {
                            this.modal.classList.add('sec-modal-overflow')
                        } else {
                            this.modal.classList.remove('sec-modal-overflow')
                        }
                    }
                }

                setContent(header, content) {
                    if (typeof header == 'string') {
                        this.modalBoxHeaderBar.innerHTML = header;
                    } else {
                        this.modalBoxHeaderBar.innerHTML = '';
                        this.modalBoxHeaderBar.appendChild(header);
                    }

                    if (typeof content == 'string') {
                        this.modalBoxContent.innerHTML = content;
                    } else {
                        this.modalBoxContent.innerHTML = '';
                        this.modalBoxContent.appendChild(content);
                    }

                    if (this.isOpen()) this.checkOverflow();

                    return this;
                }
                appendContent(content) {
                    if (typeof content == 'string') return;

                    this.modalBoxContent.appendChild(content);
                    if (this.isOpen()) this.checkOverflow();

                    return this;
                }
                isOpen() {
                    return !!document.body.classList.contains('sec-modal-show');
                }
                open() {
                    this._scrollPosition = window.pageYOffset;
                    document.body.classList.add('sec-modal-show');
                    document.body.style.top = -this._scrollPosition + 'px';
                    this._checkOverflow();
                    return this;
                }
                close() {
                    document.body.classList.remove('sec-modal-show');
                    window.scrollTo(0, this._scrollPosition);
                    document.body.style.top = null;
                }
                destroy() {
                    if (!this.modal) return;
                    if (this.isOpen()) this.close();
                    this._unbindEvents();
                    this.modal.parentNode.removeChild(this.modal);
                    this.modal = null;
                }
            };

        return {
            extend() {
                for (var i = 1; i < arguments.length; i++) {
                    for (var key in arguments[i]) {
                        if (arguments[i].hasOwnProperty(key)) {
                            arguments[0][key] = arguments[i][key]
                        }
                    }
                }
                return arguments[0]
            },
            findAncestor(el, cls) {
                while ((el = el.parentElement) && !el.classList.contains(cls));
                return el;
            },
            injectCss(rules) {
                let node = document.createElement('style');
                node.type = 'text/css';
                node.appendChild(document.createTextNode(rules));
                document.getElementsByTagName("head")[0].appendChild(node);
            },
            modal() { return (modalInstance = modalInstance || new modal()); }
        }
    })(),

    requestAccess: function() {
        let requester = {
                name: "MADREc",
                description: "Massive Anonymous Data Reconciliation. Collectively measure reference data quality.",
                cluster: "sec-demo-1"
            },
            requested = ["firstname", "lastname", "email"],
            userData = {"firstname":"Bertrand","lastname":"Foing","personalRecords":{"email":{"value":"bertrand@foing.fr","verified":true},"phone":{"value":"+447595300325","verified":true}}};

        // build fields
        let fields = '<div class="sec-modal-columns">',
            flattener = (name, fNode, uNode) => {
                let k = Object.keys(fNode);
                if(k.includes("type")) {
                    fields += `
                        <div class="sec-modal-custom-checkbox">
                            <input type="checkbox" value="${name}" id="id-ckbx-${name}" ${requested.includes(name)?'checked':''}>
                            <label for="id-ckbx-${name}">
                                ${fNode.display}
                                ${uNode && uNode.verified ? '<svg style="height:.75rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#e64a3c" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>' : ''}
                                ${requested.includes(name)?' <span class="sec-modal-text-red">*</span>':''}
                            </label>
                        </div>`;
                }
                else
                    k.forEach(e => flattener(e, fNode[e], uNode[e]));
            };
        flattener("root", sec.userFields, userData);
        fields += "</div>";

        let header = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340.67 249.86"><title>SecretariumLogo</title><path d="M119,39.06h0a29.61,29.61,0,0,1,29.6,29.46v.3A29.61,29.61,0,1,1,119,39.06m0-13.83A43.44,43.44,0,1,0,162.4,68.67,43.44,43.44,0,0,0,119,25.23" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M283.11,39.06h0a29.61,29.61,0,0,1,29.61,29.39v.44a29.61,29.61,0,0,1-59.21-.07v-.3a29.61,29.61,0,0,1,29.6-29.46m0-13.83a43.44,43.44,0,1,0,43.44,43.44,43.44,43.44,0,0,0-43.44-43.44" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M253.51,231.5a29.61,29.61,0,0,1,59.21-.07v.44a29.61,29.61,0,0,1-59.21-.07v-.3m29.6-43.29a43.44,43.44,0,1,0,43.44,43.44,43.44,43.44,0,0,0-43.44-43.44" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M31.79,154a6.91,6.91,0,0,0,9.78.14l30.27-29.42a73.52,73.52,0,0,1-9.49-10.07L31.93,144.18A6.92,6.92,0,0,0,31.79,154Z" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M226.5,114.61l-25.63,24.93-25.4-24.8A73,73,0,0,1,166,124.79l30,29.3a7.22,7.22,0,0,0,5,2,6.94,6.94,0,0,0,4.82-2L236,124.68A73.06,73.06,0,0,1,226.5,114.61Z" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/><path d="M329.06,124.1,354,149.74l-24.79,25.4a72.93,72.93,0,0,1,10,9.52l29.3-30a7.22,7.22,0,0,0,2-5,6.89,6.89,0,0,0-2-4.82l-29.41-30.26A73.52,73.52,0,0,1,329.06,124.1Z" transform="translate(-29.83 -25.23)" style="fill:#e64a3c"/></svg>
                <h4>Entrust your secrets with Secretarium</h4>
                <p>${requester.name} would like to access your private data</p>`,
            body = `
                <p><b>${requester.name}</b> would like to access your following personal information</p>
                <div>${fields}</div>
                <p class="sec-modal-text-red" style="font-size: 80%">* mandatory fields</p>`,
            confirmBtn = document.createElement('button'),
            cancelBtn = document.createElement('button'),
            btnWrap = document.createElement('div');

        confirmBtn.type = 'button';
        confirmBtn.classList.add('sec-modal-btn', 'sec-modal-btn-primary');
        confirmBtn.innerHTML = "OK";
        confirmBtn.style.minWidth = "6rem";
        confirmBtn.addEventListener('click', () => {
            alert("ok");
        });

        cancelBtn.type = 'button';
        cancelBtn.classList.add('sec-modal-btn', 'sec-modal-btn-secondary');
        cancelBtn.innerHTML = "Cancel";
        cancelBtn.style.minWidth = "6rem";
        cancelBtn.style.marginLeft = "1rem";
        cancelBtn.addEventListener('click', () => {
            alert("cancel");
        });

        btnWrap.style.marginTop = "1rem";
        btnWrap.appendChild(confirmBtn);
        btnWrap.appendChild(cancelBtn);

        sec.utils.modal().setContent(header, body).appendContent(btnWrap).open();
    },

    requestAccessLg: function(name, requestedSharings) {
        sec.utils.modal().setContent(
            '<h1>header</h1>',
            `<p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>
             <p style="background-color:#ddd; padding:10rem; margin: 2rem;">body</p>`
        ).open();
    }
};