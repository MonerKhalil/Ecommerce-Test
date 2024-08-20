<?php

use App\HelperClasses\ClassesStatic\MessagesFlash;
use App\HelperClasses\MyApp;
use Illuminate\Support\Facades\Session;


function filterDataRequest(){
    return  is_array(request('filter')) ? request('filter') : [];
}

function Error(){
    return Session::has(MessagesFlash::error)
        ? Session::get(MessagesFlash::error) : null;
}

function Success(){
    return Session::has(MessagesFlash::success)
        ? Session::get(MessagesFlash::success) : null;
}

if (!function_exists('user')) {
    /**
     * @return mixed
     */
    function user(): mixed
    {
        return MyApp::Classes()->getUser();
    }
}

if (!function_exists('uniqueSlug')){
    /**
     * @param string $title
     * @param $model
     * @param string $column
     * @return string
     * @author moner khalil
     */
    function uniqueSlug(string $title, $model, string $column = 'slug',$idIgnore = null): string
    {
        $slug = $title;

        $string = mb_strtolower($slug, "UTF-8");;
        $string = preg_replace("/[\/.]/", " ", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $slug = preg_replace("/[\s_]/", '-', $string);

        //get unique slug...
        $nSlug = $slug;
        $i = 0;

        if (!is_null($idIgnore)){
            $model = $model->whereNot("id",$idIgnore);
        }
        $model = $model->withoutGlobalScopes()->select([$column])->get();
        while (($model->where($column, '=', $nSlug)->count()) > 0) {
            $nSlug = $slug . '-' . ++$i;
        }

        return ($i > 0) ? substr($nSlug, 0, strlen($slug)) . '-' . $i : $slug;
    }
}
