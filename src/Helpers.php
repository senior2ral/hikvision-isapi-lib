<?php
namespace Hikvision;

use SimpleXMLElement;

class Helpers
{
    /**
     * List of supported countries
     *
     * @var array
     */
    public static $countries = [
        0   => 'Not supported by the algorithm',
        1   => 'Czech Republic',
        2   => 'France',
        3   => 'Germany',
        4   => 'Spain',
        5   => 'Italy',
        6   => 'Netherlands',
        7   => 'Poland',
        8   => 'Slovakia',
        9   => 'Belarus',
        10  => 'Moldova',
        11  => 'Russia',
        12  => 'Ukraine',
        13  => 'Belgium',
        14  => 'Bulgaria',
        15  => 'Denmark',
        16  => 'Finland',
        17  => 'United Kingdom',
        18  => 'Greece',
        19  => 'Croatia',
        20  => 'Hungary',
        21  => 'Israel',
        22  => 'Luxembourg',
        23  => 'Macedonia (changed to North Macedonia in 2018)',
        24  => 'Norway',
        25  => 'Portugal',
        26  => 'Romania',
        27  => 'Serbia',
        28  => 'Azerbaijan',
        29  => 'Georgia',
        30  => 'Kazakhstan',
        31  => 'Lithuania',
        32  => 'Turkmenistan',
        33  => 'Uzbekistan',
        34  => 'Latvia',
        35  => 'Estonia',
        36  => 'Albania',
        37  => 'Austria',
        38  => 'Bosnia and Herzegovina',
        39  => 'Ireland',
        40  => 'Iceland',
        41  => 'Vatican',
        42  => 'Malta',
        43  => 'Sweden',
        44  => 'Switzerland',
        45  => 'Cyprus',
        46  => 'Turkey',
        47  => 'Slovenia',
        48  => 'Montenegro',
        49  => 'Kosovo',
        50  => 'Andorra',
        51  => 'Armenia',
        52  => 'Monaco',
        53  => 'Liechtenstein',
        54  => 'San Marino',
        55  => 'Reserved',
        56  => 'Reserved',
        57  => 'Reserved',
        58  => 'Reserved',
        59  => 'China',
        60  => 'Bahrain',
        61  => 'South Korea',
        62  => 'Lebanon',
        63  => 'Nepal',
        64  => 'Thailand',
        65  => 'Pakistan',
        66  => 'United Arab Emirates',
        67  => 'Bhutan',
        68  => 'Oman',
        69  => 'North Korea',
        70  => 'Philippines',
        71  => 'Cambodia',
        72  => 'Qatar',
        73  => 'Kyrgyzstan',
        74  => 'Maldives',
        75  => 'Malaysia',
        76  => 'Mongolia',
        77  => 'Saudi Arabia',
        78  => 'Brunei',
        79  => 'Laos',
        80  => 'Japan',
        81  => 'Turkey',
        82  => 'Palestinian',
        83  => 'Tajikistan',
        84  => 'Kuwait',
        85  => 'Syria',
        86  => 'India',
        87  => 'Indonesia',
        88  => 'Afghanistan',
        89  => 'Sri Lanka',
        90  => 'Iraq',
        91  => 'Vietnam',
        92  => 'Iran',
        93  => 'Yemen',
        94  => 'Jordan',
        95  => 'Myanmar',
        96  => 'Sikkim',
        97  => 'Bangladesh',
        98  => 'Singapore',
        99  => 'Democratic Republic of Timor-Leste',
        100 => 'Reserved',
        101 => 'Reserved',
        102 => 'Reserved',
        103 => 'Reserved',
        104 => 'Egypt',
        105 => 'Libya',
        106 => 'Sudan',
        107 => 'Tunisia',
        108 => 'Algeria',
        109 => 'Morocco',
        110 => 'Ethiopia',
        111 => 'Eritrea',
        112 => 'Somalia Democratic',
        113 => 'Djibouti',
        114 => 'Kenya',
        115 => 'Tanzania',
        116 => 'Uganda',
        117 => 'Rwanda',
        118 => 'Burundi',
        119 => 'Seychelles',
        120 => 'Chad',
        121 => 'Central African',
        122 => 'Cameroon',
        123 => 'Equatorial Guinea',
        124 => 'Gabon',
        125 => 'Congo',
        126 => 'Democratic Republic of the Congo',
        127 => 'Sao Tome and Principe',
        128 => 'Mauritania',
        129 => 'Western Sahara',
        130 => 'Senegal',
        131 => 'Gambia',
        132 => 'Mali',
        133 => 'Burkina Faso',
        134 => 'Guinea',
        135 => 'Guinea-Bissau',
        136 => 'Cape Verde',
        137 => 'Sierra Leone',
        138 => 'Liberia',
        139 => 'Ivory Coast',
        140 => 'Ghana',
        141 => 'Togo',
        142 => 'Benin',
        143 => 'Niger',
        144 => 'Zambia',
        145 => 'Angola',
        146 => 'Zimbabwe',
        147 => 'Malawi',
        148 => 'Mozambique',
        149 => 'Botswana',
        150 => 'Namibia',
        151 => 'South Africa',
        152 => 'Swaziland',
        153 => 'Lesotho',
        154 => 'Madagascar',
        155 => 'Comoros',
        156 => 'Mauritius',
        157 => 'Nigeria',
        158 => 'South Sudan',
        159 => 'Saint Helena',
        160 => 'Mayotte',
        161 => 'Reunion',
        162 => 'Canary Islands',
        163 => 'Azores',
        164 => 'Madeira',
        165 => 'Reserved',
        166 => 'Reserved',
        167 => 'Reserved',
        168 => 'Reserved',
        169 => 'Canada',
        170 => 'Greenland Nuuk',
        171 => 'Pierre and Miquelon',
        172 => 'United States',
        173 => 'Bermuda',
        174 => 'Mexico',
        175 => 'Guatemala',
        176 => 'Belize',
        177 => 'El Salvador',
        178 => 'Honduras',
        179 => 'Nicaragua',
        180 => 'Costa Rica',
        181 => 'Panama',
        182 => 'Bahamas',
        183 => 'Turks and Caicos Islands',
        184 => 'Cuba',
        185 => 'Jamaica',
        186 => 'Cayman Islands',
        187 => 'Haiti',
        188 => 'Dominican Republic',
        189 => 'Puerto Rico',
        190 => 'United States Virgin Islands',
        191 => 'British Virgin Islands',
        192 => 'Anguilla',
        193 => 'Antigua and Barbuda',
        194 => 'Collectivité de Saint-Martin',
        195 => 'Autonomous country',
        196 => 'Saint-Barthélemy',
        197 => 'Saint Kitts and Nevis',
        198 => 'Montserrat',
        199 => 'Guadeloupe',
        200 => 'Dominica',
        201 => 'Martinique',
        202 => 'St. Lucia',
        203 => 'Saint Vincent and the Grenadines',
        204 => 'Grenada',
        205 => 'Barbados',
        206 => 'Trinidad and Tobago',
        207 => 'Curaçao',
        208 => 'Aruba',
        209 => 'Netherlands Antilles',
        210 => 'Colombia',
        211 => 'Venezuela',
        212 => 'Guyana',
        213 => 'Suriname',
        214 => 'Guyane Francaise',
        215 => 'Ecuador',
        216 => 'Peru',
        217 => 'Bolivia',
        218 => 'Paraguay',
        219 => 'Chile',
        220 => 'Brazil',
        221 => 'Uruguay',
        222 => 'Argentina',
        223 => 'Reserved',
        224 => 'Reserved',
        225 => 'Reserved',
        226 => 'Reserved',
        227 => 'Australia',
        228 => 'New Zealand',
        229 => 'Papua New Guinea',
        230 => 'Solomon Islands',
        231 => 'Vanuatu',
        232 => 'New Caledonia',
        233 => 'Palau',
        234 => 'Federated States of Micronesia',
        235 => 'Marshall Islands',
        236 => 'Northern Mariana Islands',
        237 => 'Guam',
        238 => 'Nauru',
        239 => 'Kiribati',
        240 => 'Fiji',
        241 => 'Tonga',
        242 => 'Tuvalu',
        243 => 'Wallis et Futuna',
        244 => 'Samoa',
        245 => 'Eastern Samoa',
        246 => 'Tokelau',
        247 => 'Niue',
        248 => 'Cook Islands',
        249 => 'French Polynesia',
        250 => 'Pitcairn Islands',
        251 => 'Hawaii State',
        252 => 'Reserved',
        253 => 'Reserved',
        254 => 'Unrecognized',
        255 => 'ALL',
        256 => 'Taiwan (China)',
        257 => 'Hong Kong (China)',
        258 => 'Macau (China)',
    ];

