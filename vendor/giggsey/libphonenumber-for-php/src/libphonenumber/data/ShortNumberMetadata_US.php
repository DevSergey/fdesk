<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[1-9]\\d{2,5}',
    'PossibleNumberPattern' => '\\d{3,6}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '[1-9]\\d{2,5}',
    'PossibleNumberPattern' => '\\d{3,6}',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '[1-9]\\d{2,5}',
    'PossibleNumberPattern' => '\\d{3,6}',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '611',
    'PossibleNumberPattern' => '\\d{3}',
    'ExampleNumber' => '611',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '
          2(?:
            4280|
            5209|
            7(?:
              449|
              663
            )
          )|
          3(?:
            2340|
            3786|
            5564|
            8(?:
              135|
              254
            )
          )|
          4(?:
            1(?:
              366|
              463
            )|
            3355|
            6(?:
              157|
              327
            )|
            7553|
            8(?:
              221|
              277
            )
          )|
          5(?:
            2944|
            4892|
            5928|
            9(?:
              187|
              342
            )
          )|
          69388|
          7(?:
            2(?:
              078|
              087
            )|
            3(?:
              288|
              909
            )|
            6426
          )|
          8(?:
            6234|
            9616
          )|
          9(?:
            5297|
            6(?:
              040|
              835
            )|
            7(?:
              294|
              688
            )|
            9(?:
              689|
              796
            )
          )
        ',
    'PossibleNumberPattern' => '\\d{3,6}',
    'ExampleNumber' => '24280',
  ),
  'sharedCost' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'personalNumber' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voip' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'pager' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'uan' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'emergency' => 
  array (
    'NationalNumberPattern' => '
          112|
          911
        ',
    'PossibleNumberPattern' => '\\d{3}',
    'ExampleNumber' => '911',
  ),
  'voicemail' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'shortCode' => 
  array (
    'NationalNumberPattern' => '
          1(?:
            1(?:
              2|
              5[1-47]|
              [68]\\d|
              7[0-57]|
              98
            )
          )|
          [2-9](?:
            11|
            \\d{4,5}
          )
        ',
    'PossibleNumberPattern' => '\\d{3,6}',
    'ExampleNumber' => '911',
  ),
  'standardRate' => 
  array (
    'NationalNumberPattern' => '
          2(?:
            3333|
            42242|
            56447|
            6688|
            75622
          )|
          3(?:
            1010|
            2665|
            7404
          )|
          40404|
          560560|
          6(?:
            0060|
            22639|
            5246|
            7622
          )|
          7(?:
            0701|
            3822|
            4666
          )|
          8(?:
            38255|
            4816|
            72265
          )|
          99099
        ',
    'PossibleNumberPattern' => '\\d{5,6}',
    'ExampleNumber' => '73822',
  ),
  'carrierSpecific' => 
  array (
    'NationalNumberPattern' => '
          33669|
          611
        ',
    'PossibleNumberPattern' => '\\d{3,5}',
    'ExampleNumber' => '33669',
  ),
  'noInternationalDialling' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'id' => 'US',
  'countryCode' => 0,
  'internationalPrefix' => '',
  'sameMobileAndFixedLinePattern' => true,
  'numberFormat' => 
  array (
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingZeroPossible' => false,
  'mobileNumberPortableRegion' => false,
);
