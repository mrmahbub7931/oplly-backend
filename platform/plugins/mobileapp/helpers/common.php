<?php

if (!function_exists('render_mobileapp_subscribe_form')) {
    /**
     * @return string
     * @throws Throwable
     */
    function render_mobileapp_subscribe_form(array $hiddenFields = [])
    {
        return view('plugins/mobileapp::partials.form', compact('hiddenFields'))->render();
    }
}
