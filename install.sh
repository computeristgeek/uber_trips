#!/bin/bash

# create a codeigniter project
composer create-project codeigniter/framework .
# rename composer folder to assets/cmpsr_pkgs
mkdir assets
mv vendor assets/cmpsr_pkgs
composer config vendor-dir 'assets/cmpsr_pkgs'
# install ion auth for secure authentication
git clone git@github.com:benedmunds/CodeIgniter-Ion-Auth.git
mkdir application/third_party/ion_auth
mv CodeIgniter-Ion-Auth/config/ application/third_party/ion_auth/
mv CodeIgniter-Ion-Auth/libraries application/third_party/ion_auth/
mv CodeIgniter-Ion-Auth/models/ application/third_party/ion_auth/
mv CodeIgniter-Ion-Auth/views/auth/ application/views/
mv CodeIgniter-Ion-Auth/controllers/Auth.php application/controllers/
mv CodeIgniter-Ion-Auth/language/* application/language/
mv CodeIgniter-Ion-Auth/language/english/auth_lang.php application/language/english/
mv CodeIgniter-Ion-Auth/language/english/ion_auth_lang.php application/language/english/
mv CodeIgniter-Ion-Auth/userguide/ user_guide/ion_auth
mv CodeIgniter-Ion-Auth/sql/ user_guide/ion_auth_migration
mv CodeIgniter-Ion-Auth/migrations/ user_guide/ion_auth_migration
rm -rf CodeIgniter-Ion-Auth/
