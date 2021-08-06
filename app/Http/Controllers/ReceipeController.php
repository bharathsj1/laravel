<?php

namespace App\Http\Controllers;

use App\Models\Ingridients;
use App\Models\Nutrition;
use App\Models\NutritionDefault;
use App\Models\Receipe;
use App\Models\ReciepeIngridient;
use App\Models\Steps;
use App\Models\UtensilReciepe;
use App\Models\Utensils;
use Illuminate\Http\Request;

class ReceipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receipe = Receipe::all();
        return response()->json([
            'success' => true,
            'data' => $receipe,
            'message' => 'All Receipes',
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipe  $receipe
     * @return \Illuminate\Http\Response
     */
    public function show(Receipe $receipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipe  $receipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipe $receipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipe  $receipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipe $receipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receipe  $receipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipe $receipe)
    {
        //
    }

    public function getReceipeById($id)
    {
        $receipe = Receipe::find($id);
        if (!$receipe) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Could not found any receipe',
            ]);
        }
        $array = array();
        $ingridients = ReciepeIngridient::where('receipe_id', $receipe->id)->with('ingridient')->get();
        $utensils = UtensilReciepe::where('receipe_id', $receipe->id)->with('utensil')->get();
        $recipeUtensils = array();
        foreach ($utensils as $key => $value) {
            $recipeUtensils[] = $value->utensil->name;
        }

        $perServiingnutrition = NutritionDefault::where('receipe_id', $receipe->id)->where('per_serving', 1)->get();
        $notPerServiingnutrition = NutritionDefault::where('receipe_id', $receipe->id)->where('per_serving', 0)->get();

        $steps = array();
        $allSteps = Steps::where('receipe_id', $receipe->id)->get();
        foreach ($allSteps as $key => $value) {

            $ingridientArray = (explode(",", $value->ingridient)); // converting ingridient ids into array
            $utensilsArray = explode(',', $value->utensil);  // converting utensils ids into array

            $filterIngridient = array();
            foreach ($ingridientArray as $key => $ingridientValue) {
                $filterIngridient[] = ReciepeIngridient::where('ingridient_id',intval($ingridientValue))->with('ingridient')->first();
            }
            $filterUtensils = array();
            foreach ($utensilsArray as $key => $utensilValue) {
                $filterUtensils[] = Utensils::find(intval($utensilValue));
            }

            $steps[] = [
                'step_no' => $value->step_no,
                'description' => $value->description,
                'image' => $value->image,
                'ingridients' => $filterIngridient,
                'utensils' => $filterUtensils,

            ];
        };



        $nutrition[] = [
            'perServing' => $perServiingnutrition,
            'notPerServing' => $notPerServiingnutrition,

        ];

        $array = [
            'reciepe' => $receipe,
            'utensils' => $recipeUtensils,
            'nutrition' => $nutrition,
            'steps' => $steps,
            'data' => $ingridients,

        ];
        return response()->json([
            'success' => true,
            'data' => $array,
            'message' => 'Your Data'
        ]);
    }
}
