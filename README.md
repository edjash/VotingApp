# Example voting app

### Prerequisites

* This code was developed with PHP Version 5.6.25
* mod_rewrite needs to be enabled in apache

### Installing

1. Rename config.example.php to config.php and edit the values.
2. Edit line 4 in the .htaccess file, changing 'voting' to whatever directory you have installed the code in.
2. Import the file 'ed_voting_app.sql' to your mysql server. This will create a  database named 'ed_voting_app' and populate it with required data.

## About
This app was created for a PHP Technical Test. The code from the first commit was created in 24 hours, it has since been updated.

The requirements of the test were:

###### Create a ‘Voting’ application. Users should be able to:
* Identify themselves to the system, and be restricted to a single vote
* Select whether they will be voting or abstaining
* Identify the constituency they will be voting within
* Identify the candidate or party they are voting for
* View a report of votes that have been cast, both country wide and per constituency.

###### Coding requirements:
* Must be written in native PHP and show your knowledge of Object Oriented Programming.
* Must not use a PHP framework or other people’s code as the base of your application.
* Must come with brief set-up instructions / requirements / seed data if applicable
* Must be OS agnostic (able to run on any basic *AMP stack)
* May use Front-end libraries such as jQuery / Bootstrap (but not required).


## Authors

* **Ed Shortt**
