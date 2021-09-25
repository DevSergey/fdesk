<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[02-8]\\d{4,6}',
    'PossibleNumberPattern' => '\\d{5,7}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            2\\d|
            3[1-8]|
            4[1-4]|
            [56]0|
            7[0149]|
            8[05]
          )\\d{3}
        ',
    'PossibleNumberPattern' => '\\d{5}',
    'ExampleNumber' => '20123',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          (?:
            7[578]|
            8[47-9]
          )\\d{5}
        ',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '7715123',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '0800\\d{3}',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '0800222',
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
  'id' => 'TO',
  'countryCode' => 676,
  'internationalPrefix' => '00',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(\\d{2})(\\d{3})',
      'format' => '$1-$2',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            [1-6]|
            7[0-4]|
            8[05]
          ',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(\\d{3})(\\d{4})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            7[5-9]|
            8[47-9]
          ',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    2 => 
    array (
      'pattern' => '(\\d{4})(\\d{3})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '0',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingZeroPossible' => true,
  'mobileNumberPortableRegion' => false,
);
