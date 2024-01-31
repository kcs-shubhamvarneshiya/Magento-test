-- Enable DEMO Store
UPDATE core_config_data SET value = '1' WHERE path = 'design/head/demonotice';

-- Disable minify/merging
DELETE FROM core_config_data WHERE path = 'dev/template/minify_html';
DELETE FROM core_config_data WHERE path = 'dev/css/merge_css_files';
DELETE FROM core_config_data WHERE path = 'dev/js/merge_files';
DELETE FROM core_config_data WHERE path = 'dev/js/enable_js_bundling';
DELETE FROM core_config_data WHERE path = 'dev/js/minify_files';

-- Remove search_query information to reduce database size
DELETE FROM search_query WHERE redirect IS NULL;

-- Drop old Amasty tables
DROP TABLE IF EXISTS am_customform_answer;
DROP TABLE IF EXISTS am_customform_form;

-- Amasty Custom Form
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'amasty_customform/email/recipient_email';

-- Amasty Elasticsearch
UPDATE core_config_data SET value = '127.0.0.1' WHERE path = 'amasty_elastic/connection/server_hostname';
UPDATE core_config_data SET value = '1' WHERE path = 'amasty_elastic/debug/log_raw_request';
UPDATE core_config_data SET value = '1' WHERE path = 'amasty_elastic/debug/log_raw_response';
UPDATE core_config_data SET value = '1' WHERE path = 'amasty_elastic/debug/log_request_object';
UPDATE core_config_data SET value = '1' WHERE path = 'amasty_elastic/debug/log_response_object';

-- Disable reCaptcha
UPDATE core_config_data SET value = '0' WHERE path = 'aminvisiblecaptcha/general/enabledCaptcha';
DELETE FROM core_config_data WHERE path = 'recaptcha_frontend/type_for/customer_create';

-- Magento analytics
UPDATE core_config_data SET value = '' WHERE path = 'analytics/general/token';
UPDATE core_config_data SET value = '0' WHERE path = 'analytics/subscription/enabled';

-- Payment
UPDATE core_config_data SET value = 'custom1' WHERE path = 'checkout/payment_failed/receiver';

-- Company
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'company/email/company_registration_copy';

-- Emails
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'contact/email/recipient_email';
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'trans_email/ident_general/email';
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'trans_email/ident_sales/email';
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'trans_email/ident_support/email';
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'trans_email/ident_custom1/email';
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'trans_email/ident_custom2/email';
UPDATE core_config_data SET value = 'zhazell@circalighting.com' WHERE path = 'rto/general/representative_email';

-- Bloomreach Engagement
UPDATE core_config_data SET value = REPLACE(value, 'token: "b787f8d4-806a-11ed-af3d-7a84ef350d7d"', 'token: "2d1861e4-09d2-11ec-b459-b2b5c3bf9aea"') WHERE path = 'design/head/includes';

-- Human Security
UPDATE core_config_data SET value = REPLACE(value, '//client.px-cloud.net/PXC3DzKfTi/main.min.js', '//client.px-cloud.net/PXRUyTF5gu/main.min.js') WHERE path = 'design/head/includes';

-- Robots
UPDATE core_config_data SET value = "User-agent: *\nDisallow: /" WHERE path = 'design/search_engine_robots/custom_instructions';
UPDATE core_config_data SET value = 'NOINDEX, NOFOLLOW' WHERE path = 'mageworx_seo/base/robots/category_ln_pages_robots';
UPDATE core_config_data SET value = '*' WHERE path = 'mageworx_seo/base/robots/noindex_nofollow_user_pages';

-- Salespad
UPDATE core_config_data SET value = 1 WHERE path = 'lyonscg_salespad/general/staging_enabled';
UPDATE core_config_data SET value = 'zhazell@circalighting.com,cklibowitz@circalighting.com' WHERE path = 'lyonscg_salespad/sync/order_sync_error_email';
UPDATE core_config_data SET value = 'customerservice@staging.circalighting.com' WHERE path = 'lyonscg_salespad/sync/order_sync_error_email_from';
UPDATE core_config_data SET value = 0 WHERE path = 'lyonscg_salespad/sync/quote_sync_enabled';

