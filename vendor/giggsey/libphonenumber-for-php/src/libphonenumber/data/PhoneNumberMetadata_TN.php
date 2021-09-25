<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[2-57-9]\\d{7}',
    'PossibleNumberPattern' => '\\d{8}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          3[012]\\d{6}|
          7\\d{7}|
          81200\\d{3}
        ',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '71234567',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          (?:
            [259]\\d|
            4[0-24]
          )\\d{6}
        ',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '20123456',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '8010\\d{4}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '80101234',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '88\\d{6}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '88123456',
  ),
  'sharedCost' => 
  array (
    'NationalNumberPattern' => '8[12]10\\d{4}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '81101234',
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
  'id' => 'TN',
  'countryCode' => 216,
  'internationalPrefix' => '00',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(\\d{2})(\\d{3})(\\d{3})',
      'format' => '$1 $2 $3',
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
  'mobileNumberPortableRegion' => false,
);
