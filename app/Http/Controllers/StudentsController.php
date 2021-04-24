<?php

namespace App\Http\Controllers;
use DataTables;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\State;
use App\Models\City;
use App\Models\Country;
Use Validator;
use Log;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $getAllCountries = Student::from('countries')->get()->pluck('name','id');
            return view('students.index', compact('getAllCountries'));
        } catch (Exception $exc) {
            abort(404);
        }
    }

    /**
    *   Create function of yajra datatable
    */

    public function studentRecords()
    {
        $model = Student::with('country','state','city');
        return DataTables::eloquent($model)
                ->addColumn('country', function (Student $student) {
                    return $student->country->name;
                })
                ->addColumn('state', function (Student $student) {
                    return $student->state->name;
                })
                ->addColumn('city', function (Student $student) {
                    return $student->city->name;
                })
                ->order(function ($query) {
                    if (request()->has('name')) {
                        $query->orderBy('name', 'asc');
                    }

                    if (request()->has('grade')) {
                        $query->orderBy('grade', 'desc');
                    }

                    if (request()->has('date_of_birth')) {
                        $query->orderBy('date_of_birth', 'desc');
                    }
                })
                ->toJson();

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'grade' => 'required|numeric|between:0,99.99',
                'address' => 'required',
                'country_id' => 'required',
                'city_id' => 'required',
                'date_of_birth' => 'required|date',
                'photo' => 'required|image'   
            ]);

            if($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
            }



            $photo = ''; 
            if ($request->hasFile('photo')) {
                $filenameWithExt = $request->file('photo')->getClientOriginalName();
                //Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $request->file('photo')->getClientOriginalExtension();
                
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload Image
                $path = $request->file('photo')->storeAs('/',$fileNameToStore);
                $photo = $fileNameToStore ;
            }
            $postFields = [
                'name' => $request->name,
                'grade' => $request->grade,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'photo' => $photo,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id
            ];

            // Add new student information log 
            Log::info('Add new student', $postFields);


            // Save all detail in student table
            Student::create($postFields);

            return redirect()->back();    
        
        } catch (Exception $exc) {
            abort(404);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
    *   Fetch data for state
    */
    public function fetchStateData(Request $request) {
        try {
            $statesData = State::where([['country_id',$request->country_id]])->get()->pluck('name','id');
            return view('students.statesFilter', compact('statesData'));
        } catch (Exception $e) {
            abort(404);
        }
    }

    /**
    *   Fetch data for city
    */
    public function fetchCityData(Request $request) {
        try {
            $citiesData = City::where([['state_id',$request->state_id]])->get()->pluck('name','id');
            return view('students.citiesFilter', compact('citiesData'));
        } catch (Exception $e) {
            abort(404);
        }
    }


}