-- Affirm
UPDATE core_config_data SET value = 'sandbox' WHERE path = 'payment/affirm_gateway/mode';
UPDATE core_config_data SET value = '0:3:jTvuyEEKWIJqZ7WznZBYzwAzaGVJp0swqR/e5STzDoSCTctkDPpnFqRBt4V9rOYkMnkewN7uNeY1gPTj' WHERE path = 'payment/affirm_gateway/private_api_key_sandbox';

-- Payfabric
UPDATE core_config_data SET value = 1 WHERE path = 'payment/payfabric/debug';
UPDATE core_config_data SET value = '2:f047844f-b6ab-b146-87a2-12c0dc2b146a' WHERE path = 'payment/payfabric/device_id' AND scope_id = 0;
UPDATE core_config_data SET value = '2:f047844f-b6ab-b146-87a2-12c0dc2b146a' WHERE path = 'payment/payfabric/device_id' AND scope_id IN (11,12);
UPDATE core_config_data SET value = '0:3:NUgmNGZ58xgIqom0zRdet7aq60bcOXDmG1bVmXzIHQaoAUQqnOPaKrqT5ZRd/w==' WHERE path = 'payment/payfabric/device_password' AND scope_id = 0;
UPDATE core_config_data SET value = '0:3:MFUd6t5F8jPCP/OfrnvB1t31f7r3jdnv8WGwxpwPkndFIcnpFmRPwObvTCB0jw==' WHERE path = 'payment/payfabric/device_password' AND scope_id IN (11,12);
UPDATE core_config_data SET value = 1 WHERE path = 'payment/payfabric/enable_detailed_errors';
UPDATE core_config_data SET value = 'Authnet' WHERE path = 'payment/payfabric/setup_id' AND scope_id = 0;
UPDATE core_config_data SET value = 'CyberSourceUKTest' WHERE path = 'payment/payfabric/setup_id' AND scope_id IN (11);
UPDATE core_config_data SET value = 'CyberSourceEUTest' WHERE path = 'payment/payfabric/setup_id' AND scope_id IN (12);
UPDATE core_config_data SET value = 'sandbox' WHERE path = 'payment/payfabric/transaction_mode';

-- Xtento
UPDATE core_config_data SET value = 'd1efce19897d7182d72f6c4c6ea900522d56de35' WHERE path = 'productexport/general/serial';

-- Avatax
UPDATE core_config_data SET value = '0:3:DCcpJqd8U/BSLBvYAFabpG/1PzFSiLR//LJutu4oQ5dyJxD9P8M=' WHERE path = 'tax/avatax/development_account_number';
UPDATE core_config_data SET value = 'CIRS' WHERE path = 'tax/avatax/development_company_code';
UPDATE core_config_data SET value = '2688092' WHERE path = 'tax/avatax/development_company_id';
UPDATE core_config_data SET value = '0:3:YoNopZFK79AlFLiqmYpHrJrsCmJshgsjt+2CMYqMYNb3AXmVelR0mKQls+I=' WHERE path = 'tax/avatax/development_license_key';
UPDATE core_config_data SET value = 0 WHERE path = 'tax/avatax/live_mode';

-- Bloomreach
UPDATE core_config_data SET value = 'stage' WHERE path = 'bloomreach_settings/api_url/autosuggest_endpoint';
UPDATE core_config_data SET value = 'stage' WHERE path = 'bloomreach_settings/api_url/search_endpoint';
UPDATE core_config_data SET value = 'stage' WHERE path = 'bloomreach_settings/api_url/category_endpoint';
UPDATE core_config_data SET value = 'stage' WHERE path = 'bloomreach_settings/api_url/widgets_endpoint';
UPDATE core_config_data SET value = 'stage' WHERE path = 'bloomreach_settings/api_url/thematic_endpoint';
UPDATE core_config_data SET value = 'stage' WHERE path = 'data_feed/connection/environment';
UPDATE core_config_data SET value = 'circalighting-staging-9f12f37c-195f-453f-900c-0bcc9c3ab528' WHERE path = 'data_feed/connection/stage_api_key';

