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
# and one way is just hold the token an option will appear on which create new env variable make a name BAREAR and make it global.

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

# Video 7 (Designing Response Payloads)

# The JSON response we send to clients are probably the most important things about our API--it's what clients are coming to our API for. It's not good enough (or safe) to just dump an Eloquent model to the response. So in this episode, you'll learn how to transform Eloquent models into well-structured JSON.

# resource resturn collection of paginated jsom controll formatted data.
public function index()
{
    return TicketResource::collection(Ticket::paginate());
}

class TicketResource extends JsonResource
{
# by default the wrap is data and we can change it like this. here we change it to ticket
    // public static $wrap = 'ticket';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'ticket',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ],
                    'links' => [
                        ['self' => 'todo']
                    ]
                ]
            ],
            'links' => [
                ['self' => route('tickets.show', ['ticket' => $this->id])]
            ]
        ];
    }
}

# result

{
    "data": {
        "type": "ticket",
        "id": 1,
        "attributes": {
            "title": "fugiat iure soluta",
            "description": "Eum at voluptatem ipsam ut. Animi in et voluptatem sit voluptas omnis sapiente accusamus. Omnis voluptatem perspiciatis et eius aliquam aut quos et.",
            "status": "C",
            "createdAt": "2024-05-28T01:43:03.000000Z",
            "updatedAt": "2024-05-28T01:43:03.000000Z"
        },
        "relationships": {
            "author": {
                "data": {
                    "type": "user",
                    "id": 1
                },
                "links": [
                    {
                        "self": "todo"
                    }
                ]
            }
        },
        "links": [
            {
                "self": "http://127.0.0.1:8000/api/v1/tickets/1"
            }
        ]
    }
}

------------------------------------------------------------------------------------------------

# Video 8 (Conditionally Omitting and Including Data)

# Different routes typically need to return different JSON structures--even if those routes work with the same resource. You can create as many Resource classes as you need to fit every situation, or you can use the tools the JsonResource class provides to conditionally omit and include data.

class TicketResource extends JsonResource
{
    // public static $wrap = 'ticket';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'ticket',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
# here we can apply condition on some attribute if the route is not selected one then not show it.
                'description' => $this->when(
                    $request->routeIs('tickets.show'),
                    $this->description
                ),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ],
                    'links' => [
                        ['self' => 'todo']
                    ]
                ]
            ],
# directly adding user resouce in ticket resource by calling user resource here
            'includes' => [
                new UserResource($this->user)
            ],
            'links' => [
                ['self' => route('tickets.show', ['ticket' => $this->id])]
            ]
        ];
    }
}

# user resource for an api.
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
# we can group attributes by condition and merge it with attributes.
                $this->mergeWhen($request->routeIs('users.*'), [
                    'emailVerifiedAt' => $this->email_verified_at,
                    'createdAt' => $this->created_at,
                    'udpatedAt' => $this->updated_at,
                ])
            ]
        ];
    }
}

----------------------------------------------------------------------------------------------------------------

# Video 9 (Using Optional Parameters to Load Optional Data)

# Some data, such as relationship data, doesn't need to be loaded and sent with every response. Instead, we want to give the client the option to include that data. In this episode, we'll use an include query parameter to allow clients to opt-in to loading relationship data.

# api controller is base class and we are expending it in this class.
# we call include function from base class and pass parameter.
# if we take model values from parameter directly then we can take relationships data with load instead of with();
class TicketController extends ApiController
{
    public function index()
    {
        if ($this->include('author')) {
            return TicketResource::collection(Ticket::with('user')->paginate());
        }

        return TicketResource::collection(Ticket::paginate());
    }

    public function show(Ticket $ticket)
    {
        if ($this->include('author')) {
            return new TicketResource($ticket->load('user'));
        }

        return new TicketResource($ticket);
    }
}

