"use strict"

const SmexUtils = {
    smileComparer(a, b) {
        return a.dist - b.dist;
    },
};
const smexCluster = store.dcapps["smex"].cluster;

const SmexApp = Vue.component('sec-smex', {
    template: '#sec-smex'
});
const SmexAppWelcome = Vue.component('sec-smex-welcome', {
    template: '#sec-smex-welcome'
});
const SmexAppSearch = Vue.component('sec-smex-search', {
    template: '#sec-smex-search',
    data: () => {
        return {
            smile: "[11CH2]1NCCN2C[C@@H]3CCC[C@@H]3c4cccc1c24",
            identity: {},
            ns: new sec.notifState(),
            results: {},
        }
    },
    methods: {
        _randomResearch() {
            let r = Math.random(), nbFirms = r < .4 ? 0 : r < .7 ? 1 : r < .85 ? 2 : 3, res = [];
            for(let i = 0; i < nbFirms; i++) {
                let adme = Math.random() > .5, spatial = adme && Math.random() > .2, solubility = Math.random() > .8,
                    cyp = Math.random() > .9, stability = Math.random() > .5, animalModels = Math.random() > .9;
                if(adme || spatial || solubility || cyp || stability || animalModels)
                    res.push({ adme: adme, spatial: spatial, solubility:solubility, cyp: cyp, stability: stability, animalModels: animalModels });
            }
            return res;
        },
        _fetchInfo(results, i, j) {
            if(i >= results.length) return;
            let z = results[i];
            $.getJSON("https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/smiles/" + encodeURIComponent(z.smile) + "/property/MolecularFormula,InChIKey,IUPACName/JSON")
                .done(x => {
                    if(!x || !x.PropertyTable || !x.PropertyTable.Properties ||
                        x.PropertyTable.Properties.length == 0 || x.PropertyTable.Properties[0].CID == 0) {
                        setTimeout(() => { this._fetchInfo(results, ++i, j) }, 200);
                        return;
                    }
                    Vue.set(results[i], "cid", x.PropertyTable.Properties[0].CID);
                    Vue.set(results[i], "formula", x.PropertyTable.Properties[0].MolecularFormula);
                    Vue.set(results[i], "inChIKey", x.PropertyTable.Properties[0].InChIKey);
                    Vue.set(results[i], "src", "https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/smiles/" + encodeURIComponent(z.smile) + "/PNG");
                    Vue.set(results[i], "name", x.PropertyTable.Properties[0].IUPACName);
                    setTimeout(() => { this._fetchInfo(results, ++i, ++j) }, 200);
                })
                .fail((w, t, e) => {
                    Vue.set(results[i], "error", "Failed fetching information: " + e);
                    setTimeout(() => { this._fetchInfo(results, ++i, j) }, 200);
                });
        },
        _onresults(x) {
            this.ns.executed("Success", true).hide(500);
            let smiles = Object.keys(x).map(smile => ({ smile: smile, dist: parseFloat(x[smile]) }));
            smiles.sort(SmexUtils.smileComparer);
            let r = smiles.map(z => {
                    let pct = z.dist <= 0.4 ? 1 : z.dist > 3 ? 0 : (1 - (3.0 - z.dist) / 2.6);
                    return {
                        smile: z.smile,
                        dist: z.dist,
                        style: { borderLeft: '.5rem solid hsl(' + ((pct * (25 - 100)) + 100) + ', 100%, 50%)'},
                        research: this._randomResearch()
                    }
            });
            Vue.set(this, "results", r);

            // fetching more info
            this._fetchInfo(this.results, 0, 0);
        },
        onSmileChanged() {
            Vue.set(this, "results", {});
            Vue.set(this, "identity", {});
        },
        search() {
            Vue.set(this, "identity", { smile: this.smile, dist: 0, style: "", research: this._randomResearch() });
            this._fetchInfo([this.identity], 0, 0);
            this.ns.processing("Searching...", true);
            $.ajax({ type: "POST", dataType: "json", contentType: "application/json",
                        url: "https://molecularsimilarity.secretarium.org/search",
                        data: JSON.stringify({smile: this.smile})})
                .done(x => { this._onresults(x); })
                .fail((j, t, e) => { this.ns.processing("Failed connecting to remote machine: " + e, true); });
        }
    }
});
const SmexAppReport = Vue.component('sec-smex-report', {
    template: '#sec-smex-report',
    props: ['data']
});

router.addRoutes([
    { path: '/smex', component: SmexApp,
        children: [
            { path: '', component: SmexAppWelcome, alias: 'home'},
            { path: 'search', component: SmexAppSearch }
        ]
    }
]);
store.dcapps["smex"].onLoad = () => (new Promise((resolve, reject) => {
    resolve();
}));
store.dcapps["smex"].reset = () => (new Promise((resolve, reject) => {
    resolve();
}));