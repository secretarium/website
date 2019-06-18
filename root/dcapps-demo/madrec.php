<style>
    #madrec-app .madrec-pie-report .card-header{padding:.5rem .25rem .5rem .25rem !important}
    #madrec-app .madrec-pie-report .card-body>div{font-size:0.6em;display:inline-block;width:125px;vertical-align:top;white-space:nowrap;overflow:hidden}
    #madrec-app .madrec-pie-report .card-body>div span.title{min-width:2.4rem;display:inline-block}
    #madrec-app .form-inline{margin-bottom:0.25em}
    #madrec-app .form-inline label{display:inline-block;text-align:left;min-width:15rem}
    #madrec-app .form-inline .form-control{height:calc(1.5em + .3rem + 2px);padding:0 .75rem;min-width:15rem}
    #madrec-app .form-inline select{width:auto}
</style>

<script type="text/x-template" id="sec-madrec">
    <div id="madrec-app" :class="{'container fixed-center':status!='granted'}">
        <div v-if="status=='granted'">
            <div class="app-header">
                <div class="container">
                    <nav class="nav nav-sec">
                        <router-link to="/madrec/members" class="nav-link" active-class="active">
                            <i class="fas fa-user-cog fa-fw mr-2"></i><span class="d-none d-sm-inline">Members</span>
                            <span v-if="requestingMembers>0" class="badge badge-info ml-1 badge-top">{{requestingMembers}}</span>
                        </router-link>
                        <router-link to="/madrec/single-lei" class="nav-link" active-class="active">
                            <i class="fas fa-chart-pie fa-fw mr-2"></i><span class="d-none d-sm-inline">Single LEI</span>
                        </router-link>
                        <router-link to="/madrec/multi-lei" class="nav-link" active-class="active">
                            <i class="fas fa-file-upload fa-fw mr-2"></i><span class="d-none d-sm-inline">Multi LEI</span>
                        </router-link>
                        <router-link to="/madrec/reports" class="nav-link" active-class="active">
                            <i class="fas fa-chart-bar fa-fw mr-2"></i><span class="d-none d-sm-inline">Reports</span>
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
                <h4><i class="fas fa-chart-pie fa-fw mr-2 text-sec"></i>MADRec DCApp</h4>
                <p class="mb-0">Collectively measure reference data quality</p>
            </div>
            <div class="card-body">
                <sec-madrec-access-requested v-if="status=='requested'"></sec-madrec-access-requested>
                <sec-madrec-access-denied v-else></sec-madrec-access-denied>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-madrec-access-denied">
    <div>
        <div class="py-2">
            <h6 class="card-title">Restricted access</h6>
            <p class="card-text">This DCApp is private and requires new members to be co-opted.</p>
        </div>
        <hr class="my-3 sec" />
        <div class="py-2">
            <h6 class="card-title">Identification required</h6>
            <p class="card-text">
                Current members of the MADRec app need minimal information about you to grant you access.<br/>
                Please grant MADRec access to the personal information listed below.
            </p>
        </div>
        <hr class="my-3 sec" />
        <div class="py-2">
            <div v-if="!records.hasEnough">
                <p class="card-text">MADRec would like to access to some of your personal records that you haven't filled yet</p>
                <ul class="list-group" style="max-width: 400px;">
                    <li class="list-group-item">
                        <i v-if="!records.hasFirstname" class="fas fa-exclamation-triangle fa-fw mr-2 text-danger"></i>
                        <i v-else class="fas fa-check fa-fw mr-2 text-primary"></i>
                        Firstname
                    </li>
                    <li class="list-group-item">
                        <i v-if="!records.hasLastname" class="fas fa-exclamation-triangle fa-fw mr-2 text-danger"></i>
                        <i v-else class="fas fa-check fa-fw mr-2 text-primary"></i>
                        Lastname
                    </li>
                    <li class="list-group-item">
                        <i v-if="!records.hasPhoneVerified&&!records.hasEmailVerified" class="fas fa-exclamation-triangle fa-fw mr-2 text-danger"></i>
                        <i v-else class="fas fa-check fa-fw mr-2 text-primary"></i>
                        Verified phone and/or email
                    </li>
                </ul>
                <router-link to="/me" class="btn btn-sec mt-4" tag="button">Update my profile</router-link>
            </div>
            <div v-else>
                <p class="card-text">MADRec would like to access to some of your personal records</p>
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="firstname" id="madrec_ra_firstname" checked="checked" disabled>
                        <label class="form-check-label" for="madrec_ra_firstname">First name</label>
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" value="lastname" id="madrec_ra_lastname" checked="checked" disabled>
                        <label class="form-check-label" for="madrec_ra_lastname">Last name</label>
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" value="phone" id="madrec_ra_phone" :checked="records.hasPhoneVerified">
                        <label class="form-check-label" for="madrec_ra_phone">
                            Phone <i v-if="records.hasPhoneVerified" class="fas fa-check-circle text-primary"></i>
                        </label>
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" value="email" id="madrec_ra_email" :checked="records.hasEmailVerified">
                        <label class="form-check-label" for="madrec_ra_email">
                            Email <i v-if="records.hasEmailVerified" class="fas fa-check-circle text-primary"></i>
                        </label>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-sec" @click.prevent="requestAccess">Share personal records and request access</button>
                        <sec-notif-state :state="nsRequest.data"></sec-notif-state>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-madrec-access-requested">
    <div class="py-2">
        <h6 class="card-title">Restricted access</h6>
        <p class="card-text">
            Your request to join MADRec has been registered.<br />
            Waiting for {{1-$root.store.user.dcapps.madrec.data.grants}} MADRec participant to grant you access.
        </p>
    </div>
