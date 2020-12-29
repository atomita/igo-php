<?php
return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2'                             => true,
        '@PHP70Migration'                   => true,
        'array_indentation'                 => true,
        'array_syntax'                      => ["syntax" => "short"],
        'trailing_comma_in_multiline_array' => true,
        'binary_operator_spaces'            => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
        'pow_to_exponentiation' => false, // risky rule
        'random_api_migration'  => false,  // risky rule
    ]);
