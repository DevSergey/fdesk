<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[2357-9]\\d{7}',
    'PossibleNumberPattern' => '\\d{8}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          2(?:
            0(?:
              1[0-6]|
              3[1-4]|
              [69]\\d
            )|
            [1-357]\\d{2}
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '21001234',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          (?:
            7(?:
              210|
              [79]\\d{2}
            )|
            9(?:
              2(?:
               1[01]|
               31
              )|
              696|
              8(?:
                1[1-3]|
                89|
                97
              )|
              9\\d{2}
            )
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '96961234',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '800[3467]\\d{4}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '80071234',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '
          5(?:
            0(?:
              0(?:
                37|
                43
              )|
              6\\d{2}|
              70\\d|
              9[0168]
            )|
            [12]\\d0[1-5]
          )\\d{3}
        ',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '50037123',
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
    'NationalNumberPattern' => '3550\\d{4}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '35501234',
  ),
  'pager' => 
  array (
    'NationalNumberPattern' => '7117\\d{4}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '71171234',
  ),
  'uan' => 
  array (
    'NationalNumberPattern' => '501\\d{5}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '50112345',
  ),
  'emergency' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voicemail' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'shortCode' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
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
  'id' => 'MT',
  'countryCode' => 356,
  'internationalPrefix' => '00',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(\\d{4})(\\d{4})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingZeroPossible' => false,
  'mobileNumberPortableRegion' => true,
);
