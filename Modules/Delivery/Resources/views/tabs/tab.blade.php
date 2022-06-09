<div class="iq-card-body">
    <ul class="nav nav-tabs delivery_tab" id="delivery_tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="newdelivery-tab" data-toggle="tab" href="#newdelivery" role="tab" aria-controls="newdelivery" aria-selected="true">New Delivery</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="predelivery-tab" data-flddept="Pre" data-toggle="tab" href="#predelivery" role="tab" aria-controls="predelivery" aria-selected="false">Pre- Delivery</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="ondelivery-tab" data-flddept="On" data-toggle="tab" href="#predelivery" role="tab" aria-controls="ondelivery" aria-selected="false">On Delivery</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="postdelivery-tab" data-flddept="Post" data-toggle="tab" href="#predelivery" role="tab" aria-controls="postdelivery" aria-selected="false">Post Delivery</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled" id="newborn-tab" data-toggle="tab" href="#newborn" role="tab" aria-controls="newborn" aria-selected="false">New Born</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pharmacydelivery-tab" data-toggle="tab" href="#pharmacydelivery" role="tab" aria-controls="pharmacydelivery" aria-selected="false">Pharmacy</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent-2">
        @include('delivery::tabs.newdelivery')
        @include('delivery::tabs.predelivery')
        @include('delivery::tabs.newborn')
        @include('delivery::tabs.pharmacydelivery')
    </div>
</div>
<div id="js-deliveryexamination-content-modal" class="modal"></div>
