<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[12467]\\d{2,3}',
    'PossibleNumberPattern' => '\\d{3,4}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '[12467]\\d{2,3}',
    'PossibleNumberPattern' => '\\d{3,4}',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '[12467]\\d{2,3}',
    'PossibleNumberPattern' => '\\d{3,4}',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '7373',
    'PossibleNumberPattern' => '\\d{4}',
    'ExampleNumber' => '7373',
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
          1(?:
            1[58]|
            2[08]
          )
        ',
    'PossibleNumberPattern' => '\\d{3}',
    'ExampleNumber' => '118',
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
            1[58]|
            2(?:
              [158]|
              00?
            )|
            900
          )|
          2100|
          4878|
          6100|
          7(?:
            010|
            100|
            373
          )
        ',
    'PossibleNumberPattern' => '\\d{3,4}',
    'ExampleNumber' => '118',
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
  'id' => 'NI',
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
