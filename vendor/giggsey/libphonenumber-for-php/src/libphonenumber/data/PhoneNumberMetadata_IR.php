<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '
          [1-8]\\d{9}|
          9(?:
            [0-4]\\d{8}|
            9\\d{2,8}
          )
        ',
    'PossibleNumberPattern' => '\\d{4,10}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            1[137]|
            2[13-68]|
            3[1458]|
            4[145]|
            5[146-8]|
            6[146]|
            7[1467]|
            8[13467]
          )\\d{8}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '2123456789',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          9(?:
            0[12]|
            [1-3]\\d
          )\\d{7}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '9123456789',
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
    'NationalNumberPattern' => '
          (?:
            [2-6]0\\d|
            993
          )\\d{7}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '9932123456',
  ),
  'pager' => 
  array (
    'NationalNumberPattern' => '943\\d{7}',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '9432123456',
  ),
  'uan' => 
  array (
    'NationalNumberPattern' => '9990\\d{0,6}',
    'PossibleNumberPattern' => '\\d{4,10}',
    'ExampleNumber' => '9990123456',
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
  'id' => 'IR',
  'countryCode' => 98,
  'internationalPrefix' => '00',
  'nationalPrefix' => '0',
  'nationalPrefixForParsing' => '0',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(21)(\\d{3,5})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '21',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '[1-8]',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    2 => 
    array (
      'pattern' => '(\\d{3})(\\d{3})(\\d{3,4})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '9',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    3 => 
    array (
      'pattern' => '(\\d{3})(\\d{2})(\\d{2,3})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '9',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    4 => 
    array (
      'pattern' => '(\\d{3})(\\d{3})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '9',
      ),
      'nationalPrefixFormattingRule' => '0$1',
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
