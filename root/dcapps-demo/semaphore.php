<style>
    #semaphore-app .semaphore-pie-report .card-header{padding:.5rem .25rem .5rem .25rem !important}
    #semaphore-app .semaphore-pie-report .card-body>div{font-size:0.6em;display:inline-block;width:125px;vertical-align:top;white-space:nowrap;overflow:hidden}
    #semaphore-app .semaphore-pie-report .card-body>div span.title{min-width:2.4rem;display:inline-block}
    #semaphore-app .row label{display:inline-block;text-align:left;min-width:14rem;margin-bottom:0}
    #semaphore-app .row .form-control{padding:0 .5rem;min-width:14rem}
    #semaphore-app .row input[type="checkbox"]{width:1.25rem;height:1.25rem;margin-top:0.25rem}
    #semaphore-app .semaphore-underline{border-bottom:.1rem solid transparent}
    #semaphore-app .semaphore-report-link{cursor:pointer}
    #semaphore-app .semaphore-report{margin:.5rem 0 1rem 0;font-size:80%;padding:1rem;background-color:#f5f5f5}
    #semaphore-app .semaphore-report-title{text-align:center;border-bottom:1px solid rgba(230,74,60,0.85)}
    #semaphore-app .semaphore-report-status{border-top:1px solid rgba(230,74,60,0.85);background-color:#ededed}
</style>

<script type="text/x-template" id="sec-semaphore">
    <div id="semaphore-app" :class="{'container fixed-center mw-md':!$root.isConnected}">
        <div v-if="$root.isConnected">
            <div class="app-header">
                <div class="container">
                    <nav class="nav nav-sec">
                        <router-link to="/semaphore/signals" class="nav-link mr-2" active-class="active">
                            <i class="fas fa-chart-bar fa-fw mr-2"></i><span class="d-none d-sm-inline">Signals</span>
                        </router-link>
                        <router-link to="/semaphore/single-contribution" class="nav-link mr-2" active-class="active">
                            <i class="fas fa-chart-pie fa-fw mr-2"></i><span class="d-none d-sm-inline">Single contribution</span>
                        </router-link>
                        <router-link to="/semaphore/multi-contribution" class="nav-link mr-2" active-class="active">
                            <i class="fas fa-file-upload fa-fw mr-2"></i><span class="d-none d-sm-inline">Multi contribution</span>
                        </router-link>
                    </nav>
                </div>
            </div>
            <div class="app-body container mt-4">
                <router-view></router-view>
            </div>
        </div>
        <div v-else class="card card-sec border-0">
            <div class="card-header">
                <h4><i class="fas fa-chart-pie fa-fw mr-2 text-sec"></i>Semaphore App</h4>
                <p class="mb-0">Collectively improve KYC processes</p>
            </div>
            <div class="card-body">
                <router-view></router-view>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-semaphore-welcome">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-chart-pie fa-fw mr-2 text-sec"></i>Semaphore App</h4>
            <p class="mb-0">Collectively improve KYC processes</p>
        </div>
        <div class="card-body">
			<div class="py-2">
                <h6 class="card-title">Presentation</h6>
                <p class="card-text text-justify">
                    Semaphore, is the newest initiative to improve the quality of KYC reference data.
                    This data is particularly hard to harvest, costly to verify, and can’t be disclosed.
                    By signaling - without disclosing - data between members, the Semaphore application helps improving KYC data.
                </p>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="semaphore-connect">
    <div class="py-2">
        <h6 class="card-title">{{$root.keysManager.keys.length>0?"Choose an account":"Welcome"}}</h6>
        <div v-if="$root.keysManager.keys.length>0">
            <semaphore-account-picker></semaphore-account-picker>
            <hr class="sec my-3">
        </div>
        {{$root.keysManager.keys.length>0?"Alternatively":"No account found on this machine. Please"}}
        <router-link to="/semaphore/register" class="btn btn-link text-sec" tag="a">register a new account</router-link>,
        drag and drop a key or <label for="sec-loadkey-file" class="m-0 text-sec pointer">browse from disk</label>
        <input type="file" id="sec-loadkey-file" accept=".secretarium" class="d-none" @change="onKeyFile" />
        <p class="m-0">
            <sec-notif-state :state="ns.data" class="mt-3 d-inline-block"></sec-notif-state>
        </p>
    </div>
</script>

