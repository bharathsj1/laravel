<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuType;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $upload_path = 'uploadedImages/menu/';


        if ($request->has('menu_image')) {
            $file_name = $request->photo->getClientOriginalName();
            $generated_new_name = time() . '.' . $file_name;
            $request->photo->move($upload_path, $generated_new_name);
            $menu = Menu::create([
                'menu_price' => $request['menu_price'],
                'menu_name' => $request['menu_name'],
                'menu_details' => $request['menu_details'],
                'menu_quantity' => $request['menu_quantity'],
                'rest_id' => $request['rest_id'],
                'menu_type_id' => $request['menu_type_id'],
                'menu_image' => $upload_path . $generated_new_name,

            ]);
        } else {
            $menu = Menu::create([
                'menu_price' => $request['menu_price'],
                'menu_name' => $request['menu_name'],
                'menu_details' => $request['menu_details'],
                'menu_quantity' => $request['menu_quantity'],
                'rest_id' => $request['rest_id'],
                'menu_type_id' => $request['menu_type_id'],

            ]);
        }

        if ($menu) {
            return response()->json([
                'success' => true,
                'data' => $menu,
                'message' => 'Menu created successfully',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Failed',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }

    public function addMenuTypes(Request $request)
    {
        $upload_path = 'uploadedImages/menu_types/';

        if ($request->has('menu_type_image')) {
            $file_name = $request->photo->getClientOriginalName();
            $generated_new_name = time() . '.' . $file_name;
            $request->photo->move($upload_path, $generated_new_name);
            $menuTypes =    MenuType::create([
                'menu_name' => $request['name'],
                'menu_type_image' => $upload_path . $generated_new_name,

            ]);
        } else {
            $menuTypes =    MenuType::create([
                'menu_name' => $request['name'],
            ]);
        }

        if ($menuTypes) {
            return response()->json([
                'success' => true,
                'data' => $menuTypes,
                'message' => 'Menu Type added'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed'
            ]);
        }
    }

    public function getMenuTypes()
    {
        $menuTypes = MenuType::all();
        if ($menuTypes) {
            return response()->json([
                'success' => true,
                'data' => $menuTypes,
                'message' => 'Menu Type added'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed'
            ]);
        }
    }

    public function getMenus($id)
    {
        $menus = Menu::where('rest_id', $id)->with('foodCategory')->get();
        if ($menus) {
            return response()->json([
                'success' => true,
                'data' => $menus,
                'message' => 'Menu List'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed'
            ]);
        }
    }

    public function getMenuItemsWithRestaurants()
    {
        $menu =   Menu::with('restaurant')->get();
        return response()->json([
            'success' => true,
            'data' => $menu,
            'message' => 'Menu Items with Restaurants',
        ]);
    }
}