-- Fastly
UPDATE core_config_data SET value = 'jcy5TuwfhPaf39Nhgcc29P1nFqJ-uKHK' WHERE path = 'system/full_page_cache/fastly/fastly_api_key';
UPDATE core_config_data SET value = '5CVBTXKknvITTYXlqhczv0' WHERE path = 'system/full_page_cache/fastly/fastly_service_id';

-- Cybersource B2B
UPDATE core_config_data SET value = 1 WHERE path ='payment/chcybersource/debug';
UPDATE core_config_data SET value = 1 WHERE path = 'payment/chcybersource/use_test_wsdl';
UPDATE core_config_data SET value = 'vcc_poc' WHERE path = 'payment/chcybersource/merchant_id' AND scope_id = 1;
UPDATE core_config_data SET value = '0:3:1dz8mhPBL8Ec+yWBSC0v3VYQXTqpoeKEV9B81ZuNo0g84+U0M6lwp+9EBLcMMLVc4hk+8A0mOKbxpKDYHI/feIGkjB4JAudlXZ4sboP/f07Q7O/NGm8ucIo8CAMbqmb5McGWRvViXSYfMSx5dc6f2MqNCWSTnQ7UKXi/dsKJ9cJCMaockHjS/qIjp5Om6BHrkQ4SFMQMBzx7txrRm9xDvlMnVHENCUlfn33SrN4PJ5P9lFYWYco/9wYbjUDgpogJXEbggvyGihYczsVgk5te0yswwISYy6lkoo2ORRo4SJeee1V8rHE36HHkD4ZGbK9G9AWBdlPX5GDaka9KprfVSxM3Fh1+fI9fI5V1jo70zWSlzhg07KifWMoYjbSHjL+fWiFTsAkA6OxwZbhim9H6RYcwMblSwiqMlbK/dfwwSEYy2vw1kUzulnXb4zCK1kaf8tC3BiY95ebxIWTUL7TvJHKBvgwMVDam1nT6TVsVFolvuy5h' WHERE path = 'payment/chcybersource/transaction_key' AND scope_id = 1;
UPDATE core_config_data SET value = '0f45c5e2-bddf-404b-8358-b2424d32f448' WHERE path = 'payment/chcybersource/rest_key_id' AND scope_id = 1;
UPDATE core_config_data SET value = '0:3:KOw/ynpjy9Vq+681SAg4CsU8XTRFtCGH7tJlmS+jit6LIsPHdpjZWiuU6lTUET7yIhobyqQtQFJtYYJcWzVz+dTO4U21DHfz' WHERE path = 'payment/chcybersource/rest_key_value' AND scope_id = 1;

