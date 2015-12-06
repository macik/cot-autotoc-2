<?php
/* ====================
[BEGIN_COT_EXT]
Code=autotoc
Name=AutoTOC
Description=Generate table of contents based on html elements in page
Version=1.2.0
Date=2015-12-05
Author=Koradhil and Cotonti Team
Copyright=Webmojo
Notes=BSD License
SQL=
Auth_guests=RW
Lock_guests=12345A
Auth_members=RW
Lock_members=12345
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
elements=01:string::h2,h3:Elements in TOC, comma separated
ch_prefix=03:string::ch:Chapter anchor prefix
ch_class=05:string::anchor:Chapter anchor class
[END_COT_EXT_CONFIG]
==================== */

defined('COT_CODE') or die('Wrong URL');
