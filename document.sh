#!/bin/bash
svn delete doc
svn ci
rm -rf doc
svn mkdir doc
phpdoc -t doc -o HTML:Smarty:PHP -d . -dn phpPoA2 --title phpPoA2 -ric README,LICENSE,GPL,INSTALL
phpdoc -t doc -o PDF:default:default -d . -dn phpPoA2 --title phpPoA2
svn add doc
