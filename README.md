# juniper-config-to-set
Converts Juniper Networks configurations to a series of set commands

The purpose of this script is to take a juniper config and convert it to set commands, i created it because i couldn't find one anywhere else and i prefer ot work with PHP (don't hate me).

There was a business need to audit the correctness of Juniper configs which is easier done when the config is in set format. The process of logging into each Juniper device and dumping the configs in set format was too burdensome on both the Juniper devices in question but also extended the execution time of the audit scripts by some minutes.  Instead you can take the stored backup configs that rancid creates and convert them to set format using this script.

The script will take a juniper config piped to it from STDIN and output the results to STDOUT 

You will need to `chown 755 juniper-config-to-set.php` after you download it, you may also need to check the PHP path in the start of the file.

Usage example:  `cat juniper-config.txt | juniper-config-to-set.php`
