<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[1359]\\d{2,3}',
    'PossibleNumberPattern' => '\\d{3,4}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '[1359]\\d{2,3}',
    'PossibleNumberPattern' => '\\d{3,4}',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '[1359]\\d{2,3}',
    'PossibleNumberPattern' => '\\d{3,4}',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
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
            0(?:
              00|
              15|
              2[2-4679]
            )|
            1(?:
              1[0-35-9]|
              2|
              37|
              [46]6|
              7[57]|
              8[79]|
              9[0-379]
            )|
            2(?:
              00|
              [12]2|
              34|
              55
            )|
            3(?:
              21|
              33
            )|
            4(?:
              0[06]|
              1[4-6]
            )|
            5(?:
              15|
              5[15]
            )|
            693|
            7(?:
              00|
              1[789]|
              2[02]|
              [67]7
            )|
            975
          )|
          3855|
          5(?:
            0(?:
              30|
              49
            )|
            510
          )|
          911
        ',
    'PossibleNumberPattern' => '\\d{3,4}',
    'ExampleNumber' => '1022',
  ),
  'standardRate' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'carrierSpecific' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'noInternationalDialling' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'id' => 'CR',
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