# it will return false if there is no param and if there is params then it will return those params in arrayy.
class ApiController extends Controller
{
    public function include(string $relationship) : bool {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
}

# here we use optionol the includes if the user is loaded when resource is called then it will show includes but if it is not loaded then it will not show it.
public function toArray(Request $request): array
{
    return [
        'type' => 'ticket',
        .......
        ......
        'includes' => new UserResource($this->whenLoaded('user')),
        ....
    ];
}

----------------------------------------------------------------------------------------------------------------

# Video 10 (Writing Filters)

# Clients need to be able to filter the data that we provide. We can approach filters in a variety of ways, but in this episode, we'll map query string parameters directly to methods on a filter class.

http://127.0.0.1:8000/api/v1/tickets?filters[title]=*soluta*s

# we will pass query string parameters in array format and we can pass more than one filter and filter data like this.

http://127.0.0.1:8000/api/v1/tickets?filters[title]=*soluta*&filters[status]=completed,cancelled
http://127.0.0.1:8000/api/v1/tickets?filters[title]=*soluta*&filters[status]=soluta

# controller index function
# filter are collected by TicketFilter and pass to scode filter of Filter Model
public function index(TicketFilter $filters)
{
    return TicketResource::collection(Ticket::filter($filters)->paginate());
}

# filter function in Filter Model
# scope filter pass query builder to apply function.
public function scopeFilter(Builder $builder, QueryFilter $filters) {
    return $filters->apply($builder);
}

# Query Filter Class
# apply function check functions of filter byy key of th filter and pass value to that function.
<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter {
    protected $builder;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function filter($arr) {
        foreach($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function apply(Builder $builder) {
        $this->builder = $builder;

        foreach($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }
}


# class Ticket Filter
# here we filter our data and return it.
<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter {
    public function createdAt($value) {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function include($value) {
        return $this->builder->with($value);
    }

    public function status($value) {
        return $this->builder->whereIn('status', explode(',', $value));
    }

    public function title($value) {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('title', 'like', $likeStr);
    }

    public function updatedAt($value) {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}

----------------------------------------------------------------------------------------------------------------

# Video 11 (Using (and Filtering) Nested Resources)

# Filtering ticket data based on the author ID is a little more complex than the basic filters we implemented in the previous episode. To be consistent with our payload, we'll access the author ID filter with a relationships query string parameter, and we'll apply the same methodology to our basic attributes, too.

# api call to call tickets of the user and filter it.
# we can call by inlclude but we cant filter it.
http://127.0.0.1:8000/api/v1/authors/5/tickets?filter[status]=C

# routes for tickets under resources.

# The 'authors.tickets' part indicates a nested resource. This means that the routes for tickets are nested within the context of authors. The route definitions will include the author's ID, making it clear which author's tickets are being referred to.

# Routes Created
# The apiResource method will generate the following routes:

# Index: GET /authors/{author}/tickets - Lists all tickets for a specific author.
# Store: POST /authors/{author}/tickets - Creates a new ticket for a specific author.
# Show: GET /authors/{author}/tickets/{ticket} - Shows a specific ticket for a specific author.
# Update: PUT/PATCH /authors/{author}/tickets/{ticket} - Updates a specific ticket for a specific author.
# Destroy: DELETE /authors/{author}/tickets/{ticket} - Deletes a specific ticket for a specific author.

Route::middleware('auth:sanctum')->apiResource('authors.tickets', AuthorTicketsController::class);

class AuthorTicketsController extends Controller
{
    public function index($author_id, TicketFilter $filters) {
        return TicketResource::collection(
            Ticket::where('user_id', $author_id)->filter($filters)->paginate()
        );
    }
}

----------------------------------------------------------------------------------------------------------------

# Video 12 (Sorting Data)

# An API is a glorified data access layer. So, it's not enough to just provide the ability to fetch and filter data; clients need to be able to sort on any attribute.

# url from postman that how to pass sort variables
http://127.0.0.1:8000/api/v1/tickets?sort=status,-title

# sortable array
protected $sortable = [
    'name',
    'email',
    'createdAt' => 'created_at',
    'updatedAt' => 'updated_at'
];

# sort function
protected function sort($value) {
#   if pass nore than one parameter.
    $sortAttributes = explode(',', $value);

    foreach($sortAttributes as $sortAttribute) {
        $direction = 'asc';

#       if there is -1 before param then take as desc order.
        if (strpos($sortAttribute, '-') === 0) {
            $direction = 'desc';
            $sortAttribute = substr($sortAttribute, 1);
        }

#       if coulmn not found in sortable or key not found in sortable varuables then it will not be present in table so      skip that param.
        if (!in_array($sortAttribute, $this->sortable) && !array_key_exists($sortAttribute, $this->sortable)) {
            continue;
        }

        $columnName = $this->sortable[$sortAttribute] ?? null;

        if ($columnName === null) {
            $columnName = $sortAttribute;
        }

        $this->builder->orderBy($columnName, $direction);
    }
}

----------------------------------------------------------------------------------------------------------------

# Video 13 (Creating Resources with Post Requests)

# APIs need to provide much more than just fetching data; clients need to be able to create resources.

# we can make rules for request like this if some data is array we can define it like this 'data.attributes.title'
# we can also make diff rules if the route is change like in if condition.
# we can also defien which sata should eb consider liek this (in:A,C,H,X)
public function rules(): array
{
    $rules = [
        'data.attributes.title' => 'required|string',
        'data.attributes.description' => 'required|string',
        'data.attributes.status' => 'required|string|in:A,C,H,X',
    ];

    if ($this->routeIs('tickets.store')) {
        $rules['data.relationships.author.data.id'] = 'required|integer';
    }

    return $rules;
}

# we can also make our own messeges.
public function messages() {
    return [
        'data.attributes.status' => 'The data.attributes.status value is invalid. Please use A, C, H, or X.'
    ];
}

# we can check model if it is not found then we can catch error by try adn catch
try {
    $user = User::findOrFail($request->input('data.relationships.author.data.id'));
} catch (ModelNotFoundException $exception) {
    return $this->ok('User not found', [
        'error' => 'The provided user id does not exists'
    ]);
}

----------------------------------------------------------------------------------------------------------------

# Video 14 (Deleting Resources with Delete Requests)

# Deleting resources is probably the most straight-forward thing you will implement in your API.

----------------------------------------------------------------------------------------------------------------

# Video 15 (Replacing Resources with Put Requests)

# There are two types of requests used to update a resource: PATCH and PUT, and they both do completely different things.

# PATCH and PUT are HTTP methods used in RESTful API design for updating resources, but they have different implications and use cases:

# PUT: The PUT method is used to update or replace an existing entire resource

# PATCH: Use PATCH when you want to update specific fields or properties of a resource.

# here we make resource routes and then avoid update route in it.
# then make another route of put instead of update to use custo routes of put isntead of update.
Route::apiResource('tickets', TicketController::class)->except(['update']);
Route::put('tickets/{ticket}', [TicketController::class, 'replace']);

----------------------------------------------------------------------------------------------------------------

# Video 16 (Updating Resources with Patch Requests)

# Updating resources with PATCH requests is a little more involved than with PUT requests--primarily because we have to update only the data that was provided in the request.

# here we used $request->mappedAttributes() just to manified code and its defined in BaseTicketRequest.

public function update(UpdateTicketRequest $request, $ticket_id)
{
    // PATCH
    try {
        $ticket = Ticket::findOrFail($ticket_id);

        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
    } catch (ModelNotFoundException $exception) {
        return $this->error('Ticket cannot be found.', 404);
    }
}

# its used for validation but it extend BaseTicektRequest class in which we implement common code just like messeges and mappedattribute funtion.

class ReplaceTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // rules
    }
}

# mapped attribite function work like the way that it checks every key in attribute map when it is not null then it update attribute to update varible with its key and value and in a result the variable contain only those attrbute which is not null and return to the funciton so here we manified our code to one line in a lot spaces.

class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes() {
        $attributeMap = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at',
            'data.relationships.author.data.id' => 'user_id',
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    public function messages() {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use A, C, H, or X.'
        ];
    }
}

----------------------------------------------------------------------------------------------------------------

# Video 17 (Using Policies for User Authorization)

# User authorization is so much more than determining if a user can do something. It also involves determining if a user can do something for a specific resource.

# command to run policy
php artisan make:policy TicketPolicy

# we have create an update method in policy which will work like if the loign user id is equal to ticket user id if same it will authorize;
class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Ticket $ticket) {
        // TODO check for token ability
        return $user->id === $ticket->user_id;
    }
}

# boot Ticketpolicy to Ticket class model in app service provider boot byy gate facade. it will apply to this class.
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Ticket::class, TicketPolicy::class);
    }
}