    /**
     * List of supported vehicle brands
     *
     * @var array
     */
    public static $brands = [
        1026 => 'ALFAROMEO',
        1027 => 'ASTONMARTIN',
        1028 => 'AUDI',
        1030 => 'PORSCHE',
        1031 => 'BUICK',
        1032 => 'BJQICHE',
        1033 => 'BQZHIDAO',
        1034 => 'BQWEIWANG',
        1035 => 'BQYINXIANG',
        1036 => 'BENZ',
        1037 => 'BMW',
        1038 => 'BAOJUN',
        1039 => 'BAOLONG',
        1040 => 'BENTLEY',
        1041 => 'BRABUS',
        1043 => 'HONDA',
        1044 => 'PEUGEOT',
        1045 => 'BYD',
        1046 => 'CHANGHE',
        1048 => 'GREATWALL',
        1049 => 'CHANGAN',
        1050 => 'DS',
        1051 => 'SOUEAST',
        1053 => 'VOLKSWAGEN',
        1054 => 'DADI',
        1056 => 'DODGE',
        1059 => 'DAIHATSU',
        1060 => 'TOYOTA',
        1063 => 'FEREARI',
        1064 => 'FORD',
        1066 => 'FUDI',
        1067 => 'FIAT',
        1069 => 'MITSUOKA',
        1070 => 'GZYUNBAO',
        1071 => 'GQCHUANQI',
        1074 => 'QOROS',
        1076 => 'HUAPU',
        1077 => 'HUATAI',
        1078 => 'HAFEI',
        1079 => 'HUMMER',
        1080 => 'HAIMA',
        1081 => 'HONGQI',
        1083 => 'GEELYAUTO',
        1084 => 'JEEP',
        1085 => 'JAGUAR',
        1086 => 'JIANGNAN',
        1088 => 'CHRYSLER',
        1089 => 'CADILLAC',
        1091 => 'KANDIONE',
        1093 => 'LAMBORGHINI',
        1094 => 'LIFAN',
        1095 => 'ROLLSROYCE',
        1096 => 'LINCOLN',
        1097 => 'EVERUS',
        1098 => 'LIANHUA',
        1100 => 'LOTUS',
        1101 => 'LANDROVER',
        1102 => 'SUZUKI',
        1103 => 'LUFENG',
        1104 => 'LEXUS',
        1105 => 'RENAULT',
        1107 => 'MINI',
        1108 => 'MASERATI',
        1109 => 'MEIYA',
        1110 => 'MCLAREN',
        1111 => 'MAYBACH',
        1112 => 'MAZDA',
        1114 => 'LUXGEN',
        1115 => 'NJJINLONG',
        1116 => 'OPEL',
        1117 => 'ACURA',
        1119 => 'VENUCIA',
        1120 => 'CHERY',
        1121 => 'KIA',
        1123 => 'NISSAN',
        1124 => 'RUIQI',
        1125 => 'ROEWE',
        1127 => 'SMART',
        1128 => 'MITSUBISHI',
        1129 => 'SQDATONG',
        1131 => 'SHUANGHUAN',
        1132 => 'SHUANGLONG',
        1133 => 'SUBARU',
        1134 => 'SKODA',
        1135 => 'SAAB',
        1138 => 'TIANMA',
        1139 => 'TEALA',
        1141 => 'DENZA',
        1143 => 'WEILIN',
        1144 => 'VOLVO',
        1145 => 'WCYINGZHI',
        1146 => 'XINKAI',
        1147 => 'XINDADI',
        1148 => 'XINYATU',
        1149 => 'HYUNDAI',
        1150 => 'SEAT',
        1151 => 'CHEVROLET',
        1152 => 'CITROEN',
        1154 => 'YONGYUAN',
        1156 => 'INFINITI',
        1157 => 'MUSTANG',
        1159 => 'YUJIE',
        1160 => 'ZXAUTO',
        1161 => 'ZHONGHUA',
        1163 => 'ZOTYE',
        1164 => 'KNOWBEANS',
        1165 => 'KAIYI',
        1166 => 'HUASONG',
        1167 => 'JXWUSHILING',
        1168 => 'BORGWARD',
        1169 => 'SQTONGJIA',
        1170 => 'HANJIANG',
        1171 => 'ZINORO',
        1172 => 'LUDIFANGZHOU',
        1173 => 'HANTENG',
        1175 => 'CHANGJIANG',
        1176 => 'SWM',
        1177 => 'KEYTON',
        1180 => 'BISU',
        1181 => 'CAKUAYUE',
        1537 => 'ANKAI',
        1538 => 'ANYUAN',
        1540 => 'BBZHONGQI',
        1546 => 'CHENGGONG',
        1547 => 'CHANGLONG',
        1549 => 'CASHANGYONG',
        1552 => 'DONGFENG',
        1554 => 'DAEWOO',
        1555 => 'DAYUN',
        1556 => 'DIMA',
        1557 => 'DONGWO',
        1559 => 'FUTIAN',
        1561 => 'GMC',
        1562 => 'GQJIAO',
        1566 => 'HUALING',
        1570 => 'HUIZHONG',
        1571 => 'HIGER',
        1574 => 'HTYUANTONG',
        1575 => 'HANGTIAN',
        1576 => 'HUANGHAI',
        1577 => 'HEIBAO',
        1578 => 'JIULONG',
        1579 => 'JIANGHUAI',
        1580 => 'JIANGHUAN',
        1581 => 'JIANGLING',
        1584 => 'JINBEI',
        1585 => 'JINLONG',
        1586 => 'KAIMA',
        1587 => 'KAWEI',
        1588 => 'KAIRUI',
        1590 => 'LIANHE',
        1592 => 'MAN',
        1594 => 'NONGYONGCHE',
        1596 => 'NANJUN',
        1597 => 'QINGLING',
        1598 => 'YOUNGMANONE',
        1599 => 'SYZHONGGONG',
        1600 => 'SHSHITONG',
        1602 => 'TRICYCLE',
        1603 => 'SQYWKHY',
        1606 => 'SHAOLIN',
        1608 => 'SHIFENG',
        1609 => 'SUNWIN',
        1611 => 'SHENYE',
        1612 => 'SHUCHI',
        1613 => 'SHANQI',
        1614 => 'SCANIA',
        1615 => 'TANGJUN',
        1619 => 'WANFENG',
        1620 => 'WUZHENG',
        1621 => 'WULING',
        1626 => 'XUGONG',
        1629 => 'FAW',
        1630 => 'YAXING',
        1631 => 'IVECO',
        1633 => 'YUTONG',
        1634 => 'YANGZI',
        1635 => 'YANTAI',
        1636 => 'YUEJIN',
        1637 => 'YINTIAN',
        1639 => 'ZGZHONGQI',
        1641 => 'ZHONGTONGONE',
        1642 => 'ZHONGSHUN',
        1644 => 'ZHONGDA',
        1646 => 'JGZHONGKA',
        1647 => 'WUZHOULONG',
        1648 => 'COACH',
        1651 => 'PICKUP',
        1654 => 'JIJIANG',
        1674 => 'DONGFANGHONG',
        1676 => 'QINGQI',
        1677 => 'TRUCK',
        1678 => 'SPYCAR',
        1679 => 'TRAILCAR',
        1683 => 'GUILIN',
        1684 => 'SCHYUNDAI',
        1688 => 'WANXIANG',
        1690 => 'LFSHIJUN',
        1691 => 'CHANGAN',
        1692 => 'ZLZHONGGONG',
        1693 => 'YINLONG',
        1695 => 'YIXING',
        1696 => 'XIWO',
        1697 => 'YANGZIJIANG',
        1698 => 'SUITONG',
        1702 => 'ZHONGTIANFC',
        1703 => 'WANDA',
        1704 => 'SHANGRAO',
        1705 => 'ZHONGZHI',
        1706 => 'ZCSDDIANDONG',
        1707 => 'ZHONGTONGTWO',
        1708 => 'GLCOACH',
        1709 => 'BEIJING',
        1710 => 'BEIFANG',
        1711 => 'BFNAPULAN',
        1712 => 'HUACHUAN',
        1713 => 'YOUYI',
        1714 => 'TONGXIN',
        1715 => 'MG',
        1716 => 'JIACHUAN',
        1717 => 'NVSHEN',
        1718 => 'SHILI',
        1719 => 'SHAOLINTWO',
        1720 => 'CHUANJIAO',
        1721 => 'CHUANMA',
        1722 => 'GUANGQI',
        1723 => 'GQRIYE',
        1724 => 'KANGDITWO',
        1725 => 'HENGTIAN',
        1726 => 'HENGTONG',
        1727 => 'XINFUDA',
        1728 => 'XINLONGMA',
        1729 => 'CHUANLAN',
        1730 => 'CHUFENG',
        1731 => 'JMCJM',
        1732 => 'JMCZQ',
        1733 => 'HAIOU',
        1734 => 'MUDAN',
        1735 => 'LEOPAARD',
        1736 => 'SHENLONG',
        1737 => 'FOTONSD',
        1738 => 'HONGXING',
        1739 => 'SHUCHITWO',
        1740 => 'SHUDU',
        1741 => 'HENGSHAN',
        1742 => 'YUEXI',
        1743 => 'YUANCHENG',
        1744 => 'JINLV',
        1745 => 'CAOUSHANG',
        1746 => 'YOUNGMANTWO',
        1747 => 'LINK',
        1748 => 'FEIDIE',
        1749 => 'FEICHI',
        1750 => 'LISHAN',
        1751 => 'JUNWEI',
        1752 => 'NANQI',
        1753 => 'DAHAN',
        1754 => 'CHUNZHOU',
        1755 => 'DIANKA',
        1756 => 'HTWANSHAN',
        1757 => 'ZOBENZFC',
        1758 => 'YUNDU',
        1759 => 'JUNMA',
        1760 => 'GUOJIN',
        1761 => 'WEIMA',
        1762 => 'OULA',
        1763 => 'LIBANG',
        1764 => 'SXCJ',
        1765 => 'HYUNDAI',
        1766 => 'FEIYE',
        1767 => 'YUNSHI',
        1768 => 'HONGCHUAN',
        1769 => 'SHAOQI',
        1770 => 'DONGFENG',
        1771 => 'HONGXINGSHI',
        1772 => 'HONGTIAN',
        1773 => 'QIUJIA',
        1774 => 'JINGZHOU',
        1775 => 'HONGSHUN',
        1776 => 'JINHUA',
        1777 => 'FUKANG',
        1778 => 'SANLU',
        1779 => 'LANGJUN',
        1780 => 'YUTONG',
        1781 => 'YOULE',
        1782 => 'YUXI',
        1783 => 'YINWEI',
        1784 => 'HONGJI',
        1785 => 'HUANGYUN',
        1786 => 'XINKAI',
        1787 => 'QIAOQI',
        1788 => 'DAIJIANG',
        1789 => 'ZHONGTONG',
        1790 => 'CHONGJI',
        1791 => 'ZHONGZHEN',
        1792 => 'JIMI',
        1793 => 'SHUXIN',
        1794 => 'HELI',
        1795 => 'CHONGFEI',
        1796 => 'GAOLIANG',
        1797 => 'XIONGFENG',
        1798 => 'YIJIE',
        1799 => 'TONGXIAN',
        1800 => 'SHUNYI',
        1801 => 'SHANTAI',
        1802 => 'TAIZHOU',
        1803 => 'QINGLONG',
        1804 => 'XINGHAO',
        1805 => 'ZHONGYONG',
        1806 => 'HONGYUAN',
        1807 => 'GELI',
        1808 => 'GAOYUN',
        1809 => 'BOYUE',
        1810 => 'SHUANGSHAN',
        1811 => 'YUNFENG',
        1812 => 'YINYUE',
        1813 => 'QUANRONG',
        1814 => 'ZHEJIANG',
        1815 => 'PARKING',
        1816 => 'PICKUPTRUCK',
        1817 => 'SUV',
        1818 => 'WAGON',
        1819 => 'CROSSOVER',
        1820 => 'VAN',
        1821 => 'MOTORCYCLE',
        1822 => 'TRICYCLE',
        1823 => 'EV',
        1824 => 'LORRY',
        1825 => 'FORKLIFT',
        1826 => 'BICYCLE',
        1827 => 'BIDIRECTIONAL',
    ];

