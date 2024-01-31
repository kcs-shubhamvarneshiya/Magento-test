SET @newurl = "https://stage4.visualcomfort.com/";
SET @newenv = "stage4.visualcomfort.com";
SET @newadmin = "https://stage4.visualcomfort.com/";
SET @newadminenv = "stage4.visualcomfort.com";

-- Update Base URLS
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/unsecure/base_url',@newurl);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/secure/base_link_url',@newurl);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/secure/base_url',@newurl);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/secure/base_link_url',@newurl);

-- Update cookie domain for local
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'web/cookie/cookie_domain',@newadminenv);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('websites',1,'web/cookie/cookie_domain',@newenv);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('websites',11,'web/cookie/cookie_domain',@newenv);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('websites',12,'web/cookie/cookie_domain',@newenv);

-- Update custom admin url/path
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('default',0,'admin/url/custom',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/unsecure/base_url',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/unsecure/base_link_url',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/secure/base_url',@newadmin);
REPLACE INTO `core_config_data` (`scope`,`scope_id`,`path`,`value`) VALUES ('stores',0,'web/secure/base_link_url',@newadmin);

-- Fastly
UPDATE core_config_data SET value = 'xXYYCZSM3VWMjptaWVfEUP4ysiLvrsz_' WHERE path = 'system/full_page_cache/fastly/fastly_api_key';
UPDATE core_config_data SET value = 'iwaimAh994Mu9sde6qvnc3' WHERE path = 'system/full_page_cache/fastly/fastly_service_id';

-- Disable Xtento export profiles for Bloomreach
UPDATE xtento_productexport_profile set enabled = 0 where name like 'Bloomreach JSON%';
