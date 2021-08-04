<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
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

    public function getMenuWithMenuTypeId($id)
    {
        $menus = Menu::where('menu_type_id', $id)->with('restaurant')->orderBy('order_count','DESC')->get();
        if ($menus) {
            return response()->json([
                'success' => true,
                'data' => $menus,
                'message' => 'Menu Items with Menu Types'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Not Found'
            ]);
        }
    }

    public function menuItemsWithTypesForSpecificRes($id)
    {
        $restaurantMenus = Menu::where('rest_id', $id)->get();
        $restaurantFoodCategory = array();
        $foodCategoryIds = array();

        foreach ($restaurantMenus as $key => $value) {
            $foodCategoryIds[] = $value->food_category_id;
        }
        $foodCategoryIds = array_unique($foodCategoryIds);
        //    $menuItemsArray = Menu::whereIn('food_category_id',$foodCategoryId)->get();

        foreach ($foodCategoryIds as $key => $foodCategoryId) {
            $foodCategoryData = FoodCategory::find($foodCategoryId);
            $menuItems = Menu::where('food_category_id', $foodCategoryId)->get();

            $rr[] = [
                'foodCategory' => $foodCategoryData,
                'menuItems' => $menuItems,
            ];
        }
        if (count($rr) > 0) {
            return response()->json([
                'success' => true,
                'data' => $rr,
                'message' => 'Menu Items With Menu Types',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'No Data Found',
            ]);
        }









        //     $menuTypeArray = array();
        //     //all Items for specific Restaurent
        //     $allMenusForSpecificRes = Menu::where('rest_id', $id)->with('menuType')->get();
        //     $allMenuTypes = MenuType::all();
        //     $menuTypeIdArray = array();

        //     foreach ($allMenusForSpecificRes as $key => $value) {
        //         foreach ($allMenuTypes as $key => $menuType) {
        //             if ($value->menu_type_id == $menuType->id) {
        //                 $menuTypeIdArray[]=$menuType->id;
        //             }
        //         }
        //     }
        //     //removing same value in array
        //    $menuTypeIdArray= array_unique( $menuTypeIdArray);
        //    $menuTypeArray = MenuType::whereIn('id',$menuTypeIdArray)->get();

        //     foreach ($menuTypeArray as $key => $value) {
        //         $menuItems = Menu::where('menu_type_id', $value->id)->get();
        //         $rr[] = [
        //             'foodCategory' => $value,
        //             'menuItems' => $menuItems,
        //         ];
        //     }
        //     return $rr;
        //     if (count($rr) > 0) {
        //         return response()->json([
        //             'success' => true,
        //             'data' => $rr,
        //             'message' => 'Menu Items With Menu Types',
        //         ]);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'data' => [],
        //             'message' => 'No Data Found',
        //         ]);
        //     }
    }

    public function MenuTypesWithAtleastOneItem()
    {
        $data = array();
        $allMenuItems = Menu::all();
        $allMenuTypes = MenuType::all();
        foreach ($allMenuItems as $key => $value) {
            foreach ($allMenuTypes as $key => $menuTypes) {
                if ($menuTypes->id == $value->id) {
                    $data[] = $menuTypes;
                }
            }
        }

        return  response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Menu Types with atleast one item'
        ]);
    }

    public function getCategoriesByMenuTypeId($id)
    {
        $allMenuItems = Menu::where('menu_type_id', $id)->get();
        if (count($allMenuItems) <= 0) {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'No data found'
                ],
            );
        }
        // WE HAVE ALL MENU ITEMS 
        $foodCategoriesID = array();

        foreach ($allMenuItems as $key => $item) {
            $foodCategoriesID[] = $item->food_category_id;
        }

        $foodCategoriesID = array_unique($foodCategoriesID);

        $foodCategories = FoodCategory::whereIn('id', $foodCategoriesID)->get();
        return response()->json([
            'success' => true,
            'data' => $foodCategories,
            'message' => 'Food Categories List',
        ]);
    }

    public function getMenuItemsByFoodCategoryIds($id)
    {
        return $id;
    }

    public function demoRecommendedItem()
    {
        $foodCategories= FoodCategory::all()->random(5)->pluck('id');
        foreach ($foodCategories as $key => $value) {
            $menuItem = Menu::where('food_category_id',$value)->with('restaurant')->first();
            if($menuItem!=null)
            $menuItems[]=$menuItem;
            else
            continue; 
            
        }
        return response()->json([
            'success'=>true,
            'data'=>$menuItems,
            'message'=>'Recommeded Items',
        ]) ;
      
       
    }
}