# make an isAble method in which we pass method of update or the specific functionolity function to it and or data model.
class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        // PATCH
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // policy
            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }
}

# here we create a general method which will be able for all policy functionality where we pass the ability to authorize and the modal class and also the policy further it will directly called that ability method and apply on the target module.
class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;

    public function include(string $relationship) : bool {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble($ability, $targetModel) {
        return Gate::authorize($ability, [$targetModel, $this->policyClass]);
    }
}

if we authorized directl in controller then we dont need policy it just understand it from the naming convention.
Gate::authorize('update', $ticket);

----------------------------------------------------------------------------------------------------------------

# Video 18 (Controlling Access with Token Abilities)

# Sanctum's token abilities allow us to essentially assign roles or permissions to users without building a system ourselves or using an external package.

# when the user login its create the token and assign abilities through Abilities class.
# by this token we can use policies to accross thr project
public function login(LoginUserRequest $request) {
    $request->validated($request->all());

    if (!Auth::attempt($request->only('email', 'password'))) {
        return $this->error('Invalid credentials', 401);
    }

    $user = User::firstWhere('email', $request->email);

    return $this->ok(
        'Authenticated',
        [
            'token' => $user->createToken(
                'API token for ' . $user->email,
                Abilities::getAbilities($user),
                now()->addMonth())->plainTextToken
        ]);
}

