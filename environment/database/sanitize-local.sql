SET @newurl = "https://circa.lcgdocker.com/";
SET @newenv = "circa.lcgdocker.com";
SET @newadmin = "https://circa.lcgdocker.com/";

-- Update Base URLS
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/unsecure/base_url',@newurl);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/secure/base_link_url',@newurl);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/secure/base_url',@newurl);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/secure/base_link_url',@newurl);

-- Update cookie domain for local
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/cookie/cookie_domain',@newenv);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('websites',1,'web/cookie/cookie_domain',@newenv);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('websites',11,'web/cookie/cookie_domain',@newenv);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('websites',12,'web/cookie/cookie_domain',@newenv);

-- Update custom admin url/path
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'admin/url/custom',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/unsecure/base_url',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/unsecure/base_link_url',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/secure/base_url',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/secure/base_link_url',@newadmin);
UPDATE `core_config_data` SET value = 0 WHERE path IN ('admin/url/use_custom', 'admin/url/use_custom_path');