    /**
     * Retrieve the brand at the specified index.
     *
     * Checks the provided index in the `brands` array and returns the corresponding brand.
     * If no brand is found, it returns 'UNKNOWN'.
     *
     * @param  integer $index The index to fetch the brand from.
     * @return string The brand name or 'UNKNOWN' if the index is invalid.
     */
    public static function getBrand($index = 0)
    {
        if ($brand = self::$brands[$index]) {
            return $brand;
        }

        return 'UNKNOWN';
    }

    /**
     * Retrieve the country at the specified index.
     *
     * Checks the provided index in the `countries` array and returns the corresponding country.
     * If no country is found, it returns 'UNKNOWN'.
     *
     * @param  integer $index The index to fetch the country from.
     * @return string The country name or 'UNKNOWN' if the index is invalid.
     */
    public static function getCountry($index = 0)
    {
        if ($country = self::$countries[$index]) {
            return $country;
        }

        return 'UNKNOWN';
    }

    /**
     * Delivery data to the given URL
     *
     * This method sends the provided data as a POST request to the specified URL.
     * If a response is returned, it decodes the JSON response and returns it.
     *
     * @param string $url Target URL
     * @param array $data Payload data
     * @return array Response data
     * @throws Exception If curl errors occur
     */
    public static function delivery($url, $data = [])
    {
        $ch          = curl_init();
        $curlOptions = [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 300,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_USERAGENT      => 'HikvisionApi/0.1',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)),
            ],
            CURLOPT_POSTFIELDS     => json_encode($data),
        ];

        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            return [
                'status'      => 'error',
                'description' => $error,
            ];
        }

        if (Helpers::isJson($response)) {
            return json_decode($response, true);
        }

        return [];
    }

    /**
     * getFilesByName
     *
     * @param  array $files
     * @return array
     */
    public static function getFilesByName($files = [])
    {
        $data = [];
        foreach ($files as $key => $value) {
            if (stristr($value['name'], 'xml')) {
                $name = 'anpr_xml';
            } else {
                if (stristr(strtolower($key), 'licenseplate') || stristr(strtolower($value['name']), 'licenseplate')) {
                    $name = 'licensePlatePicture';
                } elseif (stristr(strtolower($key), 'detection') || stristr(strtolower($value['name']), 'detection')) {
                    $name = 'detectionPicture';
                } else {
                    $name = $key;
                }
            }
            $data[$name] = $value;
        }
        return $data;
    }

    /**
     * Converts an XML file to an associative array.
     *
     * This function reads an XML file, parses its contents,
     * and returns the result as an associative array.
     *
     * @param string $filePath Path to the XML file.
     * @return array The XML data as an associative array.
     * @throws Exception If the file cannot be read or XML is invalid.
     */
    public static function xmlFileToArray($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }

        $xmlContent = file_get_contents($filePath);
        $xmlObject  = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xmlObject === false) {
            throw new Exception("Invalid XML in file: $filePath");
        }

        return json_decode(json_encode($xmlObject), true);
    }

    /**
     * Resize an image to the given width while maintaining aspect ratio.
     *
     * @param string $filePath Path to the source image file.
     * @param int $newWidth Desired width for the resized image.
     * @param int $quality JPEG quality percentage (default: 85).
     * @return string Base64 encoded string of the resized image.
     * @throws Exception If the image cannot be loaded or resized.
     */
    public static function resizeImageToBase64($filePath, $newWidth, $quality = 85)
    {
        // Validate if the file is a valid image
        if (!\getimagesize($filePath)) {
            throw new Exception('Invalid image file: ' . $filePath);
        }

        // Load the image using GD
        $srcImage = \imagecreatefromstring(file_get_contents($filePath));
        if ($srcImage === false) {
            throw new Exception('Failed to load image: ' . $filePath);
        }

        // Get original dimensions
        $origWidth  = \imagesx($srcImage);
        $origHeight = \imagesy($srcImage);

        // Calculate new height while maintaining aspect ratio
        $newHeight = intval(($origHeight / $origWidth) * $newWidth);

        // Create a new true color image for the resized version
        $resizedImage = \imagecreatetruecolor($newWidth, $newHeight);

        // Resize the image
        \imagecopyresampled(
            $resizedImage, // Destination image
            $srcImage, // Source image
            0, 0, 0, 0, // Destination and source points
            $newWidth, $newHeight, // New dimensions
            $origWidth, $origHeight // Original dimensions
        );

        // Convert resized image to Base64
        ob_start();
        \imagejpeg($resizedImage, null, $quality);
        $imageData = ob_get_clean();

        // Cleanup resources
        \imagedestroy($srcImage);
        \imagedestroy($resizedImage);

        // Return Base64 encoded string
        return base64_encode($imageData);
    }

    /**
     * Extracts the IP address from a given URL.
     *
     * This method attempts to retrieve the IP address from a URL by first parsing it to find the host.
     * If the host is present, it is returned directly. Otherwise, a regular expression is used to search
     * for an IP address pattern within the provided URL.
     *
     * @param string $url The URL from which the IP address is to be extracted.
     *
     * @return string|null Returns the IP address as a string if found, or null if no IP address is present.
     */
    public static function extractIpFromUrl($url)
    {
        // Parse the URL to extract its components
        $parsedUrl = parse_url($url);

        // Check if the 'host' part exists and return it
        if (isset($parsedUrl['host'])) {
            return $parsedUrl['host'];
        }

        // If no host is found, use REGEX to search for an IP address in the URL
        preg_match('/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/', $url, $matches);

        // Return the matched IP address or null if not found
        return $matches[0] ?? null;
    }

    /**
     * Checks if the provided string is a valid JSON.
     *
     * @param string $string Yoxlanacaq mətn
     * @return bool
     */
    public static function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Checks if the provided string is valid XML.
     *
     * @param string $string Yoxlanacaq mətn
     * @return bool
     */
    public static function isXml($string): bool
    {
        if (!is_string($string)) {
            return false;
        }

        libxml_use_internal_errors(true);
        $doc = @simplexml_load_string($string);
        if (!$doc) {
            libxml_clear_errors();
            return false;
        }
        return true;
    }

    /**
     * Converts XML response to an associative array.
     *
     * @param string $response XML cavabı
     * @return array
     * @throws Exception
     */
    public static function xmlToArray($response): array
    {
        if (!self::isXml($response)) {
            throw new Exception("Etibarsız XML cavabı.");
        }

        $xml  = simplexml_load_string($response);
        $json = json_encode($xml);

        if (!self::isJson($json)) {
            throw new Exception("Etibarsız XML cavabı.");
        }

        $array = json_decode($json, true);

        if (isset($array['@attributes'])) {
            unset($array['@attributes']);
        }

        return (array) $array;
    }

    /**
     * addArrayToXml
     *
     * @param  SimpleXMLElement $xmlElement
     * @param  array $data
     * @return void
     */
    public static function addArrayToXml(SimpleXMLElement $xmlElement, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::addArrayToXml($xmlElement->addChild($key), $value);
            } else {
                $xmlElement->addChild($key, htmlspecialchars($value));
            }
        }
    }

    /**
     * Searches for a substring in an array and returns the first matching element.
     *
     * This method iterates over an array (`haystack`) and checks if a specified substring (`needle`)
     * is present in each element. If a match is found, it returns the first matching element.
     * If no match is found, it returns false.
     *
     * @param string $needle The substring to search for.
     * @param array $haystack The array of strings to search in.
     *
     * @return string|false The first element that contains the substring, or false if no match is found.
     */
    public static function arrayFind($needle, $haystack)
    {
        foreach ($haystack as $item) {
            // Check if the needle is found in the current item
            if (strpos($item, $needle) !== false) {
                return $item; // Return the first matching element
            }
        }

        return false; // No match found
    }
}
