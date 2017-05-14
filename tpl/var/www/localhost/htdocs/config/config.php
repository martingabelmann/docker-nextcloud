<?php
${D}CONFIG = array (
    'apps_paths' => array (
        0 => array (
            "path"     => "$NC_WWW/apps",
            "url"      => "/apps",
            "writable" => false,
        ),
        1 => array (
            "path"     => "$NC_WWW/apps2",
            "url"      => "/apps2",
            "writable" => true,
        )
    ),
    'trusted_domains' => array ($NC_TRUSTED_DOMAINS),
    'datadirectory' => '$NC_DATADIR',
    'default_language' => '$NC_LANGUAGE',
    'defaultapp' => '$NC_DEFAULTAPP',
    'overwritehost' => '$NC_OVERWRITEHOST',
    'loglevel' => $NC_LOGLEVEL,
    'mail_from_address' => '$NC_MAIL_FROM_ADDRESS',
    'mail_smtpmode' => '$NC_MAIL_SMTPMODE',
    'mail_domain' => '$NC_MAIL_DOMAIN',
    'mail_smtpauthtype' => '$NC_MAIL_SMTPAUTHTYPE',
    'mail_smtpauth' => $NC_MAIL_SMTPAUTH,
    'mail_smtphost' => '$NC_MAIL_SMTPHOST',
    'mail_smtpport' => '$NC_MAIL_SMTPPORT',
    'mail_smtpname' => '$NC_MAIL_SMTPNAME',
    'mail_smtpsecure' => '$NC_MAIL_SMTPSECURE',
    'mail_smtppassword' => '$NC_MAIL_SMTPPASSWORD',
    'memcache.local' => '\\OC\\Memcache\\APCu',
);
