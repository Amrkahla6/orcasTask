<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\StoreUser;
use App\Http\Resources\UserResource;
use App\Traits\ApiTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    use ApiTrait;

  
   /**
    * The `__construct()` function is a special function that is automatically called when a new object
    * is created. In this case, we are using it to call the `middleware()` function, which is a Laravel
    * function that is used to protect routes. The `middleware()` function takes two arguments: the
    * name of the middleware and an array of routes that should be excluded from the middleware. In
    * this case, we are using the `auth:api` middleware, which is a Laravel middleware that checks to
    * see if the user is logged in. If the user is not logged in, the user will be redirected to the
    * login page. The second argument is an array of routes that should be excluded from the
    * middleware. In this case, we are excluding the `login` and `store` routes
    */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','store']]);
    }//End __construct Function


  
   /**
    * It searches for a user by email, first name, or last name
    * 
    * @param Request request The request object
    * 
    * @return A collection of users that match the search criteria.
    */
    public function search(Request $request){
        $user = UserResource::collection(User::where('email',$request->email)
            ->orWhere('firstName',$request->fName)
            ->orWhere('lastName',$request->lName)
            ->get()
        );

        return $this->returnData('search', $user);
    }//End Search Function


   
  /**
   * It takes the email from the request, finds the user in the database, and then creates a token for
   * that user
   * 
   * @param Request request The request object.
   * 
   * @return A token is being returned.
   */
    public function login(Request $request){
        $email = $request->input('email');
        $user = User::where('email', '=', $email)->first();
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }//End User Login Function


  
 /**
  * It returns a collection of users in a paginated format
  * 
  * 10 users in page
  * 
  * @return A collection of users.
  */
    public function getUsers(){
        return $this->returnData('success',
            UserResource::collection(User::paginate(10))->resource
        );

    }//End Index Function

    /**
     * @return string
     * @throws GuzzleException
     * Save List Of User
     */
    public function store(){

        try {
            //List Of Users
            $users1 = $this->getListOfUser1();
            $users2 = $this->getListOfUser2();
            $this->saveRecord($users1, 'firstName', 'lastName', 'avatar');
            $this->saveRecord($users2, 'fName', 'lName', 'picture');
            return $this->returnData('msg', 'Save Successfully');
        } catch (\Illuminate\Database\QueryException $e) {
//            $e->errorInfo[2]
            return $this->returnError('','email is exist');
        }

    }//End Store Function

    /**
     * @param $users
     * @param $fname
     * @param $lname
     * @param $avatar
     * Store Users In DB
     */
    public function saveRecord($users,$fname,$lname,$avatar){
        foreach ($users as $value){
            //Validate first
            if(!$value[$fname] || !$value[$lname] || !$value['email']){
                return "Input Is Required";
            }
            $pic = $this->avatar($value[$avatar]);
            DB::table('users')->insert(
                [
                    'firstName'   =>   $value[$fname],
                    'lastName'    =>   $value[$lname],
                    'email'       =>   $value['email'],
                    'avatar'      =>   $pic,
                ]
            );
        }
    }//End Save Record Function

    /**
     * @param $avatar
     * @return mixed|string
     */
    public function avatar($avatar){
        $url =  (parse_url($avatar));
        $path = $url['path'];
        $pic = explode('/', $path);
        return $pic[2];
    }//End Avatar Function

}


