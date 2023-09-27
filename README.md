# OICDB (Open Invoice Collector Database)
## The open standard and public database for secure and automated retrieval of supplier invoices.

### What is OICDB?
OICDB is a json schema and public database that contains information about how to download invoices from suppliers in 
simple json files. OICDB is open to everyone and can be used by anyone (even for commercial purposes) free of charge. The
idea behind OICDB is to provide a simple way to build and share invoice collectors for suppliers.

#### 1) The OICDB json schema
Describes how to download invoices from a supplier. Makes it easy to develop and validate "recipes" for new suppliers.

#### 2) The public database
A public database containing all OICDB json files for all suppliers.

#### 3) buchhalter-cli (reference implementation)
An easy to use command line tool using the OICDB database to execute OICDB json files and download invoices from suppliers.

### Why OICDB?
Collecting invoices from suppliers every month manually by logging in and clicking many buttons is a tedious and 
unnecessary task. The idea behind OICDB is to automate this process and make it easy for everyone building and maintaining 
autmomated invoice collecting workflows. 
