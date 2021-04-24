<option value="">Select City</option>
@if(!empty($citiesData))
	@foreach($citiesData as $key => $city)
		<option value="{{$key}}">{{$city}}</option>
	@endforeach
@endif