# constant differnt abiliities which is assigned through user role
final class Abilities {
    public const CreateTicket = 'ticket:create';
    public const UpdateTicket = 'ticket:update';
    public const ReplaceTicket = 'ticket:replace';
    public const DeleteTicket = 'ticket:delete';

    public const UpdateOwnTicket = 'ticket:own:update';
    public const DeleteOwnTicket = 'ticket:own:delete';

    public const CreateUser = 'user:create';
    public const UpdateUser = 'user:update';
    public const ReplaceUser = 'user:replace';
    public const DeleteUser = 'user:delete';

    public static function getAbilities(User $user) {
        if ($user->is_manager) {
            return [
                self::CreateTicket,
                self::UpdateTicket,
                self::ReplaceTicket,
                self::DeleteTicket,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        } else {
            return [
                self::CreateTicket,
                self::UpdateOwnTicket,
                self::DeleteOwnTicket
            ];
        }
    }
}

# its a ticket controller in which we are checking isAble function and passing ability and model which will check that it authorize the user or not.
public function destroy($ticket_id)
{
    try {
        $ticket = Ticket::findOrFail($ticket_id);

        // policy
        $this->isAble('delete', $ticket);

        $ticket->delete();

        return $this->ok('Ticket successfully deleted');
    } catch (ModelNotFoundException $exception) {
        return $this->error('Ticket cannot found.', 404);
    }
}

# It will check authorization by passing the abilt model and policy class.
public function isAble($ability, $targetModel) {
    return Gate::authorize($ability, [$targetModel, $this->policyClass]);
}

# in mentiond policy class it will go to the function by the ability which is passed if they have delete ticket ability then he is good to go if not then it will check whether it have the abilty to remove his ticket if yes then it will check user_id of ticket with user log in id if yes it will remove it of not it will sa unauthorized.
public function delete(User $user, Ticket $ticket) {
    if ($user->tokenCan(Abilities::DeleteTicket)) {
        return true;
    } else if ($user->tokenCan(Abilities::DeleteOwnTicket)) {
        return $user->id === $ticket->user_id;
    }

    return false;
}

----------------------------------------------------------------------------------------------------------------

# Video 19 (Applying Granular Permissions)

# We can use token abilities for anything, including controlling which attributes a user can or cannot change.

# when user have only permission of changing the ticket which user is same to login user the we can do it mapped attributes but that is apply globally and we have to check on lot of routes thats why we wil do it in its persnol request file.

# its aplly glovally thats why its not best practice
public function mappedAttributes() {
    $attributeMap = [
        'data.attributes.title' => 'title',
        'data.attributes.description' => 'description',
        'data.attributes.status' => 'status',
        'data.attributes.createdAt' => 'created_at',
        'data.attributes.updatedAt' => 'updated_at',
        'data.relationships.author.data.id' => 'user_id',
    ];

    $attributesToUpdate = [];
    foreach ($attributeMap as $key => $attribute) {
        if ($this->has($key)) {
            $attributesToUpdate[$attribute] = $this->input($key);
        }
    }

    return $attributesToUpdate;
}

# its best to do it in personal request class
# prohibited will show that you need this id to update
public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:A,C,H,X',
            'data.relationships.author.data.id' => 'sometimes|integer',
        ];

        if ($this->user()->tokenCan(Abilities::UpdateOwnTicket)) {
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }

        return $rules;
    }

