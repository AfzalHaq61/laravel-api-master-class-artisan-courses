# Video 1 (Status 200)

# We'll start our API from scratch with a basic Laravel project and build some simple routes that return JSON-formatted data. We'll also write a trait that we can use in our API controllers to simplify returning JSON structures.

# web apps changed when anguler came into world and the reality of spa came in and then react and vue came then client does a lot of heavyy lifting which server was doing generate markups whcih render thorugh browser and a server is just a data access layer but we need a server for it so we have to write an api to server to get data or pass data to stoer it

# Lumen is a micro-framework designed to provide a minimal and high-performance foundation for building web applications and APIs using PHP. It is developed by the same team behind the Laravel framework and is essentially a smaller, faster, and simpler version of Laravel. Here are some key points about Lumen:

# we will using laravel here because we want to understand and learning apis developement.

# first of all make route in api file.
# the api responses will be in json format because its statndard. there mayy be other.
# we use traits for responses which is define below.


Route::get('/login', [AuthController::class, 'login']);

class AuthController extends Controller
{
    use ApiResponses;
    public function login() {
        return $this->ok('Hello, Login!');
    }
}

<?php

namespace App\Traits;

trait ApiResponses {
    protected function ok($message) {
        return $this->success($message, 200);
    }

    protected function success($message, $statusCode = 200) {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}

# Trait
# A trait is a reusable collection of methods that you can include in multiple classes to share functionality without inheriting from a parent class.

# Example
# Defining a Trait: Create a file for your trait, usually in the App\Traits directory.

<?php

namespace App\Traits;

trait Loggable
{
    public function log($message)
    {
        echo "Log message: $message";
    }
}

# Using a Trait in a Class: Include the trait in any class that needs the log method.

<?php

namespace App\Models;

use App\Traits\Loggable;

class User
{
    use Loggable;

