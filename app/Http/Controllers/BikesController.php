<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BikeRequest;

class BikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$bikes = Bike::paginate();
		return view('bikes.index', compact('bikes'));
	}

    public function show(Bike $bike)
    {
        return view('bikes.show', compact('bike'));
    }

	public function create(Bike $bike)
	{
		return view('bikes.create_and_edit', compact('bike'));
	}

	public function store(BikeRequest $request)
	{
		$bike = Bike::create($request->all());
		return redirect()->route('bikes.show', $bike->id)->with('message', 'Created successfully.');
	}

	public function edit(Bike $bike)
	{
        $this->authorize('update', $bike);
		return view('bikes.create_and_edit', compact('bike'));
	}

	public function update(BikeRequest $request, Bike $bike)
	{
		$this->authorize('update', $bike);
		$bike->update($request->all());

		return redirect()->route('bikes.show', $bike->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Bike $bike)
	{
		$this->authorize('destroy', $bike);
		$bike->delete();

		return redirect()->route('bikes.index')->with('message', 'Deleted successfully.');
	}
}