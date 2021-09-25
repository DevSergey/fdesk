<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '
          [1-4]\\d{8}|
          [89]\\d{9,10}
        ',
    'PossibleNumberPattern' => '\\d{7,11}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            1(?:
              5(?:
                1[1-5]|
                [24]\\d|
                6[2-4]|
                9[1-7]
              )|
              6(?:
                [235]\\d|
                4[1-7]
              )|
              7\\d{2}
            )|
            2(?:
              1(?:
                [246]\\d|
                3[0-35-9]|
                5[1-9]
              )|
              2(?:
                [235]\\d|
                4[0-8]
              )|
              3(?:
                [26]\\d|
                3[02-79]|
                4[024-7]|
                5[03-7]
              )
            )
          )\\d{5}
        ',
    'PossibleNumberPattern' => '\\d{7,9}',
    'ExampleNumber' => '152450911',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          (?:
            2(?:
              5[5679]|
              9[1-9]
            )|
            33\\d|
            44\\d
          )\\d{6}
        ',
    'PossibleNumberPattern' => '\\d{9}',
    'ExampleNumber' => '294911911',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '
          8(?:
            0[13]|
            20\\d
          )\\d{7}
        ',
    'PossibleNumberPattern' => '\\d{10,11}',
    'ExampleNumber' => '8011234567',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '
          (?:
            810|
            902
          )\\d{7}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '9021234567',
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
    'NationalNumberPattern' => '249\\d{6}',
    'PossibleNumberPattern' => '\\d{9}',
    'ExampleNumber' => '249123456',
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
    'NationalNumberPattern' => '
          8(?:
            [013]|
            [12]0
          )\\d{8}|
          902\\d{7}
        ',
    'PossibleNumberPattern' => '\\d{10,11}',
    'ExampleNumber' => '82012345678',
  ),
  'id' => 'BY',
  'countryCode' => 375,
  'internationalPrefix' => '810',
  'preferredInternationalPrefix' => '8~10',
  'nationalPrefix' => '8',
  'nationalPrefixForParsing' => '8?0?',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
      'format' => '$1 $2-$3-$4',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            17[0-3589]|
            2[4-9]|
            [34]
          ',
        1 => '
            17(?:
              [02358]|
              1[0-2]|
              9[0189]
            )|
            2[4-9]|
            [34]
          ',
      ),
      'nationalPrefixFormattingRule' => '8 0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(\\d{3})(\\d{2})(\\d{2})(\\d{2})',
      'format' => '$1 $2-$3-$4',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            1(?:
              5[24]|
              6[235]|
              7[467]
            )|
            2(?:
              1[246]|
              2[25]|
              3[26]
            )
          ',
        1 => '
            1(?:
              5[24]|
              6(?:
                2|
                3[04-9]|
                5[0346-9]
              )|
              7(?:
                [46]|
                7[37-9]
              )
            )|
            2(?:
              1[246]|
              2[25]|
              3[26]
            )
          ',
      ),
      'nationalPrefixFormattingRule' => '8 0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    2 => 
    array (
      'pattern' => '(\\d{4})(\\d{2})(\\d{3})',
      'format' => '$1 $2-$3',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            1(?:
              5[169]|
              6[3-5]|
              7[179]
            )|
            2(?:
              1[35]|
              2[34]|
              3[3-5]
            )
          ',
        1 => '
            1(?:
              5[169]|
              6(?:
                3[1-3]|
                4|
                5[125]
              )|
              7(?:
                1[3-9]|
                7[0-24-6]|
                9[2-7]
              )
            )|
            2(?:
              1[35]|
              2[34]|
              3[3-5]
            )
          ',
      ),
      'nationalPrefixFormattingRule' => '8 0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    3 => 
    array (
      'pattern' => '([89]\\d{2})(\\d{3})(\\d{4})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            8[01]|
            9
          ',
      ),
      'nationalPrefixFormattingRule' => '8 $1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    4 => 
    array (
      'pattern' => '(8\\d{2})(\\d{4})(\\d{4})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '82',
      ),
      'nationalPrefixFormattingRule' => '8 $1',
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
