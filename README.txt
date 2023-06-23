The code Booking controller looks like related to api but as per the standard diretory structure, kt should be placed inside some kinda \Api\Vx\BookingController.php

We are using worst ever method for role and permissions handling, It look like we haven't integrated spatie laravel permissions or some other well know packages, or may be we can easily implement laravel gates and policies  instead we are managing permissions in worst ever way like passing id from environment variable, In real life case it wouldn't work, i.e. suppose we need to create finance department role, and under finance department role we need to give access to 10 users and all have different access to different reports.

We must need to integrate centralized return response like what I would prefer to create BaseController, this base controller will inherit the Controller, every class which is returning response should inherit this to append global function for success or failure response.

In every function we must handle exception or errors by encapsulating code in try catch block

We should not pass all reqest to repository if we need only a variable or single value

We must include some facade or helper to have functions to be called statically like convertToHoursMins() available in repository should be in separate facade or in helper.

Overall code quality is very bad, no use of facade, or helpers, called extra varaibles, didn't applied try catch block very lengthy functions.

Instead of long functions we need to follow solid prinicples. 
Functions are doing multiple jobs in repositories. 