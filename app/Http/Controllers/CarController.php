<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    private $car;

    /**
     * CarController constructor.
     *
     * @param Car $car
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Display a listing of the cars.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cars = $this->car->with('user')->orderBy('id', 'desc')->paginate(10);

        return view('cars.dashboard', compact('cars'));
    }

    /**
     * Display the form for creating a new car.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created car in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\redirectResponse
     */
    public function store(Request $request)
    {
        //Validate post request 
        $this->validate($request, [
            'make' => 'required',
            'model' => 'required',
            'year' => 'required|integer',
        ]);

        // Store car in database
        $Cars = new Car([
            'make' => $request->input('make'),
            'model' => $request->input('model'),
            'year' => $request->input('year'),
        ]);

        // Assign the user_id based on the currently authenticated user
        $Cars->user_id = auth()->user()->id;

        $Cars->save();

        return redirect()->route('admin.car.index')->with('success', 'Car created successfully');
    }

    /**
     * Display the form for editing the specified car.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $car = $this->car->find($id);

        return view('cars.edit', compact('car'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        $car = $this->car->find($id);
        $car->make = $request->make;
        $car->model = $request->model;
        $car->year = $request->year;
        $car->save();

        return redirect()->route('admin.car.index')->with('success', 'Car was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\redirectResponse
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);
        $car->delete();

        return redirect()->route('admin.car.index')->with('success', 'Car was deleted successfully');
    }

    /**
     * Validates the given request data for creating a new car.
     *
     * @param Request $request The request data to validate.
     * @return \Illuminate\Http\RedirectResponse|null Returns a redirect response with errors and input if validation fails, otherwise null.
     */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
