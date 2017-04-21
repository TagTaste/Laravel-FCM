<?php


namespace App\Interfaces;


interface ProvidesModelOwner
{
    /**
     * Returns the owner of a model, either Profile or Company Model,
     * so that additional things (like posting to their feed)
     * can be done.
     *
     * @return mixed
     */
    public function owner();
}