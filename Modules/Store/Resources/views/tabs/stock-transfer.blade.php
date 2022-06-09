<div class="tab-pane fade" id="stocktransfer" role="tabpanel" aria-labelledby="stocktransfer-tab">
            <nav class="navbar navbar-expand-lg">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">File</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Transfer</a>
                        </li>
                    </ul>
                </div>
            </nav>
                <div class="container">
                <div class="modal-nav">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#sentTo" role="tab" aria-controls="home" aria-selected="true"><span></span>Sent To</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#recieve" role="tab" aria-controls="recieve" aria-selected="true"><span></span>Receive</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent" style="height: auto;">
                        <div class="tab-pane fade show active" id="sentTo" role="tabpanel" aria-labelledby="home-tab">
                            <div class="col-md-12">
                                <div class="profile-form">
                                    <div class="row top-req">
                                        <div class="col-md-3">
                                            <div class="group__box half_box">
                                                <label>Target Comp</label>
                                            </div>
                                            <div class="group__box half_box">
                                                <input type="checkbox" name="">&nbsp;&nbsp;
                                                <div class="box__input" style="flex: 0 0 92%;">
                                                    <select readonly="">
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="group__box half_box">
                                                <div class="box__input" style="flex: 0 0 37%;">
                                                    <select readonly="">
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                    </select>
                                                </div>
                                                <div class="box__input">
                                                    <input type="" name="">
                                                </div>
                                            </div>
                                            <div class="group__box half_box">
                                                <div class="box__input" style="flex: 0 0 70%;">
                                                    <select readonly="">
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                    </select>
                                                </div>
                                                <div class="box__input" style="flex: 0 0 30%;">
                                                    <select readonly="">
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                        <option value="" selected=selected></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="group__box half_box" style="margin-left: 49%;">
                                                <div class="radio-1">&nbsp;&nbsp;
                                                    <input type="radio" name="">
                                                    <label>Generic</label>&nbsp;&nbsp;

                                                    <input type="radio" name="">
                                                    <label>Brand</label>
                                                </div>
                                            </div>
                                            <div class="group__box half_box">
                                                <div class="box__input" style="flex: 0 0 15%;">
                                                    <input type="" name="">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="date" class="f-input-date full-width">
                                                </div>
                                                 <div class="box__input" style="flex: 0 0 20%;">
                                                    <input type="" name="" disabled="">
                                                </div>
                                                <div class="box__input" style="flex: 0 0 30%;">
                                                    <input type="" name="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-scroll-md table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th class="tittle-th"></th>
                                                        <th class="tittle-th">Catogery</th>
                                                        <th class="tittle-th">Particulars</th>
                                                        <th class="tittle-th">Batch</th>
                                                        <th class="tittle-th">Expiry</th>
                                                        <th class="tittle-th">QTY</th>
                                                        <th class="tittle-th">Cost</th>
                                                        <th class="tittle-th">Vendor</th>
                                                        <th class="tittle-th">Refn</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row top-req">
                                    <div class="col-md-10">
                                        {{-- next group --}}
                                        <div class="group__box half_box">
                                            <div class="box__label" style="flex: 0 0 10%;">
                                            <button class="default-btn f-btn-icon-g full-width"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                            </div>&nbsp;
                                             <div class="box__label" style="flex: 0 0 10%;">
                                                <button class="default-btn f-btn-icon-r full-width"><i class="fas fa-code"></i>&nbsp;&nbsp;Export</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                         {{-- next group --}}
                                        <div class="group__box half_box">
                                            <div class="box__label__modal">
                                                <label class="col-12">Total:</label>
                                            </div>&nbsp;
                                            <div class="box__input"  style="flex: 0 0 62%;">
                                                <input type="" name="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="recieve" role="tabpanel" aria-labelledby="recieve-tab">
                        </div>
                    </div>
                </div>
            </div>
        </div>