<script type="text/x-template" id="semaphore-account-picker">
    <div id="key-picker" class="mb-3">
        <div v-for="(key, i) in $root.keysManager.keys" :key="key.name">
            <div class="form-row sec-key py-1" v-if="!selectedKey||key.name==selectedKey.name">
                <div class="col">
                    <button class="btn btn-sec text-left w-100" @click.prevent="onPick(key, i)">
                        {{key.name}}
                        <i v-if="key.encrypted" class="fas fa-key fa-flip-both pr-2 text-warning"></i>
                    </button>
                </div>
                <div class="col-auto">
                    <router-link :to="'/key/manage/'+i" class="btn btn-secondary">
                        <i class="fas fa-cogs"></i>
                    </router-link>
                </div>
            </div>
        </div>
        <form v-if="selectedKey&&!selectedKey.ready" class="mt-3 form-sec" @submit.prevent>
            <div class="form-row">
                <div class="col-sm">
                    <input id="ckPwd" type="password" class="form-control" placeholder="Password" autocomplete="semaphore-password">
                </div>
                <div class="col-sm-auto mt-3 mt-sm-0">
                    <button type="submit" class="btn btn-sec" @click.prevent="decryptKey">Connect</button>
                </div>
            </div>
        </form>
    </div>
</script>

