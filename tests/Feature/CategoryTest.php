<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CategoriesController;
use App\User;

class CategoryTest extends TestCase
{
    protected $user;

    public function setUp(){
        parent::setUp();

        $this->user = factory(User::class)->create();

        DB::beginTransaction();
    }    


    /**
     * Testing saving to database
     *
     * @return void
     */
    /** @test */
    public function storeCategoryTest()
    {
        $response = $this->actingAs($this->user)->post(route('categories.store'), [
            'title' => 'test',
            'color' => '#ffffff'
        ]);;

        $response->assertSessionHas("flash_message", "Category added!");
        
        $this->assertDatabaseHas('categories', [
            'title' => 'test',
            'color' => '#ffffff'
        ]);

        DB::rollBack();

    }

    /**
     * Testing saving not unique title
     *
     * @return void
     */
    /** @test */
    public function storeNotUniqueTitleCategoryTest()
    {
        $response = $this->actingAs($this->user)->post(route('categories.store'), [
            'title' => 'test',
            'color' => '#ffffff'
        ]);

        $response = $this->actingAs($this->user)->post(route('categories.store'), [
            'title' => 'test',
            'color' => '#0fffff'
        ]);

        $response->assertSessionHasErrors("title");
        
        $this->assertDatabaseMissing('categories', [
            'title' => 'test',
            'color' => '#0fffff'
        ]);

        DB::rollBack();

    }

    /**
     * Testing update other user category
     *
     * @return void
     */
    /** @test */
    public function editOtherUserCategoryTest()
    {
        //created by new user
        $category = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->put(route('categories.update', $category->id),  [
            'name' => 'xd'
        ]);

        $this->assertEquals(403, $response->status());

        DB::rollBack();

    }

}
