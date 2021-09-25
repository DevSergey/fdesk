<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '
          6\\d{8}|
          [23789]\\d{6}
        ',
    'PossibleNumberPattern' => '\\d{7,9}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            2(?:
              01|
              1[27]|
              3\\d|
              6[02-578]|
              96
            )|
            3(?:
              7[0135-7]|
              8[048]|
              9[0269]
            )
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '2345678',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          6(?:
            51[01]|
            6(?:
              0[0-6]|
              2[016-9]|
              39
            )
          )\\d{5}|
          7(?:
            [37-9]\\d|
            42|
            56
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{7,9}',
    'ExampleNumber' => '660234567',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '
          80(?:
            02[28]|
            9\\d{2}
          )\\d{2}
        ',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '8002222',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '
          90(?:
            02[258]|
            1(?:
              23|
              3[14]
            )|
            66[136]
          )\\d{2}
        ',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '9002222',
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
    'NationalNumberPattern' => '
          870(?:
            28|
            87
          )\\d{2}
        ',
    'PossibleNumberPattern' => '\\d{7}',
    'ExampleNumber' => '8702812',
  ),
  'emergency' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voicemail' => 
  array (
    'NationalNumberPattern' => '
          697(?:
            42|
            56|
            [7-9]\\d
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{9}',
    'ExampleNumber' => '697861234',
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
  'id' => 'LI',
  'countryCode' => 423,
  'internationalPrefix' => '00',
  'nationalPrefix' => '0',
  'nationalPrefixForParsing' => '0',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(\\d{3})(\\d{4})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '[23789]',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(\\d{3})(\\d{3})(\\d{3})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '6[56]',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    2 => 
    array (
      'pattern' => '(69)(7\\d{2})(\\d{4})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '697',
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