<?php

class A_Reset_Ecommerce_Settings_Form extends Mixin
{
    function render()
    {
        // Get the rendered form
        $retval = $this->call_parent('render');

        // Inject a paragraph, warning the user that this includes all Pro and e-commerce settings
        $warning = esc_html__("Please note that this includes all NextGEN Pro settings, including ecommerce options.");
        if (($index = strrpos($retval, '</td>')) !== FALSE) {
            $beginning = substr($retval, 0, $index);
            $end       = substr($retval, $index);
            $retval     = "{$beginning}<p><em>{$warning}</em></p>{$end}";
        }

        return $retval;
    }
}