# its user have createonlyticket permission then its relation author id must be equal user id.
public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships.author.data.id' => 'required|integer|exists:users,id'
        ];

        $user = $this->user();

        if ($this->routeIs('tickets.store')) {
            if ($user->tokenCan(Abilities::CreateOwnTicket)) {
                $rules['data.relationships.author.data.id'] .= '|size:' . $user->id;
            }
        }

        return $rules;
    }

# here we will pass model in isAble method instead of model variable because we are created we dont have model data.
public function store(StoreTicketRequest $request)
    {
        try {
            // policy
             $this->isAble('store', Ticket::class);
 
             return new TicketResource(Ticket::create($request->mappedAttributes()));
         } catch (AuthorizationException $ex) {
             return $this->error('You are not authorized to update that resource', 401);
         }
    }

# The line $rules['data.relationships.author.data.id'] .= '|size:' . $user->id; appends an additional validation rule to the existing validation rule for the key 'data.relationships.author.data.id'.

# Existing Rule: Before this line, the rule for 'data.relationships.author.data.id' is 'required|integer|exists:users,id'.

# Appending: The .= '|size:' . $user->id; adds more to this rule. Specifically, it appends the string |size:X, where X is the value of $user->id.

# |size:X is a validation rule in Laravel that ensures the value must be exactly X. So, by appending this rule, the validator will now also check that the id is exactly the same as the current user's id ($user->id).

----------------------------------------------------------------------------------------------------------------

# Video 20 (Implementing the AuthorTicketsController)

# We've covered filtering, handling requests, and permissions. Now we just need to apply all of those concepts to implement the AuthorTicketsController.

# The prepareForValidation() method is used to modify or format the incoming request data before the validation rules are applied. In this case, it’s handling specific logic when the route is 'authors.tickets.store'.

# Here’s a breakdown of how this works:

# Route Check:

# The method checks if the current route matches 'authors.tickets.store' using $this->routeIs('authors.tickets.store').
# If the route matches, it proceeds to modify the request data.
# Merging Data:

# $this->merge() is used to add or update the incoming request data.
# The code 'author' => $this->route('author') is merging a new key 'author' into the request data. The value of this key is set to the result of $this->route('author').

# Effect:
# $this->route('author') retrieves the 'author' parameter from the route. This typically means that the 'author' parameter is part of the route URL (e.g., /authors/{author}/tickets).
# By merging this into the request data, you ensure that 'author' is now part of the request's input data, allowing it to be accessed during validation or later in the controller.

# Example:
# Suppose your route is /authors/123/tickets, and 123 is the author parameter. This method would:

# Add 'author' => 123 to the request data.
# Now, you can access $request->input('author') in your controller or during validation, which would return 123.
# This is useful for ensuring that route parameters are available as part of the request's data during validation or processing, even if they # aren't explicitly passed in the request body.

----------------------------------------------------------------------------------------------------------------

# Video 21 (Managing Users)

# We've implemented ticket management, and now we need to implement the ability to manage users (with the appropriate permissions). Let's get to it.

----------------------------------------------------------------------------------------------------------------
