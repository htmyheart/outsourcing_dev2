flush:
	/usr/local/php5/bin/php bin/magento cache:flush

deploy:
	/usr/local/php5/bin/php bin/magento setup:static-content:deploy

reindex:
    /usr/local/php5/bin/php bin/magento indexer:reindex

cache_en:
    /usr/local/php5/bin/php bin/magento cache:enable
cache_dis:
    /usr/local/php5/bin/php bin/magento cache:disable


s_flush:
	ea-php70 bin/magento cache:flush

s_deploy:
	ea-php70 bin/magento setup:static-content:deploy

s_reindex:
    ea-php70 bin/magento indexer:reindex