<style>
    #smex-app .smex-report{border: 1px solid #ccc;padding: 1rem 0;background-color: #f5f5f5;font-size: 90%;}
    #smex-app .smex-report span.title{display:inline-block;min-width: 8rem;}
</style>

<script type="text/x-template" id="sec-smex">
    <div id="smex-app">
        <div class="app-header">
            <div class="container">
                <nav class="nav nav-sec">
                    <router-link to="/smex/home" class="nav-link mr-2" active-class="active">
                        <i class="fas fa-home fa-fw mr-2"></i><span class="d-none d-sm-inline">Home</span>
                    </router-link>
                    <router-link to="/smex/search" class="nav-link mr-2" active-class="active">
                        <i class="fas fa-search fa-fw mr-2"></i><span class="d-none d-sm-inline">Search</span>
                    </router-link>
                </nav>
            </div>
        </div>
        <div class="app-body container mt-4">
            <router-view></router-view>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-smex-welcome">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-atom fa-fw mr-2 text-sec"></i>Molecule Research Exchange App</h4>
            <p class="mb-0">Secure reconciliation of research on molecules and exchange facility</p>
        </div>
        <div class="card-body">
			<div class="py-2 row">
                <div class="col-md-6">
                    <h6 class="card-title">Measuring quality of research</h6>
                    <p class="text-center py-3">
                        <img src="/images/figure_reconcile_molecules.png" style="max-width:100%; height: 20rem;">
                    </p>
                    <p class="card-text text-justify">
                        Pharmaceutical companies have a trove of data on molecules. Especially the research that they have been accumulating privately for years.<br/>
                        We propose to create a venue to securely and anonymously pool data together and benchmark research data against competitor’s data.
                    </p>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <h6 class="card-title">Sourcing & monetizing research</h6>
                    <p class="text-center py-3">
                        <img src="/images/figure_pharma.svg" style="max-width:100%; height: 20rem;">
                    </p>
                    <p class="card-text text-justify">
                        Similar drug discovery research is carried out independently multiple times by different firms.<br/>
                        Companies could source research results from other companies and avoid long and costly work.
                    </p>
                </div>
            </div>
            <router-link to="/smex/search" class="btn btn-sec mt-3" tag="button">Start demo</router-link>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-smex-search">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-search fa-fw mr-2 text-sec"></i>Search for molecules</h4>
        </div>
        <div class="card-body form-sec container-fluid">
            <h6 class="card-title">Search smile</h6>
            <form autocomplete="off" @submit.prevent>
                <input type="text" class="form-control" autocomplete="smex" required
                        id="smex-smile" placeholder="C1=CC=C(C=C1)C=O" v-model="smile" @input="onSmileChanged">
                <div class="mt-3">
                    <button type="button" class="btn btn-sec mr-3" @click.prevent="search">Search</button>
                    <sec-notif-state :state="ns.data"></sec-notif-state>
                </div>
            </form>
            <div v-if="identity.smile">
                <hr class="mt-5 mb-4 sec" />
                <h6 class="card-title">Smile identity</h6>
                <div class="pt-2">
                    <sec-smex-report :data="identity"></sec-smex-report>
                </div>
            </div>
            <div v-if="results&&Object.keys(results).length">
                <hr class="mt-5 mb-4 sec" />
                <div class="py-2">
                    <h6 class="card-title">Most similar molecules</h6>
                    <div class="mt-3">
                        <sec-smex-report v-for="(r, i) in results" :key="i" :data="r" class="mb-4"></sec-smex-report>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-smex-report">
    <div class="row smex-report" :style="data.style">
        <div class="col-auto mr-auto">
            <div v-if="data.error&&data.error.length>0">{{data.error}}</div>
            <div class="smex-details">
                <div><span class="title">Name: </span><b>{{data.name||data.smile}}</b></div>
                <div v-if="data.dist&&data.dist>0"><span class="title">Distance: </span>{{(data.dist * 10).toFixed(2)}}</div>
                <div><span class="title">Smile: </span>{{data.smile}}</div>
                <div v-if="data.cid&&data.cid.length"><span class="title">Compound CID: </span>{{data.cid}}</div>
                <div v-if="data.formula&&data.formula.length"><span class="title">Molecular Formula: </span>{{data.formula}}</div>
                <div v-if="data.inChIKey&&data.inChIKey.length"><span class="title">InChIKey: </span>{{data.inChIKey}}</div>
            </div>
            <hr class="my-3 sec" />
            <div v-if="data.research&&Object.keys(data.research).length>0">
                <div v-for="(c, i) in data.research" :key="i" class="row ml-0 mb-1">
                    <p class="m-0 p-0"><span class="title">{{c.pharma||'(Withheld)'}}: </span>
                        <span v-if="c.adme" class="badge" style="background-color:#1875F0; color: #fff;">ADME</span>
                        <span v-if="c.spatial" class="badge" style="background-color:#17a2b8; color: #fff;">Spatial configuration</span>
                        <span v-if="c.solubility" class="badge" style="background-color:#20c997;">Aqueous solubility</span>
                        <span v-if="c.cyp" class="badge" style="background-color:#28a745; color: #fff;">CYP induction</span>
                        <span v-if="c.stability" class="badge" style="background-color:#0056b3; color: #fff;">Stability</span>
                        <span v-if="c.animalModels" class="badge" style="background-color:#7abaff;">Animal models</span>
                    </p>
                </div>
            </div>
            <div v-else>
                <p class="m-0 p-0">No research pooled</p>
            </div>
        </div>
        <div class="col-auto">
            <img :src="data.src" width="150">
        </div>
    </div>
</script>