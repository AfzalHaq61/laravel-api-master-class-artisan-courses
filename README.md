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

# Video 2 (Working with Postman)

# The browser isn't a sufficient tool for working with web APIs. Instead, we need an HTTP debugger. There are many available, but we'll use Postman in this series. The right tool makes all the difference in the world.

# Download it.
# make workspace.
# make collection.
# make a request of get and post simple and save it with a proper name.
# if it is post request so you have to write your data in form-data of body. or anyother option.
# if you have validation then it may be give some wierd responses so you have to add an (accept = "application/json) parameter to header so the response of error will be in json format.
