ABOUT
-----
The only 3rd party code is included in the vendor directory, this is limited to frontend libraries bootstrap, bootstrap-validator, and bootstrap-select.

No 3rd party backend code was used or referenced. All of the code in every folder (except for vendor) was created from scratch on 31st May - 1st June.


REQUIREMENTS
------------
1. This code was developed with PHP Version 5.6.25
2. mod_rewrite needs to be enabled in apache


INSTALLATION
------------
1. Extract the contents of the .zip file to a subdirectory named 'voting' in your document root.
- If you use a directory other than 'voting' be sure to edit Line 4 in the provided .htaccess

2. Import the file 'ed_voting_app.sql' to your mysql server. This will create a  database named 'ed_voting_app' and populate it with required data.

3. Open the file 'index.php' and edit the $config array at the top to provide your server details.
