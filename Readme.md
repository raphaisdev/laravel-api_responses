<p align="center"><img src="https://github.com/uglymanfirst/laravel-api_responses/blob/master/docs/ftd-default-api-response-logo.png?raw=true" alt="FTD Default API Response"></p>

Welcome to FTD Default API Response!
===================

[TOC]


----------


About
=====
This package was created to extend the **Laravel Framework** response system, and elevate him to the standard described on the [**{json:api}**](http://jsonapi.org) website[^jsonapi].

The answers besides creating a more friendly and readable formatting also contemplate the control of the Headers according to the last code.


----------


Installation
=====
Use composer do install our package:

```bash
composer require ftd/default-api-response
```

And call the provider inside your Laravel /config/app.php file:

```php
    'providers' => [
    ...
      /*
         * FTD Default API Response
         */
         FTD\DefaultAPIResponse\DefaultAPIResponseServiceProvider::class,
    ],
```

Now it's done and we're ready to go!


----------


How to use
=====

**FTD API Response** give us 5 new methods:

 1. success
 2. paginate
 3. error
 4. custom
 5. defaultStatusCode

Every method has a particular way to use, but always easy.

**The success method**
----------------------

This method will throw a header status code **200** and put your content inside a **data** wrapper:

**Example:**
```php
  public function index()
    {
        return response()->success(App\User::all());
    }
```

**Result:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Rodolfo",
      "email": "rodolfo@ftdapi.com",
      "created_at": null,
      "updated_at": null
    },
    {
      "id": 2,
      "name": "Shirley",
      "email": "shirlei@ftdapi.com",
      "created_at": "2017-06-16 01:02:03",
      "updated_at": null
    }
  ]
}
```


**Example:**
```php
  public function show(User $user)
    {
        return response()->success($user);
    }
```

**Result:**
```json
{
  "data": {
    "id": 1,
    "name": "Rodolfo",
    "email": "rodolfo@ftdapi.com",
    "created_at": null,
    "updated_at": null
  }
}
```

**The paginate method**
----------------------
This method will throw a header status code **200** and put your content inside a **data** wrapper, and create another wrapper called **meta**, for the pagination properties:

**Example:**
```php
  public function index()
    {
        $users = App\User::paginate(2);
      return response()->paginate($users);
    }
```

**Result:**
```json
{
  "meta": {
    "pagination": {
      "current_page": 2,
      "from": 3,
      "last_page": 3,
      "next_page_url": "http://ftdapi.com/api?page=3",
      "path": "http://ftdapi.com/api",
      "per_page": 2,
      "prev_page_url": "http://ftdapi.com/api?page=1",
      "to": 4,
      "total": 6
    }
  },
  "data": [
    {
      "id": 3,
      "name": "Marley",
      "email": "marley@ftdapi.com",
      "created_at": "2017-06-15 00:00:01",
      "updated_at": null
    },
    {
      "id": 4,
      "name": "Steve",
      "email": "steve@ftdapi.com",
      "created_at": "2017-06-16 01:02:03",
      "updated_at": null
    }
  ]
}
```


**The error method**
----------------------
This method will throw a header status code **400** and put your content inside a **errors** wrapper:

**Example:**
```php
  //User Custom Request
  public function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            'username'  => 'required|unique:users|string|max:255',
            'password'  => 'required|string|max:255'
        ];
    }

  ...

  public function response(array $errors)
    {
        return response()->error($errors);
    }
```

**Result:**
```json
{
  "errors": [
    "Name must be provided.",
    "Username must be provided.",
    "Password must be provided."
  ]
}
```

**The custom method**
----------------------
This method is used for who need more control of the entire response:

 - The default **content** is **null**
 - The default **header status code** is **200**
 - The default **extra headers** is **null**
 - The default **header content type** is **'application/json'**



**Example:**
```php
  public function myCustomMethod()
    {
        return response()->custom(
      $content = [
            "Name" => "Rodolfo",
            "Age"=>13
            ],
      $status = 200,
      $headers = ["X-USER-INFO" => TRUE],
      $headerContentType = 'application/json'
    );
    }
```

**Result:**
In your header you will see the:

```bash
  "X-USER-INFO" : true
```

or

```bash
  "X-USER-INFO" : 1
```

Depends on which browser you are using.

And, finally, the response body will receive the contents, but **without** the default **data** wrapper:
```json
{
  "Name": "Rodolfo",
  "Age": 13
}
```

If you need to force download of a PDF file, for example, this method is the right way to do it.

**The defaultStatusCode method**
----------------------

This method will throw a header status code and depends on which code, put default message content inside a **data** or **errors** wrapper:

**Example:**
```php
  public function store()
    {
        return response()->defaultStatusCode(400);
    }
```

**Result:**
```json
{
  "errors": [
    "Bad Request"
  ]
}
```

The code list
-------------

|Code | Reference               |
|-----|-------------------------------------|
| 102 | 'Processing',           |
| 200 | 'OK',               |
| 201 | 'Created',              |
| 202 | 'Accepted',             |
| 203 | 'Non-authoritative Information',  |
| 204 | '',//No Content           |
| 206 | 'Partial Content',          |
| 207 | 'Multi-Status',           |
| 302 | 'Found',              |
| 304 | 'Not Modified',           |
| 400 | 'Bad Request',            |
| 401 | 'Unauthorized',           |
| 402 | 'Payment Required',         |
| 403 | 'Forbidden',            |
| 404 | 'Not Found',            |
| 405 | 'Method Not Allowed',       |
| 406 | 'Not Acceptable',         |
| 409 | 'Conflict',             |
| 413 | 'Payload Too Large',        |
| 415 | 'Unsupported Media Type',     |
| 416 | 'Requested Range Not Satisfiable',  |
| 422 | 'Unprocessable Entity',       |
| 423 | 'Locked',             |
| 424 | 'Failed Dependency',        |
| 500 | 'Internal Server Error',      |
| 501 | 'Not Implemented',          |
| 503 | 'Service Unavailable'       |


----------


If you need more information about status code, the [HTTP Status Codes](https://httpstatuses.com/) website[^statuscodes] may help you.



[^jsonapi]: **{json:api}** A specification for building apis in json.

[^statuscodes]: **httpstatuses.com** is an easy to reference database of HTTP Status Codes with their definitions and helpful code references all in one place.