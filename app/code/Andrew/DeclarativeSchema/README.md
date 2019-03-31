# Declarative Schema:
  * Declaring tables;
  * Apply declarations;
  * Changing tables;
  * Schema and Data patches;
 
 To avoid data wrapping in one transaction, you need to use `NonTransactionableInterface` interface.
 if you want to restart the patch, then you need to remove it from the table `patch_list`. 
 Check composer.json file for dependency.
  * Migrate a module from Setup Scripts to Declarative Schema 
  
For generate `db_schema_whitelist.json` use:
````
 php ./bin/magento setup:db-declaration:generate-whitelist --module-name=Module_Name 
````
For test we cat run - Dry run:
````
 php ./bin/magento setup:up --keep-generated --dry-run=1 
````
in dry run mode magento create log file with sql in `var/log/dry-run-installation.log`