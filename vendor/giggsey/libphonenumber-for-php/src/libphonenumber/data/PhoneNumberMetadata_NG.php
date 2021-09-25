<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '
          [1-6]\\d{5,8}|
          9\\d{5,9}|
          [78]\\d{5,13}
        ',
    'PossibleNumberPattern' => '\\d{5,14}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          [12]\\d{6,7}|
          9(?:
            0[3-9]|
            [1-9]\\d
          )\\d{5}|
          (?:
            3\\d|
            4[023568]|
            5[02368]|
            6[02-469]|
            7[4-69]|
            8[2-9]
          )\\d{6}|
          (?:
            4[47]|
            5[14579]|
            6[1578]|
            7[0-357]
          )\\d{5,6}|
          (?:
            78|
            41
          )\\d{5}
        ',
    'PossibleNumberPattern' => '\\d{5,9}',
    'ExampleNumber' => '12345678',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          (?:
            1(?:
              7[34]\\d|
              8(?:
                04|
                [124579]\\d|
                8[0-3]
              )|
              95\\d
            )|
            287[0-7]|
            3(?:
              18[1-8]|
              88[0-7]|
              9(?:
                8[5-9]|
                6[1-5]
              )
            )|
            4(?:
              28[0-2]|
              6(?:
                7[1-9]|
                8[02-47]
              )|
              88[0-2]
            )|
            5(?:
              2(?:
                7[7-9]|
                8\\d
              )|
              38[1-79]|
              48[0-7]|
              68[4-7]
            )|
            6(?:
              2(?:
                7[7-9]|
                8\\d
              )|
              4(?:
                3[7-9]|
                [68][129]|
                7[04-69]|
                9[1-8]
              )|
              58[0-2]|
              98[7-9]
            )|
            7(?:
              38[0-7]|
              69[1-8]|
              78[2-4]
            )|
            8(?:
              28[3-9]|
              38[0-2]|
              4(?:
                2[12]|
                3[147-9]|
                5[346]|
                7[4-9]|
                8[014-689]|
                90
              )|
              58[1-8]|
              78[2-9]|
              88[5-7]
            )|
            98[07]\\d
          )\\d{4}|
          (?:
            70(?:
              [13-9]\\d|
              2[1-9]
            )|
            8(?:
              0[2-9]|
              1\\d
            )\\d|
            90[2359]\\d
          )\\d{6}
        ',
    'PossibleNumberPattern' => '\\d{8,10}',
    'ExampleNumber' => '8021234567',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '800\\d{7,11}',
    'PossibleNumberPattern' => '\\d{10,14}',
    'ExampleNumber' => '80017591759',
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
    'NationalNumberPattern' => '700\\d{7,11}',
    'PossibleNumberPattern' => '\\d{10,14}',
    'ExampleNumber' => '7001234567',
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
  'id' => 'NG',
  'countryCode' => 234,
  'internationalPrefix' => '009',
  'nationalPrefix' => '0',
  'nationalPrefixForParsing' => '0',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '([129])(\\d{3})(\\d{3,4})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '[129]',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(\\d{2})(\\d{3})(\\d{2,3})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            [3-6]|
            7(?:
              [1-79]|
              0[1-9]
            )|
            8[2-9]
          ',
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
        0 => '
            70|
            8[01]|
            90[2359]
          ',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    3 => 
    array (
      'pattern' => '([78]00)(\\d{4})(\\d{4,5})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '[78]00',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    4 => 
    array (
      'pattern' => '([78]00)(\\d{5})(\\d{5,6})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '[78]00',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    5 => 
    array (
      'pattern' => '(78)(\\d{2})(\\d{3})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '78',
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
  'mobileNumberPortableRegion' => true,
);
