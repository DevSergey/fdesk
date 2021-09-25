<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[23467]\\d{7}',
    'PossibleNumberPattern' => '\\d{7,8}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            2(?:
              2\\d{2}|
              5(?:11|[258]\\d|9[67])|
              6(?:12|2\\d|9[34])|
              8(?:2[34]|39|62)
            )|
            3(?:
              3\\d{2}|
              4(?:6\\d|8[24])|
              8(?:25|42|5[257]|86|9[25])|
              9(?:2\\d|3[234]|4[248]|5[24]|6[2-6]|7\\d)
            )|
            4(?:
              4\\d{2}|
              6(?:11|[24689]\\d|72)
            )
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{7,8}',
    'ExampleNumber' => '22123456',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '[67]\\d{7}',
    'PossibleNumberPattern' => '\\d{8}',
    'ExampleNumber' => '71234567',
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
  'id' => 'BO',
  'countryCode' => 591,
  'internationalPrefix' => '00(1\\d)?',
  'nationalPrefix' => '0',
  'nationalPrefixForParsing' => '0(1\\d)?',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '([234])(\\d{7})',
      'format' => '$1 $2',
      'leadingDigitsPatterns' => 
      array (
        0 => '[234]',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '0$CC $1',
    ),
    1 => 
    array (
      'pattern' => '([67]\\d{7})',
      'format' => '$1',
      'leadingDigitsPatterns' => 
      array (
        0 => '[67]',
      ),
      'nationalPrefixFormattingRule' => '',
      'domesticCarrierCodeFormattingRule' => '0$CC $1',
    ),
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingZeroPossible' => false,
  'mobileNumberPortableRegion' => false,
);