</script>

<script type="text/x-template" id="sec-madrec-welcome">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-chart-pie fa-fw mr-2 text-sec"></i>Welcome to MADRec</h4>
        </div>
        <div class="card-body">
			<div class="py-2">
                <h6 class="card-title">Presentation</h6>
                <p class="card-text">
                    MADRec, Massive Anonymous Data Reconciliation, is the newest initiative to bring data into line ahead of MiFID II.
                    Banks and data-vendors are cooperating on this joint initiative to improve the quality of counter-party reference data.
                    This data is particularly hard to harvest, costly to verify, and can’t be disclosed.
                    By comparing - without disclosing - data with other members data, the MADRec application allows the creation of data
                    quality benchmarks, and therefore reduces the scope of data to verify.
                </p>
            </div>
			<div class="py-2">
                <h6 class="card-title">Documentation</h6>
                <p class="card-text">
                    Please find the documentation regarding the Web client or the MADRec application API on
                    <a href="https://madrec.readthedocs.io" class="btn-link text-sec" target="_blank">https://madrec.readthedocs.io</a>.
                </p>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-madrec-members">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-user fa-fw mr-2 text-sec"></i>MADRec members</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover table-sec mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Firstname</th>
                            <th scope="col">Lastname</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in members">
                            <td>{{p.firstname}}</td>
                            <td>{{p.lastname}}</td>
                            <td>{{p.phone}} <i v-if="p.phoneVerified" class="fas fa-check-circle text-primary"></i></td>
                            <td>{{p.email}} <i v-if="p.emailVerified" class="fas fa-check-circle text-primary"></i></td>
                            <td>{{p.role}}</td>
                            <td>{{p.status}} <span v-if="p.status=='requested'" class="badge badge-warning">{{p.grants}}</span></td>
                            <td>
                                <button type="button" class="btn btn-sec btn-sm"
                                    @click.prevent="vote('granted', p.cooptionId)" v-if="!p.isSelf&&p.vote!='granted'">Grant</button>
                                <span v-if="p.isSelf">(you)</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                    @click.prevent="vote('denied', p.cooptionId)" v-if="!p.isSelf&&p.vote!='denied'">Deny</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <sec-notif-state :state="ns.data"></sec-notif-state>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-madrec-single-lei">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-chart-pie fa-fw mr-2 text-sec"></i>Single LEI</h4>
        </div>
        <div class="card-body">
			<div class="py-2">
                <div class="mt-2 form-inline form-sec">
                    <label for="madrecSingleLei" style="font-weight: bold;">{{lei.name}}</label>
                    <input type="text" class="form-control" id="madrecSingleLei" :placeholder="lei.name" :value="useSample?lei.sample:''">
                </div>
            </div>
            <hr class="my-3 sec" />
            <div class="py-2">
				<h6 class="card-title" data-toggle="collapse" data-target="#sec-madrec-single-lei-put-collapse"
					aria-expanded="true" aria-controls="sec-madrec-single-lei-put-collapse">
					Update fields data
					<i class="fas fa-chevron-down float-right"></i>
				</h6>
				<div class="mt-3 collapse" id="sec-madrec-single-lei-put-collapse">
                    <div v-for="(f, i) in fields" :key="f.name" class="form-inline form-sec">
                        <label :for="'madrecSingle-'+i">{{f.name}}</label>
                        <input v-if="f.type=='text'" type="text" :id="'madrecSingle-'+i" :placeholder="f.name"
                            :value="useSample?f.sample:''" class="form-control">
                        <select v-else-if="f.type=='list'" class="form-control" :id="'madrecSingle-'+i">
                            <option value="-1"></option>
                            <option v-for="(el, idx) in f.values" :value="idx" :selected="useSample&&f.sample==el">{{el}}</option>
                        </select>
                        <select v-else-if="f.type=='bool'" class="form-control" :id="'madrecSingle-'+i">
                            <option value="-1"></option>
                            <option v-for="(el, idx) in ['Y','N']" :value="idx" :selected="useSample&&f.sample==el">{{el}}</option>
                        </select>
                    </div>
                    <div class="mt-4 mb-2 form-inline form-sec">
                        <label for="madrecSingleLei">Select one hashing options</label>
                        <select class="form-control" id="madrecSingleHashOpt">
                            <option value="1">Do not hash</option>
                            <option value="2" selected>Hash</option>
                        </select>
                    </div>
                    <div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-sec mr-2" @click.prevent="madrecPut">Contribute</button>
                            <sec-notif-state :state="nsPut.data"></sec-notif-state>
                        </div>
                        <div class="mt-2 alert alert-warning alert-dismissible fade show" role="alert" v-if="warnings.length>0">
                            <div v-for="warning in warnings" class="warning">{{warning}}</div>
                            <button type="button" class="close" aria-label="Close" @click="warnings=[]">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
			</div>
            <hr class="my-3 sec" />
            <div class="py-2">
				<h6 class="card-title" data-toggle="collapse" data-target="#sec-madrec-single-lei-get-collapse"
					aria-expanded="true" aria-controls="sec-madrec-single-lei-get-collapse">
					LEI quality benchmark
					<i class="fas fa-chevron-down float-right"></i>
				</h6>
				<div class="mt-3 collapse" id="sec-madrec-single-lei-get-collapse">
                    <div class="madrec-pie-report card mt-3 mr-3" style="display: inline-block;" v-for="(item, name) in results" :key="name" >
                        <div class="card-header text-center">{{name}}</div>
                        <div class="card-body p-2" style="position:relative;">
                            <pie-chart :data="item.groups" :colors="item.colors"></pie-chart>
                            <div class="ml-1 mt-2">
                                <div class="text-nowrap">Contrib: {{item.contribution}}</div>
                                <div class="text-nowrap">Total: {{item.total}}</div>
                                <div class="text-nowrap">
                                    <span :style="{ borderBottom: '4px solid ' + item.color }" style ="display: inline-block; line-height: 12px;">Group: {{item.groups[item.group]}}</span>
                                </div>
                                <div class="text-nowrap">Split: {{JSON.stringify(item.groups)}}</div>
                            </div>
                        </div>
                        <div class="card-footer text-center p-1">
                            {{item.report}}
                        </div>
                    </div>
                    <div class="mt-3">
                        <a v-if="exportableResults.length>0" class="btn btn-sec btn-sm float-right" :href="resultsExportUrl" download="single-LEI-report.json">Export</a>
                        <button type="button" class="btn btn-sec mr-3" @click.prevent="madrecGet">Refresh</button>
                        <sec-notif-state :state="nsGet.data"></sec-notif-state>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-madrec-multi-lei">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-file-upload fa-fw mr-2 text-sec"></i>Multi LEI report</h4>
        </div>
        <div class="card-body">
            <div v-if="!hasFile">
                <div class="py-2">
                    <h6 class="card-title">Instructions</h6>
                    <p class="card-text">
                        Please find the
                        <a href="https://github.com/Secretarium/MADRec/wiki/Instructions#madrec"
                            class="btn-link text-sec" target="_blank">instructions</a>
                        on Github.
                    </p>
                </div>
                <hr class="my-3 sec" />
                <div class="py-2" v-if="$root.store.isTestEnv">
                    <h6 class="card-title">Want to test ?</h6>
                    <p class="card-text">Please use the samples:<br />
                        <a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.250.csv" download="sample.250.csv">250 LEIs</a>
                        <a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.20000.csv" download="sample.20000.csv">20k LEIs</a>
                        <a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.100000.csv" download="sample.100000.csv">100k LEIs</a>
                        <a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.234567.csv" download="sample.234567.csv">234k+ LEIs</a>
                        <a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.500000.csv" download="sample.500000.csv">500k LEIs</a>
                        <a class="btn btn-sec btn-sm mt-2 mr-2" href="/downloads/sample.1325966.csv" download="sample.1325966.csv">1.3M+ LEIs</a>
                    </p>
                </div>
                <hr class="my-3 sec" v-if="$root.store.isTestEnv" />
                <div class="py-2">
                    <h6 class="card-title">Push multiple LEIs to MADRec</h6>
                    <p class="card-text">
                        Please
                        <label for="madrecCsvFile" class="btn btn-link p-0 text-sec">browse from disk</label>
                        <input type="file" id="madrecCsvFile" accept=".csv" class="d-none" @change="csvFileChange" />
                        for your local MADRec csv file or drop it here.
                    </p>
                </div>
            </div>
            <div v-else class="py-2">
                <h6 class="card-title">Push multiple LEIs to MADRec</h6>
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
                        All {{rowsCount}} LEIs and associated fields have been successfully verified
                    </p>
                    <p class="card-text" v-if="upload.done">
                        <i class="fas fa-check text-success fa-fw mr-2"></i>
                        All {{rowsCount}} LEIs have been successfully uploaded
                    </p>
                    <div class="mt-2 alert alert-warning alert-dismissible fade show" role="alert" v-if="verify.warnings.length>0">
                        <div v-for="warning in verify.warnings" class="warning">{{warning}}</div>
                        <button type="button" class="close" aria-label="Close" @click="verify.warnings=[]">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div v-if="!upload.done" class="mt-4">
                        <p class="card-text mt-2 mb-2">Please select one of the hashing options:</p>
                        <select class="custom-select" style="width: 350px;" id="madrecMultiPutHash">
                            <option value="1" v-if="!verify.isHashed">Do not hash</option>
                            <option value="2" v-if="!verify.isHashed" selected>Hash</option>
                            <option value="3" v-if="verify.isHashed===true">Already hashed</option>
                        </select>
                        <div class="mt-3">
                            <button v-if="upload.verify.showRetry" type="button" class="btn btn-sec" @click.prevent="retryMissing">Retry the {{rowsCount - upload.executed}} missing LEIs</button>
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
                    <router-link v-if="upload.done" to="/madrec/reports" class="btn btn-success btn mt-3" tag="button">View reports</router-link>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="sec-madrec-report">
    <div class="card card-sec border-0">
        <div class="card-header">
            <h4><i class="fas fa-chart-bar fa-fw mr-2 text-sec"></i>Global report</h4>
        </div>
        <div class="card-body">
            <div class="py-2">
                <h6 class="card-title">Personal report</h6>
                <p class="card-text">Your MADRec personal report
                    <sec-notif-state :state="nsUserReport.data"></sec-notif-state>
                    <a v-show="personalReport.length>0" class="btn btn-sec btn-sm float-right" style="margin-top: -0.25em;" :href="personalReportExportUrl" download="personal-report.json">Export</a>
                </p>
                <div class="mt-2">
                    <div style="position: relative;">
                        <canvas id="madrec-user-report"></canvas>
                    </div>
                </div>
            </div>
            <hr class="my-3 sec" />
            <div class="py-2">
                <h6 class="card-title">Consortium report</h6>
                <p class="card-text">The MADRec consortium report
                    <sec-notif-state :state="nsConsortiumReport.data"></sec-notif-state>
                    <a v-show="consortiumReport.length>0" class="btn btn-sec btn-sm float-right" style="margin-top: -0.25em;" :href="consortiumReportExportUrl" download="consortium-report.json">Export</a>
                </p>
                <div class="mt-2">
                    <div style="position: relative;">
                        <canvas id="madrec-consortium-report"></canvas>
                    </div>
                </div>
            </div>
            <hr class="my-3 sec" />
            <div class="py-2">
                <h6 class="card-title">LEIs report</h6>
                <p class="card-text">Download a report with all your LEIs</p>
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
                        <input type="number" id="madrec-leis-report-cursor" placeholder="Start at" value="0" class="form-control mr-3" style="display: inline-block; width: 7em;">
                        Step
                        <select id="madrec-leis-report-step" class="custom-select" style="width: auto;">
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

