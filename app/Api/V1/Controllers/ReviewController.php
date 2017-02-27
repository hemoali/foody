<?php

namespace App\Api\V1\Controllers;
use JWTAuth;
use App\Book;
use Dingo\Api\Routing\Helpers;
use App\Api\V1\Requests\AddReviewRequest;
use App\Review;
use App\User;
use App\Restaurant;

class ReviewController extends Controller
{
    use Helpers;
    public function store(AddReviewRequest $request)
    {
        $review = new Review($request->all());
        $user = JWTAuth::parseToken()->authenticate();
        $rest = Restaurant::find($request->get('restaurant_id'));
        $review->user()->associate($user);
        $review->restaurant()->associate($rest);
        if ($review->save())
            return $this->response->created();
        else
            return $this->response->error('could_not_add_review', 500);
    }
}
