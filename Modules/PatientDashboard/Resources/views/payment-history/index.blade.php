@extends('patient.layouts.master')

@section('content')

<div class="main-content">
    <div class="main-container">
        <div class="payment-history">
            <div class="card">
                <h5 class="card-header">Payment History</h5>
                <div class="card-body">
                    <div class="payment-history-block">
                        <div class="payment-history-table">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Transition ID</th>
                                        <th>Date</th>
                                        <th>Payment Method</th>
                                        <th>Payment Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="trans-id">1245246</td>
                                        <td>22/12/2020 9:24</td>
                                        <td>eSewa</td>
                                        <td class="payment-amount">Rs. 4512.11</td>
                                    </tr>
                                    <tr>
                                        <td class="trans-id">451402</td>
                                        <td>20/12/2020 13:44</td>
                                        <td>fonepay</td>
                                        <td class="payment-amount">Rs. 2145.11</td>
                                    </tr>
                                    <tr>
                                        <td class="trans-id">451402</td>
                                        <td>20/12/2020 13:44</td>
                                        <td>Cash</td>
                                        <td class="payment-amount">Rs. 2145.11</td>
                                    </tr>
                                    <tr>
                                        <td class="trans-id">451402</td>
                                        <td>20/12/2020 13:44</td>
                                        <td>Card</td>
                                        <td class="payment-amount">Rs. 2145.11</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection