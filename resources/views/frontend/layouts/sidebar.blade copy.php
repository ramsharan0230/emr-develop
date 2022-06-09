<div class="iq-sidebar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="{{ url('admin/dashboard') }}">
            @if( Options::get('brand_image') && Options::get('brand_image') != "" )
                <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt=""/>
            @endif
            @if(isset(Options::get('siteconfig')['system_slogan']))
            <!-- <h6>{{ Options::get('siteconfig')['system_slogan'] }}</h6> -->
            @endif
        </a>
        <div class="iq-menu-bt-sidebar">
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-menu-2-line"></i></div>
                    <div class="hover-circle"><i class="ri-menu-2-line"></i></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Menu Search Box -->

    <!-- End Of Menu Search Box -->
    <div id="sidebar-scrollbar">
        <nav class="iq-sidebar-menu">
            @include('frontend.common.search_menu_input')
            <ul id="iq-sidebar-toggle" class="iq-menu">

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'system-settings' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'hospital-branch' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'hospital-department' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'prefix-setting' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'bed-setting' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'departments-setups' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'municipality-setting' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'lab-settings' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'device-settings' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'form-settings' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'medicine-settings' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'dispensing-settings' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'form-signature' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'payment-gateways' )
                )

                    <li class="{{ Route::is('setting.system')
                || Route::is('hospital.branch')
                || Route::is('hospital.department')
                || Route::is('prefix.setting')
                || Route::is('setting.bed')
                ||  Route::is('department')
                || Route::is('municipality')
                ||  Route::is('lab-setting')
                || Route::is('setting.device')
                || Route::is('setting.form')
                || Route::is('setting.medicine')
                || Route::is('setting.purchaseOrder')
                || Route::is('setting.dispensing')
                || Route::is('setting.signature.form')
                ||  Route::is('admin.paymentgateway.list') ? 'active main-active':'' }}">
                        <a href="#setting" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false"><i class="ri-settings-fill"></i><span>Settings</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="setting" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'system-settings' ) )
                                <li class="{{ Route::is('setting.system') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.system') }}">Hospital Info Setup</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'hospital-branch' ) )
                                <li class="{{ Route::is('hospital.branch') ? 'active main-active':'' }}"><a
                                            href="{{ route('hospital.branch') }}">Hospital Branch</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'hospital-department' ) )
                                <li class="{{ Route::is('hospital.department') ? 'active main-active':'' }}"><a
                                            href="{{ route('hospital.department') }}">Hospital Department</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'prefix-setting' ) )
                                <li class="{{ Route::is('prefix.setting') ? 'active main-active':'' }}"><a
                                            href="{{ route('prefix.setting') }}">Prefix Setting</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'bed-setting' ) )
                                <li class="{{ Route::is('setting.bed') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.bed') }}">Bed Floor/Type Setting</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'departments-setups' ) )
                                <li class="{{ Route::is('department') ? 'active main-active':'' }}"><a
                                            href="{{ route('department') }}">Patient Department Setups</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'municipality-setting' ) )
                                <li class="{{ Route::is('municipality') ? 'active main-active':'' }}"><a
                                            href="{{ route('municipality') }}">Municipality Setting</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'lab-settings' ) )
                                <li class="{{ Route::is('lab-setting') ? 'active main-active':'' }}"><a
                                            href="{{ route('lab-setting') }}">Lab Settings</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'device-settings' ) )
                                <li class="{{ Route::is('setting.device') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.device') }}">Device Settings</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'form-settings' ) )
                                <li class="{{ Route::is('setting.form') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.form') }}">Delivery Settings</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'medicine-settings' ) )
                                <li class="{{ Route::is('setting.medicine') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.medicine') }}">Stock color Settings</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-order-settings' ) )
                                <li class="{{ Route::is('setting.purchaseOrder') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.purchaseOrder') }}">Purchase Order Settings</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'dispensing-settings' ) )
                                <li class="{{ Route::is('setting.dispensing') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.dispensing') }}">Dispensing Settings</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'form-signature' ) )
                                <li class="{{ Route::is('setting.signature.form') ? 'active main-active':'' }}"><a
                                            href="{{ route('setting.signature.form') }}">Form Signature</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'payment-gateways' ) )
                                <li class="{{ Route::is('admin.paymentgateway.list') ? 'active main-active':'' }}"><a
                                            href="{{ route('admin.paymentgateway.list') }}">Payment Gateways</a></li>
                            @endif


                        <!-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'interface-mapping' ) ) -->
                        <!-- <li class="{{ Route::is('machine.interfacing.list') ? 'active main-active':'' }}"><a href="{{ route('machine.interfacing.list') }}">Interface Mapping</a></li> -->
                        <!-- @endif -->

                        <!-- <li class="{{ Route::is('advertisement') ? 'active main-active':'' }}"><a href="{{ route('advertisement') }}">Advertisement</a></li> -->
                        <!-- <li class="{{ Route::is('ethnic') ? 'active main-active':'' }}"><a href="{{ route('ethnic') }}">Ethnic Setting</a></li> -->
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'insurance-setting' ) )
                                <li class="{{ Route::is('insurance') ? 'active main-active':'' }}"><a
                                            href="{{ route('insurance') }}">Insurance Setting</a></li>
                            @endif


                        <!-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'ip-autobilling' ) )
                            <li class="{{ Route::is('auto.billing') ? 'active main-active':'' }}"><a href="{{ route('auto.billing') }}">IP Auto Billing</a></li>
                        @endif -->
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'notification.setting' ) )
                                <li class="{{ Route::is('notification.setting') ? 'active main-active':'' }}"><a
                                            href="{{ route('notification.setting') }}">Notification Setting</a></li>
                            @endif


                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'users' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'group' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'permission-setting' ))
                    <li class="{{ Route::is('admin.user.list') || Route::is('admin.user.groups')
                || Route::is('permission.setting')  ? 'active main-active':'' }}">
                        <a href="#user" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false"><i class="ri-user-3-fill"></i><span>User</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="user" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'users' ) )
                                <li class="{{ Route::is('admin.user.list') ? 'active main-active':'' }}"><a
                                            href="{{ route('admin.user.list') }}">Users setup</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'permission-setting' ) )
                                <li class="{{ Route::is('permission.setting') ? 'active main-active':'' }}"><a
                                            href="{{ route('permission.setting') }}">Permission Setting</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'group' ) )
                                <li class="{{ Route::is('admin.user.groups') ? 'active main-active':'' }}"><a
                                            href="{{ route('admin.user.groups') }}">Persmission Setup</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'fiscal-year-setups' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'register-settings' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'billing-mode' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'patient-discountcategory' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'autobilling' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'tax-group' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'cashier-package' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'bank-list' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'bill-status' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'user-share' )
                )

                    <li class="{{ Route::is('fiscal.setting') || Route::is('register-setting')
                    || Route::is('billing.mode') || Route::is('patient.discount.mode.form')
                    || Route::is('autobilling') || Route::is('billing.tax.group')
                    || Route::is('accountlist.cashier.package') || Route::is('billing.bank.group') || Route::is('usershare.index') || Route::is('bill.status.report') || Route::is('account.setting')   ? 'active main-active':'' }}">
                        <a href="#account" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="fa fa-cog" aria-hidden="true"></i><span>Account Settings</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="account" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'fiscal-year-setups' ) )
                                <li class="{{ Route::is('fiscal.setting')  ? 'active main-active':'' }}"><a
                                            href="{{ route('fiscal.setting') }}">Fiscal Year setups</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'billing-mode' ) )
                                <li class="{{ Route::is('billing.mode') ? 'active main-active':'' }}"><a
                                            href="{{ route('billing.mode') }}">Patient Type Setup</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'patient-discountcategory' ) )
                                <li class="{{ Route::is('patient.discount.mode.form')  ? 'active main-active':'' }}"><a
                                            href="{{ route('patient.discount.mode.form') }}">Patient Discount
                                        Category</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'register-settings' ) )
                                <li class="{{ Route::is('register-setting')  ? 'active main-active':'' }}"><a
                                            href="{{ route('register-setting') }}">Registration setup</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'tax-group' ) )
                                <li class="{{ Route::is('billing.tax.group') ? 'active main-active':'' }}">
                                    <a href="{{ route('billing.tax.group') }}"><span>Tax Group</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'cashier-package' ) )
                                <li class="{{ Route::is('accountlist.cashier.package') ? 'active main-active':'' }}">
                                    <a href="{{ route('accountlist.cashier.package') }}"><span>Service Package</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'autobilling' ) )
                                <li class="{{ Route::is('autobilling')  ? 'active main-active':'' }}"><a
                                            href="{{ route('autobilling') }}">Autobilling</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'bank-list' ) )
                                <li class="{{ Route::is('billing.bank.group') ? 'active main-active':'' }}">
                                    <a href="{{ route('billing.bank.group') }}"><span>Bank List Setup</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'bill-status' ) )
                                <li class="{{ Route::is('bill.status.report') ? 'active main-active':'' }}"><a
                                            href="{{ route('bill.status.report') }}">Bill Status</a></li>
                            @endif


                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'user-share' ) )
                                <li class="{{ Route::is('usershare.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('usershare.index') }}" class="iq-waves-effect"><span>User Share Setup</span></a>
                                </li>
                            @endif

                            <li class="{{ Route::is('account.setting') ? 'active main-active':'' }}">
                                <a href="{{ route('account.setting') }}"
                                   class="iq-waves-effect"><span>Account Setting</span></a>
                            </li>


                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'procedures' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'equipment' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'general-service' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'other-items' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-items' )
                )

                    <li class="{{ Route::is('account.laboratory.index')
                || Route::is('account.radiology.index')
                    || Route::is('account.procedure.index')
                    || Route::is('account.equipment.index')
                    || Route::is('account.generalService.index')
                    || Route::is('account.otheritem.index')
                    || Route::is('account.inventoryItem.index') ? 'active main-active':'' }}">
                        <a href="#itemmaster" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="fa fa-folder-open" aria-hidden="true"></i><span>Item Master</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="itemmaster" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory' ) )
                                <li class="{{ Route::is('account.laboratory.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('account.laboratory.index') }}">Laboratory</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology' ) )
                                <li class="{{ Route::is('account.radiology.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('account.radiology.index') }}">Radiology</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'procedures' ) )
                                <li class="{{ Route::is('account.procedure.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('account.procedure.index') }}">Procedures</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'equipment' ) )
                                <li class="{{ Route::is('account.equipment.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('account.equipment.index') }}">Equipments</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'general-service' ) )
                                <li class="{{ Route::is('account.generalService.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('account.generalService.index') }}">Gen Service</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'other-items' ) )
                                <li class="{{ Route::is('account.otheritem.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('account.otheritem.index') }}">Other Items</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-items' ) )
                                <li class="{{ Route::is('account.inventoryItem.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('account.inventoryItem.index') }}">Inventory Items</a></li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-sub-group' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-transaction' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-ledger' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-statement' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'trial-balance' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'day-book' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'profit-loss' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'balance-sheet' ))
                    <li class="{{ Route::is('subgroup') || Route::is('transaction')
                || Route::is('accounts.ledger.index') || Route::is('accounts.statement.index')
                || Route::is('accounts.trialbalance.index') || Route::is('accounts.daybook.index') || Route::is('get.group.map.index') || Route::is('accounts.profitloss.index') || Route::is('accounts.balancesheet.index') || Route::is('map.list.by.account') ? 'active main-active':'' }}">
                        <a href="#core_account" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-money-cny-box-line"></i><span>Accounts</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="core_account" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-sub-group' ) )
                                <li class="{{ Route::is('subgroup') ? 'active main-active':'' }}">
                                    <a href="{{ route('subgroup') }}">Account Group and Sub Group</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-transaction' ) )
                                <li class="{{ Route::is('transaction') ? 'active main-active':'' }}">
                                    <a href="{{ route('transaction') }}">Account Transaction</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-ledger' ) )
                                <li class="{{ Route::is('accounts.ledger.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('accounts.ledger.index') }}">Account Ledger</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'account-statement' ) )
                                <li class="{{ Route::is('accounts.statement.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('accounts.statement.index') }}">Account Statement</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'trial-balance' ) )
                                <li class="{{ Route::is('accounts.trialbalance.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('accounts.trialbalance.index') }}">Trial Balance</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'day-book' ) )
                                <li class="{{ Route::is('accounts.daybook.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('accounts.daybook.index') }}">Account Day Book</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'profit-loss' ) )
                                <li class="{{ Route::is('accounts.profitloss.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('accounts.profitloss.index') }}">Profit Loss</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'balance-sheet' ) )
                                <li class="{{ Route::is('accounts.balancesheet.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('accounts.balancesheet.index') }}">Balance Sheet</a>
                                </li>
                            @endif

                            <li class="{{ Route::is('get.group.map.index') ? 'active main-active':'' }}">
                                <a href="{{ route('get.group.map.index') }}">Map Account</a>
                            </li>

                            <li class="{{ Route::is('map.list.by.account') ? 'active main-active':'' }}">
                                <a href="{{ route('map.list.by.account') }}">Sync List By Account</a>
                            </li>
                        </ul>

                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'registration' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'registration-list' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'eappointment' )
                )
                    <li class="{{ Route::is('registrationform') || Route::is('registrationform.list') || Route::is('eappointment-list') ? 'active main-active':'' }}">
                        <a href="#reg" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-registered" aria-hidden="true"></i><span>Registration</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="reg" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'registration' ) )
                                <li class="{{ Route::is('registrationform') ? 'active main-active':'' }}">
                                    <a href="{{ route('registrationform') }}">Registration</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'eappointment' ) )
                                <li class="{{ Route::is('eappointment-list') ? 'active main-active':'' }}">
                                    <a href="{{ route('eappointment-list') }}">E-Appointment</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'registration-list' ) )
                                <li class="{{ Route::is('registrationform.list') ? 'active main-active':'' }}">
                                    <a href="{{ route('registrationform.list') }}">Registration List</a>
                                </li>
                            @endif


                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'cashier-form' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'return-form' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'deposit-form' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'discharge-clearance' )
                )
                    <li class="{{  Route::is('billing.display.form')
                    || Route::is('returnFormCashier')
                    || Route::is('billing.dischargeClearance')

                    || Route::is('depositForm')
                     ? 'active main-active':'' }}">
                        <a href="#billing" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-bill-line"></i><span>Billing</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="billing" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'cashier-form' ) )
                                <li class="{{ Route::is('billing.display.form') ? 'active main-active':'' }}">
                                    <a href="{{ route('billing.display.form') }}">Cashier Form</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'return-form' ) )
                                <li class="{{ Route::is('returnFormCashier') ? 'active main-active':'' }}">
                                    <a href="{{ route('returnFormCashier') }}"><span>Service Return</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'deposit-form' ) )
                                <li class="{{ Route::is('depositForm') ? 'active main-active':'' }}">
                                    <a href="{{ route('depositForm') }}"><span>Deposit Form</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'discharge-clearance' ) )
                                <li class="{{ Route::is('billing.dischargeClearance') ? 'active main-active':'' }}">
                                    <a href="{{ route('billing.dischargeClearance') }}">Discharge Clearance</a>
                                </li>
                        @endif


                        <!-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'extra-receipt' ) )

                            <li class="{{ Route::is('extra.receipt.index') ? 'active main-active':'' }}">
                            <a href="{{ route('extra.receipt.index') }}"><span>Extra Receipt</span></a>
                        </li>
                        @endif -->


                        <!-- <li class="{{ Route::is('account.list') ? 'active main-active':'' }}"><a href="{{ route('account.list') }}">Account Bill</a></li> -->


                        </ul>
                    </li>
                @endif
                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'generic-information' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'medicine-information' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'surgical-information' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'extra-items-information' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'labeling' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'medicine-grouping' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'out-of-order' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'pharmacy-item-activation' )
                )

                    <li class="{{ Route::is('medicines.generic.list')
                    || Route::is('medicines.medicineinfo.list')
                    || Route::is('surgical')
                     || Route::is('extra-item')
                      || Route::is('pharmacist.labelling.index')
                      || Route::is('pharmacist.protocols.index')
                       || Route::is('pharmacist.activation.index')
                       || Route::is('pharmacist.outoforder.index') ? 'active main-active':'' }}">
                        <a href="#Pharmacy-Master" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false"><i class="fa fa-medkit"></i><span>Pharmacy Master</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Pharmacy-Master" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'generic-information' ) )
                                <li class="{{ Route::is('medicines.generic.list') ? 'active main-active':'' }}">
                                    <a href="{{ route('medicines.generic.list') }}"> Generic Information</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'medicine-information' ) )

                                <li class="{{ Route::is('medicines.medicineinfo.list') ? 'active main-active':'' }}">
                                    <a href="{{ route('medicines.medicineinfo.list') }}"> Medicine Information</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'surgical-information' ) )

                                <li class="{{ Route::is('surgical') ? 'active main-active':'' }}">
                                    <a href="{{ route('surgical') }}"> Surgical Information</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'extra-items-information' ) )
                                <li class="{{ Route::is('extra-item') ? 'active main-active':'' }}">
                                    <a href="{{ route('extra-item') }}"> Extra Items Information</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'labeling' ) )
                                <li class="{{ Route::is('pharmacist.labelling.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('pharmacist.labelling.index') }}"> Labelling</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'medicine-grouping' ) )
                                <li class="{{ Route::is('pharmacist.protocols.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('pharmacist.protocols.index') }}"> Medicine Grouping</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'pharmacy-item-activation' ) )

                                <li class="{{ Route::is('pharmacist.activation.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('pharmacist.activation.index') }}"> Pharmacy Item activation</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'out-of-order' ) )

                                <li class="{{ Route::is('pharmacist.outoforder.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('pharmacist.outoforder.index') }}"> OUT of Order</a>
                                </li>
                            @endif

                        </ul>
                    </li>

                @endif
                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'supplier-information' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'demand-form' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-order' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-entry' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-transfer' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-return' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'storage-code' ))
                    <li class="{{ Route::is('supplier-info')
                    || Route::is('store.demandform')
                 || Route::is('billing.purchaseorder')
                || Route::is('purchaseentry')
                || Route::is('inventory.stock-transfer.index')
                 || Route::is('inventory.stock-return.index')
                 || Route::is('inventory.stock-consume.index')

                 || Route::is('store.storagecode')
                  ? 'active main-active':'' }}">
                        <a href="#Store-Inventory" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-store-3-fill"></i><span>Store/Inventory</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Store-Inventory" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'supplier-information' ) )
                                <li class="{{ Route::is('supplier-info') ? 'active main-active':'' }}">
                                    <a href="{{ route('supplier-info') }}"> Supplier Information</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'demand-form' ) )

                                <li class="{{ Route::is('store.demandform') ? 'active main-active':'' }}">
                                    <a href="{{ route('store.demandform') }}"> Demand Form</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-order' ) )

                                <li class="{{ Route::is('billing.purchaseorder') ? 'active main-active':'' }}">
                                    <a href="{{ route('billing.purchaseorder') }}"> Purchase Order</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-entry' ) )
                                <li class="{{ Route::is('purchaseentry') ? 'active main-active':'' }}">
                                    <a href="{{ route('purchaseentry') }}"> Purchase Entry</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-transfer' ) )
                                <li class="{{ Route::is('inventory.stock-transfer.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('inventory.stock-transfer.index') }}"> Stock Transfer</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-return' ) )
                                <li class="{{ Route::is('inventory.stock-return.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('inventory.stock-return.index') }}"> Stock Return</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )

                                <li class="{{ Route::is('inventory.stock-consume.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('inventory.stock-consume.index') }}"> Stock Consume</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'storage-code' ) )

                                <li class="{{ Route::is('store.storagecode') ? 'active main-active':'' }}">
                                    <a href="{{ route('store.storagecode') }}"> Storage Code</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif





                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'dispensing-form' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'return-form' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'dispensing-list' ))


                    <li class="{{ Route::is('dispensingForm') || Route::is('returnForm')
                    || Route::is('dispensingList')  ? 'active main-active':'' }}">
                        <a href="#pharmacy" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-first-aid-kit-line"></i><span>Pharmacy Billing</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="pharmacy" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'dispensing-form' ) )
                                <li class="{{ Route::is('dispensingForm') ? 'active main-active':'' }}">
                                    <a href="{{ route('dispensingForm') }}"><span>Dispensing Form</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'return-form' ) )
                                <li class="{{ Route::is('returnForm') ? 'active main-active':'' }}">
                                    <a href="{{ route('returnForm') }}"><span>Return Form</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'dispensing-list' ) )
                                <li class="{{ Route::is('dispensingList') ? 'active main-active':'' }}">
                                    <a href="{{ route('dispensingList') }}"><span>Dispensing List</span></a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory-grouping' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology-grouping' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-template' ))
                    <li class="{{ Route::is('technologist.index')
                    || Route::is('technologylab.grouping.display.form')
                    || Route::is('radiodiagnostic.list')
                    || Route::is('radiology.template.index')
                    || Route::is('radiology.grouping.display.grouping') ? 'active main-active':'' }}">
                        <a href="#diagnostic-master" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-dossier-fill"></i><span>Diagnostic Master</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="diagnostic-master" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory' ) )
                                <li class="{{ Route::is('technologist.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('technologist.index') }}"> Laboratory </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory-grouping' ) )
                                <li class="{{ Route::is('technologylab.grouping.display.form') ? 'active main-active':'' }}">
                                    <a href="{{ route('technologylab.grouping.display.form') }}"> Laboratory
                                        Grouping </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology' ) )
                                <li class="{{ Route::is('radiodiagnostic.list') ? 'active main-active':'' }}">
                                    <a href="{{ route('radiodiagnostic.list') }}"> Radiology </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology-grouping' ) )
                                <li class="{{ Route::is('radiology.grouping.display.grouping') ? 'active main-active':'' }}">
                                    <a href="{{ route('radiology.grouping.display.grouping') }}"> Radiology Grouping</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-template' ) )
                                <li class="{{ Route::is('radiology.template.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('radiology.template.index') }}"> Radio Template</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif





                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-sampling' ) || \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-reporting' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-verification' ) || \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-testmethod' )|| \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-verification' )|| \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-printing' ) || \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-printing' ))
                    <li class="{{  Route::is('laboratory.sampling.index')
                     || Route::is('laboratory.testmethod')
                     || Route::is('laboratory.bulk.verify')
                     || Route::is('laboratory.bulk.print')
                     || Route::is('laboratory.reporting.index')
                      || Route::is('laboratory.verify.index')
                      || Route::is('laboratory.printing.index') ? 'active main-active':'' }}">
                        <a href="#Laboratory" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="fa fa-flask"></i><span>Laboratory</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Laboratory" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-testmethod' ) )
                                <li class="{{ Route::is('laboratory.testmethod') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.testmethod') }}"> Test Method</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-sampling' ) )
                                <li class="{{ Route::is('laboratory.sampling.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.sampling.index') }}"> Test Sampling</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-reporting' ) )
                                <li class="{{ Route::is('laboratory.reporting.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.reporting.index') }}"> Test Reporting</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-verification' ) )
                                <li class="{{ Route::is('laboratory.verify.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.verify.index') }}"> Test Verification</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'test-printing' ) )
                                <li class="{{ Route::is('laboratory.printing.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.printing.index') }}"> Test Printing</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'bulk-verification' ) )
                                <li class="{{ Route::is('laboratory.bulk.verify') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.bulk.verify') }}" class="iq-waves-effect"><span>Bulk Verification</span></a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'bulk-printing' ) )
                                <li class="{{ Route::is('laboratory.bulk.print') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.bulk.print') }}" class="iq-waves-effect"><span>Bulk Printing</span></a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif


                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-reporting' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-verification' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-printing' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-appointment' ) )
                    <li class="{{ Route::is('radiology.verify')
                    || Route::is('radiology.setting')
                    || Route::is('xray')
                    || Route::is('radiology.appointment') ? 'active main-active':'' }}">
                        <a href="#Radiology" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-microscope-line"></i><span>Radiology</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Radiology" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-reporting' ) )
                                <li class="{{ Route::is('xray') ? 'active main-active':'' }}">
                                    <a href="{{ route('xray') }}"> Radio Reporting</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-verification' ) )
                                <li class="{{ Route::is('radiology.verify') ? 'active main-active':'' }}">
                                    <a href="{{ route('radiology.verify') }}"> Radio Verification</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-printing' ) )
                                <li class="{{ Route::is('radiology.setting') ? 'active main-active':'' }}">
                                    <a href="{{ route('radiology.setting') }}"> Radio Printing</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radio-appointment' ) )
                                <li class="{{ Route::is('radiology.appointment') ? 'active main-active':'' }}">
                                    <a href="{{ route('radiology.appointment') }}"> Radio Appointment</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'symptoms' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'syndromes' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'examination' )

                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'examination-grouping' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'procedure-grouping' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'selected-grouping' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'departmental-examination' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'computer-examination' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'triage-parameters' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'body-fluid' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'templates' ))
                    <li class="{{ Route::is('get.view.symptoms')
                || Route::is('get.view.syndromes')
                     || Route::is('examination.examinationlist')
                     || Route::is('consultant.group.exam.group')
                     || Route::is('display.procgroup.form.group.consultant')
                      || Route::is('consultant.group.selection.list')
                      || Route::is('display.deptexam.form.activity.consultant')
                       || Route::is('display.compexam.form.activity.consultant')
                       || Route::is('display.consultation.package.diet')
                       ||Route::is('consultant.triage.parameter')
                       || Route::is('variables.bodyfluid.index') || Route::is('template')  ? 'active main-active':'' }}">
                        <a href="#clinical-data-master" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-file-damage-line"></i><span>Clinical Data Master</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="clinical-data-master" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'symptoms' ) )
                                <li class="{{ Route::is('get.view.symptoms') ? 'active main-active':'' }}">
                                    <a href="{{ route('get.view.symptoms') }}">Symptoms </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'syndromes' ) )
                                <li class="{{ Route::is('get.view.syndromes') ? 'active main-active':'' }}">
                                    <a href="{{ route('get.view.syndromes') }}">Syndromes </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'examination' ) )
                                <li class="{{ Route::is('examination.examinationlist') ? 'active main-active':'' }}">
                                    <a href="{{ route('examination.examinationlist') }}">Examination </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'examination-grouping' ) )

                                <li class="{{ Route::is('consultant.group.exam.group') ? 'active main-active':'' }}">
                                    <a href="{{ route('consultant.group.exam.group') }}">Examination Grouping </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'procedure-grouping' ) )

                                <li class="{{ Route::is('display.procgroup.form.group.consultant') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.procgroup.form.group.consultant') }}">Procedure
                                        Grouping </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'selected-grouping' ) )

                                <li class="{{ Route::is('consultant.group.selection.list') ? 'active main-active':'' }}">
                                    <a href="{{ route('consultant.group.selection.list') }}">Selected Grouping </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'departmental-examination' ) )
                                <li class="{{ Route::is('display.deptexam.form.activity.consultant') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.deptexam.form.activity.consultant') }}">Departmental
                                        Examination </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'computer-examination' ) )

                                <li class="{{ Route::is('display.compexam.form.activity.consultant') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.compexam.form.activity.consultant') }}">Computer
                                        Examination </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'triage-parameters' ) )

                                <li class="{{ Route::is('consultant.triage.parameter') ? 'active main-active':'' }}">
                                    <a href="{{route('consultant.triage.parameter')}}">Traige Parameters </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'body-fluid' ) )
                                <li class="{{ Route::is('variables.bodyfluid.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('variables.bodyfluid.index') }}">Body fluid </a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'templates' ) )
                                <li class="{{ Route::is('template') ? 'active main-active':'' }}">
                                    <a href="{{ route('template') }}">Templates </a>
                                </li>
                            @endif


                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'emergency' ) )
                    <li class="{{ Route::is('emergency') ? 'active main-active':'' }}">
                        <a href="{{ route('emergency') }}" class="iq-waves-effect"><i
                                    class="ri-heart-pulse-fill"></i><span>Emergency</span></a>
                    </li>
                @endif

                {{--                    @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'gcs_form' ) )--}}
                {{--                        <li class="{{ Route::is('neuro') ? 'active main-active':'' }}">--}}
                {{--                            <a href="{{ route('neuro') }}" class="iq-waves-effect"><i--}}
                {{--                                    class="ri-heart-pulse-fill"></i><span>GCS Form</span></a>--}}
                {{--                        </li>--}}
                {{--                    @endif--}}


                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'icu' ) )
                    <li class="{{ Route::is('icu') ? 'active main-active':'' }}">
                        <a href="{{ route('icu') }}" class="iq-waves-effect"><i
                                    class="ri-heart-pulse-fill"></i><span>ICU</span></a>
                    </li>
                    <li class="{{ Route::is('icu-general') ? 'active main-active':'' }}">
                        <a href="{{ route('icu-general') }}" class="iq-waves-effect"><i
                                    class="ri-heart-pulse-fill"></i><span>ICU General</span></a>
                    </li>
                @endif



                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'outpatient-form' ) )
                    <li class="{{ Route::is('patient')  ? 'active main-active':'' }}">
                        <a href="#OPD" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="ri-health-book-line"></i><span>OPD</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="OPD" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                            <li class="{{ Route::is('patient') ? 'active main-active':'' }}">
                                <a href="{{ route('patient') }}">Outpatient Form</a>
                            </li>

                            <li class="{{ Route::is('physiotherapy') ? 'active main-active':'' }}">
                                <a href="{{ route('physiotherapy') }}">Physiotherapy</a>
                            </li>


                        </ul>
                    </li>
                @endif


                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'inpatient' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'delivery-form' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'bed-occupancy' ) )



                    <li class="{{ Route::is('inpatient') || Route::is('delivery') || Route::is('bedoccupancy') ? 'active main-active':'' }}">
                        <a href="#inpatient" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="fa fa-medkit" aria-hidden="true"></i><span>Inpatient</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="inpatient" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'inpatient' ) )
                                <li class="{{ Route::is('inpatient') ? 'active main-active':'' }}">
                                    <a href="{{ route('inpatient') }}">Inpatient </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'delivery-form' ) )
                                <li class="{{ Route::is('delivery') ? 'active main-active':'' }}">
                                    <a href="{{ route('delivery') }}">Delivery Form</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'bed-occupancy' ) )
                                <li class="{{ Route::is('bedoccupancy') ? 'active main-active':'' }}">
                                    <a href="{{ route('bedoccupancy') }}">Bed Occupancy</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif


                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'ot' )

                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'ot-report' ) )
                    <li class="{{ Route::is('majorprocedure') || Route::is('display.consultation.procedure.report')  || Route::is('ot.form.sign.in')  ? 'active main-active':'' }}">
                        <a href="#ormanagement" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-hospital-line"></i><span>OT Management</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="ormanagement" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'ot' ) )
                                <li class="{{ Route::is('majorprocedure') ? 'active main-active':'' }}">
                                    <a href="{{ route('majorprocedure') }}">OT</a>
                                </li>
                            @endif

                        <!-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'view-major-procedures' ) )
                            <li class="{{ Route::is('majorprocedure') ? 'active main-active':'' }}"><a href="{{ route('majorprocedure') }}">OT Plan</a></li>
                        @endif -->
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'ot-report' ) )
                                <li class="{{ Route::is('display.consultation.procedure.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.consultation.procedure.report') }}">OT Report</a>
                                </li>
                            @endif
                            <li class="{{ Route::is('ot.form.sign.in') ? 'active main-active':'' }}">
                                <a href="{{ route('ot.form.sign.in') }}">OT Sign in</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'discharge' ) )
                    <li class="{{ Route::is('discharge') ? 'active main-active':'' }}">
                        <a href="{{ route('discharge') }}" class="iq-waves-effect">
                            <i class="fa fa-wheelchair"></i>
                            <span>Discharge</span>
                        </a>
                    </li>
                @endif












                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'patient-profile' ) )
                    <li>
                        <a href="#Patient-Profile" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-profile-fill"></i><span>Patient Profile</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Patient-Profile" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                            <li><a href="javascript:void(0);" onclick="reportMainMenu.patientProfileModal()"> Patient
                                    Profile</a></li>
                        </ul>
                    </li>
                @endif


                @if (
                \App\Utils\Permission::checkPermissionFrontendAdmin( 'billing-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'user-collection' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'user-share' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'deposit-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'department' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'user-share-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'eappointment-log' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'remarks-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'item-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'entry-waiting-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'group-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'fiscal-year-bill' )
                )
                    <li class="{{  Route::is('billing.display.report')
                || Route::is('deposit.display.report')
                || Route::is('department.display.report')
                || Route::is('collection.display.report')

                || Route::is('pat-billing-share.index')
                || Route::is('eappointment-log')
                || Route::is('remarkreport')
                    || Route::is('item.display.report')
                    || Route::is('entry-waiting.display.report')
                    || Route::is('group.display.report')
                    | Route::is('fiscal.year.list')
                     ? 'active main-active':'' }}">
                        <a href="#Billing-Reports" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-file-copy-2-line"></i><span>Billing Reports</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Billing-Reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">


                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'billing-report' ) )
                                <li class="{{ Route::is('billing.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('billing.display.report') }}" class="iq-waves-effect"><span>Billing Report</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'deposit-report' ) )
                                <li class="{{ Route::is('deposit.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('deposit.display.report') }}" class="iq-waves-effect"><span>Deposit Report</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'department' ) )
                                <li class="{{ Route::is('department.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('department.display.report') }}" class="iq-waves-effect"><span>Department wise collection report</span></a>
                                </li>
                            @endif

                        <!-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'department' ) )
                            <li class="{{ Route::is('department.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('department.display.report') }}" class="iq-waves-effect"><span>Department wise summary report</span></a>
                                </li>
                            @endif -->

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'user-collection' ) )
                                <li class="{{ Route::is('collection.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('collection.display.report') }}" class="iq-waves-effect"><span>User Collection</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'user-share-report' ) )
                                <li class="{{ Route::is('pat-billing-share.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('pat-billing-share.index') }}" class="iq-waves-effect"><span>User Share Report</span></a>
                                </li>
                            @endif





                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'eappointment-log' ) )
                                <li class="{{ Route::is('eappointment-log') ? 'active main-active':'' }}">
                                    <a href="{{ route('eappointment-log') }}"> E-Appointment-log</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'remarks-report' ) )
                                <li class="{{ Route::is('remarkreport') ? 'active main-active':'' }}">
                                    <a href="{{ route('remarkreport') }}">Remarks Reports</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'item-report' ) )

                                <li class="{{ Route::is('item.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('item.display.report') }}" class="iq-waves-effect"><span>Item Report</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'entry-waiting-report' ) )

                                <li class="{{ Route::is('entry-waiting.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('entry-waiting.display.report') }}" class="iq-waves-effect"><span>Entry Waiting Report</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'group-report' ) )

                                <li class="{{ Route::is('group.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('group.display.report') }}" class="iq-waves-effect"><span>Group Report</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'fiscal-year-bill' ) )
                                <li class="{{ Route::is('fiscal.year.list') ? 'active main-active':'' }}">
                                    <a href="{{ route('fiscal.year.list') }}">Materializes view</a>
                                </li>
                            @endif


                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'demand-form' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-order' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-entry' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-return' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-transfer' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'item-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'medical-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'entry-waiting-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'group-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'under-stock' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'near-expiry-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'expiry-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-return-credit-note' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'order-vs-receive-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'demand-vs-order-vs-receive' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-db-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'item-ledger-report' )
                ||\App\Utils\Permission::checkPermissionFrontendAdmin( 'reorder-level-report' )
                )
                    <li class="{{ Route::is('demand.report') || Route::is('purchase.report')
                || Route::is('purchase.entry.report')
                || Route::is('stock.transfer.report')
                || Route::is('stock.consume.report')
                || Route::is('stock.return.report')
                || Route::is('under.stock.report')
                || Route::is('reports.nearexpiry')
                || Route::is('reports.expiry')
                || Route::is('purchase.return')
                || Route::is('order.vs.receive.report')
                || Route::is('demand-vs-order-vs-receive.report')
                || Route::is('inventory.display.report')
                || Route::is('store.inventorydb.index')
                || Route::is('item.ledger-report')
                || Route::is('reorder-level.display.report')
                 ? 'active main-active':'' }}">
                        <a href="#reports" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-file-list-line"></i><span>Store & Inventory Reports</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'demand-form' ) )
                                <li class="{{ Route::is('demand.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('demand.report') }}"> Demand Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-order' ) )
                                <li class="{{ Route::is('purchase.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('purchase.report') }}"> Purchase Order Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-entry' ) )
                                <li class="{{ Route::is('purchase.entry.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('purchase.entry.report') }}"> Purchase Entry Report</a>
                                </li>
                            @endif



                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-transfer' ) )
                                <li class="{{ Route::is('stock.transfer.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('stock.transfer.report') }}"> Stock Transfer Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )
                                <li class="{{ Route::is('stock.consume.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('stock.consume.report') }}"> Stock Consume Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-return' ) )
                                <li class="{{ Route::is('stock.return.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('stock.return.report') }}"> Stock Return Report</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'under-stock' ) )
                                <li class="{{ Route::is('under.stock.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('under.stock.report') }}"> Under Stock Report</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'near-expiry-report' ) )
                                <li class="{{ Route::is('reports.nearexpiry') ? 'active main-active':'' }}">
                                    <a href="{{ route('reports.nearexpiry') }}">Near Expiry Reports</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'expiry-report' ) )
                                <li class="{{ Route::is('reports.expiry') ? 'active main-active':'' }}">
                                    <a href="{{ route('reports.expiry') }}"> Expiry Reports</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'purchase-return-credit-note' ) )
                                <li class="{{ Route::is('purchase.return') ? 'active main-active':'' }}">
                                    <a href="{{ route('purchase.return') }}">Credit Note</a>
                                </li>
                            @endif


                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'order-vs-receive-report' ) )
                                <li class="{{ Route::is('order.vs.receive.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('order.vs.receive.report') }}"> Order VS Receive Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'demand-vs-order-vs-receive' ) )
                                <li class="{{ Route::is('demand-vs-order-vs-receive.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('demand-vs-order-vs-receive.report') }}"> Demand vs Order VS
                                        Purchase
                                        Report</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-report' ) )
                                <li class="{{ Route::is('inventory.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('inventory.display.report') }}"> Inventory Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-db-report' ) )
                                <li class="{{ Route::is('store.inventorydb.index') ? 'active main-active':'' }}"><a
                                            href="{{ route('store.inventorydb.index') }}"> Inventory DB Report</a></li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'item-ledger-report' ) )
                                <li class="{{ Route::is('item.ledger-report') ? 'active main-active':'' }}"><a
                                            href="{{ route('item.ledger-report') }}"> Item Ledger Report</a></li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'reorder-level-report' ) )

                                <li class="{{ Route::is('reorder-level.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('reorder-level.display.report') }}" class="iq-waves-effect"><span>Reorder Level Report</span></a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'pathology-count-report' ) )

                                <li class="{{ Route::is('pathology.count') ? 'active main-active':'' }}">
                                    <a href="{{ route('pathology.count') }}" class="iq-waves-effect"><span>Pathology Count Report</span></a>
                                </li>
                        @endif


                        <!-- {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}


                                <li>
                                    <a href="{{ route('report.subgroup') }}" class="iq-waves-effect"><span>Account SubGroup</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.subhead') }}" class="iq-waves-effect"><span>Account Subhead</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.transaction') }}" class="iq-waves-effect"><span>Account Transaction</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.ledger') }}" class="iq-waves-effect"><span>Account Ledger</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.statement') }}" class="iq-waves-effect"><span>Account Statement</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.daybook') }}" class="iq-waves-effect"><span>Account Day Book</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.profitloss') }}" class="iq-waves-effect"><span>Account Profit Loss</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.trailbalance') }}" class="iq-waves-effect"><span>Account Trail Balance</span></a>
                        </li>
                        {{-- @endif--}}
                        {{-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'stock-consume' ) )--}}
                                <li>
                                    <a href="{{ route('report.balancesheet') }}" class="iq-waves-effect"><span>Balance sheet</span></a>
                        </li>
                        {{-- @endif--}} -->

                        </ul>
                    </li>
                @endif








            <!--
                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'consultation' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'procedure' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'equipment' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'confinements' ))
                <li class="{{ Route::is('consultation') || Route::is('display.consultation.procedure.report') || Route::is('display.consultation.equipment.report') || Route::is('display.consultation.confinement.report') ? 'active main-active':'' }}">
                    <a href="#Service-Reports" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false">
                        <i class="ri-file-damage-line"></i><span>Service Reports</span><i class="ri-arrow-right-s-line iq-arrow-right"></i>
                    </a>
                    <ul id="Service-Reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                    </ul>
                </li>
                @endif -->


            <!-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'expiry-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'under-stock' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'inventory-db-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'item-ledger-report' )
                )
                <li class="{{ Route::is('store.inventorydb.index') ? 'active main-active':'' }}">
                    <a href="#Inventory-Reports" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false">
                        <i class="ri-profile-line"></i><span>Inventory Reports</span><i class="ri-arrow-right-s-line iq-arrow-right"></i>
                    </a>
                    <ul id="Inventory-Reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'expiry-report' ) )
                    <li><a href="javascript:void(0)" onclick="inventoryMainMenu.chooseExpiryDate()"> Expiry
                            Report</a></li>
