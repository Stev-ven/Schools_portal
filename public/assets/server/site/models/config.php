<?php
//BASIC
if (!defined("API_KEY")) {
    define("API_KEY", "2y10wDtlm0nYB6OelftUp0LgHGvz3lwbnXBjaIi1DFDbYfF0ZHBE26", false);
}

if (!defined("APPNAME")) {
    define("APPNAME", "TestApp", false);
}

if (!defined("VERSION_QUERYSTRING")) {
    define("VERSION_QUERYSTRING", "?v=JK-55-KA-PL-09-OI-ZA-OI-PO", false);
}

if (!defined("CURRENCY_GHS")) {
    define("CURRENCY_GHS", "GHS", false);
}

if (!defined("CURRENCY_TEXT_GHS")) {
    define("CURRENCY_TEXT_GHS", "GHS₵", false);
}

if (!defined("CURRENCY_RAW_GHS")) {
    define("CURRENCY_RAW_GHS", "₵", false);
}

if (!defined("ENV")) {
    define("ENV", "LOCAL", false);
}

//CONTACTS
if (!defined("EMAIL")) {
    define("EMAIL", "testingemailerapp@gmail.com", false);
}

if (!defined("SUPPORT_EMAIL")) {
    define("SUPPORT_EMAIL", "support@en.com", false);
}

if (!defined("MOBILE")) {
    define("MOBILE", "", false);
}

//URLs AND IMAGES
if (!defined("IP")) {
    require_once "getip.php";
    define("IP", getLocalIp(), false);
}

if (!defined("DOMAIN")) {
    define("DOMAIN", "http://" . IP . DIRECTORY, false);
}

if (!defined("MEDIA_DOMAIN")) {
    define("MEDIA_DOMAIN", "http://" . IP . DIRECTORY . "assets/media/", false);
}

if (!defined("RESOURCE_DOMAIN")) {
    define("RESOURCE_DOMAIN", "http://" . IP . DIRECTORY, false);
}

if (!defined("DOMAIN_RAW")) {
    define("DOMAIN_RAW", IP . DIRECTORY, false);
}

if (!defined("USER_PORTAL_DOMAIN")) {
    define("USER_PORTAL_DOMAIN", "http://" . IP . DIRECTORY, false);
}

if (!defined("USER_PORTAL_DOMAIN_RAW")) {
    define("USER_PORTAL_DOMAIN_RAW", IP . DIRECTORY, false);
}

if (!defined("DOMAIN_API_URL")) {
    define("DOMAIN_API_URL", "http://" . IP . DIRECTORY."assets/server/site/controller/controller", false);
}

if (!defined("USER_PORTAL_DOMAIN_API_URL")) {
    define("USER_PORTAL_DOMAIN_API_URL", USER_PORTAL_DOMAIN."resources/server/site/controller/controller_admin", false);
}

if (!defined("SITE_ROOT")) {
    define("SITE_ROOT", ROOT . "assets/server/site/", false);
}

if (!defined("VENDOR_PATH")) {
    define("VENDOR_PATH", ROOT . "assets/server/vendors/vendor/", false);
}

if (!defined("MEDIA_PATH")) {
    define("MEDIA_PATH", "assets/media/", false);
}

if (!defined("IMAGE_PATH")) {
    define("IMAGE_PATH", ROOT . "assets/media/", false);
}

if (!defined("IMAGE_PATH_URL")) {
    define("IMAGE_PATH_URL", MEDIA_DOMAIN . "assets/media/", false);
}

if (!defined("LOG_PATH")) {
    define("LOG_PATH", ROOT . "logs/log.txt", false);
}

if (!defined("JSON_PATH")) {
    define("JSON_PATH", USER_PORTAL_DOMAIN."resources/server/site/json/", false);
}

if (!defined("LOGOUT_URL")) {
    define("LOGOUT_URL", DOMAIN . "assets/server/site/models/logout.php", false);
}

if (!defined("ERROR_IMAGE")) {
    define("ERROR_IMAGE", MEDIA_DOMAIN . "default/notfound.jpg", false);
}

if (!defined("PROFILE_ERROR_IMAGE")) {
    define("PROFILE_ERROR_IMAGE", MEDIA_DOMAIN . "default/uub_all.png", false);
}

if (!defined("IP_INFO_URL")) {
    define("IP_INFO_URL", "https://api.iplocation.net/?ip=", false);
}

if (!defined("CURRENCY_INFO_URL")) {
    define("CURRENCY_INFO_URL", "https://freecurrencyapi.net/api/v2/latest?apikey=a4376f10-8b3e-11ec-9ca5-e3065f0f56fd&base_currency=", false);
}

//EMAIL
if (!defined("MAILER")) {
    define("MAILER", ["smtp.gmail.com", 587, "testingemailerapp@gmail.com", "testingemailerapp12345"], false);
}

//USER
if (!defined("IS_LOGGED_IN")) {
    define("IS_LOGGED_IN", "yes", false);
}

if (!defined("USERNAME")) {
    define("USERNAME", "Kwame Duodu", false);
}

if (!defined("NICKNAME")) {
    define("NICKNAME", "Kwamelal", false);
}

if (!defined("USERID")) {
    define("USERID", 1, false);
}

if (!defined("PROFILE_PHOTO")) {
    define("PROFILE_PHOTO", $_SESSION["admin_profile_photo_small"] ?? null, false);
}

if (!defined("USERID_ENCRYPTED")) {
    define("USERID_ENCRYPTED", "13WERYUR", false);
}

//STATUS CODES
if (!defined("LOGIN_ERROR_CODE")) {
    define("LOGIN_ERROR_CODE", "login_failed", false);
}

if (!defined("DB_CONNECTION_ERROR_CODE")) {
    define("DB_CONNECTION_ERROR_CODE", "connection_failed", false);
}

if (!defined("ERROR_CODE")) {
    define("ERROR_CODE", "_failed", false);
}

if (!defined("SUCCESS_CODE")) {
    define("SUCCESS_CODE", "ok", false);
}

//DB
if (!defined("DB")) {
    define("DB", ["localhost", "root", "2@zigi", "3306", "sendme"], false);
}
