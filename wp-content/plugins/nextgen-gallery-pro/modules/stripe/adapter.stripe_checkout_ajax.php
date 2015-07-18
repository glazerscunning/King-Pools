<?php

class A_Stripe_Checkout_Ajax extends Mixin
{
    function stripe_checkout_action()
    {
        $retval = array();

        $checkout = C_NextGen_Pro_Checkout::get_instance();
        return $checkout->create_stripe_charge();
    }
}