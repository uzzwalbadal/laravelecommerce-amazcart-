<?php

namespace Tests\Browser\Modules\Product;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Laravel\Dusk\Browser;
use Modules\Product\Entities\Category;
use Tests\DuskTestCase;

class CategoryTest extends DuskTestCase
{
    use WithFaker;


    public function setUp(): void
    {
        parent::setUp();


    }

    public function tearDown(): void
    {
        $categories = Category::all();
        foreach($categories as $category){
            if(File::exists(public_path($category->categoryImage->image))){
                File::delete(public_path($category->categoryImage->image));
                $category->categoryImage->delete();
            }
            $category->delete();
        }

        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_for_visit_index_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                    ->visit('/product/category')
                    ->assertSee('Category List');
        });
    }

    public function test_for_validate_create_category(){
        $this->test_for_visit_index_page();
        $this->browse(function (Browser $browser) {
            $browser->type('#name', '')
                ->type('#commission_rate', '')
                ->type('#icon', '')
                ->click('#add_category_form > div > div > div:nth-child(1) > div:nth-child(3) > div > label')
                ->pause(2000)
                ->click('#create_btn')
                ->waitForText('The name field is required.',25)
                ->assertSee('The name field is required.')
                ->assertSee('The slug field is required.');

        });
    }


    public function test_for_create_category(){
        $this->test_for_visit_index_page();
        $this->browse(function (Browser $browser) {
            $browser->type('#name', $this->faker->name)
                ->type('#commission_rate', '5')
                ->type('#icon', 'fas fa-align-justify')
                ->click('#add_category_form > div > div > div:nth-child(1) > div:nth-child(3) > div > label')
                ->pause(2000)
                ->attach('#image', __DIR__.'/files/default_category.png')
                ->click('#create_btn')
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Created successfully!');
        });
    }

    public function test_for_create_subcategory(){
        $this->test_for_create_category();
        
        $this->browse(function (Browser $browser) {
            $browser->type('#name', $this->faker->name)
                ->type('#commission_rate', '5')
                ->type('#icon', 'fas fa-align-justify')
                ->click('#add_category_form > div > div > div:nth-child(1) > div:nth-child(3) > div > label')
                ->pause(2000)
                ->click('#add_category_form > div > div > div:nth-child(1) > div:nth-child(7) > div > ul > li > label > span')
                ->waitFor('#sub_cat_div > div > div', 4)
                ->click('#sub_cat_div > div > div')
                ->click('#sub_cat_div > div > div > ul > li:nth-child(1)')
                ->click('#create_btn')
                ->pause(6000)
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Created successfully!');
        });
    }

    public function test_for_edit_category(){
        $this->test_for_create_category();
        $this->browse(function (Browser $browser) {
            $browser->visit('/product/category')
                ->click('#DataTables_Table_0 > tbody > tr:nth-child(1) > td:nth-child(6) > div')
                ->click('#DataTables_Table_0 > tbody > tr:nth-child(1) > td:nth-child(6) > div > div > a.dropdown-item.edit_category')
                ->waitForText('Edit Category', 15000)
                ->type('#name', $this->faker->name)
                ->type('#commission_rate', '7')
                ->type('#icon', 'fab fa-acquisitions-incorporated')
                ->click('#category_edit_form > div > div > div:nth-child(1) > div:nth-child(3) > div > label')
                ->click('#category_edit_form > div > div > div:nth-child(1) > div.col-xl-12.mt-20 > div > ul > li:nth-child(2) > label > span')
                ->click('#category_edit_form > div > div > div:nth-child(1) > div:nth-child(6) > div > ul > li:nth-child(2) > label > span')
                ->attach('#image', __DIR__.'/files/default_category.png')
                ->click('#create_btn')
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Updated successfully!');
        });
    }

    public function test_for_delete_category(){
        $this->test_for_create_category();
        $this->browse(function (Browser $browser) {
            $browser->visit('/product/category')
                ->click('#DataTables_Table_0 > tbody > tr:nth-child(1) > td:nth-child(6) > div')
                ->click('#DataTables_Table_0 > tbody > tr:nth-child(1) > td:nth-child(6) > div > div > a.dropdown-item.delete_brand')
                ->whenAvailable('#item_delete_form', function($modal){
                    $modal->click('#dataDeleteBtn');
                })
                ->pause(6000)
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Deleted successfully!');
        });
    }

    public function test_for_view_category(){
        $this->test_for_create_category();
        $this->browse(function (Browser $browser) {
            $browser->visit('/product/category')
                ->click('#DataTables_Table_0 > tbody > tr:nth-child(1) > td:nth-child(6) > div')
                ->click('#DataTables_Table_0 > tbody > tr:nth-child(1) > td:nth-child(6) > div > div > a.dropdown-item.show_category')
                ->whenAvailable('#item_show > div > div > div.modal-header', function($modal){
                    $modal->assertSeeIn('h4', 'Show Category');
                });
        });
    }

    public function test_for_import_bulk(){
        $this->test_for_visit_index_page();
        $this->browse(function (Browser $browser) {
            $browser->click('#formHtml > div > ul > li > a')
                ->assertPathIs('/product/bulk-category-upload')
                ->assertSee('Bulk Category Upload')
                ->click('#add_product > section > div > div > div > div > form > small > small > div > div > button')
                ->assertPathIs('/product/bulk-category-upload')
                ->assertSeeIn('#add_product > section > div > div > div > div > form > div > div > div > small > small > span', 'The file field is required.')
                ->attach('#document_file_1', __DIR__.'/files/category.xlsx')
                ->click('#add_product > section > div > div > div > div > form > small > small > div > div > button')
                ->assertPathIs('/product/bulk-category-upload')
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Successfully Uploaded !!!'); 
        });
    }


}
