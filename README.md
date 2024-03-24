# OICDB (Open Invoice Collector Database)

## The open standard and public database for secure and automated retrieval of supplier invoices.

### What is OICDB?
OICDB is a JSON schema and public database containing information about downloading invoices from suppliers in
simple JSON files. OICDB is open to everyone and can be used by anyone (even for commercial purposes) free of charge. The
idea behind OICDB is to provide a simple way to build and share invoice collectors for suppliers.

#### 1) The OICDB JSON schema
The schema describes how to download invoices from a supplier. It makes developing and validating "recipes" for new suppliers easy.

#### 2) The public database
A public database containing all OICDB JSON files for all suppliers.

#### 3) buchhalter-cli (reference implementation)
An easy-to-use command-line tool that uses the OICDB database to execute OICDB JSON files and download invoices from suppliers.
Available at [buchhalter.ai](https://buchhalter.ai/).

### Why OICDB?
Collecting invoices from suppliers every month manually by logging in and clicking many buttons is a tedious and 
unnecessary task. The idea behind OICDB is to automate this process and make it easy for everyone to build and maintain
automated invoice-collecting workflows.
