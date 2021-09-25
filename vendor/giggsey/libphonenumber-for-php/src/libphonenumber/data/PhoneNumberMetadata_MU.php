<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[2-9]\\d{6,7}',
    'PossibleNumberPattern' => '\\d{7,8}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            2(?:
              [03478]\\d|
              1[0-7]|
              6[1-69]
            )|
            4(?:
              [013568]\\d|
              2[4-7]
            )|
            5(?:
              44\\d|
              471
            )|
            6\\d{2}|
            8(?:
              14|
              3[129]
            )
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{7,8}',
    'ExampleNumber' => '2012345',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          5(?:
            2[59]\\d|
            4(?:
              2[1-389]|
              4\\d|
              7[1-9]|
              9\\d
            )|
            7\\d{2}|
            8(?:
              [2568]\\d|
              7[15-8]
            )|
            9[0-8]\\d
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '52512345',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '80[012]\\d{4}',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '8001234',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '30\\d{5}',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '3012345',
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
          3(?:
            20|
            9\\d
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '3201234',
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
  'id' => 'MU',
  'countryCode' => 230,
  'internationalPrefix' => '0(?:0|[2-7]0|33)',
  'preferredInternationalPrefix' => '020',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '([2-46-9]\\d{2})(\\d{4})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '[2-46-9]',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(5\\d{3})(\\d{4})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '5',
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
