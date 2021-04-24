<option value="">Select State</option>
@if(!empty($statesData))
	@foreach($statesData as $key => $state)
		<option value="{{$key}}">{{$state}}</option>
	@endforeach
@endif