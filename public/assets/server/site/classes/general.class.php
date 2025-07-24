<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class General extends Db
{

    public $pagename = null;
    public $profile_photo_mini_path = "img/profile/profile_mini/";
    public $profile_photo_max_path = "img/profile/profile_max/";

    public function __construct($pn = null)
    {
        $this->pagename = $pn;
    }

    public function verifyDataStructureFromHeader()
    {
        /**
         * @return String
         */
        if (function_exists("getallheaders")) {
            if (isset(getallheaders()["Api-Call"]) || isset(getallheaders()["api-call"])) {
                if (getallheaders()["Api-Call"] === "JSON-Encoded" || getallheaders()["api-call"] === "JSON-Encoded") {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public function verifyCurrency($return = false)
    {
        if (isset(getallheaders()["Cr"])) {
            if (!$this->isEmpty(getallheaders()["Cr"])) {
                if (getallheaders()["Cr"] != $this->toLowerCase(CURRENCY_TEXT)) {
                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Reload";
                    if ($return) {
                        return false;
                    }
                    header("Login-Auth-Error: Reload");
                    die($this->encodeJSONdata($response));
                }
            }
        }
    }

    public function verifyApiKey()
    {
        if (isset($_GET["api_key"])) {
            if ($_GET["api_key"] === API_KEY) {
                return true;
            }
        }
        if (function_exists("getallheaders")) {
            if (isset(getallheaders()["Api-Key"]) || isset(getallheaders()["api-key"])) {
                if ((getallheaders()["Api-Key"] ?? null) === API_KEY || (getallheaders()["api-key"] ?? null) === API_KEY) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public function closeCookieUsageNotice()
    {
        setcookie("cun", "notified", strtotime("+1month"), "/");
    }

    public function closeChatbotNotice()
    {
        $_SESSION["cnotice"] = "notified";
    }

    public function getUserInfoItems()
    {
        if (isset($_SESSION["uinfo"])) {
            return json_decode($_SESSION["uinfo"], true);
        }
    }

    public function stripTags($str)
    {
        /**
         * @param String $str
         * @return String
         */
        return strip_tags($str);
    }

    public function isNotBusinessAccount($accountType)
    {
        /**
         * @param String $accountType
         * @return Bool
         */
        if (IS_LOGGED_IN === "no") {
            return true;
        }

        if (in_array($this->toLowerCase($accountType), array("freelancer", "artisan"))) {
            return true;
        }

        return false;
    }

    public function mobileMoneyList()
    {
        /**
         * @return Array
         */
        return array(
            array("name" => "MTN Mobile Money"),
            array("name" => "Airtel Mobile Money"),
            array("name" => "Tigo Cash"),
            array("name" => "Airtel-Tigo Cash"),
            array("name" => "Vodafone Cash"),
        );
    }

    public function bankList()
    {
        /**
         * @return Array
         */
        return array(
            array("name" => "Absa Bank"),
            array("name" => "Access Bank Ghana Plc"),
            array("name" => "Agricultural Development Bank of Ghana"),
            array("name" => "Bank of Africa"),
            array("name" => "CalBank Limited"),
            array("name" => "Consolidated Bank"),
            array("name" => "Ecobank"),
            array("name" => "FBN Bank"),
            array("name" => "Fidelity Bank"),
            array("name" => "First Atlantic"),
            array("name" => "First National Bank Ghana"),
            array("name" => "GCB"),
            array("name" => "Guaranty Trust Bank"),
            array("name" => "National Investment"),
            array("name" => "OmniBSIC Bank"),
            array("name" => "Prudential"),
            array("name" => "Republic Bank"),
            array("name" => "Société Générale"),
            array("name" => "Stanbic Bank"),
            array("name" => "Standard Chartered Bank"),
            array("name" => "United Bank for Africa"),
            array("name" => "Universal Merchant"),
            array("name" => "Zenith Bank"),
        );
    }

    public function countryList()
    {
        /**
         * @return Array
         */
        return array(
            array("name" => "Afghanistan", "dial_code" => "+93", "code" => "AF"),
            array("name" => "Albania", "dial_code" => "+355", "code" => "AL"),
            array("name" => "Algeria", "dial_code" => "+213", "code" => "DZ"),
            array("name" => "American Samoa", "dial_code" => "+1684", "code" => "AS"),
            array("name" => "Andorra", "dial_code" => "+376", "code" => "AD"),
            array("name" => "Angola", "dial_code" => "+244", "code" => "AO"),
            array("name" => "Anguilla", "dial_code" => "+1264", "code" => "AI"),
            array("name" => "Antigua and Barbuda", "dial_code" => "+1268", "code" => "AG"),
            array("name" => "Argentina", "dial_code" => "+54", "code" => "AR"),
            array("name" => "Armenia", "dial_code" => "+374", "code" => "AM"),
            array("name" => "Aruba", "dial_code" => "+297", "code" => "AW"),
            array("name" => "Australia", "dial_code" => "+61", "code" => "AU"),
            array("name" => "Austria", "dial_code" => "+43", "code" => "AT"),
            array("name" => "Azerbaijan", "dial_code" => "+994", "code" => "AZ"),
            array("name" => "Bahamas", "dial_code" => "+1242", "code" => "BS"),
            array("name" => "Bahrain", "dial_code" => "+973", "code" => "BH"),
            array("name" => "Bangladesh", "dial_code" => "+880", "code" => "BD"),
            array("name" => "Barbados", "dial_code" => "+1246", "code" => "BB"),
            array("name" => "Belarus", "dial_code" => "+375", "code" => "BY"),
            array("name" => "Belgium", "dial_code" => "+32", "code" => "BE"),
            array("name" => "Belize", "dial_code" => "+501", "code" => "BZ"),
            array("name" => "Benin", "dial_code" => "+229", "code" => "BJ"),
            array("name" => "Bermuda", "dial_code" => "+1441", "code" => "BM"),
            array("name" => "Bhutan", "dial_code" => "+975", "code" => "BT"),
            array("name" => "Bosnia and Herzegovina", "dial_code" => "+387", "code" => "BA"),
            array("name" => "Botswana", "dial_code" => "+267", "code" => "BW"),
            array("name" => "Brazil", "dial_code" => "+55", "code" => "BR"),
            array("name" => "British Indian Ocean Territory", "dial_code" => "+246", "code" => "IO"),
            array("name" => "Bulgaria", "dial_code" => "+359", "code" => "BG"),
            array("name" => "Burkina Faso", "dial_code" => "+226", "code" => "BF"),
            array("name" => "Burundi", "dial_code" => "+257", "code" => "BI"),
            array("name" => "Cambodia", "dial_code" => "+855", "code" => "KH"),
            array("name" => "Cameroon", "dial_code" => "+237", "code" => "CM"),
            array("name" => "Canada", "dial_code" => "+1", "code" => "CA"),
            array("name" => "Cape Verde", "dial_code" => "+238", "code" => "CV"),
            array("name" => "Cayman Islands", "dial_code" => "+ 345", "code" => "KY"),
            array("name" => "Central African Republic", "dial_code" => "+236", "code" => "CF"),
            array("name" => "Chad", "dial_code" => "+235", "code" => "TD"),
            array("name" => "Chile", "dial_code" => "+56", "code" => "CL"),
            array("name" => "China", "dial_code" => "+86", "code" => "CN"),
            array("name" => "Christmas Island", "dial_code" => "+61", "code" => "CX"),
            array("name" => "Colombia", "dial_code" => "+57", "code" => "CO"),
            array("name" => "Comoros", "dial_code" => "+269", "code" => "KM"),
            array("name" => "Congo", "dial_code" => "+242", "code" => "CG"),
            array("name" => "Cook Islands", "dial_code" => "+682", "code" => "CK"),
            array("name" => "Costa Rica", "dial_code" => "+506", "code" => "CR"),
            array("name" => "Croatia", "dial_code" => "+385", "code" => "HR"),
            array("name" => "Cuba", "dial_code" => "+53", "code" => "CU"),
            array("name" => "Cyprus", "dial_code" => "+537", "code" => "CY"),
            array("name" => "Czech Republic", "dial_code" => "+420", "code" => "CZ"),
            array("name" => "Denmark", "dial_code" => "+45", "code" => "DK"),
            array("name" => "Djibouti", "dial_code" => "+253", "code" => "DJ"),
            array("name" => "Dominica", "dial_code" => "+1767", "code" => "DM"),
            array("name" => "Dominican Republic", "dial_code" => "+1849", "code" => "DO"),
            array("name" => "Ecuador", "dial_code" => "+593", "code" => "EC"),
            array("name" => "Egypt", "dial_code" => "+20", "code" => "EG"),
            array("name" => "El Salvador", "dial_code" => "+503", "code" => "SV"),
            array("name" => "Equatorial Guinea", "dial_code" => "+240", "code" => "GQ"),
            array("name" => "Eritrea", "dial_code" => "+291", "code" => "ER"),
            array("name" => "Estonia", "dial_code" => "+372", "code" => "EE"),
            array("name" => "Ethiopia", "dial_code" => "+251", "code" => "ET"),
            array("name" => "Faroe Islands", "dial_code" => "+298", "code" => "FO"),
            array("name" => "Fiji", "dial_code" => "+679", "code" => "FJ"),
            array("name" => "Finland", "dial_code" => "+358", "code" => "FI"),
            array("name" => "France", "dial_code" => "+33", "code" => "FR"),
            array("name" => "French Guiana", "dial_code" => "+594", "code" => "GF"),
            array("name" => "French Polynesia", "dial_code" => "+689", "code" => "PF"),
            array("name" => "Gabon", "dial_code" => "+241", "code" => "GA"),
            array("name" => "Gambia", "dial_code" => "+220", "code" => "GM"),
            array("name" => "Georgia", "dial_code" => "+995", "code" => "GE"),
            array("name" => "Germany", "dial_code" => "+49", "code" => "DE"),
            array("name" => "Ghana", "dial_code" => "+233", "code" => "GH"),
            array("name" => "Gibraltar", "dial_code" => "+350", "code" => "GI"),
            array("name" => "Greece", "dial_code" => "+30", "code" => "GR"),
            array("name" => "Greenland", "dial_code" => "+299", "code" => "GL"),
            array("name" => "Grenada", "dial_code" => "+1473", "code" => "GD"),
            array("name" => "Guadeloupe", "dial_code" => "+590", "code" => "GP"),
            array("name" => "Guam", "dial_code" => "+1671", "code" => "GU"),
            array("name" => "Guatemala", "dial_code" => "+502", "code" => "GT"),
            array("name" => "Guinea", "dial_code" => "+224", "code" => "GN"),
            array("name" => "Guinea-Bissau", "dial_code" => "+245", "code" => "GW"),
            array("name" => "Guyana", "dial_code" => "+595", "code" => "GY"),
            array("name" => "Haiti", "dial_code" => "+509", "code" => "HT"),
            array("name" => "Honduras", "dial_code" => "+504", "code" => "HN"),
            array("name" => "Hungary", "dial_code" => "+36", "code" => "HU"),
            array("name" => "Iceland", "dial_code" => "+354", "code" => "IS"),
            array("name" => "India", "dial_code" => "+91", "code" => "IN"),
            array("name" => "Indonesia", "dial_code" => "+62", "code" => "ID"),
            array("name" => "Iraq", "dial_code" => "+964", "code" => "IQ"),
            array("name" => "Ireland", "dial_code" => "+353", "code" => "IE"),
            array("name" => "Israel", "dial_code" => "+972", "code" => "IL"),
            array("name" => "Italy", "dial_code" => "+39", "code" => "IT"),
            array("name" => "Jamaica", "dial_code" => "+1876", "code" => "JM"),
            array("name" => "Japan", "dial_code" => "+81", "code" => "JP"),
            array("name" => "Jordan", "dial_code" => "+962", "code" => "JO"),
            array("name" => "Kazakhstan", "dial_code" => "+7 7", "code" => "KZ"),
            array("name" => "Kenya", "dial_code" => "+254", "code" => "KE"),
            array("name" => "Kiribati", "dial_code" => "+686", "code" => "KI"),
            array("name" => "Kuwait", "dial_code" => "+965", "code" => "KW"),
            array("name" => "Kyrgyzstan", "dial_code" => "+996", "code" => "KG"),
            array("name" => "Latvia", "dial_code" => "+371", "code" => "LV"),
            array("name" => "Lebanon", "dial_code" => "+961", "code" => "LB"),
            array("name" => "Lesotho", "dial_code" => "+266", "code" => "LS"),
            array("name" => "Liberia", "dial_code" => "+231", "code" => "LR"),
            array("name" => "Liechtenstein", "dial_code" => "+423", "code" => "LI"),
            array("name" => "Lithuania", "dial_code" => "+370", "code" => "LT"),
            array("name" => "Luxembourg", "dial_code" => "+352", "code" => "LU"),
            array("name" => "Madagascar", "dial_code" => "+261", "code" => "MG"),
            array("name" => "Malawi", "dial_code" => "+265", "code" => "MW"),
            array("name" => "Malaysia", "dial_code" => "+60", "code" => "MY"),
            array("name" => "Maldives", "dial_code" => "+960", "code" => "MV"),
            array("name" => "Mali", "dial_code" => "+223", "code" => "ML"),
            array("name" => "Malta", "dial_code" => "+356", "code" => "MT"),
            array("name" => "Marshall Islands", "dial_code" => "+692", "code" => "MH"),
            array("name" => "Martinique", "dial_code" => "+596", "code" => "MQ"),
            array("name" => "Mauritania", "dial_code" => "+222", "code" => "MR"),
            array("name" => "Mauritius", "dial_code" => "+230", "code" => "MU"),
            array("name" => "Mayotte", "dial_code" => "+262", "code" => "YT"),
            array("name" => "Mexico", "dial_code" => "+52", "code" => "MX"),
            array("name" => "Monaco", "dial_code" => "+377", "code" => "MC"),
            array("name" => "Mongolia", "dial_code" => "+976", "code" => "MN"),
            array("name" => "Montenegro", "dial_code" => "+382", "code" => "ME"),
            array("name" => "Montserrat", "dial_code" => "+1664", "code" => "MS"),
            array("name" => "Morocco", "dial_code" => "+212", "code" => "MA"),
            array("name" => "Myanmar", "dial_code" => "+95", "code" => "MM"),
            array("name" => "Namibia", "dial_code" => "+264", "code" => "NA"),
            array("name" => "Nauru", "dial_code" => "+674", "code" => "NR"),
            array("name" => "Nepal", "dial_code" => "+977", "code" => "NP"),
            array("name" => "Netherlands", "dial_code" => "+31", "code" => "NL"),
            array("name" => "Netherlands Antilles", "dial_code" => "+599", "code" => "AN"),
            array("name" => "New Caledonia", "dial_code" => "+687", "code" => "NC"),
            array("name" => "New Zealand", "dial_code" => "+64", "code" => "NZ"),
            array("name" => "Nicaragua", "dial_code" => "+505", "code" => "NI"),
            array("name" => "Niger", "dial_code" => "+227", "code" => "NE"),
            array("name" => "Nigeria", "dial_code" => "+234", "code" => "NG"),
            array("name" => "Niue", "dial_code" => "+683", "code" => "NU"),
            array("name" => "Norfolk Island", "dial_code" => "+672", "code" => "NF"),
            array("name" => "Northern Mariana Islands", "dial_code" => "+1670", "code" => "MP"),
            array("name" => "Norway", "dial_code" => "+47", "code" => "NO"),
            array("name" => "Oman", "dial_code" => "+968", "code" => "OM"),
            array("name" => "Pakistan", "dial_code" => "+92", "code" => "PK"),
            array("name" => "Palau", "dial_code" => "+680", "code" => "PW"),
            array("name" => "Panama", "dial_code" => "+507", "code" => "PA"),
            array("name" => "Papua New Guinea", "dial_code" => "+675", "code" => "PG"),
            array("name" => "Paraguay", "dial_code" => "+595", "code" => "PY"),
            array("name" => "Peru", "dial_code" => "+51", "code" => "PE"),
            array("name" => "Philippines", "dial_code" => "+63", "code" => "PH"),
            array("name" => "Poland", "dial_code" => "+48", "code" => "PL"),
            array("name" => "Portugal", "dial_code" => "+351", "code" => "PT"),
            array("name" => "Puerto Rico", "dial_code" => "+1939", "code" => "PR"),
            array("name" => "Qatar", "dial_code" => "+974", "code" => "QA"),
            array("name" => "Romania", "dial_code" => "+40", "code" => "RO"),
            array("name" => "Rwanda", "dial_code" => "+250", "code" => "RW"),
            array("name" => "Samoa", "dial_code" => "+685", "code" => "WS"),
            array("name" => "San Marino", "dial_code" => "+378", "code" => "SM"),
            array("name" => "Saudi Arabia", "dial_code" => "+966", "code" => "SA"),
            array("name" => "Senegal", "dial_code" => "+221", "code" => "SN"),
            array("name" => "Serbia", "dial_code" => "+381", "code" => "RS"),
            array("name" => "Seychelles", "dial_code" => "+248", "code" => "SC"),
            array("name" => "Sierra Leone", "dial_code" => "+232", "code" => "SL"),
            array("name" => "Singapore", "dial_code" => "+65", "code" => "SG"),
            array("name" => "Slovakia", "dial_code" => "+421", "code" => "SK"),
            array("name" => "Slovenia", "dial_code" => "+386", "code" => "SI"),
            array("name" => "Solomon Islands", "dial_code" => "+677", "code" => "SB"),
            array("name" => "South Africa", "dial_code" => "+27", "code" => "ZA"),
            array("name" => "South Georgia", "dial_code" => "+500", "code" => "GS"),
            array("name" => "Spain", "dial_code" => "+34", "code" => "ES"),
            array("name" => "Sri Lanka", "dial_code" => "+94", "code" => "LK"),
            array("name" => "Sudan", "dial_code" => "+249", "code" => "SD"),
            array("name" => "Suriname", "dial_code" => "+597", "code" => "SR"),
            array("name" => "Swaziland", "dial_code" => "+268", "code" => "SZ"),
            array("name" => "Sweden", "dial_code" => "+46", "code" => "SE"),
            array("name" => "Switzerland", "dial_code" => "+41", "code" => "CH"),
            array("name" => "Tajikistan", "dial_code" => "+992", "code" => "TJ"),
            array("name" => "Thailand", "dial_code" => "+66", "code" => "TH"),
            array("name" => "Togo", "dial_code" => "+228", "code" => "TG"),
            array("name" => "Tokelau", "dial_code" => "+690", "code" => "TK"),
            array("name" => "Tonga", "dial_code" => "+676", "code" => "TO"),
            array("name" => "Trinidad and Tobago", "dial_code" => "+1868", "code" => "TT"),
            array("name" => "Tunisia", "dial_code" => "+216", "code" => "TN"),
            array("name" => "Turkey", "dial_code" => "+90", "code" => "TR"),
            array("name" => "Turkmenistan", "dial_code" => "+993", "code" => "TM"),
            array("name" => "Turks and Caicos Islands", "dial_code" => "+1649", "code" => "TC"),
            array("name" => "Tuvalu", "dial_code" => "+688", "code" => "TV"),
            array("name" => "Uganda", "dial_code" => "+256", "code" => "UG"),
            array("name" => "Ukraine", "dial_code" => "+380", "code" => "UA"),
            array("name" => "United Arab Emirates", "dial_code" => "+971", "code" => "AE"),
            array("name" => "United Kingdom", "dial_code" => "+44", "code" => "GB"),
            array("name" => "United States", "dial_code" => "+1", "code" => "US"),
            array("name" => "Uruguay", "dial_code" => "+598", "code" => "UY"),
            array("name" => "Uzbekistan", "dial_code" => "+998", "code" => "UZ"),
            array("name" => "Vanuatu", "dial_code" => "+678", "code" => "VU"),
            array("name" => "Wallis and Futuna", "dial_code" => "+681", "code" => "WF"),
            array("name" => "Yemen", "dial_code" => "+967", "code" => "YE"),
            array("name" => "Zambia", "dial_code" => "+260", "code" => "ZM"),
            array("name" => "Zimbabwe", "dial_code" => "+263", "code" => "ZW"),
            array("name" => "Bolivia", "dial_code" => "+591", "code" => "BO"),
            array("name" => "Brunei Darussalam", "dial_code" => "+673", "code" => "BN"),
            array("name" => "Cocos Islands", "dial_code" => "+61", "code" => "CC"),
            array("name" => "Congo DR", "dial_code" => "+243", "code" => "CD"),
            array("name" => "Cote d'Ivoire", "dial_code" => "+225", "code" => "CI"),
            array("name" => "Falkland Islands", "dial_code" => "+500", "code" => "FK"),
            array("name" => "Guernsey", "dial_code" => "+44", "code" => "GG"),
            array("name" => "Vatican", "dial_code" => "+379", "code" => "VA"),
            array("name" => "Hong Kong", "dial_code" => "+852", "code" => "HK"),
            array("name" => "Iran", "dial_code" => "+98", "code" => "IR"),
            array("name" => "Isle of Man", "dial_code" => "+44", "code" => "IM"),
            array("name" => "Jersey", "dial_code" => "+44", "code" => "JE"),
            array("name" => "North Korea", "dial_code" => "+850", "code" => "KP"),
            array("name" => "South Korea", "dial_code" => "+82", "code" => "KR"),
            array("name" => "Laos", "dial_code" => "+856", "code" => "LA"),
            array("name" => "Libya", "dial_code" => "+218", "code" => "LY"),
            array("name" => "Macao", "dial_code" => "+853", "code" => "MO"),
            array("name" => "Macedonia", "dial_code" => "+389", "code" => "MK"),
            array("name" => "Micronesia", "dial_code" => "+691", "code" => "FM"),
            array("name" => "Moldova", "dial_code" => "+373", "code" => "MD"),
            array("name" => "Mozambique", "dial_code" => "+258", "code" => "MZ"),
            array("name" => "Palestine", "dial_code" => "+970", "code" => "PS"),
            array("name" => "Pitcairn", "dial_code" => "+872", "code" => "PN"),
            array("name" => "Réunion", "dial_code" => "+262", "code" => "RE"),
            array("name" => "Russia", "dial_code" => "+7", "code" => "RU"),
            array("name" => "Saint Barthélemy", "dial_code" => "+590", "code" => "BL"),
            array("name" => "Saint Helena", "dial_code" => "+290", "code" => "SH"),
            array("name" => "Saint Kitts and Nevis", "dial_code" => "+1869", "code" => "KN"),
            array("name" => "Saint Lucia", "dial_code" => "+1758", "code" => "LC"),
            array("name" => "Saint Martin", "dial_code" => "+590", "code" => "MF"),
            array("name" => "Saint Pierre and Miquelon", "dial_code" => "+508", "code" => "PM"),
            array("name" => "Saint Vincent and the Grenadines", "dial_code" => "+1784", "code" => "VC"),
            array("name" => "Sao Tome and Principe", "dial_code" => "+239", "code" => "ST"),
            array("name" => "Somalia", "dial_code" => "+252", "code" => "SO"),
            array("name" => "Svalbard and Jan Mayen", "dial_code" => "+47", "code" => "SJ"),
            array("name" => "Syria", "dial_code" => "+963", "code" => "SY"),
            array("name" => "Taiwan", "dial_code" => "+886", "code" => "TW"),
            array("name" => "Tanzania", "dial_code" => "+255", "code" => "TZ"),
            array("name" => "Timor-Leste", "dial_code" => "+670", "code" => "TL"),
            array("name" => "Venezuela", "dial_code" => "+58", "code" => "VE"),
            array("name" => "Vietnam", "dial_code" => "+84", "code" => "VN"),
            array("name" => "Virgin Islands, British", "dial_code" => "+1284", "code" => "VG"),
            array("name" => "Virgin Islands, U.S.", "dial_code" => "+1340", "code" => "VI"),
        );
    }

    public function convertDateAndTime($date, $time): String
    {
        /**
         * @param String $date
         * @param String $time
         * @return String
         */
        return date("F, d/m/y", strtotime($date)) . " at " . date("g:ia", strtotime($time));
    }

    public function convertDate($date, $auto = false): String
    {
        /**
         * @param String $date
         * @param Bool $auto
         * @return String
         */
        if ($auto === true) {
            $date = date("Y-m-d H:i:s");
        }
        return date("F jS, Y", strtotime($date)) . " at " . date("g:i a", strtotime($date));
    }

    public function convertDateOnly($date, $format = 0, $excy = false): String
    {
        /**
         * @param String $date
         * @param Int $format
         * @param Bool $excy
         * @return String
         */
        if (empty($date) || preg_match('/0000/i', $date)) {
            return "N/A";
        }

        $y = ($excy === true) ? "" : ", Y";
        return ($format > 0 ? date("M jS" . $y, strtotime($date)) : date("jS F" . $y, strtotime($date)));
    }

    public function parseDateOnly($date, $format = 0, $excy = false): String
    {
        /**
         * @param String $date
         * @param Int $format
         * @param Bool $excy
         * @return String
         */
        if (empty($date) || preg_match('/0000/i', $date)) {
            return "N/A";
        }

        $y = ($excy === true) ? "" : ", Y";
        return ($format > 0 ? date("D, F jS" . $y, strtotime($date)) : date("D, jS F" . $y, strtotime($date)));
    }

    public function getAllCountryNamesAndCodes()
    {
        /**
         * @return String
         */

        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->countryList();
        die($this->encodeJSONdata($response));
    }

    public function getGoogleMapAPIKey()
    {
        /**
         * @return String
         */

        $response["status"] = SUCCESS_CODE;
        $response["data"] = 'AIzaSyBp00jF_PxWbtR0eMu2K1Fc4NeIr2silkU';
        die($this->encodeJSONdata($response));
    }

    public function getAllCategories($return = false)
    {
        /**
         * @return String
         */

        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->readJSONFile("skill_categories");
        if ($return) {
            return $response["data"];
        }

        die($this->encodeJSONdata($response));
    }

    public function sortArrayData($data = array(), $s)
    {
        /**
         * @param Array $data
         * @return Array
         */
        array_multisort(array_column($data, $s), SORT_ASC, $data);
        return $data;
    }

    public function getAllCategoriesAndSkills($return = false)
    {
        /**
         * @return String
         */

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "skill_categories" => $this->readJSONFile("skill_categories"),
            "skills" => $this->sortArrayData($this->readJSONFile("skills"), "skill_name"),
        );
        if ($return) {
            return $response["data"];
        }

        die($this->encodeJSONdata($response));
    }

    public function getAllCategoriesSkillsLanguages($return = false)
    {
        /**
         * @return String
         */

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "skill_categories" => $this->readJSONFile("skill_categories"),
            "skills" => $this->sortArrayData($this->readJSONFile("skills"), "skill_name"),
            "languages" => $this->sortArrayData($this->readJSONFile("languages"), "name"),
        );
        if ($return) {
            return $response["data"];
        }

        die($this->encodeJSONdata($response));
    }

    public function getAllFeaturesUnderSkill($idata = array(), $return = false)
    {
        $skill_id = $this->filterData($idata["skill_id"] ?? null);
        $features = array();

        $skills = $this->sortArrayData($this->readJSONFile("skills"), "skill_name");
        foreach ($skills as $skill) {
            if ($skill["skill_id"] == $skill_id) {
                $features = $skill["features"];
            }
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = $features;
        if ($return) {
            return $features;
        }

        die($this->encodeJSONdata($response));
    }

    public function getAllSkillReferences($return = false)
    {
        $references = array();

        $skills = $this->sortArrayData($this->readJSONFile("skills"), "skill_name");
        foreach ($skills as $skill) {
            if ($skill["skill_id"] == $skill["skill_reference"]) {
                $references[] = array(
                    "skill_id" => $skill["skill_id"],
                    "skill_name" => $skill["skill_name"],
                );
            }
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = $references;

        if ($return) {
            return $references;
        }

        die($this->encodeJSONdata($response));
    }

    public function getAllSkillsUnderCategory($idata = array(), $return = false)
    {
        $category = $this->filterData($idata["category"] ?? null);
        $skillList = array();

        $skills = $this->sortArrayData($this->readJSONFile("skills"), "skill_name");
        foreach ($skills as $skill) {
            if ($skill["skill_category"] == $category) {
                $skillList[] = array(
                    "skill_id" => $skill["skill_id"],
                    "skill_name" => $skill["skill_name"],
                );
            }
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = $skillList;
        if ($return) {
            return $skillList;
        }

        die($this->encodeJSONdata($response));
    }

    public function checkPermissions($title)
    {
        $conn = $this->PDOConnection(DB[4]);

        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `users_admins_permissions` WHERE `permission_title` = :title AND `permission_value` > 0 AND `user_id` = :user_id",
            array(
                "user_id" => USERID,
                "title" => $title
            ),
            true,
            true
        );

        if ($result) return true;
        return false;
    }

    public function isSuperAdmin()
    {
        $conn = $this->PDOConnection(DB[4]);

        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `users_admin` WHERE `is_super_admin` > 0 AND `user_id` = :user_id",
            array(
                "user_id" => USERID
            ),
            true,
            true
        );

        if ($result) return true;
        return false;
    }

    public function throwPermissionStatusMessage()
    {
        $response["status"] = ERROR_CODE;
        $response["data"] = "You don't have permission to take this action.";
        return $this->encodeJSONdata($response);
    }

    public function remoteFileExists($path)
    {
        if (empty(trim($path))) {
            return false;
        }

        if (@fopen($path, "r")) {
            return true;
        }

        return false;
    }

    public function getDashboardAccountViewType($return = true)
    {
        $conn = $this->PDOConnection(DB[4]);

        $result = $this->runQuery(
            $conn,
            "SELECT `view_type`, `account_type` FROM `users_admin` WHERE `id` = :user_id",
            array(
                "user_id" => UID,
            ),
            true,
            true
        );

        if ($result) {
            if ($return) {
                return $result;
            }

            $response["status"] = SUCCESS_CODE;
            $response["data"] = $result;
            die($this->encodeJSONdata($response));
        }

        if ($return) {
            return array(
                "user_view_type" => null,
                "user_account_type" => null,
            );
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "user_view_type" => null,
            "user_account_type" => null,
        );
        die($this->encodeJSONdata($response));
    }

    public function getProjectRealId($id)
    {
        $conn = $this->PDOConnection(DB[4]);

        $result = $this->runQuery(
            $conn,
            "SELECT `project_id`, `service_id_type`, `reference_id` FROM `projects` WHERE `id` = :id",
            array(
                "id" => $id,
            ),
            true,
            true
        );

        if ($result) {
            return $result;
        }

        return null;
    }

    public function increaseUserFlagedWordsWarnings($idata = array())
    {
        $this->runQuery(
            $this->PDOConnection(DB[4]),
            "UPDATE `users_admin` SET `word_usage_warnings` = `word_usage_warnings` + 1 WHERE `id` = :user_id",
            array(
                "user_id" => UID,
            )
        );
    }

    public function increaseUserContactEntryWarnings($idata = array())
    {
        $this->runQuery(
            $this->PDOConnection(DB[4]),
            "UPDATE `users_admin` SET `contact_entry_warnings` = `contact_entry_warnings` + 1 WHERE `id` = :user_id",
            array(
                "user_id" => UID,
            )
        );
    }

    public function sendSiteMessage($idata = array())
    {
        $name = $this->filterData($idata["name"] ?? null);
        $email = $this->filterData($idata["email"] ?? null);
        $message = $this->filterData($idata["message"] ?? null);

        if (empty($name) || empty($email) || empty($message)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "The name, email, and message are all required.";
            die($this->encodeJSONdata($response));
        }

        $conn = $this->PDOConnection(DB[4]);
        $skills = array();

        $result = $this->runQuery(
            $conn,
            "INSERT INTO `site_messages`(`name`, `email`, `message`, `datetime`) VALUES(:name, :email, :message, :datetime)",
            array(
                "name" => $name,
                "email" => $email,
                "message" => $message,
                "datetime" => $this->getDatetime(),
            )
        );

        if ($result) {
            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Your message has been successfully sent. We will take a look at it and get back to you. Thank you for the feedback.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Failed to send message. Please try again.";
        die($this->encodeJSONdata($response));
    }

    public function getSkillFeatures($conn, $skill_id)
    {
        $results = $this->runQuery(
            $conn,
            "SELECT `id`, `skill_id`, `feature_name`, `feature_priority` FROM `skills_features` WHERE `skill_id` = '{$skill_id}' ORDER BY `feature_priority` ASC",
            array(),
            true
        );

        if ($results) {
            return $results;
        }

        return array();
    }

    public function generateSkillsJSONFile()
    {
        $conn = $this->PDOConnection(DB[4]);
        $skills = array();

        $results = $this->runQuery(
            $conn,
            "SELECT `skill_id`, `skill_name`, `skill_alias`, `skill_reference`, `skill_category`, `priority`, `is_new` FROM `skills`",
            array(),
            true
        );

        if ($results) {
            foreach ($results as $result) {
                $skills[] = array(
                    "skill_id" => $result["skill_id"],
                    "skill_name" => $result["skill_name"],
                    "skill_alias" => $result["skill_alias"],
                    "skill_reference" => $result["skill_reference"],
                    "skill_category" => $result["skill_category"],
                    "priority" => $result["priority"],
                    "is_new" => $result["is_new"],
                    "features" => $this->getSkillFeatures($conn, $result["skill_id"]),
                    "maps" => array(),
                );
            }
        }

        file_put_contents(JSON_SKILLS_BACKUP_PATH, file_get_contents(JSON_SKILLS_PATH));
        file_put_contents(JSON_SKILLS_PATH, json_encode($skills, true));
    }

    public function getPrioritySkills($idata = array(), $return = false)
    {
        $category = $this->filterData($idata["category"] ?? null);
        $priorities = array();

        $skills = $this->sortArrayData($this->readJSONFile("skills"), "skill_name");
        foreach ($skills as $skill) {
            if ($skill["skill_category"] == $category && $this->filterData($skill["priority"]) > 0) {
                $priorities[] = array(
                    "skill_id" => $skill["skill_id"],
                    "skill_name" => $skill["skill_name"],
                );
            }
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = $priorities;
        if ($return) {
            return $priorities;
        }

        die($this->encodeJSONdata($response));
    }

    public function initiateAPIRequest($url, $data, $errorCallBack = true, $encode = true)
    {
        try {
            $payload = $encode === true ? json_encode($data, true) : $data;
            $headers = array(
                'Api-Key: '.API_KEY,
                'Api-Call: JSON-Encoded',
            );

            if($encode === true){
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Content-Length: ' . strlen($payload);
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            $err = null;
            $error = false;

            if ($errorCallBack) {
                if (curl_errno($ch)) {
                    $err = curl_error($ch);
                    $error = true;
                }
            }

            curl_close($ch);

            if ($errorCallBack) {
                if ($error) {
                    return array(
                        "status" => ERROR_CODE,
                        "data" => $err
                    );
                }
            }

            if($this->isStringifiedArray($this->trimData($result, true))){
                return json_decode($this->trimData($result, true), true);
            }
            else{
                return array(
                    "status" => ERROR_CODE,
                    "data" => "An unknown error occurred."
                );
            }
            
        } catch (Exception $e) {
            if ($errorCallBack) {
                return array(
                    "status" => ERROR_CODE,
                    "data" => "An unknown error occurred."
                );
            }
            return array(
                "status" => ERROR_CODE,
                "data" => "An unknown error occurred."
            );
        }
    }

    public function getAllSkills($return = false)
    {
        /**
         * @return String
         */
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->sortArrayData($this->readJSONFile("skills"), "skill_name");
        
        if ($return) {
            return $response["data"];
        }

        die($this->encodeJSONdata($response));
    }

    public function getAllSkillsAndCategories($return = false)
    {
        /**
         * @return String
         */
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "skill_categories" => $this->readJSONFile("skill_categories"),
            "skills" => $this->sortArrayData($this->readJSONFile("skills"), "skill_name"),
        );
        if ($return) {
            return $response["data"];
        }

        die($this->encodeJSONdata($response));
    }

    public function getSkillCategoryName($category = null)
    {
        /**
         * @return String
         */
        $category = $this->toLowerCase($category);
        $categoryList = $this->readJSONFile("skill_categories");

        foreach ($categoryList as $cat) {
            if ($cat["skill_category"] == $category) {
                return $cat["skill_category_title"];
            }
        }
    }

    public function getSkill($skill_id = null)
    {
        /**
         * @return String
         */
        $skillList = $this->sortArrayData($this->readJSONFile("skills"), "skill_name");
        foreach ($skillList as $skill) {
            if ($skill["skill_id"] == $skill_id) {
                return $skill;
            }
        }
    }

    public function getSkillName($skill_id = null)
    {
        /**
         * @return String
         */
        $skillList = $this->sortArrayData($this->readJSONFile("skills"), "skill_name");
        foreach ($skillList as $skill) {
            if ($skill["skill_id"] == $skill_id) {
                return $skill["skill_name"];
            }
        }
        return "";
    }

    public function getAllSkillsAndCategoriesAndCountries()
    {
        /**
         * @return String
         */

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "skill_categories" => $this->readJSONFile("skill_categories"),
            "skills" => $this->sortArrayData($this->readJSONFile("skills"), "skill_name"),
            "countries" => $this->countryList(),
        );
        die($this->encodeJSONdata($response));
    }

    public function getCountryAndDialCodes($str, $getBy = "code")
    {
        /**
         * @param String $str
         * @param String $getBy
         */
        foreach ($this->countryList() as $country) {
            switch ($getBy) {
                case "dial_code":
                    if ($str == $country["dial_code"]) {
                        return $country;
                    }

                    break;
                case "code":
                    if ($str == $country["code"]) {
                        return $country;
                    }

                    break;
                case "name":
                    if ($str == $country["name"]) {
                        return $country;
                    }

                    break;
                default:
                    return array();
            }
        }
        return array();
    }

    public function getCountryName($str)
    {
        /**
         * @param String $str
         * @param String $getBy
         */
        foreach ($this->countryList() as $country) {
            if ($str == $country["code"]) {
                return $country["name"];
            }
        }
        return null;
    }

    public function checkCountryAndDialCodes($str, $check)
    {
        /**
         * @param String $str
         * @param String $check
         */
        foreach ($this->countryList() as $country) {
            switch ($check) {
                case "dial_code":
                    if ($str == $country["dial_code"]) {
                        return true;
                    }

                    break;
                case "code":
                    if ($str == $country["code"]) {
                        return true;
                    }

                    break;
                case "name":
                    if ($str == $country["name"]) {
                        return true;
                    }

                    break;
                default:
                    return false;
            }
        }
        return false;
    }

    public function generateContentTitle(string $title = null): String
    {
        /**
         * @param String $str
         * @return String
         */
        $replace_str = array(".", "`", "~", "!", '"', "'", ",", "&#39;", "+", "?", ",", "-", "|", "(", ")", "{", "}", "[", "]", "%", "$", "#", "@", "&", "*");
        $title = str_replace($replace_str, '', $title);
        return $this->toLowerCase(str_ireplace(" ", "-", preg_replace('/[,\/:*?|"]/', '', $title)) . "~" . uniqid() . (date("YHi") . sprintf('%03d', (date('z') + 1))));
    }

    function isJson($string = null) {
        try {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        } catch (Exception $e) {
            return false;
        }
    }

    public function isStringifiedArray($d = null): bool
    {
        try {
            json_decode($this->trimData($d, true));
            return (json_last_error() == JSON_ERROR_NONE);
        } catch (Exception $e) {
            return false;
        }

        /**
         * @param String[encoded JSON] $d
         * @return bool
         */
        // try {
        //     $data = json_decode($d, true);
        //     if (is_array($data)) {
        //         return true;
        //     }

        //     return false;
        // } catch (Exception $e) {
        //     return false;
        // }
    }

    // public function isNameValid(string $name=null) : bool{
    //     /**
    //      * @param String $p
    //      * @return bool
    //     */
    //     if(preg_match("/[A-Za-z]/i",$name) && !preg_match("/[0-9]/i",$name) && !preg_match("/[^A-Za-z0-9\s]+/",$name)) return true;
    //     return false;
    // }

    public function isNameValid(string $name = null): bool
    {
        if (empty($name)) {
            return false;
        }

        $names = explode(" ", str_ireplace("-", " ", $name));
        foreach ($names as $name) {
            if (!empty($name)) {
                if (preg_match("/[A-Za-z-]/i", $name) && !preg_match("/[0-9]/i", $name) && !preg_match("/[^A-Za-z0-9-\s]+/", $name)) {} else {return false;}
            }
        }
        return true;
    }

    public function isMobileNumberValid(string $mobile = null): bool
    {
        /**
         * @param String $mobile
         * @return bool
         */
        if ((preg_match('/^[0-9]{10,}+$/', $mobile)) || ($this->filterData($mobile[0] ?? null) == 0 && preg_match('/^[1-9]{9,}+$/', $mobile))) {
            return true;
        }

        return false;
    }

    public function isPasswordMatching(string $p, string $cp): bool
    {
        /**
         * @param String $p
         * @param String $cp
         * @return bool
         */
        if ($p === $cp) {
            return true;
        }

        return false;
    }

    public function generateUserName($conn, string $name = null)
    {
        /**
         * @param PDO $conn
         * @param String $name
         * @return bool
         */

        $result = $this->runQuery(
            $conn,
            "SELECT COUNT(*) AS `count` FROM `users_admin` WHERE `first_name` = :first_name;",
            array(
                "first_name" => $name,
            ),
            true,
            true
        );

        if ($result["count"] > 0) {
            return preg_replace("/\s+/i", "_", $name . $result["count"]);
        }
        return preg_replace("/\s+/i", "_", $name);
    }

    public function userEmailExists($conn, string $email = null): bool
    {
        /**
         * @param PDO $conn
         * @param String $email
         * @return bool
         */
        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `users_admin` WHERE `email_address` = :email_address LIMIT 1",
            array(
                "email_address" => $email,
            ),
            true,
            true
        );

        if ($result) {
            return true;
        }

        return false;
    }

    public function userEmailExistsWithID($conn, string $email = null, Int $id = null): bool
    {
        /**
         * @param PDO $conn
         * @param String $email
         * @param Int $id
         * @return bool
         */
        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `users_admin` WHERE `email_address` = :email_address AND `id` != :id LIMIT 1",
            array(
                "email_address" => $email,
                "id" => $id,
            ),
            true,
            true
        );

        if ($result) {
            return true;
        }

        return false;
    }

    public function userNameExists($conn, string $username = null): bool
    {
        /**
         * @param PDO $conn
         * @param String $email
         * @return bool
         */
        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `users_admin` WHERE `user_name` = :user_name AND `id` != :user_id LIMIT 1",
            array(
                "user_name" => $username,
                "user_id" => UID,
            ),
            true,
            true
        );

        if ($result) {
            return true;
        }

        return false;
    }

    public function prependWithPlusSymbol($str = null){
        /**
         * @param String $str
         * @return String
         */
        if(preg_match("/^[+]/i", $str)) return $str;
        return "+".$str;
    }

    public function userMobileNumberExistsWithID($conn, string $mobile_number_country_number = null, string $mobile_number, $user_id = null): bool
    {
        /**
         * @param PDO $conn
         * @param String $mobile_number_country_number
         * @param String $mobile_number
         * @param Int $user_id
         * @return bool
         */
        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `users_admin` WHERE ((`mobile_number_country_number` = :mobile_number_country_number AND `mobile_number` = :mobile_number) OR (`mobile_number_country_number` = :mobile_number_country_number_plus AND `mobile_number` = :mobile_number)) AND `id` != :user_id LIMIT 1",
            array(
                "mobile_number_country_number" => $mobile_number_country_number,
                "mobile_number_country_number_plus" => $this->prependWithPlusSymbol($mobile_number_country_number),
                "mobile_number" => $mobile_number,
                "user_id" => $user_id,
            ),
            true,
            true
        );

        if ($result) {
            return true;
        }

        return false;
    }

    public function userMobileNumberExists($conn, string $mobile_number_country_number = null, string $mobile_number, $user_id = null): bool
    {
        /**
         * @param PDO $conn
         * @param String $mobile_number_country_number
         * @param String $mobile_number
         * @return bool
         */
        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `users_admin` WHERE `mobile_number_country_number` = :mobile_number_country_number AND `mobile_number` = :mobile_number AND `id` != :user_id LIMIT 1",
            array(
                "mobile_number_country_number" => $mobile_number_country_number,
                "mobile_number" => $mobile_number,
                "user_id" => $user_id,
            ),
            true,
            true
        );

        if ($result) {
            return true;
        }

        return false;
    }

    public function convertMobileNumberToInternationalFormat($mobile_number_country_number, $mobile_number)
    {
        /**
         * @param String $mobile_number_country_number
         * @param String $mobile_number
         * @return String
         */
        if (strlen($mobile_number) === 0) {
            return null;
        }

        $mobile_number_country_number = preg_replace('/[\s\+]/', '', $mobile_number_country_number);
        $mobile_number = $this->filterData($mobile_number, "string");
        if ($this->filterData(substr($mobile_number, 0, 3)) == 233 && $mobile_number[3] < 1) {
            return $mobile_number_country_number . substr($mobile_number, 4);
        }

        if ($this->filterData(substr($mobile_number, 0, 3)) == 233) {
            return $mobile_number;
        }

        return $mobile_number[0] < 1 ? $mobile_number_country_number . substr($mobile_number, 1) : $mobile_number_country_number . $mobile_number;
    }

    public function getUserDefaultPhoto($accountType, $gender)
    {
        /**
         * @param String $accountType
         * @param String $gender
         * @return bool
         */
        switch ($accountType) {
            case "freelancer":
            case "artisan":
                if ($gender === "male") {
                    return DEFAULT_PROFILE_PHOTO_MALE;
                }

                return DEFAULT_PROFILE_PHOTO_FEMALE;
                break;
            default:
                return DEFAULT_PROFILE_PHOTO_BUSINESS;
        }
    }

    public function isUsernameValid(string $str = null): bool
    {
        /**
         * @param String $str
         * @return bool
         */
        if (preg_match("/[^A-Za-z0-9_]/i", $str)) {
            return true;
        }

        return false;
    }

    public function isPasswordValid(string $str = null): Bool
    {
        /**
         * @param String $str
         * @return Bool
         */
        if (strlen($str) < 8) {
            return false;
        }

        //Check for symbols
        if (!preg_match("/[^A-Za-z0-9\s]+/", $str)) {
            return false;
        }

        //Check for capital letters
        if (!preg_match("/[A-Z]/", $str)) {
            return false;
        }

        return true;
    }

    public function isEmailValid(string $e = null): bool
    {
        /**
         * @param String $e
         * @return bool
         */
        if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    function cExt($f, $e){
		$p = pathinfo($f);
		return $p["filename"].".".$e;
	}

    public function uploadFileDO($files = array())
    {
        require_once VENDOR_PATH."autoload.php";

        $s3 = new Aws\S3\S3Client(array(
            "endpoint" => "https://sfo3.digitaloceanspaces.com",
            "region"  => "us-west",
            "version" => "latest",
            "use_path_style_endpoint" => false,
            "credentials" => array(
                "key"    => "DO00LW27PJT8GDKUCEZH",
                "secret" => "cmWVubonnfr3HFzMOAsCDY5qI/Cw+k6aDS3krXC0N6E",
            )
        ));
        
        // Send a PutObject request and get the result object.
        foreach($files as $file){
            $result = $s3->putObject(array(
                "ACL" => "public-read",
                "Bucket" => "cdi-media",
                "Key"    => $file["name"],
                "Body"   => $file["content"],
            ));
        }
    }
    
    public function deleteFileDO($files = array())
    {
        try{
            require_once VENDOR_PATH."autoload.php";

            $s3 = new Aws\S3\S3Client(array(
                "endpoint" => "https://sfo3.digitaloceanspaces.com",
                "region"  => "us-west",
                "version" => "latest",
                "use_path_style_endpoint" => false,
                "credentials" => array(
                    "key"    => "DO00LW27PJT8GDKUCEZH",
                    "secret" => "cmWVubonnfr3HFzMOAsCDY5qI/Cw+k6aDS3krXC0N6E",
                )
            ));
            
            // Send a PutObject request and get the result object.
            foreach($files as $file){
                $result = $s3->deleteObject(array(
                    "Bucket" => "cdi-media",
                    "Key"    => $file,
                ));
            }
        }
        catch(Exeption $e){

        }
    }

    public function readJSONFile(string $file = null): array
    {
        /**
         * @param String $filename
         * @return bool
         */
        $files = [
            "skill_categories" => "skill_categories.json",
            "skill_references" => "skill_references.json",
            "skills" => "skills.json",
            "regions" => "regions.json",
            "currency_ghs" => "currency_rates_ghs.json",
            "currency_ngn" => "currency_rates_ngn.json",
            "currency_usd" => "currency_rates_usd.json",
            "currency_gpb" => "currency_rates_gpb.json",
            "currency_eur" => "currency_rates_eur.json",
            "languages" => "languages.json",
            "blog_category" => "blog_category.json",
            "discussion_category" => "discussion_category.json",
            "pages" => "pages.json",
        ];

        return array();

        $JSON = file_get_contents(JSON_PATH . $files[$file]);

        if ($this->isStringifiedArray($JSON)) {
            return json_decode($this->trimData($JSON, true), true);
        }
        
        return array();
    }

    public function writeToJSONFile(string $file = null, $content = array())
    {
        /**
         * @param String $filename
         * @return bool
         */
        $files = [
            "skill_categories" => "skill_categories.json",
            "skill_categories_backup" => "skill_categories-backup.json",
            "skill_references" => "skill_references.json",
            "skill_references_backup" => "skill_references-backup.json",
            "skills" => "skills.json",
            "skills_backup" => "skills-backup.json",
            "regions" => "regions.json",
            "regions_backup" => "regions-backup.json",
            "currency_ghs" => "currency_rates_ghs.json",
            "currency_ghs_backup" => "currency_rates_ghs-backup.json",
            "currency_ngn" => "currency_rates_ngn.json",
            "currency_ngn_backup" => "currency_rates_ngn-backup.json",
            "currency_usd" => "currency_rates_usd.json",
            "currency_usd_backup" => "currency_rates_usd-backup.json",
            "currency_gpb" => "currency_rates_gpb.json",
            "currency_gpb_backup" => "currency_rates_gpb-backup.json",
            "currency_eur" => "currency_rates_eur.json",
            "currency_eur_backup" => "currency_rates_eur-backup.json",
            "languages" => "languages.json",
            "languages_backup" => "languages-backup.json",
            "blog_category" => "blog_category.json",
            "blog_category_backup" => "blog_category-backup.json",
            "discussion_category" => "discussion_category.json",
            "discussion_category_backup" => "discussion_category-backup.json",
        ];

        if (!isset($files[$file])) {
            return array();
        }

        if (file_exists(JSON_PATH . $files[$file])) {
            //Back up
            file_put_contents(JSON_PATH . $files[$file."_backup"], file_get_contents(JSON_PATH . $files[$file]));

            //Add new content
            if(is_array($content)){
                file_put_contents(JSON_PATH . $files[$file], json_encode($content, true));
            }
            else{
                file_put_contents(JSON_PATH . $files[$file], $content);
            }

            return true;
        }

        return false;
    }

    public function getCurrentURI(): String
    {
        /**
         * @return String
         */
        return urlencode($_SERVER["REQUEST_URI"]);
    }

    public function encodeURI(string $url = null): String
    {
        /**
         * @param String $url
         * @return String
         */
        return urlencode($url);
    }

    public function toLowerCase(string $str = null): String
    {
        /**
         * @param String $str
         * @return String
         */
        return strtolower($str);
    }

    public function toUpperCase(string $str = null): String
    {
        /**
         * @param String $str
         * @return String
         */
        return strtoupper($str);
    }

    public function ucFirst(string $str = null): String
    {
        /**
         * @param String $str
         * @return String
         */
        return ucfirst($str);
    }

    public function isEmpty(string $str = null): String
    {
        /**
         * @param String $str
         * @return String
         */
        return empty(trim($str)) ? true : false;
    }

    public function containsOnlyString(string $str = null): bool
    {
        /**
         * @param String $str
         * @return bool
         */
        if (preg_match("/[a-zA-Z]/i", $str)) {
            return true;
        }

        return false;
    }

    public function generateID(string $prefix = null, $l = -8): String
    {
        /**
         * @param String $prefix
         * @return String
         */
        $date = new DateTime();
        $time = $date->format('U');
        $id = $time . rand(1000, 9999);
        return strtoupper($prefix) . substr($id, $l);
    }

    public function hashGenerator(string $str = null): String
    {
        /**
         * @param String $str
         * @return String
         */
        $option = [
            'cost' => 12,
        ];
        return password_hash($str, PASSWORD_BCRYPT, $option);
    }

    public function fixCaps(string $str = null): String
    {
        /**
         * @param String $str
         * @return String
         */
        return ucwords($this->toLowerCase($str));
    }

    public function isZipOrRar(string $fname = null): bool
    {
        /**
         * @param String $fname[file name]
         * @return bool
         */
        if (empty($fname)) {
            return false;
        }

        $fh = @fopen($fname, "r");
        if (!$fh) {
            return false;
        }

        $blob = fgets($fh, 5);
        fclose($fh);
        if (strpos($blob, 'Rar') !== false) {
            return true;
        } else if (strpos($blob, 'PK') !== false) {
            return true;
        }

        return false;
    }

    public function isOnWatchList($id, $type)
    {
        /**
         * @param Array $idata
         * @return String
         */

        $conn = $this->PDOConnection(DB[4]);

        if (!$conn) return false;

        $result = $this->runQuery(
            $conn, 
            "SELECT NULL FROM `watch_list` WHERE `watch_id` = :watch_id AND `watch_type` = :watch_type LIMIT 1", 
            array(
                "watch_id" => $id,
                "watch_type" => $type,
            ),
            true,
            true
        );

        if($result) return true;
        return false;
    }

    public function getNextInsertId(string $dbname, string $tbname): int
    {
        /**
         * @param String $dbname[database name]
         * @param String $tbname[table name]
         * @return int
         */
        if (empty($dbname) || empty($dbname)) {
            return 0;
        }

        $conn = $this->PDOConnection(DB[4]);
        if (!$conn) {
            $this->throwConnectionError();
        }

        $result = $this->selectSingleData($conn, 'SELECT auto_increment AS next_id FROM information_schema.tables WHERE table_schema = "' . $dbname . '" AND table_name = "' . $tbname . '"');
        if ($result) {
            return $result["next_id"];
        }

        return 0;
    }

    public function calcDiffInDate($sd, $ed)
    {
        $date1 = $sd;
        $date2 = $ed;
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        return $days;
    }

    public function convertToHTMLEnt($str)
    {
        return htmlentities($str, ENT_QUOTES, "UTF-8");
    }

    public function getActualUID($cpuid, $uid)
    {
        return (($cpuid ?? false) === true) ? CPUID : $this->filterData($uid ?? 0);
    }

    public function getActualUAC($cpuac, $uac)
    {
        return (($cpuac ?? false) === true) ? CPUAC : $this->filterData($uac ?? 0);
    }

    public function FactoryClass()
    {
        return new Factory();
    }

    public function br2nl($input)
    {
        return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n", "", str_replace("\r", "", htmlspecialchars_decode($input))));
    }

    public function stripRaw($content)
    {
        $content = str_replace("\r", " ", $content);
        $content = str_replace("\n", " ", $content);
        return preg_replace("/&#?[a-z0-9\s]+;/i", " ", trim(preg_replace('#<[^>]+>#', ' ', $content)));
    }

    public function clean($string)
    {
        $string = str_replace(' ', '-', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', strip_tags($string));
    }

    public function realTextCount($str)
    {
        return strlen($this->clean($this->stripRaw($str)));
    }
    
    public function encryptString($str, $filterData = false)
    {
        $str = $str === true ? $this->filterData($str) : $str;
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = APPNAME;
        $encryption = openssl_encrypt($str, $ciphering, $encryption_key, $options, $encryption_iv);
        return str_replace("/", "_", str_replace("=", "-", $encryption));
    }

    public function decryptString($str, $filterData = false)
    {
        $str = str_replace("_", "/", str_replace("-", "=", $str));
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = $decryption_iv = '1234567891011121';
        $encryption_key = $decryption_key = APPNAME;
        $decryption = openssl_decrypt($str, $ciphering, $decryption_key, $options, $decryption_iv);
        return $filterData === true ? $this->filterData($decryption) : $decryption;
    }

    public function decodeHtmlEntities($str)
    {
        $ret = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
        $p2 = -1;
        for (;;) {
            $p = strpos($ret, '&#', $p2 + 1);
            if ($p === false) {
                break;
            }

            $p2 = strpos($ret, ';', $p);
            if ($p2 === false) {
                break;
            }

            if (substr($ret, $p + 2, 1) == 'x') {
                $char = hexdec(substr($ret, $p + 3, $p2 - $p - 3));
            } else {
                $char = intval(substr($ret, $p + 2, $p2 - $p - 2));
            }

            //echo "$char\n";
            $newchar = iconv(
                'UCS-4', 'UTF-8',
                chr(($char >> 24) & 0xFF) . chr(($char >> 16) & 0xFF) . chr(($char >> 8) & 0xFF) . chr($char & 0xFF)
            );
            //echo "$newchar<$p<$p2<<\n";
            $ret = substr_replace($ret, $newchar, $p, 1 + $p2 - $p);
            $p2 = $p + strlen($newchar);
        }
        return $ret;
    }

    public function getIPaddress()
    {
        /**
         * @return String
         */
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'] ?? null;
        } else {
            if (function_exists('getenv')) {
                return getenv("REMOTE_ADDR");
            }

            return null;
        }
    }

    public function getExternalIPaddress()
    {
        /**
         * @return String
         */

        if (ENV === "REMOTE") {
            return $this->getIPaddress();
        }

        $extIP = @file_get_contents('http://checkip.dyndns.com/');
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $extIP, $IP);
        return !$this->isEmpty($IP[1] ?? null) ? $IP[1] : $this->getIPaddress();
    }

    public function getIPInfo2()
    {
        if(ENV === "REMOTE"){
            $EIP = $_SERVER['REMOTE_ADDR'] ?? null;
        }
        else{
            $extIP = @file_get_contents('http://checkip.dyndns.com/');
            preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $extIP, $IP);
            $EIP = !empty(trim($IP[1] ?? null)) ? $IP[1] : ($_SERVER['REMOTE_ADDR'] ?? (function_exists('getenv') ? getenv("REMOTE_ADDR") : null));
        }

        //$info = @file_get_contents("https://api.myip.com/");
        $info = json_decode(@file_get_contents("http://ip-api.com/json/".$EIP), true);
		if(isset($info["status"]) && strtolower($info["status"] ?? null) == "success"){
            $info["ip"] = $EIP;
            return array(
                "exists" => true,
                "info" => $info
            );
        }
        
        return array(
            "exists" => false
        );
    }

    public function getIPInfo()
    {
        $ipInfo = $this->getIPInfo2();

        if($ipInfo["exists"]){
            return array(
                "ip_address" => $ipInfo["info"]["ip"] ?? null,
                "country_name" => $ipInfo["info"]["country"] ?? null,
                "country_code2" => $ipInfo["info"]["countryCode"] ?? null,
            );
        }
        else{
            $IP = $this->getExternalIPaddress();

            if (!$this->isEmpty($IP)) {
                $info = $this->makeSimpleCurlRequest(IP_INFO_URL . $IP);
                if ($this->isStringifiedArray($info)) {
                    $i = json_decode($info, true);
                    $i["ip_address"] = $IP;
                    return $i;
                }
            }

            return array(
                "ip_address" => $IP,
            );
        }
    }

    public function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        /**
         * @param Int $amount
         * @param String $fromCurrency
         * @param String $toCurrency
         */

        $fromCurrency = $this->toLowerCase($fromCurrency);
        $toCurrency = $this->toLowerCase($toCurrency);
        $currencyRates = $this->getCurrencyInfo();

        switch ($fromCurrency) {
            case "ghs":
                switch ($toCurrency) {
                    case "ngn":
                        $amount = $amount * $currencyRates["ghs"]["ngn"]["rate"];
                        break;
                    case "usd":
                        $amount = $amount * $currencyRates["ghs"]["usd"]["rate"];
                        break;
                    case "eur":
                        $amount = $amount * $currencyRates["ghs"]["eur"]["rate"];
                        break;
                    case "gbp":
                        $amount = $amount * $currencyRates["ghs"]["gbp"]["rate"];
                        break;
                    default:
                        $amount = $amount;
                }
                break;
            case "ngn":
                switch ($toCurrency) {
                    case "ghs":
                        $amount = $amount * $currencyRates["ngn"]["ghs"]["rate"];
                        break;
                    case "usd":
                        $amount = $amount * $currencyRates["ngn"]["usd"]["rate"];
                        break;
                    case "eur":
                        $amount = $amount * $currencyRates["ngn"]["eur"]["rate"];
                        break;
                    case "gbp":
                        $amount = $amount * $currencyRates["ngn"]["gbp"]["rate"];
                        break;
                    default:
                        $amount = $amount;
                }
                break;
            case "usd":
                switch ($toCurrency) {
                    case "ghs":
                        $amount = $amount * $currencyRates["usd"]["ghs"]["rate"];
                        break;
                    case "ngn":
                        $amount = $amount * $currencyRates["usd"]["ngn"]["rate"];
                        break;
                    case "eur":
                        $amount = $amount * $currencyRates["usd"]["eur"]["rate"];
                        break;
                    case "gbp":
                        $amount = $amount * $currencyRates["usd"]["gbp"]["rate"];
                        break;
                    default:
                        $amount = $amount;
                }
                break;
            case "eur":
                switch ($toCurrency) {
                    case "ghs":
                        $amount = $amount * $currencyRates["eur"]["ghs"]["rate"];
                        break;
                    case "ngn":
                        $amount = $amount * $currencyRates["eur"]["ngn"]["rate"];
                        break;
                    case "usd":
                        $amount = $amount * $currencyRates["eur"]["usd"]["rate"];
                        break;
                    case "gbp":
                        $amount = $amount * $currencyRates["eur"]["gbp"]["rate"];
                        break;
                    default:
                        $amount = $amount;
                }
                break;
            case "gbp":
                switch ($toCurrency) {
                    case "ghs":
                        $amount = $amount * $currencyRates["gbp"]["ghs"]["rate"];
                        break;
                    case "ngn":
                        $amount = $amount * $currencyRates["gbp"]["ngn"]["rate"];
                        break;
                    case "usd":
                        $amount = $amount * $currencyRates["gbp"]["usd"]["rate"];
                        break;
                    case "eur":
                        $amount = $amount * $currencyRates["gbp"]["eur"]["rate"];
                        break;
                    default:
                        $amount = $amount;
                }
                break;
        }

        return round($amount);
    }

    public function getCurrencyInfo()
    {
        $data_ghs = $this->readJSONFile("currency_ghs");
        $data_ngn = $this->readJSONFile("currency_ngn");
        $data_usd = $this->readJSONFile("currency_usd");
        $data_gbp = $this->readJSONFile("currency_gbp");
        $data_eur = $this->readJSONFile("currency_eur");
        return array(
            "ghs" => $data_ghs,
            "ngn" => $data_ngn,
            "usd" => $data_usd,
            "gbp" => $data_gbp,
            "eur" => $data_eur,
        );
    }

    public function updateCurrencyInfo()
    {
        try {
            //GHS
            $currency = @file_get_contents(CURRENCY_INFO_URL . "GHS");
            if ($this->isStringifiedArray($currency)) {
                $i = json_decode($currency, true);
                $data = $this->readJSONFile("currency");
                $data["usd"]["rate"] = $i["data"]["USD"] ?? 0.00;
                $data["ghs"]["rate"] = $i["data"]["GHS"] ?? 0.00;
                $data["ngn"]["rate"] = $i["data"]["NGN"] ?? 0.00;
                $data["eur"]["rate"] = $i["data"]["EUR"] ?? 0.00;
                $data["gbp"]["rate"] = $i["data"]["GBP"] ?? 0.00;
                $data["usd"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ghs"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ngn"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["eur"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["gbp"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                file_put_contents(CURRENCY_PATH_GHS, json_encode($data, JSON_NUMERIC_CHECK));
            }

            //NGN
            $currency = @file_get_contents(CURRENCY_INFO_URL . "NGN");
            if ($this->isStringifiedArray($currency)) {
                $i = json_decode($currency, true);
                $data = $this->readJSONFile("currency");
                $data["usd"]["rate"] = $i["data"]["USD"] ?? 0.00;
                $data["ghs"]["rate"] = $i["data"]["GHS"] ?? 0.00;
                $data["ngn"]["rate"] = $i["data"]["NGN"] ?? 0.00;
                $data["eur"]["rate"] = $i["data"]["EUR"] ?? 0.00;
                $data["gbp"]["rate"] = $i["data"]["GBP"] ?? 0.00;
                $data["usd"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ghs"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ngn"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["eur"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["gbp"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                file_put_contents(CURRENCY_PATH_NGN, json_encode($data, JSON_NUMERIC_CHECK));
            }

            //USD
            $currency = @file_get_contents(CURRENCY_INFO_URL . "USD");
            if ($this->isStringifiedArray($currency)) {
                $i = json_decode($currency, true);
                $data = $this->readJSONFile("currency");
                $data["usd"]["rate"] = $i["data"]["USD"] ?? 0.00;
                $data["ghs"]["rate"] = $i["data"]["GHS"] ?? 0.00;
                $data["ngn"]["rate"] = $i["data"]["NGN"] ?? 0.00;
                $data["eur"]["rate"] = $i["data"]["EUR"] ?? 0.00;
                $data["gbp"]["rate"] = $i["data"]["GBP"] ?? 0.00;
                $data["usd"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ghs"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ngn"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["eur"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["gbp"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                file_put_contents(CURRENCY_PATH_USD, json_encode($data, JSON_NUMERIC_CHECK));
            }

            //GBP
            $currency = @file_get_contents(CURRENCY_INFO_URL . "GBP");
            if ($this->isStringifiedArray($currency)) {
                $i = json_decode($currency, true);
                $data = $this->readJSONFile("currency");
                $data["usd"]["rate"] = $i["data"]["USD"] ?? 0.00;
                $data["ghs"]["rate"] = $i["data"]["GHS"] ?? 0.00;
                $data["ngn"]["rate"] = $i["data"]["NGN"] ?? 0.00;
                $data["eur"]["rate"] = $i["data"]["EUR"] ?? 0.00;
                $data["gbp"]["rate"] = $i["data"]["GBP"] ?? 0.00;
                $data["usd"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ghs"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ngn"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["eur"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["gbp"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                file_put_contents(CURRENCY_PATH_GBP, json_encode($data, JSON_NUMERIC_CHECK));
            }

            //EUR
            $currency = @file_get_contents(CURRENCY_INFO_URL . "EUR");
            if ($this->isStringifiedArray($currency)) {
                $i = json_decode($currency, true);
                $data = $this->readJSONFile("currency");
                $data["usd"]["rate"] = $i["data"]["USD"] ?? 0.00;
                $data["ghs"]["rate"] = $i["data"]["GHS"] ?? 0.00;
                $data["ngn"]["rate"] = $i["data"]["NGN"] ?? 0.00;
                $data["eur"]["rate"] = $i["data"]["EUR"] ?? 0.00;
                $data["gbp"]["rate"] = $i["data"]["GBP"] ?? 0.00;
                $data["usd"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ghs"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["ngn"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["eur"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                $data["gbp"]["timestamp"] = $i["query"]["timestamp"] ?? null;
                file_put_contents(CURRENCY_PATH_EUR, json_encode($data, JSON_NUMERIC_CHECK));
            }
        } catch (Exception $e) {}
    }

    public function countModifier($n, $txt, $ies = null)
    {
        switch ($n) {
            case 1:
                return "1 " . $txt;
                break;
            case 0:
                if (!empty($ies)) {
                    return $this->numberToKMBT($n) . " " . rtrim($txt, "y") . $ies;
                } else {
                    return "0 " . $txt . "s";
                }
                break;
            default:
                if (!empty($ies)) {
                    return $this->numberToKMBT($n) . " " . rtrim($txt, "y") . $ies;
                } else {
                    return $this->numberToKMBT($n) . " " . $txt . "s";
                }
        }
    }

    public function trimData($str, $adv = false)
    {
        if($adv) return trim($str, "\xEF\xBB\xBF");
        return trim($str);
    }

    public function countStr($str)
    {
        return strlen($str);
    }

    public function countWords($str)
    {
        return str_word_count($str);
    }

    public function stripHTML($str)
    {
        return strip_tags($str);
    }

    public function filterContent($str)
    {
        return mb_convert_encoding($this->decodeHtmlEntities($str) ?? null, "HTML-ENTITIES", "UTF-8");
    }

    public function verifyDate($date)
    {
        if (!empty($date)) {
            $pD = date("Y-m-d", strtotime($date));
            $d = explode("-", str_ireplace(" ", "", $pD));
            if (count($d) === 3) {
                $year = $d[0];
                $month = $d[1];
                $day = $d[2];

                if (checkdate($month, $day, $year)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isStrDateValid($str)
    {
        if (preg_match("/(^\d{1,})(\s{1})(hours|hour|days|day|week|weeks|months|month|years|year|minutes|minute)/i", $str)) {
            return true;
        }

        return false;
    }

    public function regenerateTitleIds()
    {
        /**
         * @param Int $id
         * @return String
         */
        $conn = $this->PDOConnection(DB[4]);

        $result = $this->runQuery(
            $conn,
            "SELECT `title`, `id` FROM `projects`",
            array(),
            true
        );

        if ($result) {
            foreach ($result as $r) {
                $this->runQuery(
                    $conn,
                    "UPDATE `projects` SET `title_id` = :title_id WHERE `id` = :id",
                    array(
                        "id" => $r["id"],
                        "title_id" => $this->generateTitleId($r["title"], "cpj"),
                    )
                );
            }
        }

        $result2 = $this->runQuery(
            $conn,
            "SELECT `title`, `id` FROM `market_items`",
            array(),
            true
        );

        if ($result2) {
            foreach ($result2 as $r2) {
                $this->runQuery(
                    $conn,
                    "UPDATE `market_items` SET `title_id` = :title_id WHERE `id` = :id",
                    array(
                        "id" => $r2["id"],
                        "title_id" => $this->generateTitleId($r2["title"], "csv"),
                    )
                );
            }
        }

        return null;
    }

    public function generateTitleId($str, $suffix = null)
    {
        $removed = array("i", "will", "am");
        $expStr = explode(" ", $this->toLowerCase($str));
        $newStr = array();
        foreach ($expStr as $s) {
            if (!in_array($this->toLowerCase($s), $removed)) {
                $newStr[] = $this->toLowerCase($s);
            }
        }
        return substr(preg_replace("/(-){2,}/i", "-", (preg_replace("/[^a-zA-Z0-9]/i", "-", $this->toLowerCase(implode(" ", $newStr))))), 0, 140) . "_~" . time() . uniqid() . $suffix;
    }

    public function explodeURL($url)
    {
        return explode("/", $url);
    }

    public function cutAfterDot($number, $afterDot = 2)
    {
        $a = $number * pow(10, $afterDot);
        $b = floor($a);
        $c = pow(10, $afterDot);
        echo "a $a, b $b, c $c<br/>";
        return $b/$c ;
    }

    public function numberFormat($n, $dp = 2)
    {
        //return $this->cutAfterDot($n, $dp);
        return number_format(floor($n * 100) / 100, $dp);
    }

    public function systemInfo()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = "Unknown OS Platform";
        $os_array = array('/windows phone 8/i' => 'Windows Phone 8',
            '/windows phone os 7/i' => 'Windows Phone 7',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile');
        $found = false;
        $addr = $this->getIPaddress();
        $device = '';
        foreach ($os_array as $regex => $value) {
            if ($found) {
                break;
            } else if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
                $device = !preg_match('/(windows|mac|linux|ubuntu)/i', $os_platform) ? 'MOBILE' : (preg_match('/phone/i', $os_platform) ? 'MOBILE' : 'SYSTEM');
            }
        }
        $device = !$device ? 'SYSTEM' : $device;
        return array(
            'os' => $os_platform,
            'device' => $device,
        );
    }

    public function detectBrowser()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser = "Unknown Browser";

        $browser_array = array('/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser');

        foreach ($browser_array as $regex => $value) {
            if ($found) {
                break;
            } else if (preg_match($regex, $user_agent, $result)) {
                $browser = $value;
            }
        }
        return $browser;
    }

    public function detectPlatform()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $dev = "web";
        if (preg_match('/Linux/i', $ua) || preg_match('/Macintosh/i', $ua) || preg_match('/mac os x/i', $ua) || preg_match('/Windows/i', $ua) || preg_match('/win32/i', $ua)) {
            $dev = "web";
        }
        if (preg_match('/Android/i', $ua) || preg_match('/Iphone/i', $ua) || preg_match('/Ipad/i', $ua) || preg_match('/Windows Phone/i', $ua) || preg_match('/iemobile/i', $ua) || preg_match('/WPDesktop/i', $ua)) {
            $dev = "mobile";
        }
        return $dev;
    }

    public function getUserNames($user_id)
    {
        $conn = $this->PDOConnection(DB[4]);
        if (!$conn) {
            return false;
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `first_name`, `last_name`, `full_name` FROM `users_admin` usr WHERE `id` = :user_id",
            array(
                "user_id" => $user_id,
            ),
            true,
            true
        );
        if ($result) {
            return $result;
        }
        return array(
            "first_name" => null,
            "last_name" => null,
            "full_name" => null,
        );
    }

    public function getUserIdWithEmail($email)
    {
        $conn = $this->PDOConnection(DB[4]);
        
        if (!$conn) {
            return false;
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `id` FROM `users_admin` usr WHERE `email_address` = :email",
            array(
                "email" => $email,
            ),
            true,
            true
        );

        if ($result) return $result["id"];
        return null;
    }

    public function userExistsAndIsActive($id)
    {
        $conn = $this->PDOConnection(DB[4]);
        if (!$conn) {
            return false;
        }

        $current_datetime = $this->getDatetime();
        $result = $this->selectSingleData($conn, "SELECT `id`, TIMESTAMPDIFF(DAY, '{$current_datetime}', `not_available_until`) AS TS FROM users WHERE `id` = '8' AND `is_activated` = 1 AND `is_blocked` = 0 HAVING (TS <= 0 OR TS IS NULL OR TS = '') LIMIT 1");
        if ($result) {
            return true;
        }

        return false;
    }

    public function userExists($data, $checkingWith = "email"): bool
    {
        $conn = $this->PDOConnection(DB[4]);
        if (!$conn) {
            return false;
        }

        $additionalQuery = null;
        switch ($checkingWith) {
            case "email":
                $additionalQuery = " `email` = '{$data}' ";
                break;
            case "id":
                $additionalQuery = " `user_id` = '{$data}' ";
                break;
            default:
                $additionalQuery = " `email` = '{$data}' ";
        }

        $result = $this->countData($conn, "SELECT NULL FROM users WHERE {$additionalQuery} LIMIT 1");
        if ($result > 0) {
            return true;
        }
        return false;
    }

    public function getLocalIP()
    {
        $exec = exec("hostname");
        $hostname = trim($exec);
        $ip = gethostbyname($hostname);
        return ($ip == "127.0.0.1") ? "localhost" : $ip;
    }

    public function redirectURL($url)
    {
        header("location:" . $url);
        die();
    }

    public function getProtocol()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return "https://";
        }

        return "http://";
    }

    public function fixLocalIP($URI)
    {
        if ($_SERVER["HTTP_HOST"] != $this->getLocalIP()) {
            $newURI = str_ireplace($_SERVER["HTTP_HOST"], "", $URI);
            $this->redirect($this->getProtocol() . $this->getLocalIP() . $newURI);
        }
    }

    // public function getLoader(){
    //     return '<!-- General loader -->
    //             <div class="general-loader">
    //                 <div class="tac svg-loader">'.$this->FactoryClass()->Page()->getLoading(6, "90px", "90px").'</div>
    //             </div>
    //             <!-- General loader -->
    //             <div class="flash-anime">
    //                 <div><i class="lnr lnr-link fl-ani"></i></div>
    //             </div>
    //             ';
    // }

    public function numberToKMBT($n, $precision = 1)
    {
        if ($n < 900) {
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }
        return $n_format . $suffix;
    }

    public function isVIDActive()
    {
        if (isset($_COOKIE['usx_v']) && !empty($_COOKIE['usx_v'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getBlogCategoryAssoc(){
        return $this->readJSONFile("blog_category");
    }

    public function getBlogCategory($category)
    {
        $category = $this->toLowerCase($category);
        $categoryList = $this->getBlogCategoryAssoc();
        foreach($categoryList as $categoryListItem){
            if($category == $categoryListItem[0]){
                return $categoryListItem[1];
            }
        }
    }

    public function covertHTMLEntities($str, $state = "encode")
    {
        switch ($state) {
            case "encode":
                $str = preg_replace("/[<]/i", "&lt;", $str);
                $str = preg_replace("/[>]/i", "&gt;", $str);
                $str = preg_replace("/[\"]/i", "&quot;", $str);
                $str = preg_replace("/[\']/i", "&apos;", $str);
                $str = preg_replace("/[=]/i", "&equals;", $str);
                $str = preg_replace("/[\/]/i", "&sol;", $str);
                return $str;
            case "decode":
                $str = preg_replace("/&lt;/i", "<", $str);
                $str = preg_replace("/&gt;/i", ">", $str);
                $str = preg_replace("/&quot;/i", "\"", $str);
                $str = preg_replace("/&apos;/i", "'", $str);
                $str = preg_replace("/&equals;/i", "=", $str);
                $str = preg_replace("/&sol;/i", "/", $str);
                return $str;
        }
    }

    public function generateQuickActionToken($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $action = $this->filterData($idata["action"] ?? null);
        $action_id = $this->filterData($idata["action_id"] ?? null);
        $user_id = $this->filterData($idata["user_id"] ?? null);
        $token = $this->generateID("QAT");
        $datetime = $this->getDatetime();

        $conn = $this->PDOConnection(DB[4]);

        if (!$conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $conn,
            "INSERT INTO `users_quick_action`(`action`, `action_id`, `user_id`, `token`, `datetime`) VALUES(:action, :action_id, :user_id, :token, :datetime)",
            array(
                "action" => $action,
                "action_id" => $action_id,
                "user_id" => $user_id,
                "token" => $token,
                "datetime" => $datetime,
            )
        );

        if ($result) {
            return DOMAIN . "qat/" . $token;
        }

        return null;
    }

    public function getQuickActionToken($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $token = $this->filterData($idata["token"] ?? null);

        $conn = $this->PDOConnection(DB[4]);

        if (!$conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $conn,
            "SELECT uqa.`id`, uqa.`action`, uqa.`action_id`, uqa.`user_id`, uqa.`datetime`, u.`email_address` FROM `users_quick_action` uqa LEFT JOIN `users_admin` u ON uqa.`user_id` = u.`id` WHERE uqa.`token` = :token LIMIT 1",
            array(
                "token" => $token,
            ),
            true,
            true
        );

        if ($result) {
            return $result;
        }

        return false;
    }

    public function removeQuickActionToken($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $token = $this->filterData($idata["token"] ?? null);

        $conn = $this->PDOConnection(DB[4]);

        if (!$conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $conn,
            "DELETE FROM `users_quick_action` WHERE `token` = :token LIMIT 1",
            array(
                "token" => $token,
            )
        );

        if ($result) {
            return true;
        }

        return false;
    }

    public function filterData($data, $type = null, $replaceMultipleSpacesWithOne = false, $stripTags = true)
    {
        /**
         * @param Mixed $data
         * @return Mixed
         */

        $data = ($stripTags === true) ? strip_tags(trim($data), "<br /><br>") : trim($data);
        switch ($type) {
            case "number":
                return (($data[0] ?? null) == 0) ? $data : intval(filter_var($data, FILTER_SANITIZE_NUMBER_INT));
                break;
            case "string":
                if ($replaceMultipleSpacesWithOne) {
                    $data = preg_replace("/(\s{2,}|[\n\t\r])/", " ", $data);
                }

                return addSlashes(filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
                break;
            case "float":
                return ($data[0] == 0) ? $data : floatval($data);
                break;
            case "array":
                return $data;
                break;
            default:
                if (is_numeric($data)) {
                    if ($data[0] == 0) {
                        return $data;
                    } else {
                        if (preg_match("/[.]/", $data) || is_float($data) || is_double($data)) {
                            return floatval($data);
                        } else {
                            return intval(filter_var($data, FILTER_SANITIZE_NUMBER_INT));
                        }
                    }
                } else {
                    if ($replaceMultipleSpacesWithOne) {
                        $data = preg_replace("/(\s{2,}|[\n\t\r])/", " ", $data);
                    }

                    return addSlashes(filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
                }
        }
    }

    public function birthday($birthday)
    {
        /**
         * @param String $birthday
         */
        if (date_create($birthday)->diff(date_create('today'))->y) {
            return date_create($birthday)->diff(date_create('today'))->y;
        } else {
            return "N/A";
        }
    }

    public function log($log)
    {
        /**
         * @param String $str
        */
        try{
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $logFile = fopen(LOG_PATH,"a+");
            fwrite($logFile, "Line: ".$caller["line"]."(".$caller["file"].") - ".(is_array($log) ? json_encode($log, true) : $log)." at ".date("Y-m-d H:i:sa")." - ".($_SERVER["REQUEST_URI"] ?? null)." ---- "."\n");
            fclose($logFile);
        }
        catch(Exception $e){

        }
    }

    public function validateURL($url)
    {
        /**
         * @param String $url
         */
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        return false;
    }

    public function isLoginTokenValid($idata = array())
    {
        /**
         * @param Array $idata
         * @return Void
         */

        $conn = $this->PDOConnection(DB[4]);

        if (!$conn) {
            return true;
        }

        $result = $this->runQuery(
            $conn,
            "SELECT ul.`token_expiry`, u.`is_blocked`, u.`is_activated`, u.`id` FROM `users_logins` ul RIGHT JOIN `users_admin` u ON ul.`user_id` = u.`id` WHERE ul.`token` = :token LIMIT 1",
            array(
                "token" => $this->decryptString(LGTK),
            ),
            true,
            true
        );

        if ($result) {
            $currentTime = strtotime(date("Y-m-d H:i:s")) * 1000;
            $tokenTime = strtotime(date("Y-m-d H:i:s", strtotime($result["token_expiry"]))) * 1000;
            if ($currentTime > $tokenTime || $this->filterData($result["id"]) != $this->filterData(UID) || $this->filterData($result["is_blocked"]) > 0 || $this->filterData($result["is_activated"]) < 1) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function addAllowedCountriesByUser($idata = array())
    {
        /**
         * @param Array $idata
         * @return Void
         */

        $user_id = $this->filterData($idata["user_id"] ?? null);
        $country_code = $this->filterData($idata["country_code"] ?? null);
        $country_name = $this->filterData($idata["country_name"] ?? null);

        $conn = $this->PDOConnection(DB[4]);

        if (!$conn) {
            return true;
        }

        $exists = $this->runQuery(
            $conn,
            "SELECT NULL FROM `user_logins_allowed_countries` WHERE `user_id` = :user_id AND `country_code` = :country_code LIMIT 1",
            array(
                "user_id" => $user_id,
                "country_code" => $country_code,
            ),
            true,
            true
        );

        if (!$exists) {
            $result = $this->runQuery(
                $conn,
                "INSERT INTO `user_logins_allowed_countries`(`user_id`, `country_code`, `country_name`, `datetime`) VALUES(:user_id, :country_code, :country_name, :datetime)",
                array(
                    "user_id" => $user_id,
                    "country_code" => $country_code,
                    "country_name" => $country_name,
                    "datetime" => $this->getDatetime(),
                )
            );
        }
    }

    public function isLoginCountryAllowedByUser($idata = array())
    {
        /**
         * @param Array $idata
         * @return Void
         */

        $user_id = $this->filterData($idata["user_id"] ?? null);
        $country_code = $this->filterData($idata["country_code"] ?? null);

        $conn = $this->PDOConnection(DB[4]);

        if (!$conn) {
            return true;
        }

        $result = $this->runQuery(
            $conn,
            "SELECT NULL FROM `user_logins_allowed_countries` WHERE `user_id` = :user_id AND `country_code` = :country_code AND `activated` > 0 LIMIT 1",
            array(
                "user_id" => $user_id,
                "country_code" => $country_code,
            ),
            true,
            true
        );

        if ($result) {
            return true;
        }

        return false;
    }

    public function redirect($URL = null)
    {
        if (!empty($URL)) {
            header("location: " . $URL);
        } else {
            header("location: " . DOMAIN . "404.php?info=Sorry! This page does not exist.");
        }
    }

    public function link_to_domain($url)
    {
        $query = @parse_url($url, PHP_URL_QUERY);
        $host = @parse_url($url, PHP_URL_HOST);
        if (count($query) > 0 && $host != "localhost" && $host != "cedijob.com") {
            return $url;
        } else {
            return $this->url_to_domain($url);
        }
    }

    public function url_to_domain($url)
    {
        $host = @parse_url($url, PHP_URL_HOST);
        if (!$host) {
            $host = $url;
        }

        if (substr($host, 0, 4) == "www.") {
            $host = substr($host, 4);
        }

        if (strlen($host) > 50) {
            $host = substr($host, 0, 47) . '...';
        }

        return $host;
    }

    public function getAllowedRequestMethods(): array
    {
        return array("POST", "GET");
    }

    public function getFileName($f, $ext): String
    {
        $path = $f;
        $file = basename($path);
        return basename($path, $ext);
    }

    public function encodeJSONData(array $data = array(), $fn = false): String
    {
        if (is_array($data)) {
            return json_encode($data, true);
        }

        return json_encode(array(), true);
    }

    public function decodeJSONData(string $data = null): array
    {
        if ($this->isStringifiedArray($data)) {
            return json_decode($data, true);
        }

        return json_decode(array(), true);
    }

    public function handlePassword($password, $confirm_password): bool
    {
        if ($password === $confirm_password && strlen($password) >= 5) {
            return true;
        } else {
            return false;
        }
    }

    public function isDateValid($date): bool
    {
        if (!preg_match("/0000/", $date) && !empty($date)) {
            return true;
        } else {
            return false;
        }
    }

    public function validateEmail($email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function extType($type, $format = false): String
    {
        if ($format === false) {
            $ext = explode("/", $type);
            return end($ext);
        } else {
            $ext = explode(".", $type);
            return end($ext);
        }
    }

    public function shortenCounts($number = 0)
    {
        /**
         * @param Int $number
         * @return Int
         */
        return $this->filterData($number) >= 10 ? "9+" : $number;
    }

    public function checkMediaType($type, $mediaType = "photo"): String
    {
        /**
         * @param String $type
         * @param String $mediaType
         */
        $ext = explode("/", $type);
        $ext = $this->toLowerCase(end($ext));
        switch ($mediaType) {
            case "photo":
                $allowed_ext = array("jpg", "jpeg", "png");
                break;
            case "video":
                $allowed_ext = array("mp4");
                break;
            case "animated":
                $allowed_ext = array("gif");
                break;
            case "audio":
                $allowed_ext = array("mp3", "webm", "ogg", "aac");
                break;
            case "general":
                $allowed_ext = array("png", "jpg", "jpeg", "pdf", "pptx", "doc", "docx", "zip");
                break;
            case "document":
                $allowed_ext = array("pdf", "word", "pptx", "doc", "docx", "zip");
                break;
        }

        if (!in_array($ext, $allowed_ext)) {
            return false;
        }

        return true;
    }

    public function checkMimeType($type, $mediaType = "photo"): String
    {
        /**
         * @param String $type
         * @param String $mediaType
         */
        $ext = $this->toLowerCase($type);
        switch ($mediaType) {
            case "photo":
                $allowed_ext = array("image/png", "image/jpeg", "image/jpg");
                break;
            case "general":
                $allowed_ext = array("image/png", "image/jpeg", "image/jpg", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/x-zip-compressed");
                break;
            case "document":
                $allowed_ext = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/x-zip-compressed");
                break;
        }

        if (!in_array($ext, $allowed_ext)) {
            return false;
        }

        return true;
    }

    public function getExtensionWithMimeType($type)
    {
        /**
         * @param String $type
         */
        switch ($this->toLowerCase($type)) {
            case "image/png":
                return "png";
            case "image/jpeg":
                return "jpeg";
            case "image/jpg":
                return "jpg";
            case "application/pdf":
                return "pdf";
            case "application/msword":
                return "doc";
            case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                return "docx";
            case "application/vnd.openxmlformats-officedocument.presentationml.presentation":
                return "pptx";
            case "application/x-zip-compressed":
                return "zip";
        }
    }

    public function getDateInterval($date, $get = "day")
    {
        switch ($get) {
            case "day":
                return date_create($date)->diff(date_create('today'))->d;
            default:
                return date_create($date)->diff(date_create('today'))->y;
        }
    }

    public function getImageExtension($path)
    {
        return $this->toLowerCase(pathinfo($path, PATHINFO_EXTENSION));
    }

    public function copyPasteImage($nim, $im)
    {
        file_put_contents($nim, file_get_contents($im));
    }

    public function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $this->toLowerCase($regs['domain']);
        }
        return false;
    }

    public function getHost($url)
    {
        $pieces = parse_url($url);
        return $pieces['host'] ?? null;
    }

    public function convertRawLinksToClickables($string, $st = false)
    {
        if ($st == false) {
            $string = str_ireplace("&nbsp;", " ", strip_tags($string, "<img><div><h1><span><br><p>"));
        }
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#i', $string, $match);
        for ($i = 0; $i < count($match[0]); $i++) {
            $string = str_replace($match[0][$i], " <a class=\"exturl speca\" target=\"" . (($this->getHost($match[0][$i]) === MASTER_HOSTNAME || $this->getHost($match[0][$i]) == IP) ? "_self" : "_blank") . "\" href=\"" . $match[0][$i] . "\">" . $match[0][$i] . "</a> ", $string);
        }
        return $string;
    }

    public function extractMarketLink($string)
    {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#i', $string, $match);
        for ($i = 0; $i < count($match[0]); $i++) {
            if (preg_match("/(market-item-details)/i", $match[0][$i]) && ($this->getHost($match[0][$i]) == MASTER_HOSTNAME || ($this->getHost($match[0][$i]) == IP))) {
                $expLink = explode("/", $match[0][$i]);
                return end($expLink) ?? null;
            }
        }
    }

    public function getWebPageDetailsFromHTMLString($str, $pt, $st = false)
    {
        set_time_limit(180);
        try {
            if ($pt == 0) {
                $string = $this->stripRaw($str);
                preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#i', $string, $match);
                if (count($match[0]) > 0) {
                    return array(
                        "title" => $this->filterData($this->get_title($match[0][0])),
                        "domain" => $this->filterData($this->link_to_domain($match[0][0])),
                        "url" => $this->filterData($match[0][0]),
                    );
                } else {
                    return array(
                        "title" => null,
                        "domain" => null,
                        "url" => null,
                    );
                }
            } else {
                return array(
                    "title" => null,
                    "domain" => null,
                    "url" => null,
                );
            }
        } catch (Exception $e) {
            return array(
                "title" => null,
                "domain" => null,
                "url" => null,
            );
        }
    }

    public function getWebPageTitle($url)
    {
        $str = file_get_contents($url);
        if (strlen($str) > 0) {
            $str = trim(preg_replace('/\s+/', ' ', $str));
            preg_match("/\<title\>(.*)\<\/title\>/i", $str, $title);
            return $title[1] ?? null;
        }
    }

    public function getImgtype($path)
    {
        return $this->toLowerCase(pathinfo($path, PATHINFO_EXTENSION));
    }

    public function copyPasteImg($nim, $im)
    {
        file_put_contents($nim, file_get_contents($im));
    }

    public function convertResizeImage($im, $nim, $nw = "original", $nh = "original")
    {
        $ext = $this->getImgtype($im);
        switch ($ext) {
            case "jpg":
            case "jpeg":
                $source = @imagecreatefromjpeg($im);
                if (!$source) {
                    $this->copyPasteImg($nim, $im);
                    return;
                }
                break;
            case "png":
                $source = @imagecreatefrompng($im);
                if (!$source) {
                    $this->copyPasteImg($nim, $im);
                    return;
                }
                break;
            default:
                $source = @imagecreatefrompng($im);
                if (!$source) {
                    $this->copyPasteImg($nim, $im);
                    return;
                }
        }
        try {
            list($width, $height) = getimagesize($im);
            if ($nw == "original" && $nh == "original") {
                $new_width = $width;
                $new_height = $height;
                $desti = imagecreatetruecolor($new_width, $new_height);
                $black = imagecolorallocate($desti, 0, 0, 0);
                imagecolortransparent($desti, $black);
            } else {
                $width = $width == 0 ? 1 : $width;
                $height = $height == 0 ? 1 : $height;
                $nw = ($width < $nw) ? $width : $nw;
                $nh = ($height < $nh) ? $height : $nh;
                $pc = $nw / $width;
                $new_height = $pc * $height;
                $new_width = $nw;
                $desti = imagecreatetruecolor($new_width, $new_height);
            }
            imagecopyresampled($desti, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($desti, $nim, 90);
        } catch (Exception $e) {
            $this->copyPasteImg($nim, $im);
        }
    }

    public function convertDatetimeToReadableString($time, $rawd = null)
    {
        $yesterday = array("A while ago", "A minute ago", "minutes ago", "An hour ago", "hours ago", "Yesterday");
        $yl = count($yesterday);
        $some_days_ago = array("days ago");
        $sdal = count($some_days_ago);
        $week = array("Last week");
        $wl = count($week);
        $sweek = array("weeks ago");
        $swl = count($sweek);
        $month = array("month ago");
        $ml = count($month);

        if (!empty($rawd)) {
            if (date("Y-m-d", strtotime($rawd)) === date("Y-m-d", strtotime("today"))) {
                return "Today";
            }
        }

        for ($i = 0; $i < $yl; $i++) {
            if (preg_match("/{$yesterday[$i]}/i", $time)) {
                return "Yesterday";
            }
        }

        for ($i = 0; $i < $sdal; $i++) {
            if (preg_match("/{$some_days_ago[$i]}/i", $time)) {
                return "Some days ago";
            }
        }

        for ($i = 0; $i < $wl; $i++) {
            if (preg_match("/{$week[$i]}/i", $time)) {
                return "Last week";
            }
        }

        for ($i = 0; $i < $swl; $i++) {
            if (preg_match("/{$sweek[$i]}/i", $time)) {
                return "Some weeks ago";
            }
        }

        for ($i = 0; $i < $ml; $i++) {
            if (preg_match("/{$month[$i]}/i", $time)) {
                return "Last month";
            }
        }

        return "Some time ago";
    }

    public function convertDatetimeToSimpleString($d): String
    {
        if (date("Y-m-d", strtotime($d)) == date("Y-m-d")) {
            return "Today at " . date("g:ia", strtotime($d));
        }
        return date("F jS", strtotime($d)) . ", " . date("Y", strtotime($d)) . " at " . date("g:ia", strtotime($d));
    }

    public function convertDatetime($datetime, $format = 1): String
    {
        $datetime = strtotime($datetime);
        $cur_time = time();
        $time_elapsed = $cur_time - $datetime;
        $seconds = $time_elapsed;
        $minutes = round($time_elapsed / 60);
        $hours = round($time_elapsed / 3600);
        $days = round($time_elapsed / 86400);
        $weeks = round($time_elapsed / 604800);
        $months = round($time_elapsed / 2600640);
        $years = round($time_elapsed / 31207680);
        // Seconds
        if ($seconds <= 60) {
            if ($seconds == 0) {
                return ($format == 1) ? "5s" : "A while ago";
            }
            return ($format == 1) ? "5s" : "A while ago";
        }
        // Minutes
        else if ($minutes <= 60) {
            if ($minutes == 1) {
                return ($format == 1) ? "1min" : "A minute ago";
            }
            return ($format == 1) ? $minutes . "min" : $minutes . " minutes ago";
        }
        // Hours
        else if ($hours <= 24) {
            if ($hours == 1) {
                return ($format == 1) ? "1h" : "An hour ago";
            }
            return ($format == 1) ? $hours . "h" : $hours . " hours ago";
        }
        // Days
        else if ($days <= 7) {
            if ($days == 1) {
                return ($format == 1) ? "1d" : "Yesterday";
            }
            return ($format == 1) ? $days . "d" : $days . " days ago";
        }
        // Weeks
        else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                return ($format == 1) ? "1w" : "Last week";
            }
            return ($format == 1) ? $weeks . "w" : $weeks . " weeks ago";
        }
        // Months
        else if ($months <= 12) {
            if ($months == 1) {
                return ($format == 1) ? "1mon" : "Last month";
            }
            return ($format == 1) ? $months . "mon" : $months . " months ago";
        }
        // Years
        else {
            if ($years == 1) {
                return ($format == 1) ? "1y" : "A year ago";
            }
            return ($format == 1) ? $years . "y" : $years . " years ago";
        }
    }

    public function makeSimpleCurlRequest($host)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);
    }

    public function sendMail($task, $email, $subject, $body, $uid = null, $name = null, $link = null, $linklabel = null)
    {
        $this->makeSimpleCurlRequest(CURL_MAIL_URL . urlencode("task=" . $task . "&email=" . $email . "&subject=" . $subject . "&body=" . $body . "&uid=" . $uid . "&name=" . $name . "&link=" . $link . "&link_label=" . $linklabel));
    }

    public function sendMailDirect($subject, $mail)
    {
        $ehead = 'From: ' . EMAIL . ' ' .
        'Reply-To: ' . $mail . " " .
        'X-Mailer: PHP/' . phpversion();
        $mailsend = mail(EMAIL, "$subject", "$mail", "$ehead");
    }

    public function getAllCountries($state = "get", $data = null)
    {
        $countries = array
            (
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, DR',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'North Korea',
            'KR' => 'South Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );

        switch ($state) {
            case "get":
                $options = "";
                foreach ($countries as $code => $country) {
                    $options .= "<option value='" . $code . "'>" . $country . "</option>";
                }
                return $options;
            case "get_selected":
                $options = "";
                foreach ($countries as $code => $country) {
                    $options .= "<option value='" . $code . "' " . (($code === $data || $country === $data) ? "selected" : null) . ">" . $country . "</option>";
                }
                return $options;
            case "check":
                foreach ($countries as $code => $country) {
                    if ($code === $data) {
                        return true;
                    }
                }
                return false;
            case "get_country_name":
                foreach ($countries as $code => $country) {
                    if ($code === $data) {
                        return $country;
                    }
                }
        }
    }

    public function blocks()
    {
        $response = array();
        $response["status"] = ERROR_CODE;
        $response["data"] = "It appears some people have bought this item making it unfit to delete it manually. To perform this action, please contact an administrator from our team for support: " . EMAIL . ".";
        die($this->encodeJSONdata($response));
    }

    public function getAge($d)
    {
        $d1 = date_create(date("Y-m-d"));
        $d2 = date_create(date("Y-m-d", strtotime($d)));
        return date_diff($d1, $d2)->y;
    }

    public function detectConditionWithTrueFalse($condition, $true, $false)
    {
        if ($condition) {
            return $true;
        }
        return $false;
    }

    public function convertMicrotimeToTime($secs)
    {
        try {
            $hours = floor($secs / (60 * 60));
            $min = $secs % (60 * 60);
            $minutes = floor($min / 60);
            $sec = $min % 60;
            $seconds = ceil($sec);
            if ($hours <= 9) {
                $hours = "0" . $hours;
            }
            $hours = $hours . ":";
            if (floor($secs / (60 * 60)) < 1) {
                $hours = "";
            }
            if ($minutes <= 9) {
                $minutes = "0" . $minutes;
            }
            $minutes = $minutes . ":";
            if (floor($min / 60) < 1) {
                $minutes = "00:";
            }
            if ($seconds <= 9) {
                $seconds = "0" . $seconds;
            }

            if (!is_numeric(ceil($sec))) {
                return "00:00";
            } else {
                return $hours . $minutes . $seconds;
            }
        } catch (Exception $e) {
            return "00:00";
        }
    }

    public function stringContainsBannedWords($str)
    {
        if (preg_match("/(\bpimp\b|\bsteal\b|\bgangsta\b|\bgangster\b|\bgee\b|\bmotherfucker\b|\bfucker\b|\bfucked\b|\bfucking\b|\bgansta\b|\bnigga\b|\bnigger\b|\bcrazy\b|\bmad\b|\bfoolish\b|\bfool\b|\bfuck\b|\bfuckoff\b|\bbullshit\b|\bshit\b|\bpussy\b|\bsex\b|\bsexy\b|\bporn\b|\bpornography\b|\bvagina\b|\bpenis\b|\bbreast\b|\banal\b|\btits\b|\btit\b|\bbitch\b|\bgold digger\b|\blesbian\b|\bgay\b|\bbisexual\b|\bkill\b|\bmurder\b|\bmarijuana\b|\bganga\b|\bweed\b|\bcocaine\b|\bheroine\b|\bgunshot\b|\brobbery\b|\bkilled\b|\bbomb\b|\bslave\b|\bslavery\b|\bhore\b|\bhooker\b|\bprostitue\b|\bcum\b|\bgang\b|\bgangbang\b)/i", $str)) {
            $this->increaseUserFlagedWordsWarnings();
            return true;
        }
        return false;
    }

    public function stringContainsEmailMobileNumber($str)
    {
        if (!empty($str)) {
            //check emails
            $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
            preg_match_all($pattern, $str, $matches);

            //check mobile phone numbers
            $patternMn = '/[0-9]{9,}/';
            preg_match_all($patternMn, $str, $matchesMn);

            if (count($matches[0]) > 0 || count($matchesMn[0]) > 0) {
                $this->increaseUserContactEntryWarnings();
                return true;
            }
            return false;
        }
        return false;
    }

    public function isImageValid($path)
    {
        try {
            $iSize = @getimagesize($path);
            if (in_array($iSize[2] ?? null, array(IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function isPDFValid($path)
    {
        try {
            $fp = fopen($path, 'r');
            fseek($fp, 0);
            $data = fread($fp, 5);
            if (strcmp($data, "%PDF-") == 0) {
                return true;
            } else {
                return false;
            }
            fclose($fp);
        } catch (Exception $e) {
            return false;
        }
    }

    public function isDocxValid($path)
    {
        try {
            $fp = fopen($path, 'r');
            fseek($fp, 0);
            $data = fread($fp, 2);
            if (strcmp($data, "PK") == 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getDatetime($strtotime = null)
    {
        if (!$this->isEmpty($strtotime)) {
            return date("Y-m-d H:i:s", strtotime($strtotime));
        }

        return date("Y-m-d H:i:s");
    }

    public function getDate($date = null)
    {
        if (!empty($date)) {
            return date("Y-m-d", strtotime($date));
        }

        return date("Y-m-d");
    }

    public function uploadFile($target_name = "file", $type = "photo", $path, $name, $use_custom_name = false)
    {
        if (!isset($_FILES[$target_name]['name'])) {
            return array("status" => "failed", "status_text" => "No media file was found.", "file_path" => null, "file_name" => null);
        }

        $filetype = $_FILES[$target_name]['type'] ?? null;
        $filesize = $_FILES[$target_name]['size'] ?? null;
        $tmp = $_FILES[$target_name]['tmp_name'] ?? null;
        $filename = $_FILES[$target_name]['name'] ?? null;
        $fname = ($use_custom_name === true) ? $name : $name . substr(md5(uniqid() . time()), 0, 15) . "." . $this->extType($filetype);
        $filename = str_ireplace($filename, $fname, $filename);

        if (!$this->checkMediaType($filetype, $type)) {
            $additionalNote = null;

            switch ($type) {
                case "photo":
                    if (!$this->isImageValid($tmp)) {
                        $this->deleteFile($tmp);
                        $response["status"] = ERROR_CODE;
                        $response["data"] = "Media extension not supported. The image is not a valid.";
                        die($this->encodeJSONdata($response));
                    }
                    $additionalNote = "Only .jpg, .jpeg, .png files are allowed. Choose the right image file and try again.";
                    break;
                case "video":
                    $additionalNote = "Only .mp4 files are allowed. Choose the a video with an .mp4 extension to proceed.";
                    break;
                case "animated":
                    $additionalNote = "Only .gif files are allowed. Choose a .gif image file and try again.";
                    break;
                case "audio":
                    $additionalNote = "Only .mp3, .webm, .ogg, and .acc audio files are allowed. Choose the right audio file and try again.";
                    break;
                case "general":
                    $additionalNote = "Only .png, .jpg, .jpeg, .pdf, .pptx, .doc, .docx and .zip files are allowed.";
                    break;
                case "document":
                    $additionalNote = "Only .pdf, .word, .pptx, .doc, .docx and .zip files are allowed.";
                    break;
            }

            return array(
                "status" => "failed",
                "status_text" => "Media extension not supported. " . $additionalNote,
                "file_path" => null,
                "file_name" => null,
            );
        }

        if (move_uploaded_file($tmp, $path . $filename)) {
            return array(
                "status" => "ok",
                "status_text" => "File successfully uploaded.",
                "file_path" => $path,
                "file_name" => $filename,
            );
        } else {
            return array(
                "status" => "failed",
                "status_text" => "Could not upload file. Try again.",
                "file_path" => null,
                "file_name" => null,
            );
        }
    }

    public function uploadMultipleFiles($target_name = "file", $type = "photo", $path, $name, $limit = 5)
    {
        if (count(array_filter($_FILES[$target_name]['name'])) > $limit) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "You are not allowed to upload more than {$limit} files.";
            die($this->encodeJSONdata($response));
        }

        $uploadedFiles = array();

        for ($i = 0; $i < count(array_filter($_FILES[$target_name]['name'])); $i++) {
            $filetype = $_FILES[$target_name]['type'][$i] ?? null;
            $filesize = $_FILES[$target_name]['size'][$i] ?? null;
            $tmp = $_FILES[$target_name]['tmp_name'][$i] ?? null;

            if (!$this->checkMimeType($filetype, $type) || $filesize > 10000000) {
                $additionalNote = null;
                switch ($type) {
                    case "photo":
                        if (!$this->isImageValid($tmp)) {
                            $this->deleteFile($tmp);
                            $response["status"] = ERROR_CODE;
                            $response["data"] = "Media extension not supported. One of the images has an invalid format.";
                            die($this->encodeJSONdata($response));
                        }
                        $additionalNote = "File size must not exceed 10MB. Only .jpg, .jpeg, .png files are allowed. Choose the right image file and try again.";
                        break;
                    case "document":
                        switch ($filetype) {
                            case "application/x-zip-compressed":
                                if (!$this->isZipOrRar($tmp)) {
                                    $this->deleteFile($tmp);
                                    $response["status"] = ERROR_CODE;
                                    $response["data"] = "Media extension not supported for one of the uploaded files.";
                                    die($this->encodeJSONdata($response));
                                }
                                break;
                            case "application/pdf":
                                if (!$this->isPDFValid($tmp)) {
                                    $this->deleteFile($tmp);
                                    $response["status"] = ERROR_CODE;
                                    $response["data"] = "Media extension not supported for one of the uploaded files.";
                                    die($this->encodeJSONdata($response));
                                }
                                break;
                        }
                        $additionalNote = "File size must not exceed 10MB. Only .pdf, .word, .pptx, .doc, .docx and .zip files are allowed.";
                        break;
                    case "general":
                        switch ($filetype) {
                            case "image/png":
                            case "image/jpeg":
                            case "image/jpg":
                                if (!$this->isImageValid($tmp)) {
                                    $this->deleteFile($tmp);
                                    $response["status"] = ERROR_CODE;
                                    $response["data"] = "Media extension not supported for one of the uploaded files.";
                                    die($this->encodeJSONdata($response));
                                }
                                break;
                            case "application/x-zip-compressed":
                                if (!$this->isZipOrRar($tmp)) {
                                    $this->deleteFile($tmp);
                                    $response["status"] = ERROR_CODE;
                                    $response["data"] = "Media extension not supported for one of the uploaded files.";
                                    die($this->encodeJSONdata($response));
                                }
                                break;
                            case "application/pdf":
                                if (!$this->isPDFValid($tmp)) {
                                    $this->deleteFile($tmp);
                                    $response["status"] = ERROR_CODE;
                                    $response["data"] = "Media extension not supported for one of the uploaded files.";
                                    die($this->encodeJSONdata($response));
                                }
                                break;
                        }
                        $additionalNote = "File size must not exceed 10MB. Only .png, .jpg, .jpeg, .pdf, .pptx, .doc, .docx and .zip files are allowed.";
                        break;
                }
                return $uploadedFiles;
            }
        }

        for ($i = 0; $i < count(array_filter($_FILES[$target_name]['name'])); $i++) {
            $filetype = $_FILES[$target_name]['type'][$i] ?? null;
            $filesize = $_FILES[$target_name]['size'][$i] ?? null;
            $tmp = $_FILES[$target_name]['tmp_name'][$i] ?? null;
            $filename = $actualFilename = $_FILES[$target_name]['name'][$i] ?? null;
            $ext = !empty($this->getExtensionWithMimeType($filetype)) ? $this->getExtensionWithMimeType($filetype) : $this->extType($filetype);
            $fname = $name . time() . uniqid() . "." . $ext;
            $filename = str_ireplace($filename, $fname, $filename);

            if (!$this->checkMimeType($filetype, $type) || $filesize > 10000000) {
                $additionalNote = null;
                switch ($type) {
                    case "photo":
                        $additionalNote = "File size must not exceed 10MB. Only .jpg, .jpeg, .png files are allowed. Choose the right image file and try again.";
                        break;
                    case "document":
                        $additionalNote = "File size must not exceed 10MB. Only .pdf, .word, .pptx, .doc, .docx and .zip files are allowed.";
                        break;
                    case "general":
                        $additionalNote = "File size must not exceed 10MB. Only .png, .jpg, .jpeg, .pdf, .pptx, .doc, .docx and .zip files are allowed.";
                        break;
                }
            } else {
                if (move_uploaded_file($tmp, $path . $filename)) {
                    $uploadedFiles[] = array(
                        "status" => "ok",
                        "status_text" => "File successfully uploaded.",
                        "file_path" => $path,
                        "file_name" => $filename,
                        "actual_file_name" => $actualFilename,
                        "file_size" => $filesize,
                    );
                }
            }
        }
        return $uploadedFiles;
    }

    public function deleteFile($file)
    {
        /**
         * @param String $file
         * @return
         */
        @unlink($file);
        return true;
    }

    public function countFiles($file)
    {
        /**
         * @param String $file
         * @return
         */
        return count($_FILES[$file]['name']);
    }

    public function convertPayLoadToQueryString($payLoad = array())
    {
        /**
         * @param Array $payLoad
         * @return String
         */
        $queryString = null;
        foreach ($payLoad as $key => $value) {
            if (!empty($value)) {
                $queryString .= "&" . $key . "=" . $value;
            }
        }
        return $queryString;
    }

    public function curlAPIRequestGET($URL = null, $payLoad = array(), $headers = array())
    {
        /**
         * @param String $URL
         * @param Array $payLoad
         * @param Array $headers
         * @return String
         */

        $err = 0;
        $response = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL . $this->convertPayLoadToQueryString($payLoad));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = 1;
        }

        curl_close($ch);

        if ($err > 0) {
            $response["requestStatus"] = ERROR_CODE;
            $response["requestData"] = "Error on reaching the URL, resource or path.";
            return ($this->encodeJSONdata($response));
        } else {
            if ($this->isStringifiedArray($output)) {
                return $output;
            } else {
                $response["requestStatus"] = ERROR_CODE;
                $response["requestData"] = "An unknown error occurred. It may be an unreacheable URL, path or resource.";
                return ($this->encodeJSONdata($response));
            }
        }
    }

    public function getGMTTime($d = null)
    {
        $date = new DateTime($this->isEmpty($d) ? date("Y-m-d H:i:s") : date("Y-m-d H:i:s", strtotime($d)));
        $date->setTimezone(new DateTimeZone('GMT'));
        return $date->format('D jS M y, H:ia') . " - GMT";
    }

    public function detectDevice($prefix = false)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $iPod = stripos($user_agent, "iPod");
        $iPhone = stripos($user_agent, "iPhone");
        $iPad = stripos($user_agent, "iPad");
        $Android = stripos($user_agent, "Android");
        $webOS = stripos($user_agent, "webOS");
        $chromeOS = stripos($user_agent, "chrome");

        if ($iPod) {
            return ($prefix == true ? "an " : null) . "iPod";
        } else if ($iPhone) {
            return ($prefix == true ? "an " : null) . "iPhone";
        } else if ($iPad) {
            return ($prefix == true ? "an " : null) . "iPad";
        } else if ($Android) {
            return ($prefix == true ? "an " : null) . "Android device";
        } else if ($webOS) {
            return ($prefix == true ? "a " : null) . "Web OS" . ($prefix == true ? " device" : null);
        } else {
            if (strpos($user_agent, 'MSIE') !== false) {
                return ($prefix == true ? "an " : null) . 'Internet explorer' . ($prefix == true ? " browser" : null);
            } elseif (strpos($user_agent, 'Trident') !== false) {
                return ($prefix == true ? "an " : null) . 'Internet explorer' . ($prefix == true ? " browser" : null);
            } elseif (strpos($user_agent, 'Firefox') !== false) {
                return ($prefix == true ? "a " : null) . 'Mozilla Firefox' . ($prefix == true ? " browser" : null);
            } elseif (strpos($user_agent, 'Chrome') !== false && strpos($user_agent, 'OPR') === false && strpos($user_agent, 'Opera') === false && strpos($user_agent, 'Opera Mini') === false) {
                return ($prefix == true ? "a " : null) . 'Google Chrome' . ($prefix == true ? " browser" : null);
            } elseif (strpos($user_agent, 'Opera Mini') !== false) {
                return ($prefix == true ? "an " : null) . "Opera Mini" . ($prefix == true ? " browser" : null);
            } elseif (strpos($user_agent, 'Opera') !== false) {
                return ($prefix == true ? "an " : null) . "Opera" . ($prefix == true ? " browser" : null);
            } elseif (strpos($user_agent, 'OPR') !== false) {
                return ($prefix == true ? "an " : null) . "Opera" . ($prefix == true ? " browser" : null);
            } elseif (strpos($user_agent, 'Safari') !== false) {
                return ($prefix == true ? "a " : null) . "Safari" . ($prefix == true ? " browser" : null);
            } else {
                return ($prefix == true ? "a " : null) . "Browser";
            }
        }
    }

    public function curlAPIRequestPOST($URL = null, $payLoad = array(), $headers = array())
    {
        /**
         * @param String $URL
         * @param Array $payLoad
         * @param Array $headers
         * @return String
         */

        $err = 0;
        $response = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, (isset($payLoad) && is_array($payLoad)) ? json_encode($payLoad, true) : array());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = 1;
        }

        curl_close($ch);

        if ($err > 0) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Error on reaching the specified URL, resource or path.";
            return ($this->encodeJSONdata($response));
        } else {
            if ($this->isStringifiedArray($output)) {
                return $output;
            } else {
                $response["status"] = ERROR_CODE;
                $response["data"] = "An unknown error occurred. It may be an unreacheable URL, path or resource.";
                return ($this->encodeJSONdata($response));
            }
        }
    }

    public function generateIDNumeric($min, $max, $quantity)
    {
        /**
         * @param Int $min
         * @param Int $max
         * @param Int $quantity
         * @return String
         */
        $numbers = range($min, $max);
        shuffle($numbers);
        return implode("", array_slice($numbers, 0, $quantity));
    }

    public function generateSpecialID($prefix, $max = 12)
    {
        /**
         * @param String $prefix
         * @return String
         */
        return $prefix . $this->generateIDNumeric(1, $max, 10);
    }

    public function downloadFile($file, $name, $mime_type = '', $fileToDelete)
    {
        if (!is_readable($file)) {
            die('File not found or inaccessible!-');
        }

        $size = filesize($file);
        $name = rawurldecode($name);
        $known_mime_types = array(
            "htm" => "text/html",
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "doc" => "application/msword",
            "jpg" => "image/jpg",
            "php" => "text/plain",
            "xls" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",
            "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "gif" => "image/gif",
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "png" => "image/png",
            "jpeg" => "image/jpg",
        );

        if ($mime_type == '') {
            $file_extension = $this->toLowerCase(substr(strrchr($file, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
            } else {
                $mime_type = "application/force-download";
            };
        };
        @ob_end_clean();
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }

            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }

        $chunksize = 1 * (1024 * 1024);
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE'])) {
                fseek($file, $range);
            }

            while (!feof($file) &&
                (!connection_aborted()) &&
                ($bytes_send < $new_length)
            ) {
                $buffer = fread($file, $chunksize);
                echo ($buffer);
                flush();
                $bytes_send += strlen($buffer);
            }

            if (!$this->isEmpty($fileToDelete)) {
                $this->deleteFile($fileToDelete);
            }
        } else {
            die('Error - can not open file.');
        }

    }

    public function featureGeoAvailability($feature = null, $return = false)
    {
        switch ($feature) {
            case "artisan_job_posting":
                $allowed = array("gh");
                $getIPInfo = $this->getIPInfo();
                if (!in_array($this->toLowerCase($getIPInfo["country_code2"] ?? null), $allowed)) {
                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Sorry! This feature is not available in your country yet.";
                    if ($return) {
                        return $response;
                    }

                    die($this->encodeJSONdata($response));
                } else {
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = "Feature is supported.";
                    if ($return) {
                        return $response;
                    }

                    die($this->encodeJSONdata($response));
                }
                break;
            case "artisan_job_posting_cc":
                $allowed = array("gh");
                if (!in_array($this->toLowerCase(COUNTRY_CODE), $allowed)) {
                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Sorry! This feature is not available in your country yet.";
                    if ($return) {
                        return $response;
                    }

                    die($this->encodeJSONdata($response));
                } else {
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = "Feature is supported.";
                    if ($return) {
                        return $response;
                    }

                    die($this->encodeJSONdata($response));
                }
                break;
        }
    }

    public function getCedijobCommissionAmount()
    {
        switch ($this->toLowerCase(CURRENT_CURRENCY)) {
            case "ngn":
                return $this->convertCurrency(FREELANCE_COMMISSION_AMOUNT, "GHS", "NGN");
                break;
            case "usd":
                return $this->convertCurrency(FREELANCE_COMMISSION_AMOUNT, "GHS", "USD");
                break;
            case "gbp":
                return $this->convertCurrency(FREELANCE_COMMISSION_AMOUNT, "GHS", "GBP");
                break;
            case "eur":
                return $this->convertCurrency(FREELANCE_COMMISSION_AMOUNT, "GHS", "EUR");
                break;
            default:
        }
    }

    public function randomPasswordGenerator()
    {
        /**
         * @return String
         */
        $alphaNumeric = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pw = array();
        $alphaNumericLength = strlen($alphaNumeric) - 1;
        for ($i = 0; $i < 8; $i++) {
            $pw[] = $alphaNumeric[rand(0, $alphaNumericLength)];
        }
        return implode($pw);
    }

    public function getDiscussionCategoryList() : Array 
    {
        /**
         * @return Array
        */
        return $this->readJSONFile("discussion_category");
    }

    public function getDiscussionCategoryValue($category = null) : String 
    {
        /**
         * @param String $category
         * @return String
        */

        $category = $this->toLowerCase($category);

        foreach($this->getDiscussionCategoryList() as $categoryListItemKey => $categoryListItemValue){
            if($categoryListItemKey === $category){
                return $categoryListItemValue;
            }
        }

        return "";
    }

    public function getReviewCategoryList() : Array 
    {
        /**
         * @return Array
        */
        return $this->readJSONFile("review_category");
    }

    public function getReviewCategoryValue($category = null) : String 
    {
        /**
         * @param String $category
         * @return String
        */

        $category = $this->toLowerCase($category);

        foreach($this->getReviewCategoryList() as $categoryListItemKey => $categoryListItemValue){
            if($categoryListItemKey === $category){
                return $categoryListItemValue;
            }
        }

        return "";
    }

    public function logRequestInput($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */
        
        $headers = function_exists("getallheaders") ? json_encode(getallheaders()) : null;
        $endpoint = $_SERVER["REQUEST_URI"] ?? null;
        $ip_address = $_SERVER["REMOTE_ADDR"] ?? null;
        $referer = $_SERVER["HTTP_REFERER"] ?? null;
        $endpoint_method = $this->filterData($idata["task"] ?? null);
        $body = json_encode($idata["data"] ?? array());
        $user_id = $this->filterData($idata["user_id"] ?? null);
        $user_type = $this->filterData($idata["user_type"] ?? null);

        $conn = $this->PDOConnection(DB[4]);
        
        if (!$conn) {
            $this->throwConnectionError(true);
        }

        //Store logs
        $result = $this->runQuery(
            $conn,
            "INSERT INTO `logs`(`ip_address`, `referer`, `headers`, `endpoint`, `endpoint_method`, `datetime`, `user_id`, `user_type`, `data`) VALUES(:ip_address, :referer, :headers, :endpoint, :endpoint_method, :datetime, :user_id, :user_type, :data)",
            array(
                "headers" => $headers,
                "endpoint" => $endpoint,
                "endpoint_method" => $endpoint_method,
                "ip_address" => $ip_address,
                "referer" => $referer,
                "datetime" => $this->getDatetime(),
                "user_id" => $user_id,
                "user_type" => $user_type,
                "data" => $body,
            )
        );

        if($result){
            $response["status"] = SUCCESS_CODE;
            $response["statusText"] = "Logs have been stored successfully.";
            return $response;
        }

        $response["status"] = ERROR_CODE;
        $response["statusText"] = "Failed to store logs.";
        return $response;
    }

}
