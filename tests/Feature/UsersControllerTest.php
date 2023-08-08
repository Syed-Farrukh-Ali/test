<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\Town;
use App\Models\UserTowns;
use App\Models\Types;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating or updating a user.
     */
    public function testCreateOrUpdate()
    {
        $request = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'role' => config('app.CUSTOMER_ROLE_ID'),
            'consumer_type' => 'paid',
            'company_id' => 1,
            'department_id' => 1,
            'dob_or_orgid' => '1980-01-01',
            'phone' => '1234567890',
            'mobile' => '9876543210',
            'username' => 'johndoe',
            'post_code' => '123456',
            'address' => '123 Main Street',
            'city' => 'Anytown',
            'town' => 'Anytown',
            'country' => 'United States',
            'reference' => 'yes',
            'additional_info' => 'This is some additional information about John Doe.',
            'cost_place' => 'home',
            'fee' => '100',
            'time_to_charge' => '1 day',
            'time_to_pay' => '1 week',
            'charge_ob' => 'yes',
            'customer_id' => 1,
            'charge_km' => 10,
            'maximum_km' => 100,
            'translator_ex' => [2],
            'new_towns' => 'New Town',
            'user_towns_projects' => [1, 2]
        ];

        $user = $this->createUser($request);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johndoe@example.com', $user->email);
        $this->assertEquals('secret', $user->password);
        $this->assertEquals(config('app.CUSTOMER_ROLE_ID'), $user->role_id);
        $this->assertEquals(1, $user->company_id);
        $this->assertEquals(1, $user->department_id);
        $this->assertEquals('1980-01-01', $user->dob_or_orgid);
        $this->assertEquals('1234567890', $user->phone);
        $this->assertEquals('9876543210', $user->mobile);
        $this->assertEquals('johndoe', $user->username);
        $this->assertEquals('123456', $user->post_code);
        $this->assertEquals('123 Main Street', $user->address);
        $this->assertEquals('Anytown', $user->city);
        $this->assertEquals('Anytown', $user->town);
        $this->assertEquals('United States', $user->country);
        $this->assertEquals('yes', $user->meta->reference);
        $this->assertEquals('This is some additional information about John Doe.', $user->meta->additional_info);
        $this->assertEquals('home', $user->meta->cost_place);
        $this->assertEquals('100', $user->meta->fee);
        $this->assertEquals('1 day', $user->meta->time_to_charge);
        $this->assertEquals('1 week', $user->meta->time_to_pay);
        $this->assertEquals('yes', $user->meta->charge_ob);
        $this->assertEquals(1, $user->meta->customer_id);
        $this->assertEquals(10, $user->meta->charge_km);
        $this->assertEquals(100, $user->meta->maximum_km);
        $this->assertEquals([2], $user->meta->translator_ex);

        $town = Town::where('townname', '=', 'New Town')->first();
        $this->assertNotNull($town);

        $userTowns = UserTowns::where('user_id', '=', $user->id)->get();
        $this->assertEquals(2, count($userTowns));
        $this->assertEquals(1, $userTowns[0]->town_id);
        $this->assertEquals(2, $userTowns[1]->town_id);
    }

    /**
     * Helper function to create a user.
     *
     * @param  array  $request
     * @return User
     */
    protected function createUser($request)
    {
        $user = User::create($request);
        $user->attachRole($request['role']);
        return $user;
    }
}