@endif

                    @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'under-stock' ) )
                    <li><a href="javascript:void(0)" onclick="underStockReport()"> Under Stock</a></li>
@endif


                        </ul>
                    </li>
@endif -->


                <!-- Demand form Report Menu Added by ANish-->


                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'visit-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'inpatient-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'transition-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'examination-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'consultation' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'procedure' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'equipment' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'confinements' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'medical-report' )
                )
                    <li class="{{ Route::is('display.consultation.view.report')
                || Route::is('display.consultation.ip.events')
                || Route::is('display.consultation.transition')
                || Route::is('consultant.diagnostic.examination.form')
                || Route::is('consultation')
                || Route::is('display.consultation.procedure.report')
                || Route::is('display.consultation.equipment.report')
|| Route::is('display.consultation.confinement.report')
|| Route::is('medical.display.report') ? 'active main-active':'' }}">
                        <a href="#Patient-Reports" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-pages-line"></i><span>Patient Reports</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Patient-Reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'visit-report' ) )
                                <li class="{{ Route::is('display.consultation.view.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.consultation.view.report') }}"> Visit Report </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'inpatient-report' ) )
                                <li class="{{ Route::is('display.consultation.ip.events') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.consultation.ip.events') }}"> Inpatient Report </a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'transition-report' ) )
                                <li class="{{ Route::is('display.consultation.transition') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.consultation.transition') }}"> Transition Report </a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'examination-report' ) )
                                <li class="{{ Route::is('consultant.diagnostic.examination.form') ? 'active main-active':'' }}">
                                    <a href="{{ route('consultant.diagnostic.examination.form') }}"> Examination
                                        Report </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'consultation' ) )
                                <li class="{{ Route::is('consultation') ? 'active main-active':'' }}">
                                    <a href="{{ route('consultation') }}"> Consultation Report</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'procedure' ) )
                                <li class="{{ Route::is('display.consultation.procedure.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.consultation.procedure.report') }}"> Procedure Report</a>

                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'equipment' ) )
                                <li class="{{ Route::is('display.consultation.equipment.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.consultation.equipment.report') }}"> Equipments
                                        Report</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'confinements' ) )
                                <li class="{{ Route::is('display.consultation.confinement.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('display.consultation.confinement.report') }}"> Confinements
                                        Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'medical-report' ) )

                                <li class="{{ Route::is('medical.display.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('medical.display.report') }}" class="iq-waves-effect"><span>Diagnosis Report</span></a>
                                </li>
                            @endif


                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'tat-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'sample-tracking' ))
                    <li class="{{ Route::is('consultant.diagnostic.laboratory.form')
                    || Route::is('consultant.diagnostic.radiology.form')

                    || Route::is('laboratory.tat.index')
                    || Route::is('laboratory.tracking.index')  ? 'active main-active':'' }}">
                        <a href="#Diagnostic-Reports" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="ri-folder-chart-line"></i><span>Diagnostic Reports</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="Diagnostic-Reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'laboratory' ) )
                                <li class="{{ Route::is('consultant.diagnostic.laboratory.form') ? 'active main-active':'' }}">
                                    <a href="{{ route('consultant.diagnostic.laboratory.form') }}"> Laboratory
                                        Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology' ) )
                                <li class="{{ Route::is('consultant.diagnostic.radiology.form') ? 'active main-active':'' }}">
                                    <a href="{{ route('consultant.diagnostic.radiology.form') }}"> Radiology Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'tat-report' ) )

                                <li class="{{ Route::is('laboratory.tat.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.tat.index') }}"> TAT Report </a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'sample-tracking' ) )
                                <li class="{{ Route::is('laboratory.tracking.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('laboratory.tracking.index') }}"> Sample Tracking</a>
                                </li>
                        @endif
                        <!-- @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'lab-category-wise-report' ) )
                            <li>
                                <a href="{{ route('report.lab-category-wise') }}" class="iq-waves-effect"><span>Lab Category Wise Report</span></a>
                        </li>
                        @endif


                                <li class="{{ Route::is('laboratory.tracking.index') ? 'active main-active':'' }}">
                            <a href="{{ route('laboratory.tracking.index') }}"> Patient Account</a>
                        </li> -->


                        </ul>
                    </li>

                @endif

                @if( \App\Utils\Permission::checkPermissionFrontendAdmin( 'ot-plan-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology-plan-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'extraprocedure-plan-report' )

                )
                    <li class="{{ Route::is('planreport.majorprocedure')
                    || Route::is('planreport.radiologylist')
                    || Route::is('planreport.extraprocedure')
                     ? 'active main-active':'' }}">
                        <a href="#plan-report" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="fa fa-hospital" aria-hidden="true"></i><span>Plan Report</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="plan-report" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'ot-plan-report' ) )
                                <li class="{{ Route::is('planreport.majorprocedure') ? 'active main-active':'' }}">
                                    <a href="{{ route('planreport.majorprocedure') }}" class="iq-waves-effect"><i
                                                class="fa fa-file-pdf"></i><span>OT Plan Report</span></a>
                                </li>
                            @endif



                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'radiology-plan-report' ) )
                                <li class="{{ Route::is('planreport.radiologylist') ? 'active main-active':'' }}">
                                    <a href="{{ route('planreport.radiologylist') }}" class="iq-waves-effect"><i
                                                class="ri-file-list-2-line"></i><span>Radiology Plan Report</span></a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'extraprocedure-plan-report' ) )

                                <li class="{{ Route::is('planreport.extraprocedure') ? 'active main-active':'' }}">
                                    <a href="{{ route('planreport.extraprocedure') }}" class="iq-waves-effect"><i
                                                class="ri-file-history-line"></i><span>Extraprocedure Plan report</span></a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif



            <!-- HMIS Menu Added by ANish-->
                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'generate-report' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'mapping-setting' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'mapping-report' ))
                    <li class="{{ Route::is('hmisreport.index')
                    || Route::is('mapping')
                    || Route::is('mapping.report')
                     ? 'active main-active':'' }}">
                        <a href="#hmis-mapping" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="fa fa-hospital" aria-hidden="true"></i><span>HMIS 9.4</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="hmis-mapping" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'generate-report' ) )
                                <li class="{{ Route::is('hmisreport.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('hmisreport.index') }}"> Generate Report</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'mapping-setting' ) )
                                <li class="{{ Route::is('mapping') ? 'active main-active':'' }}">
                                    <a href="{{ route('mapping') }}"> Mapping Setting</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'mapping-report' ) )
                                <li class="{{ Route::is('mapping.report') ? 'active main-active':'' }}">
                                    <a href="{{ route('mapping.report') }}"> Mapping Report</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'question-master-view' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'bag-master-view' )
                || \App\Utils\Permission::checkPermissionFrontendAdmin( 'donor-master-view' ))
                    <li class="{{ Route::is('bloodbank.question-master.index')
                    || Route::is('bloodbank.bag-master.index')
                    || Route::is('bloodbank.donor-master.index')
                     ? 'active main-active':'' }}">
                        <a href="#bloodbank-master" class="iq-waves-effect collapsed" data-toggle="collapse"
                           aria-expanded="false">
                            <i class="fa fa-hospital" aria-hidden="true"></i>
                            <span>Blood Bank Master</span>
                            <i class="ri-arrow-right-s-line iq-arrow-right"></i>
                        </a>
                        <ul id="bloodbank-master" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'question-master-view' ) )
                                <li class="{{ Route::is('bloodbank.question-master.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('bloodbank.question-master.index') }}"> Question Master</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'bag-master-view' ) )
                                <li class="{{ Route::is('bloodbank.bag-master.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('bloodbank.bag-master.index') }}"> Bag Master</a>
                                </li>
                            @endif
                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'donor-master-view' ) )
                                <li class="{{ Route::is('bloodbank.donor-master.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('bloodbank.donor-master.index') }}"> Donor Master</a>
                                </li>
                            @endif

                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'consent-form-view' ) )
                                <li class="{{ Route::is('bloodbank.consent-form.index') ? 'active main-active':'' }}">
                                    <a href="{{ route('bloodbank.consent-form.index') }}"> Consent Form</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                <li class="{{ Route::is('template') ? 'active main-active':'' }}">
                    <a href="{{ route('template') }}" class="iq-waves-effect"><i class="ri-device-line"></i><span>Templates</span></a>
                </li>
                <li>
                    <a href="{{ route('report.idcard') }}" class="iq-waves-effect"><span>idcard</span></a>
                </li>
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>
