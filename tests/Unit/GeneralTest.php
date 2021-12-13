<?php

namespace Tests\Unit;

use App\Http\Modules\Auth\Models\Users;
use App\Http\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GeneralTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_getItem(){
        $item = new Item();
        $data = $item->get_all("", 10, 0);
        echo "Total : ".$data['total'];
        var_dump($data);
        $this->assertNotNull($data, "Data");
    }
    
}