<script type="text/x-template" id="semaphore-register">
    <div>
        <div>
            <router-link to="/semaphore/connect" class="btn btn-link text-sec">
                <i class="fas fa-angle-left fw pr-1"></i>
                back to login
            </router-link>
        </div>
        <hr class="my-3 sec" />
        <div class="py-2">
            <h6 class="card-title">Create a new account</h6>
            <form @submit.prevent class="form-sec">
                <div class="form-group mt-3">
                    <label for="id-register-firstname">Firstname</label>
                    <input type="text" class="form-control" id="id-register-firstname" placeholder="Your firstname" required v-model="firstname">
                </div>
                <div class="form-group">
                    <label for="id-connec-password">Password</label>
                    <input type="password" class="form-control" id="id-connec-password" placeholder="Password" required v-model="password">
                </div>
                <button type="submit" class="btn btn-sec mt-2" @click.prevent="register" :disabled="registering">Register</button>
            </form>
            <sec-notif-state :state="ns.data" class="mt-3 d-inline-block"></sec-notif-state>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-semaphore-single-contribution">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-chart-pie fa-fw mr-2 text-sec"></i>Single contribution</h4>
        </div>
        <div class="card-body form-sec container-fluid">
            <form autocomplete="off" @submit.prevent>
            <div v-for="(f, i) in fields" :key="f.name" class="semaphore-field mb-2 mb-lg-1">
                <div class="row align-items-center">
                    <div class="col-6 col-lg-3">
                        <label :for="'semaphoreSingle-'+i">{{f.name}}</label>
                    </div>
                    <div class="col-lg-3 order-2 order-lg-1">
                        <input v-if="f.type=='text'" type="text" class="form-control form-control-sm" autocomplete="semaphore" :required="f.required"
                            :id="'semaphoreSingle-'+i" :placeholder="f.name" v-model="values[f.name]" @input="onFieldChanged(f.name, i)">
                        <select v-else-if="f.type=='list'&&f.values.length<10" class="form-control form-control-sm" autocomplete="semaphore"
                            :id="'semaphoreSingle-'+i" v-model="values[f.name]" @change="onFieldChanged(f.name, i)" :required="f.required">
                            <option value=""></option>
                            <option v-for="el in f.values" :value="el">{{el}}</option>
                        </select>
                        <input v-else-if="f.type=='list'" type="text" class="form-control form-control-sm" autocomplete="semaphore" :required="f.required"
                            :id="'semaphoreSingle-'+i" :placeholder="f.name" v-model="values[f.name]" @input="onFieldChanged(f.name, i)">
                        <select v-else-if="f.type=='bool'" class="form-control form-control-sm" autocomplete="semaphore"
                            :id="'semaphoreSingle-'+i" v-model="values[f.name]" @change="onFieldChanged(f.name, i)" :required="f.required">
                            <option value=""></option>
                            <option v-for="el in ['Y','N']" :value="el">{{el}}</option>
                        </select>
                    </div>
                    <div class="col-6 text-right text-lg-left order-1 order-lg-2">
                        <span v-if="results[f.name]" class="semaphore-underline semaphore-report-link ml-lg-3 mb-3 mb-lg-0"
                            @click="toggledReport=(toggledReport==i?-1:i)" :style="{ borderBottomColor: results[f.name].color }">
                            {{results[f.name].report}}
                        </span>
                    </div>
                </div>
                <div v-if="toggledReport==i" class="semaphore-report row">
                    <div class="col-auto p-0">
                        <pie-chart :data="results[f.name].groups" :colors="results[f.name].colors"></pie-chart>
                    </div>
                    <div class="col pt-3 pt-lg-1" style="overflow: hidden;">
                        <div class="text-nowrap">Contrib: {{results[f.name].contribution}}</div>
                        <div class="text-nowrap">Total: {{results[f.name].total}}</div>
                        <div class="text-nowrap">
                            <span class="semaphore-underline" :style="{ borderBottomColor: results[f.name].color }">
                                Group: {{results[f.name].groups[results[f.name].group]}}
                            </span>
                        </div>
                        <div class="text-nowrap">Split: {{JSON.stringify(results[f.name].groups)}}</div>
                    </div>
                </div>
            </div>
            <hr class="sec my-3">
            <div class="row align-items-center mb-2">
                <div class="col-lg-6 mt-2 mt-lg-0">
                    <button type="button" class="btn btn-sec ml-1 mr-3" :disabled="!modified" @click.prevent="contribute">Contribute</button>
                    <sec-notif-state :state="ns.data"></sec-notif-state>
                </div>
            </div>
            </form>
            <div class="mt-2 alert alert-warning alert-dismissible fade show" role="alert" v-if="warnings.length>0">
                <div v-for="warning in warnings" class="warning">{{warning}}</div>
                <button type="button" class="close" aria-label="Close" @click="warnings=[]">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div v-if="results&&Object.keys(results).length">
                <hr class="my-3 sec" />
                <div class="py-2">
                    <h6 class="card-title"
                        data-toggle="collapse" data-target="#semaphoreSingleContribution-collapse"
                        aria-expanded="true" aria-controls="semaphoreSingleContribution-collapse">
                        Data quality reports
                        <i class="fas fa-chevron-down float-right"></i>
                    </h6>
                    <div class="mt-3 collapse show" id="semaphoreSingleContribution-collapse">
                        <div class="sec-columns pt-3" style="column-width:14em">
                            <div v-for="(r, name) in results" class="mb-4 mr-2">
                                <h6 class="semaphore-report-title m-0 p-1">{{name}}</h6>
                                <div class="semaphore-report row m-0 p-2">
                                    <div class="col-auto p-0">
                                        <pie-chart :data="r.groups" :colors="r.colors"></pie-chart>
                                    </div>
                                    <div class="col px-2 pt-3 pt-lg-1" style="overflow: hidden;">
                                        <div class="text-nowrap">Contrib: {{r.contribution}}</div>
                                        <div class="text-nowrap">Total: {{r.total}}</div>
                                        <div class="text-nowrap">
                                            <span class="semaphore-underline" :style="{ borderBottomColor: r.color }">
                                                Group: {{r.groups[r.group]}}
                                            </span>
                                        </div>
                                        <div class="text-nowrap">Split: {{JSON.stringify(r.groups)}}</div>
                                    </div>
                                </div>
                                <div class="semaphore-report-status text-center rounded-bottom p-1">
                                    {{r.report}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</script>

<script type="text/x-template" id="sec-semaphore-multi-contribution">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-file-upload fa-fw mr-2 text-sec"></i>Multi contribution</h4>
        </div>
        <div class="card-body">
            <div v-if="!hasFile">
                <div class="py-2">
                    <h6 class="card-title">Bulk upload from file</h6>
                    <p class="card-text">
                        Please
                        <label for="semaphoreCsvFile" class="btn btn-link p-0 text-sec">browse from disk</label>
                        <input type="file" id="semaphoreCsvFile" accept=".csv" class="d-none" @change="csvFileChange" />
                        for your local csv file or drop it here.
                    </p>
                </div>
            </div>
            <div v-else class="py-2">
                <h6 class="card-title">Bulk upload from file</h6>
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
                        All {{rowsCount}} rows have been successfully verified
                    </p>
                    <p class="card-text" v-if="upload.done">
                        <i class="fas fa-check text-success fa-fw mr-2"></i>
                        All {{rowsCount}} rows have been successfully uploaded
                    </p>
                    <div class="mt-2 alert alert-warning alert-dismissible fade show" role="alert" v-if="verify.warnings.length>0">
                        <div v-for="warning in verify.warnings" class="warning">{{warning}}</div>
                        <button type="button" class="close" aria-label="Close" @click="verify.warnings=[]">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div v-if="!upload.done" class="mt-4">
                        <div class="mt-3">
                            <button v-if="upload.verify.showRetry" type="button" class="btn btn-sec" @click.prevent="retryMissing">Retry the {{rowsCount - upload.executed}} missing rows</button>
                            <button v-else type="button" class="btn btn-sec" @click.prevent="uploadFile" :disabled="upload.showProgress">Start upload</button>
                            <span class="small text-muted ml-3">
                                {{upload.msg}}
                            </span>
                        </div>
                    </div>
                    <div class="progress mt-3 mb-3" v-if="upload.showProgress" style="height: 5px;">
                        <div class="progress-bar no-transition" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                            v-for="b in upload.blocks"
                            :class="[b.class]"
                            :aria-valuenow="b.items*100.0/rowsCount"
                            :style="{'width': `${b.items*100.0/rowsCount}%`}"
                            :title="b.title">
                        </div>
                    </div>
                    <p class="card-text" v-if="upload.verify.counter>0">
                        {{upload.verify.issueMsg}}
                    </p>
                    <router-link v-if="upload.done" to="/semaphore/signals" class="btn btn-sec btn mt-3" tag="button">View signals</router-link>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-semaphore-signals">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-chart-bar fa-fw mr-2 text-sec"></i>Signals</h4>
        </div>
        <div class="card-body">
            <div class="py-2">
                <h6 class="card-title">All your signals</h6>
                <div class="table-responsive" v-if="signals.length">
					<table class="table table-hover fs-85 mb-0">
						<thead>
							<tr>
								<th scope="col">Country</th>
								<th scope="col">Company Registration Number</th>
								<th scope="col">Fields name</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="s in signals" :key="s.country+s.regNum">
								<td class="text-nowrap">{{s.country}}</td>
								<td class="text-nowrap">{{s.regNum}}</td>
								<td class="text-nowrap">{{s.fields}}</td>
							</tr>
						</tbody>
					</table>
                </div>
                <p v-else>No signals received yet.</p>
				<p class="m-0">
					<sec-notif-state :state="ns.data" class="mt-3 d-inline-block"></sec-notif-state>
				</p>
            </div>
            <hr class="my-3 sec" />
            <div class="py-2">
                <h6 class="card-title">Full report</h6>
                <p class="card-text">Download a report with all your signals</p>
                <div class="mt-4" v-if="!download.done">
                    <button v-if="download.showRetry" type="button" class="btn btn-sec" @click.prevent="retry">Retry</button>
                    <div v-else class="btn-group">
                        <button type="button" class="btn btn-sec" @click.prevent="downloadAsCsv"
                            :disabled="download.started">Download as Csv</button>
                        <button type="button" class="btn btn-sec dropdown-toggle dropdown-toggle-split"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="download.started">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item" @click.prevent="downloadAsJson"
                                :disabled="download.started">Download as Json</button>
                        </div>
                    </div>
                    <button v-if="download.started&&!download.stopped" type="button" class="btn btn-sec ml-3" @click.prevent="stop">Stop</button>
                    <div v-else-if="!download.started" class="ml-3" style="display:inline;">
                        Start at
                        <input type="number" id="semaphore-signals-report-cursor" placeholder="Start at" value="0" class="form-control mr-3" style="display: inline-block; width: 7em;">
                        Step
                        <select id="semaphore-signals-report-step" class="custom-select" style="width: auto;">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100" selected>100</option>
                        </select>
                    </div>
                    <span v-if="download.started" class="ml-3">{{download.msg}}</span>
                </div>
                <div class="mt-2" v-else>
                    <p class="card-text"><i class="fas fa-check text-success fa-fw mr-2"></i> {{download.msg}}</p>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-pie-chart">
    <svg class="pie" width="84" height="84">
        <path v-for="item in items" :d="item.path" :fill="item.color" />
        <text v-for="item in items" :x="item.text.x" :y="item.text.y">{{item.text.value}}</text>
    </svg>
</script>