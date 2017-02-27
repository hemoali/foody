<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use App\Book;
use Dingo\Api\Routing\Helpers;
use App\Api\V1\Requests\AddRestaurantRequest;
use App\Api\V1\Requests\DeleteRestaurantRequest;
use App\Api\V1\Requests\SearchRestaurantRequest;
use App\Api\V1\Requests\FetchRestaurantRequest;
use App\Api\V1\Requests\ReviewRestaurantRequest;
use App\Restaurant;

class RestaurantController extends Controller
{
    use Helpers;

    public function fetch(FetchRestaurantRequest $request)
    {
        return Restaurant::paginate(15);
    }

    public function store(AddRestaurantRequest $request)
    {
        $restaurant = new Restaurant($request->all());
        if ($restaurant->save())
            return $this->response->created();
        else
            return $this->response->error('could_not_add_restaurant', 500);

    }

    public function update(AddRestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant)
            throw new NotFoundHttpException;

        $restaurant->fill($request->all());
        if ($restaurant->save())
            return $this->response->noContent();
        else
            return $this->response->error('could_not_update_restaurant', 500);

    }

    public function destroy(DeleteRestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant)
            throw new NotFoundHttpException;

        if ($restaurant->delete())
            return $this->response->noContent();
        else
            return $this->response->error('could_not_delete_restaurant', 500);

    }

    public function reviews(ReviewRestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant)
            throw new NotFoundHttpException;

        return $restaurant->reviews()->simplePaginate(1);
    }

    public function search(SearchRestaurantRequest $request, $q)
    {
        $restaurants = Restaurant::where('desc', 'like', "%$q%")->get();

        return $restaurants;
    }
}
