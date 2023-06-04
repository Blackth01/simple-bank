<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('real_name', function ($attribute, $value) {
            $names = explode(" ", $value);

            foreach($names as $name){

                #Verifying if the name doesn't contain numbers
                if(preg_match('~[0-9]+~', $name)){
                    return False;
                }
                #Verifying if the name has the first letter capitalized and all the other letters aren't capittalized
                else if($name !== ucfirst(strtolower($name))){ 
                    if(!in_array($name, array("do","da","dos","de"))){
                        return False;
                    }
                }
            }

            return True;
        });
    }
}
