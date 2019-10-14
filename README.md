# User Status

To enable status for a model, use the `Fligno\User\Traits\HasStatus` trait on the model:

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fligno\User\Traits\HasStatus

class Flight extends Model
{
    use HasStatus;
}
```

You should also add the `is_active` column to your database table.

```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_active')->default(0);
});
```

Now, you can call the `activate` method on the model, the `is_active` column will be set to `1`. `deactivate` method on model for deactivation and `toggleStatus` to toggle `is_active` column to true/false.

To determine if a given model instance has been activated use the `isActive()` method:

```php
if ($user->isActive()) {
    //
}
```

### Extending methods to your Controller

To enable status for a controller, use the `Fligno\User\Traits\ManageStatus` trait on the controller and create a variable `protected $model = 'App\User'`

```php
<?php

namespace App\Http\Controllers;

use Fligno\User\Traits\ManageStatus;

class UserController extends Controller
{
	use  ManageStatus;

	protected $model = 'App\User';
}
```

Available methods in controller:

Methods | Parameter | set `is_active` | Request Params
--- | --- | --- | ---
activate| {id} | true
deactivate| {id} | false
toggleStatus| {id} | true/false
bulkStatusUpdate| N/A | true/false | Array `ids`, Boolean `status`

Now you can add this to your `routes/web.php`:

```php
<?php

Route::post('/user/active/{id}', 'UserController@activate')->name('user.activation');

Route::post('/user/deactivate/{id}', 'UserController@deactivate')->name('user.deactivation');

Route::post('/user/toggle/{id}', 'UserController@toggleStatus')->name('user.toggle');

Route::post("/user/bulk/update", "UserController@bulkStatusUpdate")->name("user.bulkUpdate");
```

Or you can simply add this function to you `routes/web.php`:

```php
<?php

use Fligno\User\UserStatus;

UserStatus::routes(['UserController']);
```
For multiple controller.

```php
UserStatus::routes(['UserController', 'MemberController']);
```
This will generate the routes above as kebab case:

Controller | Link | Route Name
--- | --- | --- 
UserController| /user/../{id} | user.*
MemberController| /member/../{id} | member.*
UserAdminController| /user-admin/../{id} | user-admin.*

`...` = `active`, `deactivate` and `toggle`
`*` =  `activation`, `deactivation` and `toggle`

You can also define the custom base link for route:
```php
UserStatus::routes(['UserController' => 'custom-user']);
```
This will generate the routes above as kebab case:
```
/custom-user/../{id}
```
route name:  `custom-user.*`
## Unit Testing

```php
/** @test */
public  function  a_user_can_activate_user()
{
	$user  =  factory('App\User')->create();
	$this->postJson(route('user.activation',  $user->id));

	tap($user->fresh(),  function  ($user)  {
		$this->assertTrue($user->isActive());
	});
}

/** @test */
public  function  a_user_can_deactivate_user()
{
	$user  =  factory('App\User')->create(['is_active'  =>  true]);
	$this->postJson(route('user.deactivation',  $user->id));

	tap($user->fresh(),  function  ($user)  {
		$this->assertFalse($user->isActive());
	});
}

/** @test */
public  function  a_user_can_toggle_user_status()
{
	$user  =  factory('App\User')->create(['is_active'  =>  true]);

	$this->postJson(route('user.toggle',  $user->id));

	tap($user->fresh(),  function  ($user)  {
		$this->assertFalse($user->isActive());
	});
}

/** @test */
public  function  a_user_can_update_mulitple_user_status()
{

	$users  =  factory('App\User',  5)->create(['is_active'  =>  false]);
	$ids  =  $users->pluck('id')->all();

	$this->postJson(route('user.bulkUpdate'), [
		'ids'  =>  $ids,
		'status'  =>  true
	]);

	tap($users->fresh(),  function  ($users)  {
		foreach  ($users  as  $user)  {
			$this->assertTrue($user->isActive());
		}
	});
}
```

