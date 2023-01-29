<div class="row">

    <div
        class="content-wrapper-before  gradient-45deg-indigo-purple ">
    </div>


    <div class="col s12">
        <div class="container">

            <div class="section">
                <div class="row vertical-modern-dashboard">
                    <div class="col s12 m12 l12 animate fadeRight">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s6">
                                        <h4 class="card-title float-left"> Categories</h4>
                                    </div>
                                    <div class="col s6">
                                        <a class="float-right" id="addCategory" href="#">
                                            <i class="material-icons left" style="font-size: 2.5em;">add_box</i>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="row">
                                        <div class="input-field col m3 s6">
                                            <i class="material-icons prefix">access_time</i>
                                            <input id="from_date" type="text" class="validate datepicker">
                                            <label for="from_date">From Date</label>
                                        </div>
                                        <div class="input-field col m3 s6">
                                            <i class="material-icons prefix">access_time</i>
                                            <input id="to_date" type="text" class="validate datepicker">
                                            <label for="to_date">To Date</label>
                                        </div>
                                        <div class="input-field col m3 s6">
                                            <i class="material-icons prefix">vpn_key</i>
                                            <input id="vehicle_reg_no" type="text" class="validate">
                                            <label for="vehicle_reg_no">Reg No</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <div class="input-field col s12">
                                                <button class="btn cyan waves-effect waves-light" type="submit"
                                                        name="action" id="searchClaim">
                                                    <i class="material-icons left">search</i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="row">
                                    <div class="col s12">
                                        <table id="data-table-simple" class="display">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Created</th>
                                                <th>Operation</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Current balance & total transactions cards-->

            </div>
        </div>

        <div class="content-overlay"></div>
    </div>
</div>