    // Other methods and properties of the User class
}

# Accessing Trait Methods: Use the log method in an instance of the User class.

$user = new User();
$user->log('This is a log message.');

# Output

Log message: This is a log message.

------------------------------------------------------------------------------------------------

# Video 3 (Working with Postman)

# The browser isn't a sufficient tool for working with web APIs. Instead, we need an HTTP debugger. There are many available, but we'll use Postman in this series. The right tool makes all the difference in the world.

# Download it.
# make workspace.
# make collection.
# make a request of get and post simple and save it with a proper name.
# if it is post request so you have to write your data in form-data of body. or anyother option.
# if you have validation then it may be give some wierd responses so you have to add an (accept = "application/json) parameter to header so the response of error will be in json format.

--------------------------------------------------------------------------------------------------

# Video 3 (Designing the Url)

# Every application has a user interface--even web APIs! Except that our UI is the URL because that how users interact with our application. So, it's important to think about our URLs and design them to be logically usable.

# URL:
# A URL (Uniform/Universal Resource Locator) is a reference or address used to access resources on the internet.

# Components of a URL
# Scheme: Indicates the protocol used to access the resource (e.g., http, https, ftp).
# Host: The domain name or IP address of the server where the resource is located (e.g., www.example.com).
# Port: Optional and specifies the port number to connect to on the server (e.g., :80 for HTTP or :443 for HTTPS).
# Path: Specifies the exact location of the resource on the server (e.g., /path/to/resource).
# Query String: Optional and provides additional parameters or inputs in key-value pairs (e.g., ?key1=value1&key2=value2).
# Fragment: Optional and indicates a specific part of the resource, often used in HTML documents to navigate to a section within the page (e.g., #section1).

# Example of a URL
https://www.example.com:443/path/to/resource?search=query#section1

# Example of apis
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

'title' => fake()->words(3, true),

# it will create 3 word and passign true will return string.

Ticket::factory(100)->recycle($users)->create();

# the recycle will take user and assign random user to ticket.

-------------------------------------------------------------------------------------------------------------------

# Video 4 (How to Version Your API)

# Versioning is one of the most important parts of designing an API. It protects you from making breaking changes for your clients, and it helps to structure your project in a logical manner. Let's look at one of the ways you can implement versioning for your API.

php artisan make:controller Api/V1/TicketController --resource --model=Ticket --requests

# this command will make controller with all the function we need and it will use the model which we mentioned and with the request

# api versions rule
# 1. make api_v1.php (api_version.php) file in routes folder.
# 2. then you have to register this route in app.php file in bootstrap folder like below.
# 3. make folder of Api/V1 everywhere where you have related files.

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

----------------------------------------------------------

then: function () {
    Route::middleware('api')
        ->prefix('api/v1')
        ->group(base_path('routes/api_v1.php'));
},

# you can register cutom route like this.

Route::apiResource('tickets', TicketController::class);

# here we use apiResource which will create route for apis not all routes it will exclude route like create, edit etc.

------------------------------------------------------------------------------------------------------------

# Video 5 (Authentication Using Tokens)

# Unlike typical web applications, APIs rely on tokens to determine if clients are authenticated. In this episode, you'll learn how to generate Sanctum tokens for clients and protect routes with the Sanctum middleware.

# there is another ways but we will create token and token will be created on sanctum because it is easy and provice all requirements.

# add HasApiToken to User model.
# and this function will authenticate user and then create a token for user and formated it ot plain text.
# then you have to provide this token in postman in barear token of authrizationtan.
# add middleware('auth:sanctum') to your routes if the user is login with this token you provided then it will respone otherwise it will not.

public function login(LoginUserRequest $request) {
    $request->validated($request->all());

    if (!Auth::attempt($request->only('email', 'password'))) {
        return $this->error('Invalid credentials', 401);
    }

    $user = User::firstWhere('email', $request->email);

    return $this->ok(
        'Authenticated',
        [
            'token' => $user->createToken('API token for ' . $user->email)->plainTextToken
        ]
        );
}

Route::middleware('auth:sanctum')->apiResource('tickets', TicketController::class);

----------------------------------------------------------------------------------------------------------------

# Video 6 (Revoking Authentication Tokens)

# It is important to learn how to revoke client tokens to ensure the security of your API. I'll show you how to do that, as well as set an expiration time for your tokens.

# byy default sanctum tokens are not foing to expired but it depend on the condition where you want to expire your tokens or not so here we need to expire our token it may done in many cases we hace three diff ways.

# 1. it will delete all the user tokens.
# but we dont have delete all the tokens for the user because it may be for something imp we need to delete specific token for the auth.
# it may be used when we delete user account.
public function logout(Request $request) {
    $request->user()->tokens()->delete();
}

# 2. it will delete user token by id but now we dont have one.
public function logout(Request $request) {
    $request->user()->tokens()->where('id', $tokenId)->delete();
}

# 3. it will delete user token by current token on which we authenticated.
public function logout(Request $request) {
    $request->user()->currentAccessToken()->delete();
}

# we need post route and it must have auth:sanctum middleware so it pass the token to thr function.
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

# we can also make global env variables in env so we can use it all over project
# and one way is just select all the token an option will appear on which create new env variable make a name BAREAR and make it global.

return $this->ok(
    'Authenticated',
        [
            'token' => $user->createToken(
                'API token for ' . $user->email,
                ['*'],
                now()->addMonth())->plainTextToken
        ]
    );

# when we create a token for user then the first parameter is for name, the second parameter is for the abilities (her we pass ['*'] for all the abilities) and the third parameter is for expiration date of the token. it will directly store data in personal access token table in the database and the token id will be generated against the user id.

# by default the expiration date of the token is empty but we can change it in sanctum configuration in sanctum.php in config folder but change the expiration date from null to any time we want but it accepts the time in minutes. 

# This value controls the number of minutes until an issued token will be | considered expired. This will override any values set in the token's | "expires_at" attribute, but first-party sessions are not affected.

----------------------------------------------------------------------------------------------------------------------

