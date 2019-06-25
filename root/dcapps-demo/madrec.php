<style>
    #madrec-app .madrec-pie-report .card-header{padding:.5rem .25rem .5rem .25rem !important}
    #madrec-app .madrec-pie-report .card-body>div{font-size:0.6em;display:inline-block;width:125px;vertical-align:top;white-space:nowrap;overflow:hidden}
    #madrec-app .madrec-pie-report .card-body>div span.title{min-width:2.4rem;display:inline-block}
    #madrec-app .row label{display:inline-block;text-align:left;min-width:14rem;margin-bottom:0}
    #madrec-app .row .form-control{padding:0 .5rem;min-width:14rem}
    #madrec-app .row input[type="checkbox"]{width:1.25rem;height:1.25rem;margin-top:0.25rem}
    #madrec-app .madrec-underline{border-bottom:.1rem solid transparent}
    #madrec-app .madrec-report-link{cursor:pointer}
    #madrec-app .madrec-report{margin:.5rem 0 1rem 0;font-size:80%;padding:1rem;background-color:#f1f1f1}
</style>

<script type="text/x-template" id="sec-madrec">
    <div id="madrec-app" :class="{'container fixed-center':status!='granted'}">
        <div v-if="status=='granted'">
            <div class="app-header">
                <div class="container">
                    <nav class="nav nav-sec">
                        <router-link to="/madrec/members" class="nav-link mr-2" active-class="active">
                            <i class="fas fa-user-cog fa-fw mr-2"></i><span class="d-none d-sm-inline">Members</span>
                            <span v-if="requestingMembers>0" class="badge badge-info ml-1 badge-top">{{requestingMembers}}</span>
                        </router-link>
                        <router-link to="/madrec/single-lei" class="nav-link mr-2" active-class="active">
                            <i class="fas fa-chart-pie fa-fw mr-2"></i><span class="d-none d-sm-inline">Single LEI</span>
                        </router-link>
                        <router-link to="/madrec/multi-lei" class="nav-link mr-2" active-class="active">
                            <i class="fas fa-file-upload fa-fw mr-2"></i><span class="d-none d-sm-inline">Multi LEI</span>
                        </router-link>
                        <router-link to="/madrec/reports" class="nav-link mr-2" active-class="active">
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
            <h4><i class="fas fa-chart-pie fa-fw mr-2 text-sec"></i>MADRec DCApp</h4>
            <p class="mb-0">Collectively measure reference data quality</p>
        </div>
        <div class="card-body">
			<div class="py-2">
                <h6 class="card-title">Presentation</h6>
                <p class="card-text text-justify">
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
                            <td class="text-nowrap">{{p.phone}} <i v-if="p.phoneVerified" class="fas fa-check-circle text-primary"></i></td>
                            <td class="text-nowrap">{{p.email}} <i v-if="p.emailVerified" class="fas fa-check-circle text-primary"></i></td>
                            <td>{{p.role}}</td>
                            <td>{{p.status}} <span v-if="p.status=='requested'" class="badge badge-warning">{{p.grants}}</span></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm"
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
        <div class="card-body form-sec container-fluid">
            <div class="row pt-2 align-items-center">
                <div class="col-lg-3">
                    <label for="madrecSingleLei" style="font-weight: bold;">LEI Code</label>
                </div>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="madrecSingleLei" placeholder="LEI Code" v-model="values.lei" @input="onLeiChanged">
                </div>
                <div class="col-lg-6 mt-2 mt-lg-0">
                    <a v-if="leiState==2&&exportUrl.length>0" class="btn btn-sec ml-1 mr-3" :href="exportUrl" download="single-LEI-report.json">Export</a>
                    <button v-else-if="leiState==1||modified" type="button" class="btn btn-sec ml-1 mr-3" @click.prevent="loadLEI">
                        {{modified?'Reload':'Load'}}
                    </button>
                    <sec-notif-state :state="nsGet.data"></sec-notif-state>
                </div>
            </div>
            <hr class="sec my-3">
            <div v-for="(f, i) in fields" :key="f.name" class="madrec-field mb-2 mb-lg-1">
                <div class="row align-items-center">
                    <div class="col-6 col-lg-3">
                        <label :for="'madrecSingle-'+i">{{f.name}}</label>
                    </div>
                    <div class="col-lg-3 order-2 order-lg-1">
                        <input v-if="f.type=='text'" type="text" class="form-control form-control-sm"
                            :id="'madrecSingle-'+i" :placeholder="f.name" v-model="values[f.name]" @input="onFieldChanged(f.name, i)">
                        <select v-else-if="f.type=='list'" class="form-control form-control-sm"
                            :id="'madrecSingle-'+i" v-model="values[f.name]" @change="onFieldChanged(f.name, i)">
                            <option value=""></option>
                            <option v-for="el in f.values" :value="el">{{el}}</option>
                        </select>
                        <select v-else-if="f.type=='bool'" class="form-control form-control-sm"
                            :id="'madrecSingle-'+i" v-model="values[f.name]" @change="onFieldChanged(f.name, i)">
                            <option value=""></option>
                            <option v-for="el in ['Y','N']" :value="el">{{el}}</option>
                        </select>
                        <!-- Does not provide 'not contributed' option
                        <input v-else-if="f.type=='bool'" type="checkbox"
                            :id="'madrecSingle-'+i" v-model="values[f.name]" @change="onFieldChanged(f.name, i)"> -->
                    </div>
                    <div class="col-6 text-right text-lg-left order-1 order-lg-2">
                        <span v-if="results[f.name]" class="madrec-underline madrec-report-link ml-lg-3 mb-3 mb-lg-0"
                            @click="toggledReport=(toggledReport==i?-1:i)" :style="{ borderBottomColor: results[f.name].color }">
                            {{results[f.name].report}}
                        </span>
                    </div>
                </div>
                <div v-if="toggledReport==i" class="madrec-report row">
                    <div class="col-auto p-0">
                        <pie-chart :data="results[f.name].groups" :colors="results[f.name].colors"></pie-chart>
                    </div>
                    <div class="col pt-3 pt-lg-1" style="overflow: hidden;">
                        <div class="text-nowrap">Contrib: {{results[f.name].contribution}}</div>
                        <div class="text-nowrap">Total: {{results[f.name].total}}</div>
                        <div class="text-nowrap">
                            <span class="madrec-underline" :style="{ borderBottomColor: results[f.name].color }">
                                Group: {{results[f.name].groups[results[f.name].group]}}
                            </span>
                        </div>
                        <div class="text-nowrap">Split: {{JSON.stringify(results[f.name].groups)}}</div>
                    </div>
                </div>
            </div>
            <hr class="sec my-3">
            <div class="row align-items-center mb-2">
                <div class="col-lg-3">
                    <label for="madrecSingleLei">Hashing options</label>
                </div>
                <div class="col-lg-3">
                    <select class="form-control" id="madrecSingleHashOpt" @change="onHashChanged">
                        <option value="1" selected>Do not hash</option>
                        <option value="2">Hash</option>
                    </select>
                </div>
                <div class="col-lg-6 mt-2 mt-lg-0">
                    <button type="button" class="btn btn-sec ml-1 mr-3" :disabled="!modified" @click.prevent="contributeLEI">Contribute</button>
                    <sec-notif-state :state="nsPut.data"></sec-notif-state>
                </div>
            </div>
            <div class="mt-2 alert alert-warning alert-dismissible fade show" role="alert" v-if="warnings.length>0">
                <div v-for="warning in warnings" class="warning">{{warning}}</div>
                <button type="button" class="close" aria-label="Close" @click="warnings=[]">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <router-link v-if="upload.done" to="/madrec/reports" class="btn btn-sec btn mt-3" tag="button">View reports</router-link>
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