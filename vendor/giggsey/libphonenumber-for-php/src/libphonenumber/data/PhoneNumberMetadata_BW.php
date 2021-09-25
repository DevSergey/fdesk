<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[2-79]\\d{6,7}',
    'PossibleNumberPattern' => '\\d{7,8}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            2(?:
              4[0-48]|
              6[0-24]|
              9[0578]
            )|
            3(?:
              1[0235-9]|
              55|
              6\\d|
              7[01]|
              9[0-57]
            )|
            4(?:
              6[03]|
              7[1267]|
              9[0-5]
            )|
            5(?:
              3[0389]|
              4[0489]|
              7[1-47]|
              88|
              9[0-49]
            )|
            6(?:
              2[1-35]|
              5[149]|
              8[067]
            )
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '2401234',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          7(?:
            [1-356]\\d|
            4[0-7]|
            7[014-7]
          )\\d{5}
        ',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '71123456',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '90\\d{5}',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '9012345',
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
    'NationalNumberPattern' => '79[12][01]\\d{4}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '79101234',
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
  'id' => 'BW',
  'countryCode' => 267,
  'internationalPrefix' => '00',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(\\d{3})(\\d{4})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '[2-6]',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(7\\d)(\\d{3})(\\d{3})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '7',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    2 => 
    array (
      'pattern' => '(90)(\\d{5})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '9',
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
  'mobileNumberPortableRegion' => false,
);
