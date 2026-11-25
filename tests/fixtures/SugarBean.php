<?php

// The SugarBean of SuiteCRM, as far as the hook sees it, for the tests only
// (SuiteCRM is not installed here): a record whose fields are properties.

if (!class_exists('SugarBean', false)) {
    #[AllowDynamicProperties]
    class SugarBean {}
}
