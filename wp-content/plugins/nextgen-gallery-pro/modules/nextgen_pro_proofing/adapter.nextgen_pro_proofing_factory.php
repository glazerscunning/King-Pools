<?php

class A_NextGen_Pro_Proofing_Factory extends Mixin
{
    function nextgen_proof($properties = array(), $mapper = FALSE, $context = FALSE)
    {
        return new C_NextGen_Pro_Proofing($properties, $mapper, $context);
    }
}