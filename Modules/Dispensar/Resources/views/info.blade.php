@if($type == 'Pricing' || $type == 'Current')
	<ul style="list-style-type:none;">
		<li><strong>Batch: </strong> {{ ($data) ? $data->fldbatch : '' }} </li>
		<li><strong>Expiry: </strong> {{ ($data) ? $data->fldexpiry : '' }} </li>
		<li><strong>Rate: </strong> {{ ($data) ? $data->fldsellpr : '' }} </li>
		<li><strong>Quantity: </strong> {{ ($data) ? $data->fldqty : '' }} </li>
	</ul>
@elseif($type == 'Inventory')
	<div class="table-responsive table-dispensing">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Particular</th>
					<th>Batch</th>
					<th>Expiry</th>
					<th>QTY</th>
					<th>Sell Price</th>
					<th>Order</th>
					<th>Comp</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $d)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $d->fldstockid }}</td>
					<td>{{ $d->fldbatch }}</td>
					<td>{{ $d->fldexpiry }}</td>
					<td>{{ $d->fldqty }}</td>
					<td>{{ $d->fldsellpr }}</td>
					<td>{{ $d->fldstatus }}</td>
					<td>{{ $d->fldcomp }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@elseif($type == 'Alternate')
	<div class="table-responsive table-dispensing">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Medicine</th>
					<th>Brand</th>
					<th>Location</th>
					<th>QTY</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $d)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $d->fldstockid }}</td>
					<td>{{ $d->medbrand->fldbrand }}</td>
					<td>{{ $d->fldcomp }}</td>
					<td>{{ $d->fldqty }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif