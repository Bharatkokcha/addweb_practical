<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Students') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('add-student') }}" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div>
                        <x-jet-label for="name" value="{{ __('Name') }}" />
                        <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name"  required autofocus />
                        <div class="text-danger" style="color: red !important">{{ $errors->first("name") }}</div>
                    </div>

                    <div class="mt-4">
                        <x-jet-label for="grade" value="{{ __('Grade') }}" />
                        <x-jet-input id="grade" class="block mt-1 w-full" type="text" name="grade"  required />
                        <div class="text-danger" style="color: red !important">{{ $errors->first("grade") }}</div>
                    </div>

                    <div class="mt-4">
                        <x-jet-label for="photo" value="{{ __('Photo') }}" />
                        <x-jet-input id="photo" class="block mt-1 w-full" type="file" name="photo" required/>
                        <div class="text-danger" style="color: red !important">{{ $errors->first("photo") }}</div>
                    </div>

                    <div class="mt-4">
                        <x-jet-label for="date_of_birth" value="{{ __('Date of Birth') }}" />
                        <x-jet-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" required/>
                        <div class="text-danger" style="color: red !important">{{ $errors->first("date_of_birth") }}</div>
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="address" value="{{ __('Address') }}" />
                        <textarea class="block mt-1 w-full" name="address"></textarea>
                        <div class="text-danger" style="color: red !important">{{ $errors->first("address") }}</div>
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="country_id" value="{{ __('Country') }}" />
                        <select class="block mt-1 w-full" name="country_id" id="country_id">
                            <option value="">Select Country</option>
                            @foreach($getAllCountries as $key => $country) 
                                <option value="{{$key}}">{{$country}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" style="color: red !important">{{ $errors->first("country_id") }}</div>
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="country_id" value="{{ __('State') }}" />
                        <select class="block mt-1 w-full" name="state_id" id="state_id">
                            <option></option>
                        </select>
                        <div class="text-danger" style="color: red !important">{{ $errors->first("state_id") }}</div>
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="country_id" value="{{ __('City') }}" />
                        <select class="block mt-1 w-full" name="city_id" id="city_id">
                            <option></option>
                        </select>
                        <div class="text-danger" style="color: red !important">{{ $errors->first("city_id") }}</div>
                    </div>

                                        
                    <div class="flex items-center justify-end mt-4">
                        <x-jet-button class="ml-4">
                            {{ __('Add') }}
                        </x-jet-button>
                    </div>
                </form>
        
            </div>
        </div>
    </div>
    <div class="py-12 row">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="table table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Date of Birth</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</x-app-layout>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>



<script type="text/javascript">
    $(document).ready( function () {
        // Use datatable with Ajax
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('student-records') !!}',
            columns: [
                { data: 'name', name: 'name',searchable: true },
                { data: 'grade', name: 'grade',searchable: true  },
                { data: 'date_of_birth', name: 'date_of_birth',searchable: true },
                { data: 'country', name: 'country',searchable: false  },
                { data: 'state', name: 'state',searchable: false  },
                { data: 'city', name: 'city',searchable: false  }

            ]
        });

        // Add on change event for country selection and fetch state country wise
        $(document).on('change','#country_id',function() {
          $.ajax({
                  url: "{{ url('/fetch-states') }}",
                  method: 'post',
                  data: { 
                    "_token": "{{ csrf_token() }}",
                    'country_id' : $(this).val()
                  },
                  success: function(result){
                    $('#state_id').html('');
                    $('#state_id').html(result);
                    $('#city_id').html(''); 
                  }
              });
               
        })

        // Add on change event for state selection and fetch city state wise
        $(document).on('change','#state_id',function() {
          $.ajax({
                  url: "{{ url('/fetch-cities') }}",
                  method: 'post',
                  data: { 
                    "_token": "{{ csrf_token() }}",
                    'state_id' : $(this).val()
                  },
                  success: function(result){
                    $('#city_id').html('');
                    $('#city_id').html(result);
                  }
              });
               
        })

    } );
</script>