<?php

namespace App\Models\Concerns;

trait ConvertsMarkdownToHtml

{
    protected static function bootConvertsMarkdownToHtml(){
        static::saving(function(self $model){
            $model->fill(['html' => str($model->body)->markdown([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
                'max_nesting_level' => 5,
            ])]);
        });
    }

}