<script type="text/javascript">
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
                else if(field.split.length < field.total) {
                    field.report = "split consensus with " + (field.group == 0 ? "majority" : "minority");
                }
                else {
                    field.report = "no consensus";
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
                if(f != MADRec.lei.name && f != "Original " + MADRec.lei.name && fieldsIndex[f.name] != undefined)
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
        data: function () {
            return {
                nsRequest: new notifState(2)
            }
        },
        beforeMount: function() {
            store.SCPs[MADRecCluster]
                .sendQuery("madrec", "get-status", "madrec-get-status")
                .onResult(x => {
                    Vue.set(store.user.dcapps.madrec.data, "accessStatus", x.status);
                    Vue.set(store.user.dcapps.madrec.data, "grants", x.grants || 0);
                });
        },
        computed: {
            status() { return store.user.dcapps.madrec.data.accessStatus; },
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
            },
            requestingMembers() {
                return store.user.dcapps.madrec.data.requestingMembers || 0;
            }
        },
        methods: {
            async requestAccess() {
                let args = { id: store.dcapps.madrec.id, items: [ "firstname", "lastname" ] },
                    dcapp = store.dcapps["madrec"];
                if($("#madrec_ra_phone").is(":checked")) args.items.push("phone");
                if($("#madrec_ra_email").is(":checked")) args.items.push("email");
                this.nsRequest.start();
                store.SCPs[MADRecCluster]
                    .sendTx("identity", "share-with", "identity-share-with", args)
                    .onError(x => { this.nsRequest.failed(x, true); })
                    .onAcknowledged(x => { this.nsRequest.acknowledged(); })
                    .onProposed(x => { this.nsRequest.proposed(); })
                    .onCommitted(x => { this.nsRequest.committed(); })
                    .onExecuted(x => {
                        this.nsRequest.executed();
                        store.SCPs[MADRecCluster]
                            .sendTx("madrec", "request-access", "madrec-request-access", { role: "participant" })
                            .onError(x => { this.nsRequest.failed(x, true); })
                            .onAcknowledged(x => { this.nsRequest.acknowledged(); })
                            .onProposed(x => { this.nsRequest.proposed(); })
                            .onCommitted(x => { this.nsRequest.committed(); })
                            .onExecuted(x => { this.nsRequest.executed().hide(); });
                    });
            }
        }
    });
    const MADRecAppAccessDenied = Vue.component('sec-madrec-access-denied', {
        template: '#sec-madrec-access-denied',
        data: function () {
            return {
                nsRequest: new notifState(2)
            }
        },
        beforeMount: function() {
            store.SCPs[MADRecCluster]
                .sendQuery("madrec", "get-status", "madrec-get-status")
                .onResult(x => {
                    Vue.set(store.user.dcapps.madrec.data, "accessStatus", x.status);
                    Vue.set(store.user.dcapps.madrec.data, "grants", x.grants || 0);
                });
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
            },
            requestingMembers() {
                return store.user.dcapps.madrec.data.requestingMembers || 0;
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
                    .onAcknowledged(x => { this.nsRequest.acknowledged(); })
                    .onProposed(x => { this.nsRequest.proposed(); })
                    .onCommitted(x => { this.nsRequest.committed(); })
                    .onExecuted(x => {
                        this.nsRequest.executed();
                        store.SCPs[MADRecCluster]
                            .sendTx("madrec", "request-access", "madrec-request-access", { role: "participant" })
                            .onError(x => { this.nsRequest.failed(x, true); })
                            .onAcknowledged(x => { this.nsRequest.acknowledged(); })
                            .onProposed(x => { this.nsRequest.proposed(); })
                            .onCommitted(x => { this.nsRequest.committed(); })
                            .onExecuted(x => { this.nsRequest.executed().hide(); });
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
                                status: p.status, isSelf: p.isSelf, grants: p.grants || 0, vote: p.vote || ""
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
                    .onAcknowledged(x => { this.ns.acknowledged(); })
                    .onProposed(x => { this.ns.proposed(); })
                    .onCommitted(x => { this.ns.committed(); })
                    .onExecuted(x => {
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
                results: [],
                exportableResults: "",
                warnings: [],
                lei: MADRec.lei,
                fields: MADRec.fields,
                useSample: true
            }
        },
        computed: {
            resultsExportUrl() {
                let blob = new Blob([this.exportableResults], { type: 'application/json;charset=utf-8;' });
                return URL.createObjectURL(blob);
            }
        },
        methods: {
            async madrecPut() {
                this.warnings = [];
                let lei = $('#madrecSingleLei').val(), values = {};
                for(let [i, f] of MADRec.fields.entries()) {
                    let v = $('#madrecSingle-' + i).val();
                    switch(f.type) {
                        case "text": if(v != "") values[f.name] = v; break;
                        case "list": if(v > -1) values[f.name] = f.values[v]; break;
                        case "bool": if(v > -1) values[f.name] = ["Y", "N"][v]; break;
                    }
                }
                let check = MADRecUtils.verifyData(lei, values), w = check.warnings;
                if(check.success !== true) {
                    this.nsPut.failed(check.field + " " + check.error, true);
                    return;
                }
                if(check.isHashed) {
                    this.nsPut.failed("Some of your data seem to be already hashed", true);
                    return;
                }
                if(w) this.warnings = Object.keys(w).map(x => { return x + (w[x] > 1 ? " (" + w[x] + "  times)" : ""); });

                let hashOpt = $('#madrecSingleHashOpt').val();
                let args = { [MADRec.lei.name]: lei, hashed: hashOpt > 1, values: [] };
                for(let v in values) {
                    args.values.push({ name: v, value: values[v]});
                }
                if(hashOpt == 2) {
                    let salt = await sec.utils.hashBase64(args[MADRec.lei.name]);
                    args[MADRec.lei.name] = await MADRecUtils.hashData(salt, args[MADRec.lei.name]);
                    for(let v of args.values) {
                        v.value = await MADRecUtils.hashData(salt, v.value);
                    }
                }

                this.nsPut.start();
                store.SCPs[MADRecCluster]
                    .sendTx("madrec", "put", "madrec-single-put", args)
                    .onError(x => { this.nsPut.failed(x, true); })
                    .onAcknowledged(x => { this.nsPut.acknowledged(); })
                    .onProposed(x => { this.nsPut.proposed(); })
                    .onCommitted(x => { this.nsPut.committed(); })
                    .onExecuted(x => {
                        this.nsPut.executed().hide();
                        this.madrecGet();
                    });
            },
            madrecGet() {
                let lei = $("#madrecSingleLei").val(), warning = "";
                if(typeof lei === "string" && lei.length == 44)
                    warning = "hashed LEI, can't verify format";
                else {
                    let v = MADRec.lei.verifier(lei);
                    if(v.success !== true) {
                        this.nsGet.failed(v.error, true);
                        this.results = {};
                        this.exportableResults = "";
                        return;
                    }
                }
                this.nsGet.start(warning, warning != "");
                store.SCPs[MADRecCluster]
                    .sendQuery("madrec", "get", "madrec-single-get", { [MADRec.lei.name]: lei, subscribe: true })
                    .onError(x => { this.nsGet.failed(x, true); })
                    .onResult(x => {
                        this.nsGet.executed().hide();
                        MADRecUtils.fillReport(x.fields);
                        this.exportableResults = JSON.stringify(x.fields, null, 4);
                        MADRecUtils.fillColors(x.fields);
                        this.results = x.fields;
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
                    .onAcknowledged(x => {
                        if(this.upload.blocks[blockId].state != "failed") {
                            this.updateUploadBlockState(blockId, "acknowledged");
                            this.upload.acknowledged += items;
                        }
                    })
                    .onExecuted(x => {
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
</script>