-- Cybersource UK
UPDATE core_config_data SET value = '4ddf231a-ace9-4b9d-beb4-5c65e4acba4a' WHERE path = 'payment/chcybersource/rest_key_id' AND scope_id = 11;
UPDATE core_config_data SET value = '0:3:lSmnnXbeNmltDZi1TgQlH5FFjSVSeS3rFzEJMkZA4480PSsZOgpPHzflO+tXc8b+rYwjGRyfIql8Ttw7l74oQW35xWHzrrOI' WHERE path = 'payment/chcybersource/rest_key_value' AND scope_id = 11;
UPDATE core_config_data SET value = 'B63B3A68-F669-4887-A21A-26947A859B95' WHERE path = 'payment/chcybersource/sop_profile_id' AND scope_id = 11;
UPDATE core_config_data SET value = 'e1cc75755ad23eb08183719c30d75cad' WHERE path = 'payment/chcybersource/sop_access_key' AND scope_id = 11;
UPDATE core_config_data SET value = '0:3:O+MvOJhYahyGN3pke3GJZHZ6/JH9olvb96NJjD7wimiMmOfKo4VjU2pQboifrymr9t8He3Gfxkm//E5D9v3ccGOvpjz+yE9rdlN6HQqI96t8V+0sJF1J7wVL4usOOSmubbQ6a+vYMfPLKAcPJ8ulQ/w/Z5Y3yrmNw6t3ysQATNgJoc43gPr1Oac/E5hAO7CXNcRBr91BBBtqCEYI7PfIEyYEMt7b9T8YmOgnHN0x5nZwsVYvXgjMMJxJZH1wF5eQMVMww0/sLft+IXHjs1u6fK7P+KXzOTBrPOQan/Wi1dWEZ59NWtAOWziL4MFd3IbWqOybkBQGrrnfzIBAHwKYvg1huRO2C3mlDZok4fcWyc1DPqHULvkzSc+F84M=' WHERE path = 'payment/chcybersource/sop_secret_key' AND scope_id = 11;
UPDATE core_config_data SET value = '0:3:1NoSL0mDRKO2SDIr2PBPta6fbL6MlSEh3TnVWRroMgeKqrvTuxwfTjyCKR+OwsWS6/VIndyBbIMkrEXtX88gJRsXnxCHv/yLZ7rQdXnQi+h7jPzAUHzw8Xj0qfKA+E3rTaKrCJt2tfzVBEsKhSSqGmFfzTcfiFeBIwsAo85wC+GHPoyGgHCtl+0BxiAT1LLGmUiZm1HY8HytzRzs98SRembdcxLHNkPwPTxXvDNMTwoAjGT/UcNtK1GgNbA3qrpIRSMIfPHnknHgjyOiHqRyB0EVzNOAfz7SGWetXcfEE/cnjaNbMoLhzAUXGNJ/YQey1fOMpFbMZH+mfiekHV97DHc6nDSdVvDpjxhNmEcpGgYFLkM9GiE3Q5WLRHxkW23+0ODDCCyccxV6z88O0mDUvEYIvZNloq0feHbTqJ+SIko2vmBPVEa4i4NSseg19kc+QNyG/SG1zLtU/PeU6gPmV/L64v6tOkA6vYvE1Shc0oY8BTZj' WHERE path = 'payment/chcybersource/transaction_key' AND scope_id = 11;
UPDATE core_config_data SET value = 0 WHERE path = 'payment/chcybersource/active_3ds';

-- Cleanup Customer Data
UPDATE customer_entity
  SET email = replace(email,'@','@tester-')
WHERE
  1 = 1
  AND email NOT LIKE '%circalighting.com%'
  AND email NOT LIKE '%visualcomfort.com%'
  AND email NOT LIKE '%capgemini%'
  AND email NOT LIKE 'rmgliane%'
  AND email NOT LIKE '%hazell%'
  AND email NOT LIKE '%wolaver%'
  AND email NOT LIKE '%mailosaur.net%'
;

-- Remove orders
DELETE FROM sales_order;
TRUNCATE sales_order_grid;
TRUNCATE avatax_log;
TRUNCATE salespad_api_errors;

-- Remove uneeded amasty tables
TRUNCATE amasty_conditions_quote;
TRUNCATE amasty_customform_answer;
TRUNCATE amasty_fpc_activity;
TRUNCATE amasty_fpc_flushes_log;
TRUNCATE amasty_fpc_log;
TRUNCATE amasty_menu_item_content;

-- Remove old bronto data
TRUNCATE bronto_connector_event_queue;

-- Remove unnecessary bulk edit status
TRUNCATE magento_operation;

-- Activate ecommtester accounts
UPDATE admin_user 
SET is_active = 1
WHERE username LIKE '%ecommtester%' 
  AND SUBSTRING(email, LOCATE( '@', email) + 1,LENGTH(email)) IN ('visualcomfort.com','rysun.com','herodigital.com','kcsitglobal.com');
